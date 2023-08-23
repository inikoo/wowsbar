<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Firebase;

use Kreait\Firebase\Database\RuleSet;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ChangeRulesFirebase
{
    use AsObject;
    use AsAction;
    use WithAttributes;

    public function handle($tenant): void
    {
        $database = app('firebase.database');

        $ruleSet = RuleSet::fromArray([
            'rules' => json_encode($this->rules($tenant))
        ]);
        $database->updateRules($ruleSet);
    }

    public function rules($tenant): array
    {
        return [
            '.read' => "if request.auth.uid == $tenant->slug",
            '.write' => "if request.auth.uid == $tenant->slug"
        ];
    }
}
