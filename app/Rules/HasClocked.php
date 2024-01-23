<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 15 Jan 2024 13:24:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Rules;

use App\Models\HumanResources\Clocking;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class HasClocked implements ValidationRule, ValidatorAwareRule
{
    protected Validator $validator;

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    public function validate($attribute, $value, $fail): void
    {
        $type         = Relation::getMorphedModel($value);
        $modelIDField = preg_replace('/type$/', 'id', $attribute);
        $modelID      = Arr::get($this->validator->getData(), $modelIDField);

        if (Clocking::where('subject_type', class_basename($type))->where('subject_id', $modelID)->exists()) {
            $fail('The user has been clocked.')->translate();
        }
    }
}
