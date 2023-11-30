<?php

namespace App\Http\Resources;

use App\Http\Resources\Subscriber as SubscriberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Tag extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subscribers' => SubscriberResource::collection($this->whenLoaded('subscribers')),
            'created_at' => $this->created_at->toDateTimeString(),
            'update_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
