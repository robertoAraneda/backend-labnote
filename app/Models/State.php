<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class State extends Model
{
  use SoftDeletes;

  protected $table = 'states';

  protected $guarded = [];

  public function format()
  {
    return [
      'id' => $this->id,
      'description' => $this->description,
      'created_at' => $this->created_at != null ?  Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
      'updated_at' => $this->updated_at != null ?  Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
      'deleted_at' => $this->deleted_at != null ?  Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
      'links' => [
        'href' => route('api.states.show', ['state' => $this->id]),
        'rel' => 'self'
      ]
    ];
  }

  public function workAreas()
  {
    return $this->hasMany(WorkArea::class);
  }

  public function sections()
  {
    return $this->hasMany(Section::class);
  }

  public function vihKeys()
  {
    return $this->hasMany(VihKey::class);
  }
}
