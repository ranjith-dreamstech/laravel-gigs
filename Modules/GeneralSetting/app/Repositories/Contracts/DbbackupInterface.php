<?php

namespace Modules\GeneralSetting\Repositories\Contracts;

use Illuminate\Support\Collection;
use Modules\GeneralSetting\Models\Dbbackup;

interface DbbackupInterface
{
    /**
     * @return Collection<int, Dbbackup>
     */
    public function getDatabaseBackups(): Collection;

    /**
     * @return Collection<int, Dbbackup>
     */
    public function getSystemBackups(): Collection;

    public function deleteBackup(int $id): bool;

    public function getTotalBackupCount(): int;
}
