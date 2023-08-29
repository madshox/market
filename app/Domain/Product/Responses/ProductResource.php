<?php

namespace Domain\Product\Responses;

use Domain\Category\Responses\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        return [
            'id' => $this->id,
            'name' => $this->name[$locale],
            'category' => new CategoryResource($this->category),
            'price' => $this->price,
            'image' => $this->image ? env('APP_URL') . '/uploads/' . $this->image : null,
            'description' => $this->description[$locale],
            'in_stock' => $this->in_stock,
        ];
    }
}
