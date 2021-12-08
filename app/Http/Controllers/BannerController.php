<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BannerUpdateRequest;

class BannerController extends Controller
{
    /**
     * Get the active banners from DB
     *
     * @return array
     */
    public function get(): object
    {
        return Banner::all();
    }

    /**
     * Saves Banner to DB
     *
     * @param BannerRequest $request
     * @return void
     */
    public function store(BannerRequest $request)
    {
        $file = $request->getFile();
        $file->storeAs('public/banners',$file->getClientOriginalName());

        $text = $request->getText();
        $text->storeAs('public/banners',$text->getClientOriginalName());

        Banner::create([
            'url'   => $file->getClientOriginalName(),
            'title' => $text->getClientOriginalName(),
        ]);
    }

    /**
     * Update Banner in DB
     *
     * @param BannerUpdateRequest $request
     * @param Banner $banner
     * @return void
     */
    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        if ($request->getFile() != null) {
            Storage::disk('public')->delete('banners/'.$banner->url);
            $file = $request->getFile();
            $file->storeAs('public/banners',$file->getClientOriginalName());
            
            $banner->url = $file->getClientOriginalName();
        }

        if ($request->getTitle() != null) {
            Storage::disk('public')->delete('banners/'.$banner->title);
            $file = $request->getTitle();
            $file->storeAs('public/banners',$file->getClientOriginalName());
            
            $banner->title = $file->getClientOriginalName();
        }
            
        $banner->save();
    }

    /**
     * Delete banner from DB
     *
     * @param Banner $banner
     * @return void
     */
    public function delete(Banner $banner)
    {
        Storage::disk('public')->delete('banners/'.$banner->url);
        Storage::disk('public')->delete('banners/'.$banner->title);
        $banner->delete();
    }
}
