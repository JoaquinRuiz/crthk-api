<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user_id' => (isset($this->resource['userId']) ? $this->resource['userId'] : $this->resource['user_id']),
            'title' => $this->resource['title'],
            'body' => (isset($this->resource['body']) ? $this->resource['body'] : $this->resource['content']),
        ];
    }
}
