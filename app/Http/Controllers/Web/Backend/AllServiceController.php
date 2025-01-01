<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AllServiceController extends Controller
{
     //for web backend
     public function allService()
     {
         $service = Service::paginate(5);
         return view('backend.layout.service.index', compact('service'));
     }
 
     /**
      * Show the form for creating a new resource.
      */
     public function create()
     {
        $service = Service::all();
         return view('backend.layout.service.create',compact('service'));
     }
 
     /**
      * Store a newly created resource in storage.
      */
     public function store(Request $request)
     {
          // Validate the input
          $validated = $request->validate([
             'title' => 'required|string|max:255',
             'description' => 'required|string',
             'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:5120',
             'status' => 'required|in:active,inactive',
         ]);

         // Handle image upload 
         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $imageName = time() . '_' . $image->getClientOriginalName();
             $imagePath = public_path('/service/image/');
             // Move the image to the public storage folder
             $image->move($imagePath, $imageName);
             $validated['image'] = '/service/image/' . $imageName;
         }

         // Create and store the service
         Service::create($validated);

         return redirect()->route('service.all')->with('t-success', 'Service created successfully');
     }
 
     /**
      * Show the form for editing the specified resource.
      */
     public function edit(string $id)
     {
         $service = Service::findOrFail($id);
         return view('backend.layout.service.edit', compact('service'));
     }
 
     /**
      * Update the specified resource in storage.
      */
     public function update(Request $request, string $id)
     {
         $request->validate([
             'title' => 'required|string|max:255',
             'description' => 'required|string',
             'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:5120',
         ]);
 
         $service = Service::findOrFail($id);
         
         // Handle image upload 
         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $imageName = time() . '_' . $image->getClientOriginalName();
             $imagePath = public_path('/service/image/');
             // Move the image to the public storage folder
             $image->move($imagePath, $imageName);
             $service->image = '/service/image/' . $imageName;
         }
         
         $service->update($request->only(['title', 'description']));
 
         return redirect()->route('service.all')->with('t-success', 'Service updated successfully');
     }
 
     // Update service status
     public function updateStatus($id)
     {
         // Find the service by its ID
         $service = Service::find($id);
     
         // Check if the service exists
         if (!$service) {
             return response()->json(['success' => false, 'message' => 'Service not found']);
         }
     
         // Toggle the status between 'active' and 'inactive'
         if ($service->status == 'active') {
             $service->status = 'inactive';
             $message = 'Service unpublished successfully.';
         } else {
             $service->status = 'active';
             $message = 'Service published successfully.';
         }
     
         // Save the updated status
         $service->save();
     
         // Return a JSON response with the updated status and success message
         return response()->json([
             'success' => true,
             'message' => $message,
             'data' => $service,
         ]);
     }
     
 
 
 
     /**
      * Remove the specified resource from storage.
      */
      public function destroy(string $id)
      {
          try {
              $service = Service::findOrFail($id);
              $service->delete();
              
              // Return a JSON response for AJAX success
              return response()->json(['success' => true]);
      
          } catch (\Exception $e) {
              // Return a JSON response for failure
              return response()->json(['success' => false, 'message' => $e->getMessage()]);
          }
      }
      
}
