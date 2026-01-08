<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'short_slug' => $this->short_slug,
            'short_url' => $this->short_url,
            'target_url' => $this->target_url,
            'created_at' => $this->created_at->toIso8601String(),
            'total_clicks' => $this->whenCounted('clicks', fn () => $this->totalClicks()),
        ];
    }
}
