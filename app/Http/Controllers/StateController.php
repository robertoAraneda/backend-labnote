<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateCollection;
use App\Http\Resources\State as StateResource;
use App\Http\Resources\WorkArea as WorkareaResource;
use App\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
      if (request()->isJson()) {
        return response()->json([
          'success' => true,
          'data' => new StateCollection(State::all()),
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

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
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
      if (request()->isJson()) {

        $state = new StateResource(State::find($idState));

        $state['sections'] = [
          'href' => url('api/v2/states/' . $idState . '/sections'),
          'rel' => 'sections',
          'sections' => $state->sections->map->formatModel()
        ];

        return response()->json([
          'success' => true,
          'data' => $state->sections,
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
