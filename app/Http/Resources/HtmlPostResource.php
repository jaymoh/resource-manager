<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class HtmlPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'description' => $this->description,
            'html_snippet' => $this->retrieveHtmlSnippet(),
            'relationships' => [
                'post' => new PostResource($this->whenLoaded('post')),
            ]
        ];
    }

    /**
     * Retrieve HTML Snippet from storage
     *
     * @return false|string
     */
    private function retrieveHtmlSnippet(): bool|string
    {
        $path = Post::HTML_STORAGE_PATH . '/' . $this->post_id . '.html';

        // retrieve from storage
        try {
            return file_get_contents(storage_path('app/') . $path);
        } catch (\Exception $e) {
            return '<p>No HTML Snippet found</p>';
        }
    }
}
