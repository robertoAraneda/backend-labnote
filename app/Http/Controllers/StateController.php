<?php

namespace App\Http\Controllers;

use App\Http\Resources\Collections\StateCollection;
use App\Http\Resources\Jsons\State as StateResource;
use App\Http\Resources\Jsons\WorkArea as WorkareaResource;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

      $valitate = $this->validateData();

      if ($valitate->fails())
        return $this->responseException($valitate->errors()->first());

      $state = new State();

      $state = $state->create(request()->all());

      return $this->responseSuccess($state->format());
    } catch (\Exception $exception) {

      return $this->responseException($exception->getMessage());
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

      return $this->responseSuccess($state->format());
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

      $valitate = $this->validateData();

      if ($valitate->fails())
        return $this->responseException($valitate->errors()->first());

      $state->update(request()->all());

      DB::commit();

      return $this->responseSuccess($state->fresh()->format());
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
      $valitate = $this->validateData();

      if ($valitate->fails())
        return $this->responseException($valitate->errors()->first());

      $state->delete(request()->all());

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
        'state' => $state,
        'href' => route('api.states.sections', ['state' => $state->id]),
        'rel' => 'sections',
        'sections' => $state->sections->map->format()
      ];

      return $this->responseSuccess($state->sections);
    } catch (\Exception $exception) {
      return $this->responseException($exception->getMessage());
    }
  }

  public function workareas($idState)
  {
    try {
      if (request()->isJson()) {

        $state = new WorkareaResource(State::find($idState));

        $state['workareas'] = [
          'state' => $state,
          'href' => route('api.states.workAreas', ['state' => $state->id]),
          'rel' => 'workareas',
          'workareas' => $state->workareas->map->format()
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
        'href' => route('api.states.vihKeys', ['state' => $state->id]),
        'rel' => 'vih-keys',
        'vihKeys' => $state->vihKeys->map->format()
      ];

      return $this->responseSuccess($state->vihKeys);
    } catch (\Exception $exception) {
      return $this->responseException($exception);
    }
  }

  protected function validateData()
  {

    return Validator::make(request()->all(), [
      'description' => 'required|unique:states|max:255'
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
      'error' => $exception,
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
