<?php

namespace App\Http\Controllers\Web\Backend\CMS\HomePage;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\CMS;
use App\Models\ContactCms;
use App\Models\HomeCMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    public function headerBanner()
    {
        $service = CMS::find(4);

        if (!$service) {
            return redirect()->back()->with('error', 'Home Banner not found');
        }

        return view('backend.layout.cms.home.header.index', compact('service'));
    }

    // public function headerContentImage(Request $request)
    // {
    //     $data = HomeCMS::findOrFail($request->id);

    //     $rules = [
    //         'image_url' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp|max:2048',
    //     ];

    //     Validator::make($request->all(), $rules);

    //     $image_url = $data->image_url;

    //     if ($request->hasFile('image_url')) {
    //         $image_url = $request->file('image_url')->store('images/home/header', 'public');
    //     }

    //     $updated = $data->update([
    //         'title' => $request->title,
    //         'sub_title' => $request->sub_title,
    //         'image_url' => $image_url,
    //     ]);

    //     return redirect()->back()->with(
    //         $updated ? 't-success' : 't-error',
    //         $updated ? 'Data Updated Successfully' : 'Data update failed!'
    //     );
    // }



    public function headerBannerupdate(Request $request){

        // Validate the request
        $request->validate([
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'image'=>'nullable|image|mimes:jpeg,jpg,png,svg,webp|max:3048',
        ]);

        $service = CMS::find(4);

        if (!$service) {
            return redirect()->back()->with('t-error', 'Cart Banner not found');
        }

        // Update the data
        $updated = $service->update([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('/images/home/header/');
            $image->move($imagePath, $imageName);
            $service->image = '/images/home/header/' . $imageName;
            $updated = $service->save();

        }

        return redirect()->route('cms.home.header')->with(
            $updated ? 't-success' : 't-error',
            $updated ? 'Data Updated Successfully' : 'Data update failed!'
        );
    }



    public function about()
    {
        $data = About::all();
        return view('backend.layout.cms.home.about.index', compact('data'));
    }

    public function aboutupdate(Request $request)
    {
        $data = About::findOrFail($request->id);

        // Validate the request
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        // Update the data
        $updated = $data->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with(
            $updated ? 't-success' : 't-error',
            $updated ? 'about Updated Successfully' : 'Data update failed!'
        );
    }

    public function contact()
    {
        $data = ContactCms::all();
        return view('backend.layout.cms.home.contact.index', compact('data'));
    }

    public function contactupdate(Request $request)
    {
        $data = ContactCms::findOrFail($request->id);


        // Validate the request
        $request->validate([
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'support_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'opening_time' => 'nullable|string|max:255',
        ]);

        // Update the data
        $updated = $data->update([
            'contact' => $request->contact,
            'email' => $request->email,
            'support_email' => $request->support_email,
            'location' => $request->location,
            'opening_time' => $request->opening_time,
        ]);

        return redirect()->back()->with(
            $updated ? 't-success' : 't-error',
            $updated ? 'Data Updated Successfully' : 'Data update failed!'
        );
    }

    //..........................service banner .........................
    public function serviceBanner()
    {
        // Fetch the record with ID 1
        $service = CMS::find(1);

        // If the record is not found, show an error message
        if (!$service) {
            return redirect()->back()->with('error', 'Service Banner not found');
        }

        // Return the view with the service data
        return view('backend.layout.service.banner', compact('service'));
    }

    // Method to update the service banner
    public function serviceBannerUpdate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp|max:10240',
        ]);

        // Fetch the record with ID 1
        $service = CMS::find(1);

        // If the record is not found, show an error message
        if (!$service) {
            return redirect()->back()->with('error', 'Service Banner not found');
        }

        // Update the record with the new data
        $updated = $service->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // If an image is uploaded, handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('/images/cms/service/');
            $image->move($imagePath, $imageName);
            $service->image = '/images/cms/service/' . $imageName;
            $updated = $service->save();  // Save the updated record
        }

        // Redirect back with success or error message
        return redirect()->route('cms.service.banner')->with(
            $updated ? 't-success' : 't-error',
            $updated ? 'Service Banner Updated Successfully' : 'Service Banner update failed!'
        );
    }


    // ......................product banner...........................

    public function productBanner()
    {
        // Fetch the record with ID 2
        $service = CMS::find(2);

        // If the record is not found, show an error message
        if (!$service) {
            return redirect()->back()->with('error', 'Product Banner not found');
        }

        // Return the view with the service data
        return view('backend.layout.product.banner', compact('service'));
    }

    // Method to update the Product Banner
    public function productBannerUpdate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp|max:10240',
        ]);

        // Fetch the record with ID 1
        $service = CMS::find(2);

        // If the record is not found, show an error message
        if (!$service) {
            return redirect()->back()->with('t-error', 'Product Banner not found');
        }

        // Update the record with the new data
        $updated = $service->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // If an image is uploaded, handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('/images/cms/product/');
            $image->move($imagePath, $imageName);
            $service->image = '/images/cms/product/' . $imageName;
            $updated = $service->save();  // Save the updated record
        }

        // Redirect back with success or error message
        return redirect()->route('cms.product.banner')->with(
            $updated ? 't-success' : 't-error',
            $updated ? 'Product Banner Updated Successfully' : 'Product Banner update failed!'
        );
    }
    // ......................Cart banner...........................

    public function cartBanner()
    {
        // Fetch the record with ID 3
        $service = CMS::find(3);

        // If the record is not found, show an error message
        if (!$service) {
            return redirect()->back()->with('t-error', 'Cart Banner not found');
        }

        // Return the view with the service data
        return view('backend.layout.cms.cart.banner', compact('service'));
    }

    // Method to update the service banner
    public function cartBannerUpdate(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,svg,webp|max:10240',
        ]);

        // Fetch the record with ID 1
        $service = CMS::find(3);

        // If the record is not found, show an error message
        if (!$service) {
            return redirect()->back()->with('t-error', 'Cart Banner not found');
        }

        // Update the record with the new data
        $updated = $service->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // If an image is uploaded, handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('/images/cms/cart/');
            $image->move($imagePath, $imageName);
            $service->image = '/images/cms/cart/' . $imageName;
            $updated = $service->save();  // Save the updated record
        }

        // Redirect back with success or error message
        return redirect()->route('cms.cart.banner')->with(
            $updated ? 't-success' : 't-error',
            $updated ? 'Cart Banner Successfully' : 'Cart Banner update failed!'
        );
    }
}
