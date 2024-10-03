<?php

namespace App\Http\Controllers\Front;

use Exception;
use App\Models\AdMdl;
use App\Models\CityMdl;
use App\Models\CountryMdl;
use App\Models\AdPhotosMdl;
use App\Models\CategoryMdl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubcategoryMdl;
use App\Models\CountryCategoryMdl;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\CountrySubcategoryMdl;

class HomeController extends Controller
{
    /**
     * ============================
     * ============================
     */
    public function index()
    {
        $country = CountryMdl::where([['country_code', currentLoc()], ['status', 1]])->first();
        if ($country) {
            return redirect()->route('country', Str::lower($country->country_code));
        }
        return view('front.main');
    }
    /**
     * ============================
     * ============================
     */

    public function country($country)
    {
        $country = CountryMdl::where([['country_code', Str::upper($country)], ['status', 1]])->first();

        if (!$country) {
            return redirect()->route('front');
        }

        if (App::getLocale() == 'ar') {
            $cities = CityMdl::where([['country_id', $country->id], ['status', 1]])->orderBy('name_ar', 'ASC')->get();
        } else {
            $cities = CityMdl::where([['country_id', $country->id], ['status', 1]])->orderBy('name_en', 'ASC')->get();
        }

        if (App::getLocale() == 'ar') {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('category_id')->all();

        $citiesAds = AdMdl::where([['country_id', $country->id], ['status', 3]])->selectRaw('count(id) as adsCount , city_id')->groupBy('city_id')->get()->pluck('adsCount', 'city_id')->all();

        $featuredAds = AdMdl::where([['country_id', $country->id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(10)->get();

        $lastAds = AdMdl::where([['country_id', $country->id], ['status', 3]])->orderBy('id', 'DESC')->limit(9)->get();

        return view('front.home', compact('country', 'cities', 'categories', 'countryCategories', 'citiesAds', 'featuredAds', 'lastAds'));
    }
    /**
     * ============================
     * ============================
     */
    public function city($country, $city)
    {
        $country = CountryMdl::where([['country_code', $country], ['status', 1]])->first();

        $city = CityMdl::where([['name_en', Str::title($city)], ['status', 1]])->first();

        if (!$country || !$city) {
            return redirect()->route('front');
        }

        if (App::getLocale() == 'ar') {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('category_id')->all();

        $ads = AdMdl::where([['city_id', $city->id], ['status', 3]])->orderBy('id', 'DESC')->orderBy('ad_type', 'DESC')->paginate(pageCount);

        $featuredAds = AdMdl::where([['city_id', $city->id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(10)->get();

        return view('front.city', compact('country', 'city', 'categories', 'countryCategories', 'ads', 'featuredAds'));
    }
    /**
     * ============================
     * ============================
     */
    public function getCategory($country, $category)
    {

        $country = CountryMdl::where('country_code', $country)->first();

        $category = CategoryMdl::where('name_en', Str::title($category))->first();

        if (!$country || !$category) {
            return redirect()->route('front');
        }

        $ads = AdMdl::where([['country_id', $country->id], ['category_id', $category->id], ['status', 3]])->orderBy('id', 'DESC')->orderBy('ad_type', 'DESC')->paginate(pageCount);

        $featuredAds = AdMdl::where([['country_id', $country->id], ['category_id', $category->id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(5)->get();

        $countrySubcategory = CountrySubcategoryMdl::where([['country_id', $country->id]])->get()->pluck('country_id', 'subcategory_id')->all();

        if (App::getLocale() == 'ar') {
            $subcategories = SubcategoryMdl::where('category_id', $category->id)->orderBy('name_ar', 'ASC')->get();
        } else {
            $subcategories = SubcategoryMdl::where('category_id', $category->id)->orderBy('name_en', 'ASC')->get();
        }

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('category_id')->all();


        return view('front.category', compact('country', 'category', 'ads', 'featuredAds', 'countrySubcategory', 'subcategories', 'countryCategories'));
    }
    /**
     * ============================
     * ============================
     */
    public function about()
    {
        return view('front.about');
    }
    /**
     * ============================
     * ============================
     */
    public function contact()
    {
        return view('front.contact');
    }
    /**
     * ============================
     * ============================
     */
    public function policy()
    {
        return view('front.policy');
    }
    /**
     * ============================
     * ============================
     */


    public function countryCategory($country, $category)
    {

        $country = CountryMdl::find($country);

        $category = CategoryMdl::find($category);

        $ads = AdMdl::where([['country_id', $country->id], ['category_id', $category->id], ['status', 3]])->orderBy('id', 'DESC')->orderBy('ad_type', 'DESC')->paginate(pageCount);

        $featuredAds = AdMdl::where([['country_id', $country->id], ['category_id', $category->id], ['status', 3], ['featured', 1]])->orderBy('id', 'DESC')->inRandomOrder()->limit(10)->get();

        if (App::getLocale() == 'ar') {
            $subcategories = SubcategoryMdl::where('category_id', $category->id)->orderBy('name_ar', 'ASC')->get();
        } else {
            $subcategories = SubcategoryMdl::where('category_id', $category->id)->orderBy('name_en', 'ASC')->get();
        }

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('category_id')->all();

        return view('front.category', compact('country', 'category', 'ads', 'featuredAds', 'subcategories', 'countryCategories'));
    }
    /**
     * ============================
     * ============================
     */

    public function cityCategory($city, $category)
    {

        $city = CityMdl::find($city);

        $country = CountryMdl::find($city->country_id);

        $category = CategoryMdl::find($category);

        $ads = AdMdl::where([['category_id', $category->id], ['city_id', $city->id], ['status', 3]])->orderBy('id', 'DESC')->orderBy('ad_type', 'DESC')->paginate(pageCount);

        $featuredAds = AdMdl::where([['category_id', $category->id], ['city_id', $city->id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(10)->get();

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('category_id')->all();

        return view('front.city', compact('city', 'ads', 'featuredAds', 'countryCategories'));
    }
    /**
     * ============================
     * ============================
     */
    public function subcategory($country, $category, $subcategory)
    {
        $country = CountryMdl::where('country_code', Str::upper($country))->first();

        $subcategory = SubcategoryMdl::where('name_en', Str::title($subcategory))->first();

        $category = CategoryMdl::find($subcategory->category_id);

        $ads = AdMdl::where([['country_id', $country->id], ['subcategory_id', $subcategory->id], ['status', 3]])->orderBy('id', 'DESC')->orderBy('ad_type', 'DESC')->paginate(pageCount);

        $featuredAds = AdMdl::where([['country_id', $country->id], ['subcategory_id', $subcategory->id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(10)->get();

        $countrySubcategory = CountrySubcategoryMdl::where([['country_id', $country->id]])->get()->pluck('country_id', 'subcategory_id')->all();

        if (App::getLocale() == 'ar') {
            $subcategories = SubcategoryMdl::where('category_id', $category->id)->orderBy('name_ar', 'ASC')->get();
        } else {
            $subcategories = SubcategoryMdl::where('category_id', $category->id)->orderBy('name_en', 'ASC')->get();
        }

        $countryCategories = CountryCategoryMdl::where('country_id', $country->id)->get()->pluck('category_id')->all();

        return view('front.category', compact('subcategory', 'country', 'category', 'ads', 'featuredAds', 'countrySubcategory', 'subcategories', 'countryCategories'));
    }
    /**
     * ============================
     * ============================
     */
    public function adSingle($country, $city, $category, $subcategory, $ad)
    {

        $ad = AdMdl::find($ad);

        $ad->views = $ad->views + 1;
        $ad->save();

        $photos = AdPhotosMdl::where('ad_id', $ad->id)->get();

        $relatedAds = AdMdl::where([['country_id', $ad->country_id], ['category_id', $ad->category_id], ['status', 3]])->inRandomOrder()->limit(10)->get();

        $featuredAds = AdMdl::where([['country_id', $ad->country_id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(10)->get();

        $countryCategories = CountryCategoryMdl::where('country_id', $ad->county_id)->get()->pluck('category_id')->all();

        return view('front.single', compact('ad', 'photos', 'relatedAds', 'featuredAds', 'countryCategories'));
    }
    /**
     * ============================
     * ============================
     */
    public function newtst()
    {
        return view('front.new');
    }
    /**
     * ============================
     * ============================
     */
}