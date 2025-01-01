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
    public function RedirectToProvider($provider)
    {
        return response()->json([
            'status' => true,
            'url' => Socialite::driver($provider)
                         ->stateless()
                         ->redirect()
                         ->getTargetUrl(),
        ]);
    }

    public function HandleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
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

    // public function SocialLogin(Request $request)
    // {
    //     $request->validate([
    //         'token'    => 'required',
    //         'provider' => 'required|in:google,facebook,apple',
    //     ]);
    //     try {
    //         $provider   = $request->provider;
    //         $socialUser = Socialite::driver('google')->stateless()->userFromToken('eyJhbGciOiJSUzI1NiIsImtpZCI6IjkyODg2OGRjNDRlYTZhOThjODhiMzkzZDM2NDQ1MTM2NWViYjMwZDgiLCJ0eXAiOiJKV1QifQ.eyJuYW1lIjoiU29sYWltYW4gU2lhbSIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NKekJyWDdWWEoxZlhDdnhEMC1TanVaYjFUeE1XV1NNd0UzcG85aE1NbWRvdVV2dTg0PXM5Ni1jIiwiaXNzIjoiaHR0cHM6Ly9zZWN1cmV0b2tlbi5nb29nbGUuY29tL2t3cndvb2R3b3JraW5nLWJmZDg2IiwiYXVkIjoia3dyd29vZHdvcmtpbmctYmZkODYiLCJhdXRoX3RpbWUiOjE3MzI0NDE0MjksInVzZXJfaWQiOiJqOWNFWjNvTExSWDVSb2RiMG93ellka09OYmcyIiwic3ViIjoiajljRVozb0xMUlg1Um9kYjBvd3pZZGtPTmJnMiIsImlhdCI6MTczMjQ0MTQyOSwiZXhwIjoxNzMyNDQ1MDI5LCJlbWFpbCI6InNvbGFpbWFuc2lhbTkzQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJmaXJlYmFzZSI6eyJpZGVudGl0aWVzIjp7Imdvb2dsZS5jb20iOlsiMTA2MTM3ODY3NjcwNzgxNDIxNTE4Il0sImVtYWlsIjpbInNvbGFpbWFuc2lhbTkzQGdtYWlsLmNvbSJdfSwic2lnbl9pbl9wcm92aWRlciI6Imdvb2dsZS5jb20ifX0.RRNO9RhaI6J2E8R31JoS2_TKccg01he2-8BSRMJ33wWNNVAQ7hc9rgpSBgCmKgygvKeWENzllNybKcYFzFhdxSrbmAScapd53WkPXCekcCiovAR1bO2ubBJDCFCyJKZWT4jRDz6pVnxUg2Jk6vYmVSpUSTcPaPuKO9m-qSb251r_shZeNvPcQlqZNQCeOd7xUZORh-RCJp6p9mE0Kga1aq4IPbVx2L-q66HXloV1jwOCAPjbMr94ICiRWVvhQRXLbW13jQ-3jf_KjEdvT6oWzZoZbMr8iOvvqa9JEMbmS7jF-TcAfYuJi5t1lCT1UklR5VM608oR_kigO_z50tWHmA');

    //         if ($socialUser) {
    //             $user      = User::where('email', $socialUser->email)->first();
    //             $isNewUser = false;
    //             if (!$user) {
    //                 $password = Str::random(16);
    //                 $user     = User::create([
    //                     'name'              => $socialUser->getName(),
    //                     'email'             => $socialUser->getEmail(),
    //                     'google_id'         => $socialUser->getId(),
    //                     'password'          => bcrypt($password)
    //                 ]);
    //                 $isNewUser = true;
    //             }

    //             $token = auth('api')->login($user);
    //             return response()->json([
    //                 'status'     => true,
    //                 'message'    => 'User logged in successfully.',
    //                 'code'       => 200,
    //                 'token_type' => 'bearer',
    //                 'token'      => $token,
    //                 'expires_in' => auth('api')->factory()->getTTL() * 60,
    //                 'data' => $user
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'User not found',
    //             ], 403);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Something went wrong+' . $e->getMessage(),
    //         ], 403);
    //     }
    // }
}
