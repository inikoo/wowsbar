<?php

namespace App\Http\Resources\Portfolio;

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
        /** @var \App\Models\Portfolio\Snapshot $snapshot */
        $snapshot = $this;

        return [
            'slug' => $snapshot->slug,
            'current' => $snapshot->current,
            'published_at' => $snapshot->published_at,
            'published_until' => $snapshot->published_until,
            'layout' => $snapshot->layout,
            'state' => $snapshot->state,
            'comment' => $snapshot->comment,
        ];
    }
}
