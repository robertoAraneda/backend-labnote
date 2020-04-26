<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class VihKey extends Model
{
  use SoftDeletes;

  protected $table = 'vih_keys';

  protected $guarded = [];

  public function format()
  {
    return [
      'links' => [
        'href' => url('api/v2/vih-keys/' . $this->id),
        'rel' => 'self'
      ],
      'vihkey' => [
        'id' => $this->id,
        'description' => $this->description,
        'state' => $this->state->format(),
        'user_created' => $this->userCreated->format(),
        'user_updated' => $this->userUpdated->format(),
        'created_at' => $this->created_at != null ?  Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
        'updated_at' => $this->updated_at != null ?  Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
        'deleted_at' => $this->deleted_at != null ?  Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null
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
}
