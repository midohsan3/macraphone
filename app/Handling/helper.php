<?php

use App\Models\AdMdl;
use App\Models\CityMdl;
use App\Models\CountryMdl;
use App\Models\CategoryMdl;
use Illuminate\Support\Str;
use App\Models\SubcategoryMdl;
use App\Models\CountryCategoryMdl;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;

function currentLoc()
{
  $ip = Request()->ip();
  try {
    return Location::get($ip)->countryCode;
  } catch (\Exception $th) {
    return 'US';
  }
}
/*
============================
============================
*/
function currentCountryName()
{
  //$ip = Request()->ip();
  //$countryCode = Location::get()->countryCode;

  $country = CountryMdl::where('country_code', currentLoc())->first();

  if ($country) {
    if (App::getLocale() == 'ar') {
      $countryName = $country->name_ar;
    } else {
      $countryName = $country->name_en;
    }
    return $countryName;
  } else {
    return 'Not Supported';
  }
}
/*
============================
============================
*/
function usedCurrency()
{

  //$ip = Request()->ip();
  //$loc = Location::get()->countryCode;
  try {
    $country = CountryMdl::where('country_code', currentLoc())->first();
    return $country->currency_code;
  } catch (\Exception $th) {
    return 'USD';
  }
}
/*
============================
============================
*/
function countries()
{
  if (App::getLocale() == 'ar') {
    $countries = CountryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
  } else {
    $countries = CountryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
  }
  return $countries;
}
/*
============================
============================
*/

function currentCities()
{
  //$ip = Request()->ip();
  //$loc = Location::get();
  $country = CountryMdl::where('country_code', currentLoc())->first();
  if ($country) {
    if (App::getLocale() == 'ar') {
      $current_cities = CityMdl::where([['country_id', $country->id], ['status', 1]])->orderBy('name_ar', 'ASC')->get();
    } else {
      $current_cities = CityMdl::where([['country_id', $country->id], ['status', 1]])->orderBy('name_en', 'ASC')->get();
    }
  }
  return $current_cities;
}
/*
============================
============================
*/

function naveCategories()
{
  if (App::getLocale() == 'ar') {
    $categories = CategoryMdl::where('status', 1)->limit(5)->inRandomOrder()->get();
  } else {
    $categories = CategoryMdl::where('status', 1)->inRandomOrder()->limit(5)->get();
  }

  return $categories;
}
/*
============================
============================
*/
function categories()
{
  if (App::getLocale() == 'ar') {
    return CategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
  } else {
    return CategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
  }
}
/*
============================
============================
*/

function subcategories()
{
  if (App::getLocale() == 'ar') {
    $subcategories = SubcategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
  } else {
    $subcategories = SubcategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
  }
  return $subcategories;
}
/*
============================
============================
*/
function not_completed_ads()
{
  return AdMdl::where([['user_id', Auth::user()->id], ['status', 0]])->count();
}
/*
============================
============================
*/
function review_ads()
{
  return AdMdl::where([['user_id', Auth::user()->id], ['status', 1]])->count();
}
/*
============================
============================
*/
function archived_ads()
{
  return AdMdl::where([['user_id', Auth::user()->id], ['status', 2]])->count();
}
/*
============================
============================
*/
function active_ads()
{
  return AdMdl::where([['user_id', Auth::user()->id], ['status', 3]])->count();
}
/*
============================
============================
*/
function rejected_ads()
{
  return AdMdl::where([['user_id', Auth::user()->id], ['status', 4]])->count();
}
/*
============================
============================
*/