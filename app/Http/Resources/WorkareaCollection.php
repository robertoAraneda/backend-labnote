<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkareaCollection extends ResourceCollection
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
      'workAreas' => $this->collection,
      'links' => [
        'href' => url('api/v2/work-areas'),
        'rel' => 'self'
      ]
    ];
  }
}
