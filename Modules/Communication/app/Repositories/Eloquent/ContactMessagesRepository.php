<?php

namespace Modules\Communication\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Communication\Models\Contact;
use Modules\Communication\Repositories\Contracts\ContactMessagesRepositoryInterface;

class ContactMessagesRepository implements ContactMessagesRepositoryInterface
{
    public function store(Request $request): array
    {
        try {
            $data = $request->only(['name', 'email', 'phone_number', 'message']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('contacts', 'public');
            }

            $contact = Contact::create($data);

            return [
                'code' => 200,
                'success' => true,
                'message' => __('admin.support.contact_message_create_success'),
                'data' => $contact,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'success' => false,
                'message' => __('admin.common.default_create_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    public function list(Request $request): array
    {
        try {
            $sortBy = $request->get('sort_by', 'latest');
            $searchInput = $request->get('search', '');
            $search = is_string($searchInput) ? $searchInput : '';

            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
            $sevenStartDate = Carbon::now()->subDays(7)->startOfDay();
            $sevenEndDate = Carbon::now()->endOfDay();

            $contacts = Contact::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                })
                ->when($sortBy === 'latest', fn ($query) => $query->orderBy('created_at', 'desc'))
                ->when($sortBy === 'ascending', fn ($query) => $query->orderBy('name', 'asc'))
                ->when($sortBy === 'descending', fn ($query) => $query->orderBy('name', 'desc'))
                ->when($sortBy === 'last_month', fn ($query) => $query->whereBetween('created_at', [$startDate, $endDate]))
                ->when($sortBy === 'last_7_days', fn ($query) => $query->whereBetween('created_at', [$sevenStartDate, $sevenEndDate]))
                ->get()
                ->map(function ($contact) {
                    $contact->name = ucwords($contact->name);
                    $contact->image = uploadedAsset($contact->image, 'profile');
                    $contact->created_date = formatDateTime($contact->created_at, false);
                    unset($contact->created_at);
                    return $contact;
                });

            return [
                'code' => 200,
                'success' => true,
                'message' => __('admin.common.default_retrieve_success'),
                'data' => $contacts,
            ];
        } catch (\Exception $e) {
            Log::error('Fetching contacts failed: ' . $e->getMessage());

            return [
                'code' => 500,
                'success' => false,
                'message' => __('admin.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    public function delete(Request $request): array
    {
        $response = [
            'code' => 500,
            'success' => false,
            'message' => __('admin.common.default_delete_error'),
        ];

        try {
            $idInput = $request->id;

            if (! is_numeric($idInput)) {
                $response['code'] = 400;
                $response['message'] = 'Invalid contact ID format.';
            } else {
                $contact = Contact::find((int) $idInput);

                if (! $contact) {
                    $response['code'] = 404;
                    $response['message'] = 'Contact not found.';
                } else {
                    $contact->delete();

                    return [
                        'code' => 200,
                        'success' => true,
                        'message' => __('admin.support.contact_message_delete_success'),
                    ];
                }
            }
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return $response;
    }
}
