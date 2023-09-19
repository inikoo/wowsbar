<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Slide;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBannerImage
{
    use AsAction;


    public function handle(Banner $banner): Banner
    {
        $snapshot = match ($banner->state) {
            BannerStateEnum::LIVE, BannerStateEnum::RETIRED => $banner->liveSnapshot,
            default => $banner->unpublishedSnapshot,
        };

        /** @var Slide $slide */
        $slide    = $snapshot->slides()->where('visibility', true)->first();
        $image_id = $slide?->image_id;

        if (!$image_id and $banner->state == BannerStateEnum::UNPUBLISHED) {
            $image_id = Arr::get($banner->data, 'unpublished_image_id');
        }


        $banner->image_id = $image_id;
        $banner->saveQuietly();

        return $banner;
    }

    public function getCommandSignature(): string
    {
        return 'banner:image {customer} {slug}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('customer'))->firstOrFail();
        $tenant->makeCurrent();

        $banner = Banner::where('slug', $command->argument('slug'))->firstOrFail();

        $banner = $this->handle($banner);

        $command->info("Done! banner $banner->name image updated  🥳");
    }

}
