<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ContactNotifications;
use Illuminate\Support\Facades\Notification;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socialMedia = SocialMedia::all();
        return view('backend.layout.system_setting.social-media', compact('socialMedia'));
    }

    
    public function create()
    {
        return view('backend.layout.system_setting.create-social');
    }

    
    public function store(Request $request)
    {

        // dd($request->all());
        $validatedData = $request->validate([
            'platform' => 'required|in:facebook,tiktok,twitter,instagram,youtube,linkedin,discord,telegram',
            'link' => 'required|url',
            'status' => 'required|in:active,deactive',
        ]);
        

        SocialMedia::create($validatedData);
        return redirect()->route('social.media')->with('t-success', 'Social Media link added successfully');
    }


    public function update(Request $request, $id)
    {
        $socialMedia = SocialMedia::findOrFail($id);

        $validatedData = $request->validate([
            'link' => 'required|url',
            'status' => 'required|in:active,deactive',
        ]);

        $socialMedia->update($validatedData);

        return redirect()->route('social.media')->with('success', 'Social Media link updated successfully');
    }

    public function destroy($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->delete();

        return redirect()->route('social.media')->with('success', 'Social Media link deleted successfully');
    }


}
