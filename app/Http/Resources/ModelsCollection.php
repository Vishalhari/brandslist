<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ModelsCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logopath = 'public/storage/models/';
        return [
            'id' => $this->id,
            'modelname' => $this->modelname,
            'year' => $this->manufacture_year,
            'image' => $logopath.$this->modelimage,
        ];
    }
}
