<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{

    // for api fetch all service
    public function index()
    {
        $service = Service::all();
        return response()->json([
            'status' => 'success',
            'message' => 'services list retrieved successfully',
            'users' => $service,
        ]);
    }    
    public function lastFourService()
    {
        $service = Service::orderBy('created_at', 'desc')->take(4)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Latest services list retrieved successfully',
            'users' => $service,
        ]);
    }    


    public function serviceDetails($id)
    {
        // Fetch the service using the provided ID
        $service = Service::find($id);
    
        // Check if the service exists
        if (!$service) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service not found',
            ], 404); // Return a 404 if the service is not found
        }
    
        // Return the service details as a JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Service details retrieved successfully',
            'data' => $service,
        ]);
    }
    

    public function faq()
    {
        $faqs = Faq::select('answer')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'FAQs retrieved successfully',
            'data' => $faqs,
        ]);
    }

    //Review
    public function review()
    {
        $review = Review::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Review retrieved successfully',
            'data' => $review,
        ]);
    }
    
}
