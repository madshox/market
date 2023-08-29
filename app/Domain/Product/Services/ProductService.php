<?php

namespace Domain\Product\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProductService
{
    private Product $model;
//    private $private_key_name = 'id';
//
//    public function setPrivateKeyName($private_key_name)
//    {
//        $this->private_key_name = $private_key_name;
//        return $this;
//    }

    public function get()
    {
        if (!isset($this->model)) {
            throw new \Exception('Product has not been set yet');
        }

        return $this->model;
    }

    public function getQuery($request = null)
    {
        $category_id = $request->category_id??null;
        return Product::query()
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })
            ->orderBy('id', 'desc');
    }

    public function set(Model $model)
    {
        if (isset($model)) {
            $this->model = $model;
        } else {
            throw new \Exception('Product not found');
        }
        return $this;
    }

    public function setById($id)
    {
        $model = Product::query()->where('id', $id)->first();
        if ($model) {
            $this->set($model);
        } else {
            throw new \Exception('Product not found with id:' . $id);
        }
        return $this;
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $model = Product::create($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception("Cannot store product. Error:{$exception->getMessage()}");
        }
        DB::commit();
        $this->set($model);
        return $this;
    }

    public function update($data)
    {
        DB::beginTransaction();
        try {
            $this->get()->update($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception("Cannot update product. Error:{$exception->getMessage()}");
        }
        DB::commit();
        return $this;
    }

    public function delete()
    {
        $this->get()->delete();
        return $this;
    }
}
