<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\AdMdl;
use App\Mail\ExpiredAd;
use App\Models\AdPhotosMdl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientAdsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    /**
     * ============================
     * ============================
     */
    public function index()
    {
        $ads = AdMdl::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('user_id', Auth::user()->id)->count();

        return view('ads.client.index', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function notCompleted()
    {
        $ads = AdMdl::where([['user_id', Auth::user()->id], ['status', 0]])->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where([['user_id', Auth::user()->id], ['status', 0]])->count();

        return view('ads.client.index', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function reviewing()
    {
        $ads = AdMdl::where([['user_id', Auth::user()->id], ['status', 1]])->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where([['user_id', Auth::user()->id], ['status', 1]])->count();

        return view('ads.client.index', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function archived()
    {
        $ads = AdMdl::where([['user_id', Auth::user()->id], ['status', 2]])->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where([['user_id', Auth::user()->id], ['status', 2]])->count();

        return view('ads.client.index', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function active()
    {
        $ads = AdMdl::where([['user_id', Auth::user()->id], ['status', 3]])->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where([['user_id', Auth::user()->id], ['status', 3]])->count();

        return view('ads.client.index', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function reject()
    {
        $ads = AdMdl::where([['user_id', Auth::user()->id], ['status', 4]])->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where([['user_id', Auth::user()->id], ['status', 4]])->count();

        return view('ads.client.index', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function show(AdMdl $ad)
    {
        $ad::find($ad);

        $photos = AdPhotosMdl::where('ad_id', $ad->id)->get();

        return view('ads.client.show', compact('ad', 'photos'));
    }
    /**
     * ============================
     * ============================
     */
    public function edit(AdMdl $ad)
    {
        $ad::find($ad);
        return view('ads.client.edit', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function update(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'ad'           => 'required|integer|exists:ads,id',
            'title'         => 'required|string|min:3|max:50',
            'phone'       => 'nullable|regex:/numeric|min:9|max:14',
            'whatApp'   => 'nullable|regex:/numeric|min:9|max:14',
            'price'         => 'nullable|numeric',
            'description' => 'required|string|min:30|max:500',
        ], [
            'ad.required' => __('general.Please Try Again Later'),
            'ad.integer'  => __('general.Please Try Again Later'),
            'ad.integer'  => __('general.Please Try Again Later'),

            'ad.required' => __('general.This Field Is Required'),
            'ad.string'    => __('general.Format Not Matching'),
            'ad.min'      => __('ad.Text Is Too Short'),
            'ad.max'     => __('ad.Text Is Much Long'),

            'phone.numeric' => 'Phone Have To Start with + then Number',
            'phone.min'  => 'Number Is Too Short',
            'phone.Max'  => 'Number Is Too Long',

            'whatApp.numeric' => 'Phone Have To Start with + then Number',
            'whatApp.min'  => 'Number Is Too Short',
            'whatApp.Max'  => 'Number Is Too Long',

            'description.required' => 'Description Is Required',
            'description.string'    => 'Format Not Matching',
            'description.min'      => 'Description NOt Enough',
            'description.max'     => 'Description Too Much Long',
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        $ad = AdMdl::findOrFail($req->ad);

        $ad->status         = 1;
        $ad->title            = $req->title;
        $ad->description   = $req->description;
        $ad->price           = $req->price;
        $ad->phone          = $req->phone;
        $ad->whatsApp     = $req->whatsApp;
        $ad->save();

        $user = User::find(Auth::user()->id);
        $user->phone = $req->phone;
        $user->save();

        Toastr()->success(__('general.Updated Successfully'));

        return view('frontAds.choosePublish', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function activate($ad)
    {
        $ad = AdMdl::find($ad);

        return view('frontAds.choosePublish', compact('ad'));
    }
    /*
     * ============================
     * ============================
     */
    public function deactivate($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->status = 2;
        $ad->save();

        $data = ['user' => Auth::user()->name, 'title' => $ad->title];

        Mail::to(Auth::user()->email)->send(new ExpiredAd($data));

        Toastr()->success(__('general.Deactivated Successfully'));
        return back();
    }
    /*
     * ============================
     * ============================
     */
    public function destroy(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'adID' => 'required|integer|exists:ads,id',
        ]);

        if ($valid->fails()) {
            Toastr()->error(__('general.Please Try Again Later'));
            return back();
        }

        $ad = AdMdl::findOrFail($req->adID);

        $photos = AdPhotosMdl::where('ad_id', $ad->id)->get();

        foreach ($photos as $photo) {
            File::delete('storage/app/public/imgs/ads/' . $photo->photo);
            //$photo->forceDelete();
        }

        $ad->forceDelete();

        Toastr()->success(__('general.Deleted Successfully'));
        return back();
    }
    /*
     * ============================
     * ============================
     */
    public function photos($ad)
    {
        $ad = AdMdl::find($ad);

        $photos = AdPhotosMdl::where('ad_id', $ad->id)->get();

        return view('ads.client.photos', compact('ad', 'photos'));
    }
    /**
     * ============================
     * ============================
     */
    public function photos_store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'ad' => 'required|integer|exists:ads,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
        ], [
            'ad.required' => __('general.Please Try Again Later'),
            'ad.integer'  => __('general.Please Try Again Later'),
            'ad.integer'  => __('general.Please Try Again Later'),

            'photo.required' => 'File Is Required',
            'photo.image'    => 'The File You Used Is Not Image',
            'photo.mimes'    => 'The Image You Used Not Supported',
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid);
        }

        if ($req->hasFile('photo')) {
            $img = $req->file('photo');
            $imgName = rand() . '.' . $img->getClientOriginalExtension();
            //save file like a temp
            $img->move(('imgs/ads'), $imgName);
            //RESIZING THE PICTURE AND MAKE A COPY WITH NEW SIZE IN STORAGE FILE
            $resize = Image::make("imgs/ads/{$imgName}")->resize(700, 700)->encode('png');
            Storage::put("public/imgs/ads/{$imgName}", $resize->__toString());
            //delete the file as a temp
            File::delete('imgs/ads/' . $imgName);
        } else {
            $imgName = null;
        }

        AdPhotosMdl::create([
            'ad_id' => $req->ad,
            'photo' => $imgName,
        ]);

        $ad = AdMdl::findOrFail($req->ad);

        $ad->photo = $imgName;
        $ad->save();

        Toastr()->success(__('general.Added Successfully'));
        return redirect()->route('client.ads.photos', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
    public function photos_default($photo)
    {
        $photo = AdPhotosMdl::find($photo);

        $ad = AdMdl::find($photo->ad_id);

        $ad->photo = $photo->photo;
        $ad->save();

        Toastr()->success(__('ad.Set Default Successfully'));
        return redirect()->route('client.ads.photos', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
    public function photos_destroy($photo)
    {
        $photo = AdPhotosMdl::find($photo);

        $ad = AdMdl::find($photo->ad_id);

        if ($ad->photo !== $photo->photo) {
            File::delete('storage/app/public/imgs/ads/' . $photo->photo);
            $photo->forceDelete();
        } else {
            File::delete('storage/app/public/imgs/ads/' . $photo->photo);
            $photo->forceDelete();

            $lastPhoto = AdPhotosMdl::where('ad_id', $ad->id)->orderBy('id', 'DESC')->first();

            if ($lastPhoto) {
                $ad->photo = $lastPhoto->photo;
                $ad->save();
            } else {
                $ad->photo = null;
                $ad->save();
            }
        }

        Toastr()->success(__('general.Deleted Successfully'));
        return redirect()->route('client.ads.photos', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
}