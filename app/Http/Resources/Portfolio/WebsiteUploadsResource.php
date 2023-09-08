<?php

namespace App\Http\Resources\Portfolio;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @property string $original_filename
 * @property string $number_rows
 * @property string $filename
 * @property string $uploaded_at
 */
class WebsiteUploadsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'original_filename' => $this->original_filename,
            'filename' => $this->filename,
            'number_rows' => $this->number_rows,
            'uploaded_at' => $this->uploaded_at
        ];
    }
}
