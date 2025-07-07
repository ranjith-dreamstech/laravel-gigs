<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\Faq;
use Modules\GeneralSetting\Models\Language;
use Modules\GeneralSetting\Repositories\Contracts\FaqInterface;

class FaqRepository implements FaqInterface
{
    /**
     * @param array{
     *     question: string,
     *     answer: string,
     *     language_id: int,
     *     status: bool
     * } $data
     */
    public function store(array $data): Faq
    {
        $data['order_by'] = (Faq::max('order_by') ?? 0) + 1;
        return Faq::create($data);
    }

    /**
     * @param array{
     *     question?: string,
     *     answer?: string,
     *     language_id?: int,
     *     status?: bool
     * } $data
     */
    public function update(int $id, array $data): Faq
    {
        $faq = Faq::findOrFail($id);
        $faq->update($data);
        return $faq;
    }

    public function delete(int $id): bool
    {
        $faq = Faq::findOrFail($id);
        return (bool) $faq->delete();
    }

    /**
     * @param array{
     *     language_id?: int,
     *     status?: bool,
     *     sort_by?: string,
     *     search?: string
     * } $filters
     *
     * @return Collection<int, Faq>
     */
    public function list(array $filters): Collection
    {
        $defaultLanguage = Language::where('default', 1)->value('language_id');

        return Faq::when($filters['language_id'] ?? null, function ($query, $languageId) {
            return $query->where('language_id', $languageId);
        }, function ($query) use ($defaultLanguage) {
            return $query->where('language_id', $defaultLanguage);
        })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->where('status', (bool) ($filters['status'] ?? false));
            })
            ->when($filters['sort_by'] ?? null, function ($query, $sort) {
                return match ($sort) {
                    'asc' => $query->orderBy('order_by', 'asc'),
                    'desc' => $query->orderBy('order_by', 'desc'),
                    'last_7_days' => $query->where('created_at', '>=', now()->subDays(7)),
                    'last_month' => $query->where('created_at', '>=', now()->subMonth()),
                    default => $query->orderBy('order_by', 'desc'),
                };
            })
            ->when($filters['search'] ?? null, function ($query, $search) {
                return $query->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            })
            ->get();
    }
}
