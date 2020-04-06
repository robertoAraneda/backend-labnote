<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\User;
use GuzzleHttp\Client;

class AuthController extends Controller
{
  /**
   * Create user
   *
   * @param  [string] name
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @return [string] message
   */
  public function signup(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|string|email|unique:users',
      'password' => 'required|string|confirmed'
    ]);
    $user = new User([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password)
    ]);
    $user->save();
    return response()->json([
      'message' => 'Successfully created user!'
    ], 201);
  }

  /**
   * Login user and create token
   *
   * @param  [string] email
   * @param  [string] password
   * @param  [boolean] remember_me
   * @return [string] access_token
   * @return [string] token_type
   * @return [string] expires_at
   */
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string'
    ]);
    $credentials = request(['email', 'password']);
    if (!Auth::attempt($credentials))
      return response()->json([
        'message' => 'Usuario o contraseña incorrectos'
      ], 401);
    $user = $request->user();
    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->token;
    if ($request->remember_me)
      $token->expires_at = Carbon::now()->addWeeks(1);
    $token->save();
    return response()->json([
      'access_token' => $tokenResult->accessToken,
      'token_type' => 'Bearer',
      'expires_at' => Carbon::parse(
        $tokenResult->token->expires_at
      )->toDateTimeString()
    ]);
  }

  /**
   * Logout user (Revoke the token)
   *
   * @return [string] message
   */
  public function logout(Request $request)
  {
    $request->user()->token()->revoke();
    return response()->json([
      'message' => 'Successfully logged out'
    ]);
  }

  /**
   * Get the authenticated User
   *
   * @return [json] user object
   */
  public function user(Request $request)
  {
    return response()->json($request->user());
  }

  // public function login(Request $request)
  // {

  //   try {
  //     $http = new Client;

  //     $response = $http->post(config('services.passport.login_endpoint'), [
  //       'form_params' => [
  //         'grant_type' => 'password',
  //         'client_id' => config('services.passport.client_id'),
  //         'client_secret' => config('services.passport.client_secret'),
  //         'email' => $request->email,
  //         'password' => $request->password,
  //       ]
  //     ]);

  //     return $response->getBody();
  //   } catch (\GuzzleHttp\Exception\BadResponseException $e) {

  //     if ($e->getCode() === 400) {
  //       return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
  //     } else if ($e->getCode() === 401) {
  //       return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
  //     }

  //     return response()->json('Something went wrong on the server.', $e->getCode());
  //   }
  // }

  // public function logout()
  // {
  //   auth()->user()->tokens->each(function ($token, $key) {
  //     $token->delete();
  //   });

  //   return response()->json('Logged out successfully', 200);
  // }
}
