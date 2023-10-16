<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 13:51:20 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Models\Media\Media;
use App\Models\Web\Website;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @property \Exception $exception
 */
class SetInitialWebsiteLogo
{
    use AsAction;

    private Exception $exception;

    public function handle(Website $website): ?Website
    {
        try {
            /** @var Media $media */

            $media = $website->addMediaFromUrl("https://api.dicebear.com/7.x/initials/svg?backgroundType=gradientLinear&seed=".$website->name)
                ->preservingOriginal()
                ->usingName($website->slug."-logo")
                ->usingFileName($website->slug."-logo.sgv")
                ->toMediaCollection('logo');

            $logoID = $media->id;

            $website->logo_id = $logoID;
            $website->saveQuietly();
        } catch (Exception $e) {
            $this->exception = $e;

            return null;
        }

        return $website;
    }



}
