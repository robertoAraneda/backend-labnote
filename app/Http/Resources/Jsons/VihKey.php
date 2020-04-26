<?php

namespace App\Http\Resources\Jsons;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VihKey extends JsonResource
{
  public $preserveKeys = true;
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
        'href' => route('api.vihKeys.show', ['vih_key' => $this->id]),
        'rel' => 'self'
      ],
      'id' => $this->id,
      'description' => $this->description,
      'state' => $this->state->format(),
      'created_at' => $this->created_at != null ?  Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
      'updated_at' => $this->updated_at != null ?  Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
      'deleted_at' => $this->deleted_at != null ?  Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null

    ];
  }
}
