<?php

namespace Domain\Category\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CategoryService
{
    private Category $model;

    public function get()
    {
        if (!isset($this->model)) {
            throw new \Exception('Category has not been set yet');
        }

        return $this->model;
    }

    public function getQuery($request = null)
    {
        return Category::query()->orderBy('id', 'desc');
    }

    public function set(Model $model)
    {
        if (isset($model)) {
            $this->model = $model;
        } else {
            throw new \Exception('Category not found');
        }
        return $this;
    }

    public function setById($id)
    {
        $model = Category::query()->where('id', $id)->first();
        if ($model) {
            $this->set($model);
        } else {
            throw new \Exception('Category not found with id:' . $id);
        }
        return $this;
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            $model = Category::create($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception("Cannot store category. Error:{$exception->getMessage()}");
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
            throw new \Exception("Cannot update category. Error:{$exception->getMessage()}");
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
