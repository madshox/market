<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotSelfParent implements Rule
{
    protected int $categoryId;
    protected int $parentId;

    public function __construct($categoryId, $parentId)
    {
        $this->categoryId = $categoryId;
        $this->parentId = $parentId;
    }

    public function passes($attribute, $value): bool
    {
        return $this->categoryId != $this->parentId;
    }

    public function message()
    {
        return "A category cannot have itself as its parent.";
    }
}
