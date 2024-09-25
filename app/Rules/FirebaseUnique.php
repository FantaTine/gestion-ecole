<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Facades\ReferentielFirebaseFacade;

class FirebaseUnique implements ValidationRule
{
    protected $collection;
    protected $field;
    protected $excludeId;

    public function __construct($collection, $field, $excludeId = null)
    {
        $this->collection = $collection;
        $this->field = $field;
        $this->excludeId = $excludeId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $firebase = ReferentielFirebaseFacade::getFacadeRoot();
        $items = $firebase->getAll($this->collection);

        foreach ($items as $id => $item) {
            if ($item[$this->field] === $value && $id !== $this->excludeId) {
                $fail("The $attribute has already been taken.");
                return;
            }
        }
    }
}