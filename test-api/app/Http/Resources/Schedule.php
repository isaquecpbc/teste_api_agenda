<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Schedule as ScheduleResource;

class Schedule extends JsonResource
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
            'id'                    => $this->id,
            'user'                  => $this->user,
            'user_id'               => $this->user_id,
            'title'                 => $this->title,
            'type'                  => $this->type,
            'description'           => $this->description,
            'status'                => $this->status,
            'start_at'              => $this->start_at->format('H:i d/m/Y'),
            'conclusion_at'         => $this->conclusion_at->format('H:i d/m/Y'),
            'created_at'            => $this->created_at->format('d/m/Y'),
            'updated_at'            => $this->updated_at->format('d/m/Y'),
        ];
    }
}
