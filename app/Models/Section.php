<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Section extends Model
{
  use SoftDeletes;

  protected $table = 'sections';

  protected $guarded = [];


  public function format()
  {
    return [
      'id' => $this->id,
      'description' => $this->description,
      'state' => $this->state->format(),
      'user_created' => $this->userCreated->format(),
      'user_updated' => $this->userUpdated->format(),
      'created_at' => $this->created_at != null ?  Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
      'updated_at' => $this->updated_at != null ?  Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
      'deleted_at' => $this->deleted_at != null ?  Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
      'links' => [
        'href' => url('api/v2/sections/' . $this->id),
        'rel' => 'self'
      ]
    ];
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  public function userCreated()
  {
    return $this->belongsTo(User::class);
  }

  public function userUpdated()
  {
    return $this->belongsTo(User::class);
  }

  public function workAreas()
  {
    return $this->hasMany(WorkArea::class);
  }
}
