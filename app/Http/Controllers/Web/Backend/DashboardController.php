<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ContactNotifications;
use Illuminate\Support\Facades\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.layout.dashboard');
    }


   /*  public function contactForm()
    {
        return view('frontend.contact');
    } */


    public function message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sms' => 'required|string',
        ]);
        
        // If validation fails, return a 422 Unprocessable Entity response with errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
       
    
        // Find all admins
        $admins = User::where('role', 'admin')->get();
    
        // Send notification to all admins
        try {
            foreach ($admins as $admin) {
                $admin->notify(new ContactNotifications($request->all()));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send your message. Please try again.'], 422);
        }
    
        // Return success response
        return response()->json(['success' => 'Your message has been sent successfully!']);
    }


    
    public function userManagement()
    {

        $user = User::paginate(10);
        return view('backend.layout.users.index',compact('user'));
    }
}
