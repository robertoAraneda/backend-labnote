<?php

namespace App\Http\Controllers;

use App\Helpers\MakeResponse;
use App\Http\Resources\Collections\VihKeyCollection;
use App\Models\VihKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VihKeyController extends Controller
{


  protected function validateData($request)
  {

    return Validator::make($request, [
      'description' => 'required|unique:vih_keys|max:255',
      'state_id' => 'required|integer'
    ]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      if (!request()->isJson())
        return MakeResponse::unauthorized();

      $vihKeys = new VihKeyCollection(VihKey::all());

      return MakeResponse::success($vihKeys);
    } catch (\Exception $exception) {
      return MakeResponse::exception($exception->getMessage());
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    try {
      if (!request()->isJson())
        return MakeResponse::unauthorized();

      $valitate = $this->validateData(request()->all());

      if ($valitate->fails())
        return MakeResponse::exception($valitate->errors());

      $vihKey = new VihKey();

      $vihKey = $vihKey->create(request()->all());

      return MakeResponse::success($vihKey->format());
    } catch (\Exception $exception) {
      return MakeResponse::exception($exception->getMessage());
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    try {
      if (!request()->isJson())
        return MakeResponse::unauthorized();

      if (!is_numeric($id))
        return MakeResponse::badRequest();

      $vihKey = VihKey::whereId($id)->first();

      if (!isset($vihKey))
        return MakeResponse::noContent();

      return MakeResponse::success($vihKey->format());
    } catch (\Exception $exception) {
      return MakeResponse::exception($exception->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    try {

      if (!request()->isJson())
        return MakeResponse::unauthorized();

      if (!is_numeric($id))
        return MakeResponse::badRequest();

      $vihKey = VihKey::whereId($id)->first();

      if (!isset($vihKey))
        return MakeResponse::noContent();

      DB::beginTransaction();
      $valitate = $this->validateData(request()->all());

      if ($valitate->fails())
        return MakeResponse::exception($valitate->errors());

      $vihKey->update(request()->all());

      DB::commit();

      return MakeResponse::success($vihKey->fresh()->format());
    } catch (\Exception $exception) {
      DB::rollBack();
      return MakeResponse::exception($exception->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {

      if (!request()->isJson())
        return MakeResponse::unauthorized();

      if (!is_numeric($id))
        return MakeResponse::badRequest();

      $vihKey = VihKey::whereId($id)->first();

      if (!isset($vihKey))
        return MakeResponse::noContent();

      DB::beginTransaction();

      $vihKey->user_deleted_id = auth()->id();
      $vihKey->update($vihKey);

      $vihKey->delete($vihKey);

      DB::commit();

      return MakeResponse::success(null);
    } catch (\Exception $exception) {
      DB::rollBack();
      return MakeResponse::exception($exception->getMessage());
    }
  }
}
