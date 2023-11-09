<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 16:15:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\HydrateModel;
use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateSentEmails;
use App\Models\Mail\Mailshot;
use Illuminate\Support\Collection;

class HydrateMailshots extends HydrateModel
{
    public function handle(Mailshot $mailshot): void
    {
        MailshotHydrateSentEmails::run($mailshot);
    }

    public string $commandSignature = 'hydrate:mailshots {slugs?*}';

    protected function getModel(string $slug): Mailshot
    {
        return Mailshot::firstWhere($slug);
    }

    protected function getAllModels(): Collection
    {
        return Mailshot::get();
    }

}