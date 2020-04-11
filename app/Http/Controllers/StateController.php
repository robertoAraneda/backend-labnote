<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateCollection;
use App\Http\Resources\State as StateResource;
use App\Http\Resources\WorkArea as WorkareaResource;
use App\State;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{


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

  public function store()
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();

      $dataStore = $this->validateData();

      $state = new State();

      $state = $state->create($dataStore);

      return $this->responseSuccess($state->formatModel());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }


  public function show($id)
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $state = State::whereId($id)->first();

      if (!isset($state))
        return $this->responseNoContent();

      return $this->responseSuccess($state->formatModel());
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  public function update($id)
  {
    try {

      if (!request()->isJson())
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $state = State::whereId($id)->first();

      if (!isset($state))
        return $this->responseNoContent();

      DB::beginTransaction();
      $dataUpdate = $this->validateData();

      $state->update($dataUpdate);

      DB::commit();

      return $this->responseSuccess($state->fresh()->formatModel());
    } catch (\Exception $exception) {
      DB::rollBack();
      return $this->responseException($exception);
    }
  }

  public function destroy($id)
  {
    try {

      if (!request()->isJson())
        return $this->responseUnauthorized();

      if (!is_numeric($id))
        return $this->responseBadRequest();

      $state = State::whereId($id)->first();

      if (!isset($state))
        return $this->responseNoContent();

      DB::beginTransaction();
      $dataDelete = $this->validateData();

      $state->delete($dataDelete);

      DB::commit();

      return $this->responseSuccess(null);
    } catch (\Exception $exception) {
      DB::rollBack();
      return $this->responseException($exception);
    }
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

        return $this->responseSuccess($state->workareas);
      } else {
        return $this->responseUnauthorized();
      }
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  public function vihKeys($idState)
  {
    try {
      if (!request()->isJson())
        return $this->responseUnauthorized();


      $state = new StateResource(State::find($idState));

      $state['vihKeys'] = [
        'state' => $state,
        'href' => url('api/v2/states/' . $idState . '/vih-keys'),
        'rel' => 'vih-keys',
        'vihKeys' => $state->vihKeys->map->formatModel()
      ];

      return $this->responseSuccess($state->vihKeys);
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

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
}
