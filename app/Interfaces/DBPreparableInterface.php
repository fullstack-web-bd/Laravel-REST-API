<?php

namespace App\Interfaces;

interface DBPreparableInterface
{
    public function prepareForDB(array $data): array;
}
