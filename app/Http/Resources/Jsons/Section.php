<?php

namespace App\Http\Resources\Jsons;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Section extends JsonResource
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
      'links' => [
        'href' => route('api.sections.show', ['section' => $this->id]),
        'rel' => 'self'
      ],
      'id' => $this->id,
      'description' => $this->description,
      'state' => $this->state->format(),
      'user_created' => $this->userCreated->format(),
      'user_updated' => $this->userUpdated->format(),
      'created_at' => $this->created_at != null ?  Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
      'updated_at' => $this->updated_at != null ?  Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
      'deleted_at' => $this->deleted_at != null ?  Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null

    ];
  }
}
