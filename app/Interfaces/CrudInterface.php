<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;

interface CrudInterface
{
    public function getAll(int $perPage): Paginator;

    public function getById(int $id): object|null;
}
