<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'post_type' => $this->post_type,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
            'relationships' => [
                'pdf' => new PdfPostResource($this->whenLoaded('pdfPost')),
                'link' => new LinkPostResource($this->whenLoaded('linkPost')),
                'html' => new HtmlPostResource($this->whenLoaded('htmlPost')),
            ]
        ];
    }
}
