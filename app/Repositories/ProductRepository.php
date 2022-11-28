<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements CrudInterface
{
    public function getAll(?int $perPage = 10): Paginator
    {
        return Product::paginate($perPage);
    }

    public function getById(int $id): Product
    {
        return Product::find($id);
    }
}
