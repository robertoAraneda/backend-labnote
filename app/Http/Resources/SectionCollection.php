<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SectionCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'sections' => $this->collection,
      'links' => [
        'href' => url('api/v2/sections'),
        'rel' => 'self'
      ]
    ];
  }
}
