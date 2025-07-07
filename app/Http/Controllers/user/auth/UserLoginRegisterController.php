<?php

namespace App\Http\Controllers\user\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Modules\GeneralSetting\Models\EmailTemplate;
use Modules\GeneralSetting\Models\GeneralSetting;
use Modules\GeneralSetting\Models\UserDevice;

class UserLoginRegisterController extends Controller
{
    /**
     * @return View|RedirectResponse
     */
    public function userLogin(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('user.auth.login');
    }
    /** @return View|RedirectResponse */
    public function userRegister(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('user.auth.register');
    }
    /** @return View|RedirectResponse */
    public function forgotPassword(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('user.auth.forgot-password');
    }
    /** @return View|RedirectResponse */
    public function resetPassword(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('user.auth.password-reset');
    }
    /** @return JsonResponse */
    public function resetPasswordUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'current_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:current_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found.',
            ], 404);
        }

        $user->password = Hash::make($request->current_password);
        $user->save();

        return response()->json([
            'code' => 200,
            'message' => 'Password updated successfully.',
        ]);
    }

    /** @return JsonResponse */
    public function getOtpSettings(Request $request): JsonResponse
    {
        $email = $request->input('email');

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email address'], 400);
        }

        $response = [];
        $statusCode = 200;

        $user = User::where('email', $email)->first();
        $type = $request->input('type');

        if (! $user || ($type === 'forgot' && in_array($email, ['demouser@gmail.com', 'demoprovider@gmail.com']))) {
            $response = ['error' => 'The given email is not registered.'];
            $statusCode = 400;
        } else {
            $settings = GeneralSetting::whereIn('key', ['otp_digit_limit', 'otp_expire_time', 'otp_type'])
                ->pluck('value', 'key');

            if (! in_array($settings['otp_type'], ['email', 'sms'])) {
                $response = ['error' => 'Unsupported OTP type'];
                $statusCode = 400;
            } else {
                $otp = in_array($email, ['demouser@gmail.com', 'demoprovider@gmail.com'])
                    ? '1234'
                    : $this->generateOtp($settings['otp_digit_limit']);

                $otpExpireMinutes = (int) filter_var($settings['otp_expire_time'], FILTER_SANITIZE_NUMBER_INT);
                $expiresAt = now()
                    ->addMinutes($otpExpireMinutes)
                    ->setTimezone('Asia/Kolkata')
                    ->format('Y-m-d H:i:s');

                $otpData = ['otp' => $otp, 'expires_at' => $expiresAt];

                DB::table('otp_settings')->updateOrInsert(
                    ['email' => $email],
                    $otpData
                );

                // Default values
                $subject = 'OTP Verification for login';
                $content = 'Your OTP Verification for login';

                if ($settings['otp_type'] === 'sms') {

                    $template = EmailTemplate::select('subject', 'sms_content')
                        ->where('type', 2)
                        ->where('notification_type', 2)
                        ->first();

                    if (! $template) {
                        $response = ['error' => 'SMS template not found'];
                        $statusCode = 404;
                    } else {
                        $subject = $template->subject;
                        $content = str_replace(
                            ['{{user_name}}', '{{otp}}'],
                            [$user->name, $otp],
                            $template->sms_content
                        );

                        // Successful response
                        $response = [
                            'name' => $user->name,
                            'otp_digit_limit' => $settings['otp_digit_limit'],
                            'otp_expire_time' => $settings['otp_expire_time'],
                            'otp_type' => $settings['otp_type'],
                            'otp' => $otp,
                            'expires_at' => $expiresAt,
                            'email_subject' => $subject,
                            'email_content' => $content,
                        ];
                    }
                } else {
                    // Email type success response
                    $response = [
                        'name' => $user->name,
                        'otp_digit_limit' => $settings['otp_digit_limit'],
                        'otp_expire_time' => $settings['otp_expire_time'],
                        'otp_type' => $settings['otp_type'],
                        'otp' => $otp,
                        'expires_at' => $expiresAt,
                        'email_subject' => $subject,
                        'email_content' => $content,
                    ];
                }
            }
        }

        return response()->json($response, $statusCode);
    }


    /** @return JsonResponse */
    public function verifyOtp(Request $request): JsonResponse
    {
        if ($request->login_type === 'register') {
            $request->validate([
                'otp' => 'required',
            ]);

            $otpSetting = DB::table('otp_settings')->where('email', $request->email)->first();
            if (isset($otpSetting)) {
                $expire = $otpSetting->expires_at ?? '';
                if ($expire !== '') {
                    $currentDateTime = now()->setTimezone('Asia/Kolkata'); // Adjust timezone if needed
                    if ($currentDateTime->greaterThanOrEqualTo($expire)) {
                        return response()->json(['error' => 'OTP is expired'], 400);
                    }
                }
                $otp = $otpSetting->otp ?? '';
                if ($otp !== $request->otp) {
                    return response()->json(['error' => 'The OTP you entered is invalid. Please check and try again.'], 400);
                }
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'user_type' => 3,
            ];

            $save = User::create($data);

            Auth::login($save);

            session(['user_id' => $save->id]);
            Cache::forget('user_auth_id');
            Cache::forever('user_auth_id', $save->id);
            DB::table('otp_settings')->where('email', $request->email)->delete();

            return response()->json(['message' => 'OTP verified successfully']);
        }
        if ($request->login_type === 'forgot_email') {
            $request->validate([
                'forgot_email' => 'required|email',
                'otp' => 'required',
            ]);

            $user = User::where('email', $request->forgot_email)->first();

            if (! $user) {
                return response()->json([ 'code' => 200, 'error' => 'User not found'], 404);
            }

            $otpSetting = DB::table('otp_settings')->where('email', $request->forgot_email)->first();
            if (isset($otpSetting)) {
                $expire = $otpSetting->expires_at ?? '';
                if ($expire !== '') {
                    $currentDateTime = now()->setTimezone('Asia/Kolkata');
                    if ($currentDateTime->greaterThanOrEqualTo($expire)) {
                        return response()->json(['error' => 'OTP is expired'], 400);
                    }
                }
                $otp = $otpSetting->otp ?? '';
                if ($otp !== $request->otp) {
                    return response()->json(['code' => 422, 'error' => 'The OTP you entered is invalid. Please check and try again.'], 400);
                }
            }
            DB::table('otp_settings')->where('email', $request->forgot_email)->delete();

            $data = 'done';

            return response()->json(['code' => 200, 'message' => 'OTP verified successfully', 'data' => $data, 'email' => $request->forgot_email]);
        }
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $otpSetting = DB::table('otp_settings')->where('email', $request->email)->first();
        if (isset($otpSetting)) {
            $expire = $otpSetting->expires_at ?? '';
            if ($expire !== '') {
                $currentDateTime = now()->setTimezone('Asia/Kolkata'); // Adjust timezone if needed
                if ($currentDateTime->greaterThanOrEqualTo($expire)) {
                    return response()->json(['error' => 'OTP is expired'], 400);
                }
            }
            $otp = $otpSetting->otp ?? '';
            if ($otp !== $request->otp) {
                return response()->json(['error' => 'The OTP you entered is invalid. Please check and try again.'], 400);
            }
        }

        Auth::guard('web')->login($user);

        session(['user_id' => $user->id]);
        if ($user->user_type === '2') {
            Cache::forget('provider_auth_id');
            Cache::forever('provider_auth_id', $user->id);
        } else {
            Cache::forget('user_auth_id');
            Cache::forever('user_auth_id', $user->id);
        }
        DB::table('otp_settings')->where('email', $request->email)->delete();

        return response()->json(['message' => 'OTP verified successfully']);
    }

    /** @return JsonResponse */
    public function validateEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $exists]);
    }

    /** @return JsonResponse */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|regex:/^[A-Za-z]+$/|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'username.required' => 'Username is required',
            'username.regex' => 'Username must only contain alphabets (A-Z, a-z)',
            'username.min' => 'Username must be at least 3 characters long',
            'username.max' => 'Username must not exceed 50 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $regStatus = DB::table('general_settings')->where('key', 'register')->value('value');

        if ($regStatus === '0') {
            $user = User::create([
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 3,
            ]);

            UserDetail::create(['user_id' => $user->id]);

            Auth::login($user);
            session(['user_id' => $user->id]);
            $notificationType = 1;
            $template = EmailTemplate::select('subject', 'description')
                ->where('notification_type', $notificationType)
                ->first();

            if (! $template) {
                return response()->json(['error' => 'Welcome Template is not Found'], 404);
            }
            $companyName = GeneralSetting::where('key', 'organization_name')->value('value') ?? 'Default Company Name';

            $subject = $template->subject;
            $content = str_replace(
                ['{user_name}', '{company_name}'],
                [$request->username, $companyName],
                $template->description
            );
            return response()->json([
                'status' => true,
                'code' => 200,
                'register_status' => $regStatus,
                'name' => $request->username,
                'email_subject' => $subject,
                'email_content' => $content,
                'redirect_url' => route('home'),
                'email' => $request->email,
                'message' => 'Registration successful',
            ]);
        }

        $email = $request->email;

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email address'], 400);
        }

        $settings = GeneralSetting::whereIn('key', ['otp_digit_limit', 'otp_expire_time', 'otp_type'])
            ->pluck('value', 'key');

        if (! in_array($settings['otp_type'], ['email', 'sms'])) {
            return response()->json(['error' => 'Unsupported OTP type'], 400);
        }

        $otp = $this->generateOtp($settings['otp_digit_limit']);

        $expiresAt = now()
            ->addMinutes((int) $settings['otp_expire_time'])
            ->setTimezone('Asia/Kolkata')
            ->format('Y-m-d H:i:s');

        DB::table('otp_settings')->updateOrInsert(
            ['email' => $email],
            ['otp' => $otp, 'expires_at' => $expiresAt]
        );


        $subject = 'OTP Verification for Register';
        $content = 'Your OTP Verification for Register {{otp}} ';

        $content = str_replace(
            ['{{otp}}'],
            [$otp],
            $content
        );

        return response()->json([
            'status' => true,
            'code' => 200,
            'register_status' => $regStatus,
            'message' => 'OTP sent successfully',
            'otp_type' => $settings['otp_type'],
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'email_subject' => $subject,
            'email_content' => $content,
            'name' => $request->username,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);
    }

    /** @return JsonResponse */
    public function login(Request $request): JsonResponse
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.exists' => 'No account found with this email',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $response = null;
        if ($user && $user->user_type === 1) {
            $response = response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Admin access is not allowed here',
            ], 422);
        }else{
            if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))) {
                $agent = new Agent();
                $ip = $request->ip();
                $device_type = $agent->device() ?? '';
                $os = $agent->platform();
                $browser = $agent->browser();

                $locationData = Http::get("http://ip-api.com/json/{$ip}?fields=status,country,city,regionName,lat,lon")->json();
                $location = $locationData['status'] === 'success'
                    ? $locationData['country'] . ' / ' . $locationData['city']
                    : 'India / Coimbatore';

                $user_device = new UserDevice();
                $user_device->user_id = Auth::guard('web')->user()->id;
                $user_device->device_type = $device_type;
                $user_device->browser = $browser;
                $user_device->os = $os;
                $user_device->ip_address = $ip;
                $user_device->location = $location;
                $user_device->save();
                $redirectTo = session('intended_url', route('home'));
                session()->forget('intended_url');
                if (session()->has('intended_booking')) {
                    $redirectTo = route('user.booking.redirect');
                }
                $response = response()->json([
                    'status' => true,
                    'code' => 200,
                    'redirect_url' => $redirectTo,
                    'message' => 'Login successful',
                ]);
            }else{
                $response = response()->json([
                    'status' => false,
                    'code' => 401,
                    'message' => 'Invalid email or password',
                ], 401);
            }
        }

        return $response;
    }

    /** @return RedirectResponse */
    public function userlogout(): RedirectResponse
    {
        Auth::guard('web')->logout();
        return redirect()->route('home');
    }
    /** @return string */
    private function generateOtp(int $digitLimit): string
    {
        return str_pad((string) random_int(0, pow(10, $digitLimit) - 1), $digitLimit, '0', STR_PAD_LEFT);
    }
}
