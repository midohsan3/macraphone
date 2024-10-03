<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdMdl;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $users = User::where('role_name', 'Client')->paginate(pageCount);

        $usersCount = User::where('role_name', 'Client')->count();

        $ads = AdMdl::selectRaw('count(id) as adsCount, user_id')->groupBy('user_id')->get()->pluck('adsCount', 'user_id')->all();

        $list_title = __('general.All');

        return view('users.index', compact('users', 'usersCount', 'ads', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
}
