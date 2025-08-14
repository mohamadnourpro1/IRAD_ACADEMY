<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
          'name' => $this->name,
          'description' => $this->description,
          'course_category'=>$this->category->pluck('name'),
          'groups' => GroupResource::collection($this->whenLoaded('groups')),
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ];
    }
}
