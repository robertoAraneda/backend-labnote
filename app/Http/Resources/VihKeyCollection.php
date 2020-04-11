<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VihKeyCollection extends ResourceCollection
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
      'vihKeys' => $this->collection,
      'links' => [
        'href' => url('api/v2/vih-keys'),
        'rel' => 'self'
      ]
    ];
  }
}
