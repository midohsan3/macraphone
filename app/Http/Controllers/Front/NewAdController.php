<?php

namespace App\Http\Controllers\Front;

use Carbon\Carbon;
use App\Mail\NewAd;
use App\Models\AdMdl;
use App\Models\CityMdl;
use App\Models\CountryMdl;
use App\Models\AdPhotosMdl;
use Illuminate\Http\Request;
use App\Models\SubcategoryMdl;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewAdController extends Controller
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
        return view('frontAds.index');
    }
    /**
     * ============================
     * ============================
     */
    public function create($category)
    {
        if (App::getLocale() == 'ar') {
            $subcategories = SubcategoryMdl::where('category_id', $category)->orderBy('name_ar', 'ASC')->get();
        } else {
            $subcategories = SubcategoryMdl::where('category_id', $category)->orderBy('name_en', 'ASC')->get();
        }

        if (App::getLocale() == 'ar') {
            $countries = CountryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
            $cities = CityMdl::orderBy('name_ar', 'ASC')->get();
        } else {
            $countries = CountryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
            $cities = CityMdl::orderBy('name_en', 'ASC')->get();
        }

        return view('frontAds.create', compact('category', 'subcategories', 'countries', 'cities'));
    }
    /**
     * ============================
     * ============================
     */
    public function store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'category'     => 'required|integer|exists:categories,id',
            'subcategory' => 'required|integer|exists:subcategories,id',
            'city'           => 'required|integer|exists:cities,id',
        ], [
            'category.required' => 'Refresh the page and try again',
            'category.integer'  => 'Refresh the page and try again',
            'category.exists'    => 'Refresh the page and try again',

            'subcategory.required' => 'Category Is Required',
            'subcategory.integer'  => 'Category Is Required',
            'subcategory.exists'    => 'Category Is Required',

            'city.required' => 'City Is Required',
            'city.integer'  => 'Category Is Required',
            'city.exists'    => 'Category Is Required',
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid);
        }

        $city = CityMdl::find($req->city);

        $country = CountryMdl::find($city->country_id);

        $ad = AdMdl::create([
            'user_id'           => Auth::user()->id,
            'category_id'     => $req->category,
            'subcategory_id' => $req->subcategory,
            'country_id'      => $country->id,
            'city_id'           => $req->city,
            'ad_type'         => $req->adType,
            'currency'        => $country->currency_code,
        ]);

        return redirect()->route('newAd.description', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
    public function description($ad)
    {
        $ad = AdMdl::find($ad);
        return view('frontAds.description', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function description_store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'ad'            => 'required|integer|exists:ads,id',
            'title'          => 'required|string|min:3|max:50',
            'price'         => 'nullable|numeric',
            'description' => 'required|string|min:30|max:1500',
        ], [
            'ad.required' => 'Please Try Again Later',
            'ad.integer'  => 'Please Try Again Later',
            'ad.exists'    => 'Please Try Again Later',

            'title.required' => 'Description Is Required',
            'title.string'    => 'Format Not Matching',
            'title.min'      => 'Description NOt Enough',
            'title.max'     => 'Description Too Much Long',

            'description.required' => 'Description Is Required',
            'description.string'    => 'Format Not Matching',
            'description.min'      => 'Description NOt Enough',
            'description.max'     => 'Description Too Much Long',
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        $ad = AdMdl::findOrFail($req->ad);

        $ad->title = $req->title;
        $ad->description = $req->description;
        $ad->price = $req->price;
        $ad->save();

        return redirect()->route('newAd.contacts', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
    public function contacts($ad)
    {
        $ad = AdMdl::find($ad);
        return view('frontAds.contacts', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function contacts_store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'ad'          => 'required|integer|exists:ads,id',
            'phone'     => 'nullable|min:9|max:14',
            'whatApp' => 'nullable|min:9|max:14',
        ], [
            'ad.required' => 'Please Try Again Later',
            'ad.integer'  => 'Please Try Again Later',
            'ad.exists'    => 'Please Try Again Later',

            //'phone.regex' => 'Phone Have To Start with + then Number',
            'phone.min'  => 'Number Is Too Short',
            'phone.Max'  => 'Number Is Too Long',

            //'whatApp.regex' => 'Phone Have To Start with + then Number',
            'whatApp.min'  => 'Number Is Too Short',
            'whatApp.Max'  => 'Number Is Too Long',
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        $ad = AdMdl::findOrFail($req->ad);

        $ad->phone      = $req->phone;
        $ad->whatsApp = $req->whatApp;
        $ad->mail = Auth::user()->email;
        $ad->save();

        return redirect()->route('newAd.photos', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
    public function photos($ad)
    {
        $ad = AdMdl::find($ad);
        $adPhotos = AdPhotosMdl::where('ad_id', $ad->id)->get();
        return view('frontAds.photos', compact('ad', 'adPhotos'));
    }
    /**
     * ============================
     * ============================
     */
    public function photos_store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'ad' => 'required|integer|exists:ads,id',
            'adPhoto' => 'required|image|mimes:jpeg,png,jpg,gif',
        ], [
            'ad.required' => 'Please Try Again Later',
            'ad.integer'  => 'Please Try Again Later',
            'ad.exists'    => 'Please Try Again Later',

            'adPhoto.required' => 'File Is Required',
            'adPhoto.image'    => 'The File You Used Is Not Image',
            'adPhoto.mimes'    => 'The Image You Used Not Supported',
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid);
        }

        if ($req->hasFile('adPhoto')) {
            $img = $req->file('adPhoto');
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

        return redirect()->route('newAd.photos', $ad->id);
    }
    /**
     * ============================
     * ============================
     */
    public function photos_delete($photo)
    {
        $photo = AdPhotosMdl::find($photo);
        File::delete('storage/app/public/imgs/ads/' . $photo->photo);
        $photo->forceDelete();
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function choosePublish($ad)
    {
        $ad = AdMdl::find($ad);
        return view('frontAds.choosePublish', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function freePublish($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->featured    = 0;
        $ad->status       = 1;
        $ad->start_date = Carbon::now();
        $ad->end_date   = Carbon::now()->addDays(30);
        $ad->save();

        $data = ['user' => Auth::user()->name, 'title' => $ad->title];

        //Mail::to(Auth::user()->email)->send(new NewAd($data));

        Toastr()->success(__('front.Your Post Submitted Successfully'));
        return redirect()->route('dashboard');
    }
    /**
     * ============================
     * ============================
     */
    public function featuredDay($ad)
    {
        $ad = AdMdl::find($ad);
        return view('frontAds.pay', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function featuredDays($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->featured = 1;
        $ad->status = 1;
        $ad->start_date = Carbon::now();
        $ad->end_date   = Carbon::now()->addDays(2);
        $ad->save();

        $data = ['user' => Auth::user()->name, 'title' => $ad->title];

        Mail::to(Auth::user()->email)->send(new NewAd($data));

        Toastr()->success(__('front.Your Post Submitted Successfully'));
        return redirect()->route('dashboard');
    }
    /**
     * ============================
     * ============================
     */
    public function featuredWeek($ad)
    {
        $ad = AdMdl::find($ad);
        return view('frontAds.payWeek', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function featuredWeeks($ad)
    {
        $ad = AdMdl::find($ad);
        $ad->featured = 1;
        $ad->status = 1;
        $ad->start_date = Carbon::now();
        $ad->end_date   = Carbon::now()->addDays(7);
        $ad->save();

        $data = ['user' => Auth::user()->name, 'title' => $ad->title];

        //Mail::to(Auth::user()->email)->send(new NewAd($data));

        Toastr()->success(__('front.Your Post Submitted Successfully'));
        return redirect()->route('dashboard');
    }
    /**
     * ============================
     * ============================
     */
    public function featuredMonth($ad)
    {
        $ad = AdMdl::find($ad);

        return view('frontAds.payMonth', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
    public function featuredMonths($ad)
    {
        $ad = AdMdl::find($ad);
        $ad->featured = 1;
        $ad->status = 1;
        $ad->start_date = Carbon::now();
        $ad->end_date   = Carbon::now()->addDays(30);
        $ad->save();

        $data = ['user' => Auth::user()->name, 'title' => $ad->title];

        //Mail::to(Auth::user()->email)->send(new NewAd($data));

        Toastr()->success(__('front.Your Post Submitted Successfully'));
        return redirect()->route('dashboard');
    }
    /**
     * ============================
     * ============================
     */
    public function featured($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->featured = 1;
        $ad->status = 1;
        $ad->start_date = Carbon::today();
        $ad->end_date   = Carbon::today()->addDays(7);
        $ad->save();
        return view('frontAds.pay', compact('ad'));
    }
    /**
     * ============================
     * ============================
     */
}