<?php

namespace App\Http\Controllers;

use App\Section;
use App\Http\Resources\Section as SectionResource;
use App\Http\Resources\SectionCollection;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
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

      $sections = new SectionCollection(Section::all());

      return $this->responseSuccess($sections);
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

      $section = new Section();

      $section = $section->create($dataStore);

      return $this->responseSuccess($section->formatModel());
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

      $section = Section::whereId($id)->first();

      if (!isset($section))
        return $this->responseNoContent();

      return $this->responseSuccess($section->formatModel());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
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
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $section = Section::whereId($id)->first();

      if (!isset($section))
        return $this->responseNoContent();

      DB::beginTransaction();
      $updateData = $this->validateData();

      $section->update($updateData);

      DB::commit();

      return $this->responseSuccess($section->fresh()->formatModel());
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

      $section = Section::whereId($id)->first();

      if (!isset($section))
        return $this->responseNoContent();

      $section->delete($section);

      return $this->responseSuccess(null);
    } catch (\Exception $exception) {

      return $this->responseException($exception);
    }
  }


  /**
   * Display a list of WorkAreas from specific Section.
   *
   * @return \Illuminate\Http\Response
   */
  public function workAreas($idSection)
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();


      $section = new SectionResource(Section::find($idSection));

      if (!isset($section))
        return $this->responseNoContent();

      $section['data'] = [
        'href' => url('api/v2/sections/' . $idSection . '/work-areas'),
        'rel' => 'work-areas',
        'sections' => $section->workAreas->map->formatModel()
      ];

      return $this->responseSuccess($section->workAreas);
    } catch (\Exception $exception) {
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
      'description' => 'required|max:255',
      'state_id' => 'required|integer',
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
