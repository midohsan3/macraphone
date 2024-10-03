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
use Brian2694\Toastr\Facades\Toastr;
use Database\Seeders\CountryActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
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
        if (App::getLocale() == 'ar') {
            $cities = CityMdl::orderBy('country_id', 'ASC')->orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $cities = CityMdl::orderBy('country_id', 'ASC')->orderBy('name_en', 'ASC')->paginate(pageCount);
        }
        $citiesCount = CityMdl::count();

        $list_title = __('general.All');

        return view('city.index', compact('cities', 'citiesCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function active()
    {
        if (App::getLocale() == 'ar') {
            $cities = CityMdl::where('status', 1)->orderBy('country_id', 'ASC')->orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $cities = CityMdl::where('status', 1)->orderBy('country_id', 'ASC')->orderBy('name_en', 'ASC')->paginate(pageCount);
        }
        $citiesCount = CityMdl::where('status', 1)->count();

        $list_title = __('general.Active');

        return view('city.index', compact('cities', 'citiesCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function inactive()
    {
        if (App::getLocale() == 'ar') {
            $cities = CityMdl::where('status', 0)->orderBy('country_id', 'ASC')->orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $cities = CityMdl::where('status', 0)->orderBy('country_id', 'ASC')->orderBy('name_en', 'ASC')->paginate(pageCount);
        }
        $citiesCount = CityMdl::where('status', 0)->count();

        $list_title = __('general.Inactive');

        return view('city.index', compact('cities', 'citiesCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function create()
    {
        if (App::getLocale() == 'ar') {
            $countries = CountryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $countries = CountryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }
        return view('city.create', compact('countries'));
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
        ], [
            'country.required' => __('general.This Field Is Required'),
            'country.integer'  => __('general.Format Not Matching'),
            'country.exists'    => __('country.This Country Not Exists'),

            'nameAr.required' => __('general.This Field Is Required'),
            'nameAr.string'    => __('general.Format Not Matching'),
            'nameAr.min'      => __('general.Text Is Too Short'),
            'nameAr.regex'    => __('general.This Field Accept Arabic Letters Only'),

            'nameEn.required' => __('general.This Field Is Required'),
            'nameEn.string'    => __('general.Format Not Matching'),
            'nameEn.min'      => __('general.Text Is Too Short'),
            'nameEn.regex'    => __('general.This Field Accept English Letters Only'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
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
    public function show(CityMdl $city)
    {
        $city::find($city);
        return view('city.show', compact('city'));
    }
    /**
     * ============================
     * ============================
     */
    public function edit(CityMdl $city)
    {
        $city::find($city);
        $countries = CountryMdl::orderBy('name_en', 'asc')->get();
        return view('city.edit', compact('city', 'countries'));
    }
    /**
     * ============================
     * ============================
     */
    public function update(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'city'       => 'required|integer|exists:cities,id',
            'country'  => 'required|integer|exists:countries,id',
            'nameAr' => 'required|string|min:3|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
        ], [
            'city.required' => __('general.This Field Is Required'),
            'city.integer'  => __('general.Format Not Matching'),
            'city.exists'    => __('country.This Country Not Exists'),

            'country.required' => __('general.This Field Is Required'),
            'country.integer'  => __('general.Format Not Matching'),
            'country.exists'    => __('country.This Country Not Exists'),

            'nameAr.required' => __('general.This Field Is Required'),
            'nameAr.string'    => __('general.Format Not Matching'),
            'nameAr.min'      => __('general.Text Is Too Short'),
            'nameAr.regex'    => __('general.This Field Accept Arabic Letters Only'),

            'nameEn.required' => __('general.This Field Is Required'),
            'nameEn.string'    => __('general.Format Not Matching'),
            'nameEn.min'      => __('general.Text Is Too Short'),
            'nameEn.regex'    => __('general.This Field Accept English Letters Only'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        $city = CityMdl::findOrFail($req->city);

        $city->country_id = $req->country;
        $city->name_en = $req->nameEn;
        $city->name_ar = $req->nameAr;
        $city->save();

        //COUNTRY UPDATE
        CountryActivitiesMdl::create([
            'country_id' => $city->country_id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Update City ' . $city->name_en,
            'activity_ar' => 'تحديث بيانات مدينة ' . $city->name_ar,
        ]);

        //USER ACTION
        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Update City ' . $city->name_en,
            'action_ar' => 'تحديث بيانات مدينة  ' . $city->name_ar,
        ]);

        Toastr()->success(__('general.Updated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function activate(CityMdl $city)
    {
        $city::find($city);

        $city->status = 1;
        $city->save();

        //COUNTRY UPDATE
        CountryActivitiesMdl::create([
            'country_id' => $city->country_id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Activate City ' . $city->name_en,
            'activity_ar' => 'تفعيل مدينة ' . $city->name_ar,
        ]);

        //USER ACTION
        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Activate City ' . $city->name_en,
            'action_ar' => 'تفعيل  مدينة  ' . $city->name_ar,
        ]);

        Toastr()->success(__('general.Activated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    /**
     * ============================
     * ============================
     */
    public function deactivate(CityMdl $city)
    {
        $city::find($city);

        $city->status = 0;
        $city->save();

        //COUNTRY UPDATE
        CountryActivitiesMdl::create([
            'country_id' => $city->country_id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Deactivate City ' . $city->name_en,
            'activity_ar' => 'إلغاء تفعيل مدينة ' . $city->name_ar,
        ]);

        //USER ACTION
        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Deactivate City ' . $city->name_en,
            'action_ar' => 'إلغاء تفعيل  مدينة  ' . $city->name_ar,
        ]);

        Toastr()->success(__('general.Deactivated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function destroy(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'cityID' => 'required|integer|exists:cities,id',
        ]);

        if ($valid->fails()) {
            Toastr()->error(__('general.Please Try Again Later'));
            return back();
        }

        $city = CityMdl::findOrFail($req->cityID);

        $city->delete();

        $city = CityMdl::withTrashed()->findOrFail($req->cityID);

        //COUNTRY UPDATE
        CountryActivitiesMdl::create([
            'country_id' => $city->country_id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Delete City ' . $city->name_en,
            'activity_ar' => ' حذف مدينة ' . $city->name_ar,
        ]);

        //USER ACTION
        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Delete City ' . $city->name_en,
            'action_ar' => 'حذف   مدينة  ' . $city->name_ar,
        ]);

        Toastr()->success(__('general.Deleted Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
}