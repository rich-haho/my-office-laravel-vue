<?php

namespace App\Http\Resources;

use App\Models\SlotBooking;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
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
            'id'                => $this->id,
            'product_id'        => $this->product_id,
            'days'              => $this->days,
            'start_date'        => $this->start_date,
            'end_date'          => $this->end_date,
            'min_participant'   => $this->min_participant,
            'max_participant'   => $this->max_participant,
            'space_available'   => $this->spaceAvailable(),
            'times'             => SlotTimeResource::collection($this->slotTimes)
        ];
    }

    /**
     * @return array
     */
    private function spaceAvailable()
    {
        $array = [];
        $result = SlotBooking::select(['date', 'start_time', 'end_time', \DB::raw('SUM(quantity) as quantity')])
            ->where('slot_id', $this->id)
            ->groupBy(['date', 'start_time', 'end_time'])
            ->get();

        foreach ($result as $value) {
            if ($value['start_time'] === null && $value['end_time'] === null) {
                if (isset($array[$value['date']])) {
                    $array[$value['date']] -= $value['quantity'];
                } else {
                    $array[$value['date']] = $this->max_participant - $value['quantity'];
                }
            } else {
                $start_time = substr($value['start_time'], 0, 5);
                $end_time = substr($value['end_time'], 0, 5);

                if (isset($array[$value['date']][$start_time . ' - ' . $end_time])) {
                    $array[$value['date']][$start_time . ' - ' . $end_time] -= $value['quantity'];
                } else {
                    $array[$value['date']][$start_time . ' - ' . $end_time] = $this->max_participant
                        - $value['quantity'];
                }
            }
        }

        return $array;
    }
}
