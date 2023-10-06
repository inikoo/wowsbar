<?php

namespace App\Http\Resources\Portfolio;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SnapshotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Helpers\Snapshot $snapshot */
        $snapshot = $this;


        $comment=$snapshot->comment;

        if(!$comment and  $snapshot->state==SnapshotStateEnum::LIVE){
            $comment=__('First commit');
        }




        return [
            'slug'            => $snapshot->slug,
            'current'         => $snapshot->current,
            'published_at'    => $snapshot->published_at,
            'published_until' => $snapshot->published_until,
            'layout'          => $snapshot->layout,
            'state'           => match ($snapshot->state) {
                SnapshotStateEnum::LIVE => [
                    'tooltip' => __('live'),
                    'icon'    => 'fal fa-broadcast-tower',
                    'class'   => 'text-green-600 animate-pulse'
                ],
                SnapshotStateEnum::UNPUBLISHED => [
                    'tooltip' => __('unpublished'),
                    'icon'    => 'fal fa-seedling',
                    'class'   => 'text-indigo-500'
                ],
                SnapshotStateEnum::HISTORIC => [
                    'tooltip' => __('retired'),
                    'icon'    => 'fal fa-eye-slash'
                ]
            },
            'comment'         => $comment,
        ];
    }
}
