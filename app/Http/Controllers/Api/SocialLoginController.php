<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    // public function RedirectToProvider($provider)
    // {
    //     return Socialite::driver($provider)->redirect();
    // }

    // public function HandleProviderCallback($provider)
    // {
    //     $socialUser = Socialite::driver($provider)->stateless()->user();
    //     // dd($socialUser);
    // }

    public function SocialLogin(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'provider' => 'required|in:google,facebook,apple',
        ]);
        try {
            $provider   = $request->provider;
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);
            if ($socialUser) {
                $user      = User::where('email', $socialUser->email)->first();
                $isNewUser = false;
                if (!$user) {
                    $password = Str::random(16);
                    $user     = User::create([
                        'name'              => $socialUser->getName(),
                        'email'             => $socialUser->getEmail(),
                        'google_id'         => $socialUser->getId(),
                        'password'          => bcrypt($password)
                    ]);
                    $isNewUser = true;
                }

                $token = auth('api')->login($user);
                return response()->json([
                    'status'     => true,
                    'message'    => 'User logged in successfully.',
                    'code'       => 200,
                    'token_type' => 'bearer',
                    'token'      => $token,
                    'expires_in' => auth('api')->factory()->getTTL() * 60,
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'User not found',
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong+' . $e->getMessage(),
            ], 403);
        }
    }
}
