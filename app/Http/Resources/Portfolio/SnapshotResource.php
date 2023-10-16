<?php

namespace App\Http\Resources\Portfolio;

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Http\Resources\HasSelfCall;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\OrganisationUser;
use Illuminate\Http\Resources\Json\JsonResource;

class SnapshotResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Helpers\Snapshot $snapshot */
        $snapshot = $this;


        $comment = $snapshot->comment;

        if ($snapshot->first_commit) {
            $comment = __('First commit');
        }

        $publisher       = '';
        $publisherAvatar = null;
        if ($snapshot->publisher_id) {
            switch ($snapshot->publisher_type) {
                case 'CustomerUser':
                    /** @var CustomerUser $customerUser */
                    $customerUser = $snapshot->publisher;

                    $publisher       = $customerUser->user->contact_name;
                    $publisherAvatar = $customerUser->user->avatarImageSources(48, 48);
                    break;
                case 'OrganisationUser':
                    /** @var OrganisationUser $organisationUser */
                    $organisationUser = $snapshot->publisher;

                    $publisher       = $organisationUser->contact_name;
                    $publisherAvatar = $organisationUser->avatarImageSources(48, 48);
            }
        }



        return [
            'slug'             => $snapshot->slug,
            'published_at'     => $snapshot->published_at,
            'published_until'  => $snapshot->published_until,
            'first_commit'     => $snapshot->first_commit,
            'recyclable'       => $snapshot->recyclable,
            'recyclable_tag'   => $snapshot->recyclable_tag,
            'layout'           => $snapshot->layout,
            'publisher'        => $publisher,
            'publisher_avatar' => $publisherAvatar,
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
                    'tooltip' => __('historic'),
                    'icon'    => 'fal fa-ghost'
                ]
            },
            'comment'          => $comment,
        ];
    }
}
