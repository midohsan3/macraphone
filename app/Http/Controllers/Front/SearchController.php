<?php

namespace App\Http\Controllers\Front;

use App\Models\AdMdl;
use App\Models\CountryMdl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{

    public function search(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'country'  => 'required|min:2|max:2|exists:countries,country_code',
            'title'       => 'required|string',
            'category' => 'nullable|integer|exists:categories,id',
            'city'       => 'nullable|integer|exists:cities,id',
        ], [
            'country.required' => __('front.Please Refresh and Try agin'),
            'country.min'      => __('front.Please Refresh and Try agin'),
            'country.max'     => __('front.Please Refresh and Try agin'),
            'country.exists'    => __('front.Please Refresh and Try agin'),

            'title.required'     => __('front.Searching Text Is Required'),
            'title.string'        => __('front.Searching Text Is Required'),

            'category.integer' => __('front.Please Refresh and Try agin'),
            'category.exists'   => __('front.Please Refresh and Try agin'),
            '
            city.integer' => __('front.Please Refresh and Try agin'),
            'city.exists'   => __('front.Please Refresh and Try agin'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        $country = CountryMdl::where('country_code', $req->country)->first();

        if (($req->category !== null) && ($req->city !== null)) {
            $ads = AdMdl::where([['city_id', $req->city], ['category_id', $req->category], ['status', 3]])->where('description', 'LIKE', "%{$req->title}%")->paginate(pageCount);
        } elseif (($req->category == null) && ($req->city !== null)) {
            $ads = AdMdl::where([['city_id', $req->city], ['status', 3]])->where('description', 'LIKE', "%{$req->title}%")->paginate(pageCount);
        } elseif (($req->category !== null) && ($req->city == null)) {
            $ads = AdMdl::where([['country_id', $country->id], ['category_id', $req->category], ['status', 3]])->where('description', 'LIKE', "%{$req->title}%")->paginate(pageCount);
        } elseif (($req->category == null) && ($req->city == null)) {
            $ads = AdMdl::where([['country_id', $country->id], ['status', 3]])->where('description', 'LIKE', "%{$req->title}%")->paginate(pageCount);
        }

        $featuredAds = AdMdl::where([['country_id', $country->country_id], ['status', 3], ['featured', 1]])->inRandomOrder()->limit(10)->get();
        return view('front.search', compact('country', 'ads', 'featuredAds'));
    }
}