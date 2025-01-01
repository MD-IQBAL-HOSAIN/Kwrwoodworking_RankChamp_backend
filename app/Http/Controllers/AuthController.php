<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Custom validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // If validation fails, return the errors with a 422 Unprocessable Entity status code
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Retrieve credentials from the validated data
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate using JWTAuth
        $token = JWTAuth::attempt($credentials);

        // If token is not generated, return an unauthorized error
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        // Fetch the authenticated user using JWTAuth
        $user = JWTAuth::user();

        // Return successful login response with user details and token
        return response()->json([
            'status' => 'success',
            'message' => 'Login Successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }


    public function register(Request $request)
    {
        // Custom validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // If validation fails, return a 422 Unprocessable Entity response with errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing the password
        ]);

        // Generate the JWT token for the user
        $token = JWTAuth::fromUser($user);

        // Return success response with user data and JWT token
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successfully done',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }


    public function logout()
    {
        $user = JWTAuth::user();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'status' => 'success',
            'user_name' => $user->name,
            'message' => 'Successfully logged out',
        ]);
    }


    //for new token create
    public function refresh()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return response()->json([
            'status' => 'success',
            'user' => JWTAuth::user(),
            'authorisation' => [
                'token' => $newToken,
                'type' => 'bearer',
            ]
        ]);
    }

    //show all user
    /*  public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'message' => 'User list retrieved successfully',
            'users' => $users,
        ]);
    } */

    //Account Delete functions
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // Revoke all tokens before deleting the account
        $user->tokens()->delete();

        // Delete the user account
        $user->delete();

        return response()->json([
            'status' => 'success',
            'user_name' => $user->name,
            'message' => 'Account deleted successfully'
        ]);
    }

    public function ProfileUpdate(Request $request)
    {
        // Get the currently authenticated user
        $authenticatedUser = User::find(auth()->user()->id);

        // Custom validation rules for profile update
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $authenticatedUser->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // If validation fails, return a 422 response with error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile Update Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the user's profile information
        $authenticatedUser->name = $request->name;
        $authenticatedUser->email = $request->email;

        // Handle image upload if there's a new image
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($authenticatedUser->image && File::exists(public_path($authenticatedUser->image))) {
                File::delete(public_path($authenticatedUser->image));
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('/profile/image/');

            // Move the image to the public storage folder
            $image->move($imagePath, $imageName);

            // Save the path of the image in the database
            $authenticatedUser->image = '/profile/image/' . $imageName;
        }

        // Save the updated user data
        $authenticatedUser->save();

        // Return success response with the updated user data
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $authenticatedUser->only(['name', 'email', 'image'])
        ]);
    }

    // password change
    public function ChangePassword(Request $request)
    {
        // Create custom validator using Validator facade
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors
            return response()->json([
                'status' => 'error',
                'message' => 'password change Validation failed',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity status code
        }

        // Authenticate the user using JWT
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or unauthorized'
            ], 401);
        }

        // Check if the old password matches the current password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Old password is incorrect'
            ], 400);
        }

        // Hash the new password and save it to the database
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'user_name' => $user->name,
            'message' => 'Password changed successfully'
        ], 200);
    }



    // Forgot Password API - send OTP to email
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors
            return response()->json([
                'status' => 'error',
                'message' => 'Forget Password Validation failed',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity status code
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found with this email address.'
            ], 404);
        }

        // Generate a 6-digit reset token
        $token = rand(100000, 999999);

        // Store the token and expiry time in the database
        $user->password_reset_token = $token;
        $user->password_reset_token_expiry = now()->addMinutes(5);  // Token expires after 5 minutes
        $user->save();

        // Send token to the user's email (using Queue)
        Mail::to($user->email)->queue(new PasswordResetMail($token));

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset OTP has been sent to your email.'
        ], 200);
    }



    // OTP Verification API - Verify OTP sent to email
    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors
            return response()->json([
                'status' => 'error',
                'message' => 'OTP verify Validation failed',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity status code
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found with this email address.'
            ], 404);
        }

        // Check if the OTP matches
        if ($user->password_reset_token !== $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP.'
            ], 400);
        }

        // Check if the OTP has expired
        if ($user->password_reset_token_expiry < now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP has expired.'
            ], 400);
        }

        // OTP is valid, proceed to allow password reset
        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified successfully. You can now reset your password.'
        ], 200);
    }



    // Password Reset API - Reset user password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors
            return response()->json([
                'status' => 'error',
                'message' => 'password reset Validation failed',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity status code
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found with this email address.'
            ], 404);
        }

        // Check if OTP verification is done
        if ($user->password_reset_token === null || $user->password_reset_token_expiry < now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP verification failed or expired. Please request a new OTP.'
            ], 400);
        }

        // If OTP is verified and not expired, proceed with password reset
        $user->password = Hash::make($request->password); // Hash the new password
        $user->password_reset_token = null; // Clear the token after password reset
        $user->password_reset_token_expiry = null; // Clear the expiry
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password has been successfully reset.'
        ], 200);
    }


    // Resend OTP API - resend OTP to email if expired or not sent previously
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resend OTP Validation failed',
                'errors' => $validator->errors(),
            ], 422); 
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found with this email address.'
            ], 404);
        }

        // Check if there's a valid reset token
        if ($user->password_reset_token_expiry && $user->password_reset_token_expiry > now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'A reset token has already been sent and is still valid.'
            ], 400);
        }

        // Generate a new 6-digit reset token
        $token = rand(100000, 999999);

        // Store the new token and set expiry time
        $user->password_reset_token = $token;
        $user->password_reset_token_expiry = now()->addMinutes(5);  // Token expires after 5 minutes
        $user->save();

        // Send the new token to the user's email
        Mail::to($user->email)->queue(new PasswordResetMail($token));

        return response()->json([
            'status' => 'success',
            'message' => 'A new password reset OTP has been sent to your email.'
        ], 200);
    }
}
