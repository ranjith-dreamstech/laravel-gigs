<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\Testimonial;

interface TestimonialInterface
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
    public function create(array $data): Testimonial;

    /**
     * @param array{
     *     customer_name?: string,
     *     review?: string,
     *     ratings?: int,
     *     image?: string|null,
     *     status?: bool
     * } $data
     */
    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function find(int $id): ?Testimonial;

    /**
     * @param array{
     *     search?: string,
     *     ratings?: array<int>,
     *     sort?: string
     * } $filters
     *
     * @return Collection<int, Testimonial>
     */
    public function all(array $filters = []): Collection;

    /**
     * @param array{
     *     search?: string,
     *     ratings?: array<int>,
     *     sort?: string
     * } $filters
     *
     * @return LengthAwarePaginator<int, Testimonial>
     */
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator;
}
