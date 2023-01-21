<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Interfaces\DBPreparableInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductRepository implements CrudInterface, DBPreparableInterface
{
    public function getAll(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);

        $query = Product::orderBy($filter['orderBy'], $filter['order']);

        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $searched = '%' . $filter['search'] . '%';
                $query->where('title', 'like', $searched)
                    ->orWhere('slug', 'like', $searched);
            });
        }

        return $query->paginate($filter['perPage']);
    }

    public function getFilterData(array $filterData): array
    {
        $defaultArgs = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];

        return array_merge($defaultArgs, $filterData);
    }

    public function getById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): ?Product
    {
        $data = $this->prepareForDB($data);

        return Product::create($data);
    }

    public function prepareForDB(array $data): array
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->createUniqueSlug($data['title']);
        }

        if (!empty($data['image'])) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        $data['user_id'] = Auth::id();

        return $data;
    }

    private function createUniqueSlug(string $title): string
    {
        return Str::slug(substr($title, 0, 80)) . '-' . time();
    }

    private function uploadImage($image): string
    {
        $imageName = time() . '.' . $image->extension();

        $image->storePubliclyAs('public', $imageName);

        return $imageName;
    }
}
