<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlotTimeResource extends JsonResource
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
            'id'            => $this->id,
            'slot_id'       => $this->slot_id,
            'start_time'    => substr($this->start_time, 0, 5),
            'end_time'      => substr($this->end_time, 0, 5),
        ];
    }
}
