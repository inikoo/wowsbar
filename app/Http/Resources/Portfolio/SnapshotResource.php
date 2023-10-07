<?php

namespace App\Http\Resources\Portfolio;

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\Auth\CustomerUser;
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


        $comment = $snapshot->comment;

        if ($snapshot->first_commit) {
            $comment = __('First commit');
        }

        $publisher        = '';
        $publisher_avatar = null;
        if ($snapshot->user_id) {
            /** @var CustomerUser $customerUser */
            $customerUser = $snapshot->user;

            $publisher        = $customerUser->user->contact_name;
            $publisher_avatar = $customerUser->user->avatarImageSources(48, 48);
        }


        return [
            'slug'             => $snapshot->slug,
            'published_at'     => $snapshot->published_at,
            'published_until'  => $snapshot->published_until,
            'layout'           => $snapshot->layout,
            'publisher'        => $publisher,
            'publisher_avatar' => $publisher_avatar,
            'state'            => match ($snapshot->state) {
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
                    'icon'    => 'fal fa-ghost'
                ]
            },
            'comment'          => $comment,
        ];
    }
}
