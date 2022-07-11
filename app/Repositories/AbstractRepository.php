<?php

namespace App\Repositories;

use Illuminate\Support\Str;

abstract class AbstractRepository
{
    /**
     * @param $dir
     * @return bool
     */
    public function allowedSortDir($dir): bool
    {
        $dir = Str::upper($dir);

        $sortDir = ['ASC', 'DESC'];

        return in_array($dir, $sortDir);
    }
}
