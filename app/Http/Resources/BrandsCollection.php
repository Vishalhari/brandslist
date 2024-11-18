<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\ModelsCollection;

class BrandsCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        $logopath = 'storage/brands/';
        return [
            'id' => $this->id,
            'brandname' => $this->brandname,
            'logo' => $logopath.$this->logo,
            'models'=>ModelsCollection::collection($this->whenLoaded('modelslist'))
            
            
            
        ];
    }
}
