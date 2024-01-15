<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 15 Jan 2024 13:24:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class PolyExist implements ValidationRule, ValidatorAwareRule
{
    protected Validator $validator;

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    public function validate($attribute, $value, $fail): void
    {

        $type = Relation::getMorphedModel($value);
        if (!class_exists($type)) {
            $fail('The :attribute class not found.')->translate();
        }


        $modelIDField=preg_replace('/type$/', 'id', $attribute);

        if (!Arr::has(
            $this->validator->getData(),
            $modelIDField
        )
        ) {
            $fail('The :attribute id required.')->translate();
        }

        $modelID=Arr::get($this->validator->getData(), $modelIDField);






        if (empty(resolve($type)->find($modelID))) {
            $fail('The :attribute not found.')->translate();
        }
    }
}
