<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\SubBanner;
use App\Models\Text;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing',[
            'banners'    => Banner::all(),
            'subBanners' => SubBanner::all(),
            'texts'      => Text::all(),
        ]);
    }
}
