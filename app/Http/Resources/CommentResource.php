<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'id' => $this->resource['id'],
            'post_id' => (isset($this->resource['postId']) ? $this->resource['postId'] : $this->resource['post_id']),
            'name' => $this->resource['name'],
            'email' => $this->resource['email'],
            'body' => (isset($this->resource['body']) ? $this->resource['body'] : $this->resource['comment']),
        ];
    }
}
