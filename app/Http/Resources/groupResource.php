<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class groupResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'course'    => $this->course ? [
                'id'   => $this->course->id,
                'name' => $this->course->name,
            ] : null,

            // قائمة المعلمين
            'teachers'  => $this->teachers->map(function ($teacher) {
                return [
                    'id'   => $teacher->id,
                    'name' => $teacher->name,
                ];
            }),

            // قائمة الطلاب
            'students'  => $this->students->map(function ($student) {
                return [
                    'id'   => $student->id,
                    'name' => $student->name,
                ];
            }),

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
