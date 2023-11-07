<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use App\Http\Resources\Portfolio\SnapshotResource;
use App\Models\Portfolio\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $live_snapshot_id
 */
class EmailTemplateResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Mail\EmailTemplate $emailTemplate */
        $emailTemplate = $this;

        $image          = null;
        $imageThumbnail = null;
        if ($emailTemplate->image) {
            $image          = (new Image())->make($emailTemplate->image->getImgProxyFilename());
            $imageThumbnail = (new Image())->make($emailTemplate->image->getImgProxyFilename())->resize(0, 48);
        }

        $publishedSnapshot = [];
        if ($emailTemplate->state == BannerStateEnum::LIVE and $this->live_snapshot_id) {
            $snapshot          = $emailTemplate->liveSnapshot;
            $publishedSnapshot = SnapshotResource::make($snapshot)->getArray();
        }

        return [
            'id'                 => $emailTemplate->id,
            'type'               => $emailTemplate->type,
            'ulid'               => $emailTemplate->ulid,
            'slug'               => $emailTemplate->slug,
            'name'               => $emailTemplate->name,
            'state'              => $emailTemplate->state,
            'state_label'        => $emailTemplate->state->labels()[$emailTemplate->state->value],
            'state_icon'         => $emailTemplate->state->stateIcon()[$emailTemplate->state->value],
            'image_thumbnail'    => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,
            'image'              => $image ? GetPictureSources::run($image) : null,
            'route'              => [
                'name'       => 'customer.banners.banners.show',
                'parameters' => [$emailTemplate->slug]
            ],
            'updated_at'         => $emailTemplate->updated_at,
            'created_at'         => $emailTemplate->created_at,
            'workshopRoute'      => [
                'name'       => 'customer.banners.banners.workshop',
                'parameters' => [$emailTemplate->slug]
            ],
            'compiled_layout'    => $emailTemplate->compiled_layout,
            'delivery_url'       => config('app.delivery_url').'/banners/'.$emailTemplate->ulid,
            'published_snapshot' => $publishedSnapshot,
            'views'              => $emailTemplate->stats?->number_views,
        ];
    }
}
