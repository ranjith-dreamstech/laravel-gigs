<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\GeneralSetting\Models\Faq;

interface FaqInterface
{
    /**
     * @param array{
     *     question: string,
     *     answer: string,
     *     language_id: int,
     *     status: bool
     * } $data
     */
    public function store(array $data): Faq;

    /**
     * @param array{
     *     question?: string,
     *     answer?: string,
     *     language_id?: int,
     *     status?: bool
     * } $data
     */
    public function update(int $id, array $data): Faq;

    public function delete(int $id): bool;

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
    public function list(array $filters): Collection;
}
