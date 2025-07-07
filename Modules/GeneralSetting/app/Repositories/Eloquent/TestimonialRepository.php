<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\GeneralSetting\Models\Testimonial;
use Modules\GeneralSetting\Repositories\Contracts\TestimonialInterface;

class TestimonialRepository implements TestimonialInterface
{
    /**
     * @param array{
     *     customer_name: string,
     *     review: string,
     *     ratings: int,
     *     image?: string|null,
     *     status?: bool
     * } $data
     */
    public function create(array $data): Testimonial
    {
        return Testimonial::create($data);
    }

    /**
     * @param array{
     *     customer_name?: string,
     *     review?: string,
     *     ratings?: int,
     *     image?: string|null,
     *     status?: bool
     * } $data
     */
    public function update(int $id, array $data): bool
    {
        return Testimonial::findOrFail($id)->update($data);
    }

    public function delete(int $id): bool
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image) {
            Storage::delete($testimonial->image);
        }
        return (bool) $testimonial->delete();
    }

    public function find(int $id): ?Testimonial
    {
        return Testimonial::find($id);
    }

    /**
     * @param array{
     *     search?: string,
     *     ratings?: array<int>,
     *     sort?: string
     * } $filters
     *
     * @return Collection<int, Testimonial>
     */
    public function all(array $filters = []): Collection
    {
        $query = Testimonial::query();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('customer_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('review', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (! empty($filters['ratings'])) {
            $query->whereIn('ratings', $filters['ratings']);
        }

        switch (strtolower($filters['sort'] ?? '')) {
            case 'ascending':
                $query->orderBy('created_at', 'asc');
                break;
            case 'last month':
                $query->whereBetween('created_at', [now()->subMonth(), now()]);
                break;
            case 'last 7 days':
                $query->whereBetween('created_at', [now()->subDays(7), now()]);
                break;
            case 'descending':
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    /**
     * @param array{
     *     search?: string,
     *     ratings?: array<int>,
     *     sort?: string
     * } $filters
     *
     * @return LengthAwarePaginator<int, Testimonial>
     */
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = Testimonial::query();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('customer_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('review', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }
}
