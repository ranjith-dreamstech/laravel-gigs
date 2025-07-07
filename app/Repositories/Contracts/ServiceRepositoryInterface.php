<?php

namespace App\Repositories\Contracts;

use App\Models\Gigs;
use Illuminate\Support\Collection;
use Modules\Category\Models\Categories;

/**
 * Interface ServiceRepositoryInterface
 */
interface ServiceRepositoryInterface
{
    /**
     * Fetch all top-level categories for the current locale.
     *
     * @return Collection<int, Categories>
     */
    public function fetchAllCategories(): Collection;

    /**
     * Search gigs based on a keyword.
     *
     * @param string $keyword
     *
     * @return Collection<int, Gigs>
     */
    public function searchGigs(string $keyword): Collection;
}
