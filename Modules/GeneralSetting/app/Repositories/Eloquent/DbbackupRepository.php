<?php

namespace Modules\GeneralSetting\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Modules\GeneralSetting\Models\Dbbackup;
use Modules\GeneralSetting\Repositories\Contracts\DbbackupInterface;

class DbbackupRepository implements DbbackupInterface
{
    /**
     * @return Collection<int, Dbbackup>
     */
    public function getDatabaseBackups(): Collection
    {
        return Dbbackup::where('type', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return Collection<int, Dbbackup>
     */
    public function getSystemBackups(): Collection
    {
        return Dbbackup::where('type', 2)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteBackup(int $id): bool
    {
        $backup = Dbbackup::findOrFail($id);
        return (bool) $backup->delete();
    }

    public function getTotalBackupCount(): int
    {
        return Dbbackup::count();
    }
}
