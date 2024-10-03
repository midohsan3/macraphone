<?php

namespace App\Http\Controllers\Admin;

use App\Models\CityMdl;
use App\Models\CountryMdl;
use App\Models\CategoryMdl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubcategoryMdl;
use App\Models\UserActivityMdl;
use App\Models\CountryCategoryMdl;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\CountryActivitiesMdl;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\CountrySubcategoryMdl;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
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
            $countries = CountryMdl::orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $countries = CountryMdl::orderBy('name_en', 'ASC')->paginate(pageCount);
        }

        $countriesCount = CountryMdl::count();

        $cities = CityMdl::selectRaw('count(id) as citiesCount, country_id')->groupBy('country_id')->get()->pluck('citiesCount', 'country_id')->all();

        $list_title = __('general.All');

        return view('country.index', compact('countries', 'countriesCount', 'cities', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function active()
    {
        if (App::getLocale() == 'ar') {
            $countries = CountryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $countries = CountryMdl::where('status', 1)->orderBy('name_en', 'ASC')->paginate(pageCount);
        }

        $countriesCount = CountryMdl::where('status', 1)->count();

        $cities = CityMdl::selectRaw('count(id) as citiesCount, country_id')->groupBy('country_id')->get()->pluck('citiesCount', 'country_id')->all();

        $list_title = __('general.Active');

        return view('country.index', compact('countries', 'countriesCount', 'cities', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function inactive()
    {
        if (App::getLocale() == 'ar') {
            $countries = CountryMdl::where('status', 0)->orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $countries = CountryMdl::where('status', 0)->orderBy('name_en', 'ASC')->paginate(pageCount);
        }

        $countriesCount = CountryMdl::where('status', 0)->count();

        $cities = CityMdl::selectRaw('count(id) as citiesCount, country_id')->groupBy('country_id')->get()->pluck('citiesCount', 'country_id')->all();

        $list_title = __('general.Inactive');

        return view('country.index', compact('countries', 'countriesCount', 'cities', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function create()
    {
        return view('country.create');
    }
    /**
     * ============================
     * ============================
     */
    public function store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'nameAr' => 'required|string|unique:countries,name_ar|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|unique:countries,name_ar|regex:/^[a-zA-z\s]+$/',
            'ariaCode'  => 'required|unique:countries,country_code|regex:/^[a-zA-Z\s]{2}+$/',
            'phoneCode' => 'required|regex:/[0-9]+$/|max:5',
            'currency'    => 'required|regex:/^[a-zA-ZÑñ\s]{3}/',
            'flag'          => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ], [
            'nameAr.required' => __('general.This Field Is Required'),
            'nameAr.string'    => __('general.Format Not Matching'),
            'nameAr.unique'   => __('general.This Field Is Already Exists'),
            'nameAr.regex'    => __('general.This Field Accept Arabic Letters Only'),

            'nameEn.required' => __('general.This Field Is Required'),
            'nameEn.string'    => __('general.Format Not Matching'),
            'nameEn.unique'   => __('general.This Field Is Already Exists'),
            'nameEn.regex'    => __('general.This Field Accept English Letters Only'),

            'ariaCode.required' => __('general.This Field Is Required'),
            'ariaCode.unique'   => __('general.This Field Is Already Exists'),
            'ariaCode.regex'    => __('country.Aria Code Must Be 2 English Letters'),

            'phoneCode.required' => __('general.This Field Is Required'),
            'phoneCode.regex'    => __('country.Phone Code Should Be Number'),
            'phoneCode.max'      => __('country.Phone Code Maximum 4 Digits'),

            'currency.required' => __('general.This Field Is Required'),
            'currency.regex'    => __('country.Currency Should Be 3 English Letters'),

            'flag.image'        => __('general.The file you uploaded is Not Image'),
            'flag.mimes'        => __('general.Image formate is not allowed'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        if ($req->hasFile('flag')) {
            $img = $req->file('flag');
            $imgName = rand() . '.' . $img->getClientOriginalExtension();
            //save file like a temp
            $img->move(('imgs/countries'), $imgName);
            //RESIZING THE PICTURE AND MAKE A COPY WITH NEW SIZE IN STOREAGE FILE
            $resize = Image::make("imgs/countries/{$imgName}")->resize(45, 25)->encode('png');
            Storage::put("public/imgs/countries/{$imgName}", $resize->__toString());
            //delete the file as a temp
            File::delete('imgs/countries/' . $imgName);
        } else {
            $imgName = null;
        }

        $country = CountryMdl::create([
            'name_en'       => Str::title($req->nameEn),
            'name_ar'       => $req->nameAr,
            'country_code'  => Str::upper($req->ariaCode),
            'phone_code'    => '+' . $req->phoneCode,
            'currency_code' => Str::upper($req->currency),
            'flag'          => $imgName,
        ]);

        CountryActivitiesMdl::create([
            'country_id' => $country->id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Submitted To System',
            'activity_ar' => 'تسجيل بالنظام',
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Submit New Country ' . $country->name_en,
            'action_ar' => 'تسجيل دولة جديدة  ' . $country->name_ar,
        ]);

        Toastr()->success(__('general.Added Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function show(CountryMdl $country)
    {
        $country::find($country);

        $cities = CityMdl::where('country_id', $country->id)->get();

        return view('country.show', compact('country', 'cities'));
    }
    /**
     * ============================
     * ============================
     */
    public function edit(CountryMdl $country)
    {
        $country::find($country);

        if (App::getLocale() == 'ar') {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('country_id', 'category_id')->all();

        if (App::getLocale() == 'ar') {
            $subcategories = SubcategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $subcategories = SubcategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }

        $countrySubcategories = CountrySubcategoryMdl::where('country_id', $country->id)->get()->pluck('country_id', 'subcategory_id')->all();


        return view('country.edit', compact('country', 'categories', 'countryCategories', 'subcategories', 'countrySubcategories'));
    }
    /**
     * ============================
     * ============================
     */
    public function update(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'country' => 'required|integer|exists:countries,id',
            'nameAr' => 'required|string|unique:countries,name_ar,' . $req->country . '|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|unique:countries,name_ar,' . $req->country . '|regex:/^[a-zA-z\s]+$/',
            'ariaCode'  => 'required|unique:countries,country_code,' . $req->country . '|regex:/^[a-zA-Z\s]{2}+$/',
            'phoneCode' => 'required|regex:/[0-9]+$/|max:5',
            'currency'    => 'required|regex:/^[a-zA-ZÑñ\s]{3}/',
            'flag'          => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ], [
            'country.required' => __('general.This Field Is Required'),
            'country.integer'   => __('general.Format Not Matching'),
            'country.exists'     => __('country.This Country Not Exists'),

            'nameAr.required' => __('general.This Field Is Required'),
            'nameAr.string'    => __('general.Format Not Matching'),
            'nameAr.unique'   => __('general.This Field Is Already Exists'),
            'nameAr.regex'    => __('general.This Field Accept Arabic Letters Only'),

            'nameEn.required' => __('general.This Field Is Required'),
            'nameEn.string'    => __('general.Format Not Matching'),
            'nameEn.unique'   => __('general.This Field Is Already Exists'),
            'nameEn.regex'    => __('general.This Field Accept English Letters Only'),

            'ariaCode.required' => __('general.This Field Is Required'),
            'ariaCode.unique'   => __('general.This Field Is Already Exists'),
            'ariaCode.regex'    => __('country.Aria Code Must Be 2 English Letters'),

            'phoneCode.required' => __('general.This Field Is Required'),
            'phoneCode.regex'    => __('country.Phone Code Should Be Number'),
            'phoneCode.max'      => __('country.Phone Code Maximum 4 Digits'),

            'currency.required' => __('general.This Field Is Required'),
            'currency.regex'    => __('country.Currency Should Be 3 English Letters'),

            'flag.image'        => __('general.The file you uploaded is Not Image'),
            'flag.mimes'        => __('general.Image formate is not allowed'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        $country = CountryMdl::findOrFail($req->country);

        if ($req->hasFile('flag')) {
            $img = $req->file('flag');
            $imgName = rand() . '.' . $img->getClientOriginalExtension();
            //save file like a temp
            $img->move(('imgs/countries'), $imgName);
            //RESIZING THE PICTURE AND MAKE A COPY WITH NEW SIZE IN STOREAGE FILE
            $resize = Image::make("imgs/countries/{$imgName}")->resize(45, 25)->encode('png');
            Storage::put("public/imgs/countries/{$imgName}", $resize->__toString());
            //delete the file as a temp
            File::delete('imgs/countries/' . $imgName);
            File::delete('storage/app/public/imgs/countries/' . $req->oldPhoto);
        } else {
            $imgName = $req->oldPhoto;
        }

        //UPDATE COUNTRY INFORMATION

        $country->name_en = Str::title($req->nameEn);
        $country->name_ar = $req->nameAr;
        $country->country_code = Str::upper($req->ariaCode);
        $country->phone_code = $req->phoneCode;
        $country->currency_code = Str::upper($req->currency);
        $country->flag = $imgName;
        $country->save();

        //COUNTRY CATEGORY UPDATE
        $country->categoryCountry()->sync($req->category);
        //COUNTRY SUBCATEGORY UPDATE
        $country->subcategoryCountry()->sync($req->subcategory);

        //RECORD UPDATE

        CountryActivitiesMdl::create([
            'country_id' => $country->id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Update Information',
            'activity_ar' => 'تحديث البيانات ',
        ]);

        //USER ACTIVITY

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Update Country ' . $country->name_en,
            'action_ar' => 'تحديث بيانات دولة   ' . $country->name_ar,
        ]);

        Toastr()->success(__('general.Updated Successfully'));
        //return redirect()->route('country.index');
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function history(CountryMdl $country)
    {
        $country::find($country);

        $actions = CountryActivitiesMdl::where('country_id', $country->id)->orderBy('id', 'DESC')->paginate(pageCount);

        return view('country.history', compact('country', 'actions'));
    }
    /**
     * ============================
     * ============================
     */
    public function activate(CountryMdl $country)
    {
        $country::find($country);

        $country->status = 1;
        $country->save();

        //RECORD UPDATE

        CountryActivitiesMdl::create([
            'country_id' => $country->id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Activate Country',
            'activity_ar' => 'تفعيل الدولة ',
        ]);

        //USER ACTIVITY

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Activate Country ' . $country->name_en,
            'action_ar' => 'تفعيل دولة   ' . $country->name_ar,
        ]);

        Toastr()->success(__('general.Activated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function deactivate(CountryMdl $country)
    {
        $country::find($country);

        //DEACTIVATE CITIES
        $cities = CityMdl::where('country_id', $country->id)->get();

        if ($cities->count() > 0) {
            foreach ($cities as $city) {
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
            }
        }

        $country->status = 0;
        $country->save();

        //RECORD UPDATE

        CountryActivitiesMdl::create([
            'country_id' => $country->id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Deactivate Country',
            'activity_ar' => 'إلغاء تفعيل الدولة ',
        ]);

        //USER ACTIVITY

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Deactivate Country ' . $country->name_en,
            'action_ar' => 'إلغاء تفعيل دولة   ' . $country->name_ar,
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
            'countryID' => 'required|integer|exists:countries,id',
        ]);

        if ($valid->fails()) {
            Toastr()->error(__('general.Please Try Again Later'));
            return back();
        }

        $country = CountryMdl::findOrFail($req->countryID);

        $cities = CityMdl::where('country_id', $country->id)->get();

        if ($cities->count() > 0) {
            foreach ($cities as $city) {
                $city_id = $city->id;

                $city->delete();

                $city = CityMdl::withTrashed()->findOrFail($city_id);

                //COUNTRY UPDATE
                CountryActivitiesMdl::create([
                    'country_id' => $city->country_id,
                    'user_id'      => Auth::user()->id,
                    'activity_en' => 'Delete City ' . $city->name_en,
                    'activity_ar' => 'حذف  مدينة ' . $city->name_ar,
                ]);

                //USER ACTION
                UserActivityMdl::create([
                    'user_id' => Auth::user()->id,
                    'action_en' => 'Delete City ' . $city->name_en,
                    'action_ar' => 'حذف  مدينة  ' . $city->name_ar,
                ]);
            }
        }

        $country->delete();

        $country = CountryMdl::withTrashed()->findOrFail($req->countryID);

        //RECORD UPDATE

        CountryActivitiesMdl::create([
            'country_id' => $country->id,
            'user_id'      => Auth::user()->id,
            'activity_en' => 'Country Deleted',
            'activity_ar' => ' حذف الدولة ',
        ]);

        //USER ACTIVITY

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Delete Country ' . $country->name_en,
            'action_ar' => ' حذف دولة   ' . $country->name_ar,
        ]);

        Toastr()->success(__('general.Deleted Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
}
