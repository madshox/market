<?php

namespace Domain\Category\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent' => new CategoryResource($this->parent),
            'icon' => $this->icon ? env('APP_URL') . '/uploads/' . $this->icon : null,
        ];
    }
}
