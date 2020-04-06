<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateCollection;
use App\Http\Resources\State as StateResource;
use App\Http\Resources\WorkArea as WorkareaResource;
use App\State;
use Illuminate\Http\Request;

class StateController extends Controller
{

  protected function validateData()
  {
    return request()->validate([
      'description' => 'required|max:255'
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

      $states = new StateCollection(State::all());

      return $this->responseSuccess($states);
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();

      $dataStore = $this->validateData();

      $state = new State();

      $state = $state->create($dataStore);

      return $this->responseSuccess($state);
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

      $data = State::whereId($id)->first();

      if (!isset($state))
        return $this->responseNoContent();

      return $this->responseSuccess($data);
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
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  public function sections($idState)
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();


      $state = new StateResource(State::find($idState));

      $state['sections'] = [
        'href' => url('api/v2/states/' . $idState . '/sections'),
        'rel' => 'sections',
        'sections' => $state->sections->map->formatModel()
      ];

      return $this->responseSuccess($state->sections);
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }


  public function workareas($idState)
  {
    try {
      if (request()->isJson()) {

        $state = new WorkareaResource(State::find($idState));

        $state['workareas'] = [
          'href' => url('api/v2/states/' . $idState . '/workareas'),
          'rel' => 'workareas',
          'workareas' => $state->workareas->map->formatModel()
        ];

        return response()->json([
          'success' => true,
          'data' => $state->workareas,
          'error' => null,
          'statusCode' => 200
        ], 200);
      } else {
        return response()->json([
          'success' => false,
          'data' => null,
          'error' => 'Unauthorized',
          'statusCode' => 401
        ], 200);
      }
    } catch (\Exception $exception) {
      return response()->json([
        'success' => false,
        'data' => null,
        'error' => $exception->getMessage(),
        'statusCode' => 500
      ], 200);
    }
  }
}
