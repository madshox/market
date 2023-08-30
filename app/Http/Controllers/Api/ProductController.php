<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FindOneRequest;
use App\Http\Requests\FindAllRequest;
use App\Models\Product;
use App\Models\User;
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
            $credentials = $request->only([
                'category_id',
                'name',
                'price',
                'description',
            ]);
            if ($this->authorize('create', Product::class)) {
                $credentials['in_stock'] = $request->in_stock;
            }
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
            $credentials = $request->only([
                'category_id',
                'name',
                'price',
                'description',
            ]);
            $item = $this->service->setById($request->post('id'))->get();
            if ($this->authorize('update', $item)) {
                $credentials['in_stock'] = $request->in_stock;
            }
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
