<?php

namespace App\Http\Controllers\Api;

use App\Models\CMS;
use App\Models\About;
use App\Models\HomeCMS;
use App\Models\ContactCms;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\AboutUpdateResource;

class ApiCMSController extends Controller
{




    public function headerBanner(){
        $service = CMS::find(4);
        return response()->json([
            'status' => 'success',
            'message' => 'All header list retrieved successfully',
            'data' => $service,
        ]);
    }






    // about list
    public function about()
    {
        $about = About::all();
        return response()->json([
            'status' => 'success',
            'message' => 'About list retrieved successfully',
            'data' => $about,
        ]);
    }




    public function aboutupdate($id)
    {
        try {
            $aboutupdate = About::find($id);
            if (!$aboutupdate) {
                return response()->json([
                    'status' => true,
                    'message' => 'not found',
                    'data' => [],
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'All about list retrieved successfully',
                'data' => new AboutUpdateResource($aboutupdate),
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'All about list retrieved successfully'. $th->getMessage(),
            ],500);
        }
    }

    public function contact()
    {
        $contact = ContactCms::all();
        return response()->json([
            'status' => 'success',
            'message' => 'All contact list retrieved successfully',
            'data' => $contact
        ]);
    }

   


    // It's for system setting
    public function systemSetting()
    {
        $setting = SystemSetting::all();
        return response()->json([
            'status' => 'success',
            'message' => 'All system setting retrieved successfully',
            'data' => $setting
        ]);
    }

    // public function profileindex(){
    //     $profile = SystemSetting::all();
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'All system setting retrieved successfully',
    //         'data' => $profile
    //     ]);
    // }


    // It's for social-media
    public function social()
    {
        $social = SocialMedia::all();
        return response()->json([
            'status' => 'success',
            'message' => 'All social media retrieved successfully',
            'data' => $social
        ]);
    }


    // It's for privacy-policy, terms and condition
    public function privacy($id)
    {
        $privacy = PrivacyPolicy::find($id);
        if (!$privacy) {
            return response()->json([
                'status' => false,
                'message' => 'Privacy policy not found',
                'data' => []
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Privacy policy and terms and condition retrieved successfully',
            'data' => $privacy
        ]);
    }




    //allBanner Banner
    public function allBanner($id)
    {
        $allbanner = CMS::where('id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Banners retrieved successfully',
            'data' => $allbanner
        ]);
    }
}
