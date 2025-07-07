<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Communication\Emails\Samplemail;
use Modules\Communication\Helpers\MailConfigurator;

class EmailController extends Controller
{
    public function sendEmail(Request $request): JsonResponse
    {
        try {
            MailConfigurator::configureMail();

            $tomail = $request->input('to_email');
            $subject = $request->input('subject');
            $content = $request->input('content');
            $attachment = $request->input('attachment'); // Attachment path

            $data = [
                'message' => $content,
                'subject' => $subject,
                'attachment' => $attachment,
            ];

            if (empty($tomail)) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Recipient email is required.',
                ], 400);
            }

            if (is_array($tomail)) {
                foreach ($tomail as $email) {
                    Mail::to($email)->send(new Samplemail($data));
                }
            } else {
                Mail::to($tomail)->send(new Samplemail($data));
            }

            return response()->json(['code' => 200, 'message' => __('Email sent successfully.'), 'data' => []], 200);
        } catch (\Throwable $th) {
            return response()->json(['code' => 500, 'message' => $th->getMessage(), 'data' => []], 200);
        }
        
    }
}
