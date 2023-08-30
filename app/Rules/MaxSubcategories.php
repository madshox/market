<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class MaxSubcategories implements Rule
{
    protected int $maxCount;
    protected int $parentId;
    protected int | null $categoryId;

    public function __construct($maxCount, $parentId, $categoryId = null)
    {
        $this->maxCount = $maxCount;
        $this->parentId = $parentId;
        $this->categoryId = $categoryId;
    }

    public function passes($attribute, $value): bool
    {
        $subcategoriesCount = Category::query()->where('parent_id', $this->parentId);
        if ($this->categoryId != null) {
            $subcategoriesCount = $subcategoriesCount->where('id', '<>', $this->categoryId);
        }
        $subcategoriesCount = $subcategoriesCount->count();
        return $subcategoriesCount + 1 <= $this->maxCount;
    }

    public function message(): string
    {
        return "The :attribute must have up to {$this->maxCount} subcategories under the specified parent.";
    }
}
