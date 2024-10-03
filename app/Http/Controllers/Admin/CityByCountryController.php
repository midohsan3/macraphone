<?php

namespace App\Http\Controllers\Admin;

use App\Models\CityMdl;
use App\Models\CountryMdl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserActivityMdl;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\CountryActivitiesMdl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CityByCountryController extends Controller
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
    public function index($country)
    {
        $country = CountryMdl::find($country);
        if (App::getLocale() == 'ar') {
            $cities = CityMdl::where('country_id', $country->id)->orderBy('country_id', 'ASC')->orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $cities = CityMdl::where('country_id', $country->id)->orderBy('country_id', 'ASC')->orderBy('name_en', 'ASC')->paginate(pageCount);
        }
        $citiesCount = CityMdl::where('country_id', $country->id)->count();

        $list_title = __('general.All');

        return view('cityByCountry.index', compact('country', 'cities', 'citiesCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function create($country)
    {
        $country = CountryMdl::find($country);
        return view('cityByCountry.create', compact('country'));
    }
    /**
     * ============================
     * ============================
     */
    public function store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'country'  => 'required|integer|exists:countries,id',
            'nameAr' => 'required|string|min:3|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
        ]);

        if ($valid->fails()) {
            Toastr()->error(__('general.Please Try Again Later'));
            return back();
        }

        $city = CityMdl::create([
            'country_id' => $req->country,
            'name_en'   => Str::title($req->nameEn),
            'name_ar'   => $req->nameAr,
        ]);

        CountryActivitiesMdl::create([
            'country_id' => $city->country_id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Submitted New City ' . $city->name_en,
            'activity_ar' => 'تسجيل مدينة ' . $city->name_ar,
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Submit New City ' . $city->name_en,
            'action_ar' => 'تسجيل مدينة جديدة  ' . $city->name_ar,
        ]);

        Toastr()->success(__('general.Added Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function show($country, $city)
    {
        $country = CountryMdl::find($country);
        $city = CityMdl::find($city);

        return view('cityByCountry.show', compact('country', 'city'));
    }
    /**
     * ============================
     * ============================
     */
    public function edit($country, $city)
    {
        $country = CountryMdl::find($country);
        $city = CityMdl::find($city);

        return view('cityByCountry.edit', compact('country', 'city'));
    }
    /**
     * ============================
     * ============================
     */
}