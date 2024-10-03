<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\AdMdl;
use App\Mail\AproveAd;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Mail\CountryReject;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class AdController extends Controller
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
        $ads = AdMdl::orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::count();

        $list_title = __('general.All');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function notcompleted()
    {
        $ads = AdMdl::where('status', 0)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('status', 0)->count();

        $list_title = __('ad.Not Competed');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function underReview()
    {
        $ads = AdMdl::where('status', 1)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('status', 1)->count();

        $list_title = __('ad.Under Review');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function archived()
    {
        $ads = AdMdl::where('status', 2)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('status', 2)->count();

        $list_title = __('ad.Archive');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function active()
    {
        $ads = AdMdl::where('status', 3)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('status', 3)->count();

        $list_title = __('general.Active');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function rejected()
    {
        $ads = AdMdl::where('status', 4)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('status', 4)->count();

        $list_title = __('ad.Reject');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */ public function expired()
    {
        $ads = AdMdl::where('status', 3)->whereDate('end_date', '<=', Carbon::now())->orderBy('end_date', 'ASC')->paginate(pageCount);

        $adsCount = AdMdl::where('status', 4)->count();

        $list_title = __('ad.Expired');

        return view('ads.admin.index', compact('ads', 'adsCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function userPosts($user)
    {

        $user = User::find($user);

        $ads = AdMdl::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(pageCount);

        $adsCount = AdMdl::where('user_id', $user->id)->count();

        return view('ads.admin.userads', compact('ads', 'adsCount'));
    }
    /**
     * ============================
     * ============================
     */
    public function approveAd($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->status = 3;
        $ad->start_date  = Carbon::now();
        $ad->end_date   = Carbon::now()->addDays(30);
        $ad->save();

        $data = ['user' => $ad->userAd->name, 'title' => $ad->title, 'finish_dt' => $ad->end_date];

        Mail::to($ad->userAd->email)->send(new AproveAd($data));

        Toastr()->success('Add Approved Successfully');
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function countryReject($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->status = 4;
        $ad->save();

        $data = ['user' => $ad->userAd->name, 'title' => $ad->title];

        Mail::to($ad->userAd->email)->send(new CountryReject($data));

        Toastr()->success('Add Rejected Successfully');
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function contentReject($ad)
    {
        $ad = AdMdl::find($ad);

        $ad->status = 4;
        $ad->save();

        $data = ['user' => $ad->userAd->name, 'title' => $ad->title];

        Mail::to($ad->userAd->email)->send(new CountryReject($data));

        Toastr()->success('Add Rejected Successfully');
        return back();
    }
    /**
     * ============================
     * ============================
     */
}
