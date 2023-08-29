<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FindOneRequest;
use App\Http\Requests\FindAllRequest;
use Illuminate\Support\Facades\Storage;
use Domain\Product\Services\ProductService;
use Domain\Product\Responses\ProductResource;
use Domain\Product\Requests\ProductStoreRequest;
use Domain\Product\Requests\ProductUpdateRequest;

class ProductController extends BaseController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }

    public function index(FindAllRequest $request): object
    {
        try {
            $items = $this->service->getQuery($request)->paginate($request->post('limit'));
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->paginated(ProductResource::class, $items);
    }

    public function show(FindOneRequest $request)
    {
        try {
            $item = $this->service->setById($request->post('id'))->get();
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(ProductResource::class, $item);
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            $credentials = $request->all();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'uploads');
                $credentials['image'] = $imagePath;
            }
            $item = $this->service->create($credentials)->get();
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(ProductResource::class, $item, 201);
    }

    public function update(ProductUpdateRequest $request)
    {
        try {
            $credentials = $request->all();
            $item = $this->service->setById($request->post('id'))->get();
            if ($request->hasFile('image')) {
                if ($item->image) {
                    Storage::disk('uploads')->delete($item->image);
                }

                $imagePath = $request->file('image')->store('products', 'uploads');
                $credentials['image'] = $imagePath;
            }
            $item->update($credentials);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(ProductResource::class, $item, 202);
    }

    public function destroy(FindOneRequest $request)
    {
        try {
            $item = $this->service->setById($request->post('id'))->get();
            if ($item->image) {
                Storage::disk('uploads')->delete($item->image);
            }
            $item->delete();
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }

        return abort(204);
    }
}
