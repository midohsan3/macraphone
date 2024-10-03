<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryMdl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubcategoryMdl;
use App\Models\UserActivityMdl;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\CategoryUpdatesMdl;
use App\Models\CountryActivitiesMdl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
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
            $subcategories = SubcategoryMdl::orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $subcategories = SubcategoryMdl::orderBy('name_en', 'ASC')->paginate(pageCount);
        }

        $subcategoriesCount = SubcategoryMdl::count();

        $list_title = __('general.All');

        return view('subcategory.index', compact('subcategories', 'subcategoriesCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function create()
    {
        if (App::getLocale() == 'ar') {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }

        return view('subcategory.create', compact('categories'));
    }
    /**
     * ============================
     * ============================
     */
    public function store(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'category' => 'required|integer|exists:categories,id',
            'nameAr' => 'required|string|min:3|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
        ], [
            'category.required' => __('general.This Field Is Required'),
            'category.integer'  => __('general.Format Not Matching'),
            'category.exists'    => __('country.This Category Not Exists'),

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

        $subcategory = SubcategoryMdl::create([
            'category_id' => $req->category,
            'name_en' => Str::title($req->nameEn),
            'name_ar' => $req->nameAr,
        ]);

        CategoryUpdatesMdl::create([
            'category_id' => $subcategory->category_id,
            'user_id'      => Auth::user()->id,
            'action_en' => 'Submitted New Subcategory ' . $subcategory->name_en,
            'action_ar' => 'تسجيل تصنيف فرعي  ' . $subcategory->name_ar,
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Submit New Subcategory ' . $subcategory->name_en,
            'action_ar' => 'تسجيل تصنيف فرعي جديدة  ' . $subcategory->name_ar,
        ]);

        Toastr()->success(__('general.Added Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function show(SubcategoryMdl $subcategory)
    {
        $subcategory::find($subcategory);
        return view('subcategory.show', compact('subcategory'));
    }
    /**
     * ============================
     * ============================
     */
    public function edit(SubcategoryMdl $subcategory)
    {
        $subcategory::find($subcategory);

        if (App::getLocale() == 'ar') {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_ar', 'ASC')->get();
        } else {
            $categories = CategoryMdl::where('status', 1)->orderBy('name_en', 'ASC')->get();
        }
        return view('subcategory.edit', compact('subcategory', 'categories'));
    }
    /**
     * ============================
     * ============================
     */
    public function update(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'subcategory' => 'required|integer|exists:subcategories,id',
            'category' => 'required|integer|exists:categories,id',
            'nameAr' => 'required|string|min:3|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
        ], [
            'subcategory.required' => __('general.This Field Is Required'),
            'subcategory.integer'  => __('general.Format Not Matching'),
            'subcategory.exists'    => __('country.This Category Not Exists'),

            'category.required' => __('general.This Field Is Required'),
            'category.integer'  => __('general.Format Not Matching'),
            'category.exists'    => __('country.This Category Not Exists'),

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

        $subcategory = SubcategoryMdl::findOrFail($req->subcategory);

        $subcategory->category_id = $req->category;
        $subcategory->name_en = Str::title($req->nameEn);
        $subcategory->name_ar = $req->nameAr;
        $subcategory->save();

        CategoryUpdatesMdl::create([
            'category_id' => $subcategory->category_id,
            'user_id'      => Auth::user()->id,
            'action_en' => 'Update Subcategory ' . $subcategory->name_en,
            'action_ar' => 'تحديث بيانات تصنيف فرعي  ' . $subcategory->name_ar,
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Update Subcategory ' . $subcategory->name_en,
            'action_ar' => 'تحديث بيانات تصنيف فرعي جديدة  ' . $subcategory->name_ar,
        ]);

        Toastr()->success(__('general.Updated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function activate($subcategory)
    {
        $subcategory = SubcategoryMdl::where('id', $subcategory)->first();

        $subcategory->status = 1;
        $subcategory->save();

        CategoryUpdatesMdl::create([
            'category_id' => $subcategory->category_id,
            'user_id'      => Auth::user()->id,
            'action_en' => 'Activated Subcategory ' . $subcategory->name_en,
            'action_ar' => 'تفعيل تصنيف فرعي  ' . $subcategory->name_ar,
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Activated Subcategory ' . $subcategory->name_en,
            'action_ar' => 'تفعيل تصنيف فرعي جديدة  ' . $subcategory->name_ar,
        ]);

        Toastr()->success(__('general.Activated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function deactivate($subcategory)
    {
        $subcategory = SubcategoryMdl::where('id', $subcategory)->first();

        $subcategory->status = 0;
        $subcategory->save();

        CategoryUpdatesMdl::create([
            'category_id' => $subcategory->category_id,
            'user_id'      => Auth::user()->id,
            'action_en' => 'Deactivated Subcategory ' . $subcategory->name_en,
            'action_ar' => 'إلغاء تفعيل تصنيف فرعي  ' . $subcategory->name_ar,
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Deactivated Subcategory ' . $subcategory->name_en,
            'action_ar' => 'إلغاء تفعيل تصنيف فرعي جديدة  ' . $subcategory->name_ar,
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
            'subcategoryID' => 'required|integer|exists:subcategories,id',
        ]);

        if ($valid->fails()) {
            Toastr()->error(__('general.Please Try Again Later'));
            return back();
        }

        $subcategory = SubcategoryMdl::findOrFail($req->subcategoryID);

        $subcategory->delete();

        $subcategory = SubcategoryMdl::withTrashed()->findOrFail($req->subcategoryID);

        //COUNTRY UPDATE
        CategoryUpdatesMdl::create([
            'category_id' => $subcategory->category_id,
            'user_id'      => Auth::user()->id,
            'action_en' => 'Delete Subcategory ' . $subcategory->name_en,
            'action_ar' => ' حذف التصنيف الفرعي ' . $subcategory->name_ar,
        ]);

        //USER ACTION
        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Delete Subcategory ' . $subcategory->name_en,
            'action_ar' => 'حذف   التصنيف الفرعي  ' . $subcategory->name_ar,
        ]);

        Toastr()->success(__('general.Deleted Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
}