<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FindAllRequest;
use App\Http\Requests\FindOneRequest;
use Illuminate\Support\Facades\Storage;
use Domain\Category\Services\CategoryService;
use Domain\Category\Responses\CategoryResource;
use Domain\Category\Requests\CategoryStoreRequest;
use Domain\Category\Requests\CategoryUpdateRequest;

class CategoryController extends BaseController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    public function index(FindAllRequest $request): object
    {
        try {
            $items = $this->service->getQuery($request)->paginate($request->post('limit'));
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->paginated(CategoryResource::class, $items);
    }

    public function show(FindOneRequest $request)
    {
        try {
            $item = $this->service->setById($request->post('id'))->get();
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(CategoryResource::class, $item);
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $credentials = $request->all();
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('categories', 'uploads');
                $credentials['icon'] = $iconPath;
            }
            $item = $this->service->create($credentials)->get();
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(CategoryResource::class, $item, 201);
    }

    public function update(CategoryUpdateRequest $request)
    {
        try {
            $credentials = $request->all();
            $item = $this->service->setById($request->post('id'))->get();
            if ($request->hasFile('icon')) {
                if ($item->icon) {
                    Storage::disk('uploads')->delete($item->icon);
                }

                $iconPath = $request->file('icon')->store('categories', 'uploads');
                $credentials['icon'] = $iconPath;
            }
            $item->update($credentials);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(CategoryResource::class, $item, 202);
    }

    public function destroy(FindOneRequest $request)
    {
        try {
            $item = $this->service->setById($request->post('id'))->get();
            if ($item->icon) {
                Storage::disk('uploads')->delete($item->icon);
            }
            $item->delete();
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }

        return abort(204);
    }
}
