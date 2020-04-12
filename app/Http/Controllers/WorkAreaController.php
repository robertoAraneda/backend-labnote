<?php

namespace App\Http\Controllers;

use App\Http\Resources\Collections\WorkareaCollection;
use App\Models\WorkArea;
use Illuminate\Support\Facades\DB;

class WorkAreaController extends Controller
{
  /**
   * List of WorkAreas.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();

      $workareas = new WorkareaCollection(WorkArea::all());

      return $this->responseSuccess($workareas);
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  /**
   * Store a WorkArea.
   *
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();

      $dataStore = $this->validateData();

      $workarea = new WorkArea();

      $workarea = $workarea->create($dataStore);

      return $this->responseSuccess($workarea->format());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  /**
   * Display one WorkArea.
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

      $workarea = WorkArea::whereId($id)->first();

      if (!isset($workarea))
        return $this->responseNoContent();

      return $this->responseSuccess($workarea->format());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  /**
   * Update a WorkArea.
   *
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

      $workarea = WorkArea::whereId($id)->first();

      if (!isset($workarea))
        return $this->responseNoContent();

      DB::beginTransaction();
      $dataUpdate = $this->validateData();

      $workarea->update($dataUpdate);

      DB::commit();

      return $this->responseSuccess($workarea->fresh()->format());
    } catch (\Exception $exception) {
      DB::rollBack();
      return $this->responseException($exception);
    }
  }

  /**
   * Remove WorkArea.
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

      $workarea = WorkArea::whereId($id)->first();

      if (!isset($workarea))
        return $this->responseNoContent();

      DB::beginTransaction();
      $dataDelete = $this->validateData();

      $workarea->delete($dataDelete);

      DB::commit();

      return $this->responseSuccess(null);
    } catch (\Exception $exception) {
      DB::rollBack();
      return $this->responseException($exception);
    }
  }

  /**
   * Validate Data.
   *
   * @return Validate
   */
  protected function validateData()
  {
    return request()->validate([
      'description' => 'required|unique|max:255',
      'state_id' => 'required|integer',
      'section_id' => 'required|integer'
    ]);
  }
  /**
   * Response an status code 200, Success.
   *
   * @param  object  $data
   * @return \Illuminate\Http\Response
   */
  protected function responseSuccess($data)
  {
    return response()->json([
      'success' => true,
      'data' => $data,
      'error' => null,
      'statusCode' => 200
    ]);
  }

  /**
   * Response an status code 500, Server Error.
   *
   * @param  Exception  $exception
   * @return \Illuminate\Http\Response
   */
  protected function responseException($exception)
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => $exception->getMessage(),
      'statusCode' => 500
    ]);
  }

  /**
   * Response an status code 401, Anauthorized.
   *
   * @return \Illuminate\Http\Response
   */
  protected function responseUnauthorized()
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => 'Sin autorización',
      'statusCode' => 401
    ]);
  }

  /**
   * Response an status code 400, Bad Request.
   *
   * @return \Illuminate\Http\Response
   */
  protected function responseBadRequest()
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => 'Url mal formada. Detente!!',
      'statusCode' => 400
    ]);
  }

  /**
   * Response an status code 204, No Content.
   *
   * @return \Illuminate\Http\Response
   */
  protected function responseNoContent()
  {
    return response()->json([
      'success' => false,
      'data' => null,
      'error' => 'Elemento no encontrado',
      'statusCode' => 204
    ]);
  }
}
