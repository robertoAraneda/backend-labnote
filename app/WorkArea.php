<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class WorkArea extends Model
{
  use SoftDeletes;

  protected $table = 'work_areas';

  protected $guarded = [];

  public function formatModel()
  {
    return [
      'id' => $this->id,
      'description' => $this->description,
      'state_id' => $this->state_id,
      'created_at' => $this->created_at != null ?  Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
      'updated_at' => $this->updated_at != null ?  Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
      'deleted_at' => $this->deleted_at != null ?  Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
    ];
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  public function section()
  {
    return $this->belongsTo(Section::class);
  }
}
