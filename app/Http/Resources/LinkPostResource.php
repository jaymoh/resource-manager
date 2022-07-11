<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LinkPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'url' => $this->url,
            'open_in_new_tab' => $this->open_in_new_tab,
            'relationships' => [
                'post' => new PostResource($this->whenLoaded('post')),
            ]
        ];
    }
}
