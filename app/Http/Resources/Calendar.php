<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Calendar extends JsonResource
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
            'id' => (string)$this->id,
            'type' => 'Calendars',
            'attributes' => [
                'title' => $this->title,
                'body'  => $this->body,
                'due_date' => $this->formatDate($this->due_date),
                'created_at' => $this->formatDate($this->created_at),
                'update_at' => $this->formatDate($this->updated_at),
            ]

        ];
    }

    private function formatDate($date): string
    {
        if(!$date) {
            return "";
        }
        return Carbon::parse($date)->format('l jS \of F Y h:i:s A');
    }
}
