<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\EmailTemplate;

use App\Actions\InertiaAction;
use App\Http\Resources\Mail\EmailTemplateResource;
use App\Models\EmailTemplateCategory;
use App\Models\Mail\EmailTemplate;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetEmailTemplateOptions extends InertiaAction
{
    use AsObject;

    public function handle(ActionRequest $request): array
    {
        $selectOptions = [];

        /** @var \App\Models\Mail\EmailTemplate $emailTemplates */
        if($request->get('category') == null) {
            $emailTemplates = EmailTemplate::all();
        } else {
            $emailTemplates = EmailTemplateCategory::where('name', 'ILIKE', '%'.$request->get('category').'%')->first()->templates;
        }

        foreach ($emailTemplates as $template) {
            $selectOptions[$template->id] = new EmailTemplateResource($template);
        }

        return $selectOptions;
    }
}
