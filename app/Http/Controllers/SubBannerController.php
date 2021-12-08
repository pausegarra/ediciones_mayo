<?php

namespace App\Http\Controllers;

use App\Models\SubBanner;
use Illuminate\Http\Request;
use App\Http\Requests\SubBannerUpdate;
use App\Http\Requests\SubBannerRequest;
use Illuminate\Support\Facades\Storage;

class SubBannerController extends Controller
{
    /**
     * Get all Sub Banners from DB
     *
     * @return object
     */
    public function get(): object
    {
        return SubBanner::all();
    }

    /**
     * Saves subBanner from DB
     *
     * @param SubBannerRequest $request
     * @return void
     */
    public function store(SubBannerRequest $request)
    {
        $file = $request->getFile();
        $file->storeAs('public/sub-banners',$file->getClientOriginalName());

        SubBanner::create([
            'icon'   => $file->getClientOriginalName(),
            'title'  => $request->getTitle(),
            'period' => $request->getPeriod(),
        ]);
    }

    /**
     * Updates Sub Banner from DB
     *
     * @param SubBannerUpdate $request
     * @param SubBanner $subBanner
     * @return void
     */
    public function update(SubBannerUpdate $request, SubBanner $subBanner)
    {
        if ($request->getFile() != null) {
            Storage::disk('public')->delete('sub-banners/'.$subBanner->icon);
            $file = $request->getFile();
            $file->storeAs('public/sub-banners',$file->getClientOriginalName());
            
            $subBanner->icon = $file->getClientOriginalName();
        }

        if ($request->get('title') != null)
            $subBanner->title = $request->title;
        if ($request->get('period') != null)
            $subBanner->period = $request->period;
        $subBanner->save();
    }

    /**
     * Delete subBaner from Db
     *
     * @param SubBanner $banner
     * @return void
     */
    public function delete(SubBanner $subBanner)
    {
        Storage::disk('public')->delete('sub-banners/'.$subBanner->icon);
        $subBanner->delete();
    }
}
