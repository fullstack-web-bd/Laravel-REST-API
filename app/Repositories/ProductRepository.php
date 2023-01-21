<?php

namespace App\Repositories;

use Exception;
use App\Interfaces\CrudInterface;
use App\Interfaces\DBPreparableInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $product = Product::find($id);

        if (empty($product)) {
            throw new Exception("Product does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $product;
    }

    public function create(array $data): ?Product
    {
        $data = $this->prepareForDB($data);

        return Product::create($data);
    }

    public function update(int $id, array $data): ?Product
    {
        $product = $this->getById($id);

        $updated = $product->update($this->prepareForDB($data, $product));

        if ($updated) {
            $product = $this->getById($id);
        }

        return $product;
    }

    public function delete(int $id): ?Product
    {
        $product = $this->getById($id);

        $this->deleteImage($product->image_url);

        $deleted = $product->delete();

        if (!$deleted) {
            throw new Exception("Product could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $product;
    }

    public function prepareForDB(array $data, ?Product $product = null): array
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->createUniqueSlug($data['title']);
        }

        if (!empty($data['image'])) {
            if (!is_null($product)) {
                $this->deleteImage($product->image_url);
            }
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

    private function deleteImage(?string $imageUrl): void
    {
        if(!empty($imageUrl)) {
            $imageName = ltrim(strstr($imageUrl, 'storage/'), 'storage/');

            if(!empty($imageName) && Storage::exists('public/' . $imageName)) {
                Storage::delete('public/' . $imageName);
            }
        }
    }
}
