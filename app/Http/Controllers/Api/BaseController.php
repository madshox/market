<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function success(mixed $data = [], string $message = 'Success', int $status = 200): object
    {
        return response()->json([
            'message' => $this->translate($message),
        ], $status);
    }

    public function failure(mixed $data = [], string $message = 'Failure', int $status = 404): object
    {
        return response()->json([
            'message' => $this->translate($message),
        ], $status);
    }

    public function error(string $message = 'Failure', int $status = 400): object
    {
        return response()->json([
            'message' => $this->translate($message),
        ], $status);
    }

    protected function get($resource, $items): array
    {
        return [
            'result' => $resource::collection($items)
        ];
    }

    protected function paginated($resource, $items, $status_code = 200): object
    {
//        if (count($items)) {
            return response()->json([
                'pagination' => [
                    'current' => $items->currentPage(),
                    'previous' => $items->currentPage() > 1 ? $items->currentPage() - 1 : 0,
                    'next' => $items->hasMorePages() ? $items->currentPage() + 1 : 0,
                    'perPage' => $items->perPage(),
                    'totalPage' => $items->lastPage(),
                    'totalItem' => $items->total(),
                ],
                'result' => $resource::collection($items->items())
            ], $status_code);
//        } else {
//            return $this->error();
//        }
    }

    protected function singleItem($resource, $item, $status_code = 200): object
    {
        return response()->json([
            "result" => new $resource($item)
        ], $status_code);
    }

    protected function response($resource, $item, $status_code = 200): object
    {
        return response()->json([
            "result" => $resource::collection($item)->all()
        ], $status_code);
    }

    public function translate(string $key): string
    {
        return __($key);
    }
}
