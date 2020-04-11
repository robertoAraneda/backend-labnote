<?php

namespace App\Http\Controllers;

use App\Http\Resources\VihKeyCollection;
use App\VihKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VihKeyController extends Controller
{
  protected function validateData()
  {
    return request()->validate([
      'description' => 'required|max:255',
      'state_id' => 'required|integer'
    ]);
  }
  protected function responseSuccess($data)
  {
    return response()->json([
      'success' => true,
      'data' => $data,
      'error' => null,
      'statusCode' => 200
    ]);
  }
  protected function responseException($exception)
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => $exception->getMessage(),
      'statusCode' => 500
    ]);
  }
  protected function responseUnauthorized()
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => 'Sin autorización',
      'statusCode' => 401
    ]);
  }
  protected function responseBadRequest()
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => 'Url mal formada. Detente!!',
      'statusCode' => 400
    ]);
  }
  protected function responseNoContent()
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => 'Elemento no encontrado',
      'statusCode' => 204
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
        return $this->responseUnauthorized();

      $vihKeys = new VihKeyCollection(VihKey::all());

      return $this->responseSuccess($vihKeys);
    } catch (\Exception $exception) {
      return $this->responseException($exception);
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
        return $this->responseUnauthorized();

      $dataStore = $this->validateData();

      $vihKey = new VihKey();

      $vihKey = $vihKey->create($dataStore);

      return $this->responseSuccess($vihKey->formatModel());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
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
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $vihKey = VihKey::whereId($id)->first();

      if (!isset($vihKey))
        return $this->responseNoContent();

      return $this->responseSuccess($vihKey->formatModel());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update($id)
  {
    try {

      if (!request()->isJson())
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $vihKey = VihKey::whereId($id)->first();

      if (!isset($vihKey))
        return $this->responseNoContent();

      DB::beginTransaction();
      $dataUpdate = $this->validateData();

      $vihKey->update($dataUpdate);

      DB::commit();

      return $this->responseSuccess($vihKey->fresh()->formatModel());
    } catch (\Exception $exception) {
      DB::rollBack();
      return $this->responseException($exception);
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
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $vihKey = VihKey::whereId($id)->first();

      if (!isset($vihKey))
        return $this->responseNoContent();

      DB::beginTransaction();
      $dataDelete = $this->validateData();

      $vihKey->delete($dataDelete);

      DB::commit();

      return $this->responseSuccess($vihKey->fresh()->formatModel());
    } catch (\Exception $exception) {
      DB::rollBack();
      return $this->responseException($exception);
    }
  }
}
