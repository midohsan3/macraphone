<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryMdl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserActivityMdl;
use App\Models\CategoryUpdatesMdl;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware(['auth', 'activeAdmin']);
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
            $categories = CategoryMdl::orderBy('name_ar', 'ASC')->paginate(pageCount);
        } else {
            $categories = CategoryMdl::orderBy('name_en', 'ASC')->paginate(pageCount);
        }
        $categoriesCount = CategoryMdl::count();

        $list_title = __('general.All');

        return view('category.index', compact('categories', 'categoriesCount', 'list_title'));
    }
    /**
     * ============================
     * ============================
     */
    public function create()
    {
        return view('category.create');
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ], [
            'nameAr.required' => __('general.This Field Is Required'),
            'nameAr.string'    => __('general.Format Not Matching'),
            'nameAr.unique'   => __('general.This Field Is Already Exists'),
            'nameAr.regex'    => __('general.This Field Accept Arabic Letters Only'),

            'nameEn.required' => __('general.This Field Is Required'),
            'nameEn.string'    => __('general.Format Not Matching'),
            'nameEn.unique'   => __('general.This Field Is Already Exists'),
            'nameEn.regex'    => __('general.This Field Accept English Letters Only'),

            'logo.image'        => __('general.The file you uploaded is Not Image'),
            'logo.mimes'       => __('general.Image formate is not allowed'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        if ($req->hasFile('logo')) {
            $img = $req->file('lgo');
            $imgName = rand() . '.' . $img->getClientOriginalExtension();
            //save file like a temp
            $img->move(('imgs/categories'), $imgName);
            //RESIZING THE PICTURE AND MAKE A COPY WITH NEW SIZE IN STOREAGE FILE
            $resize = Image::make("imgs/categories/{$imgName}")->resize(45, 25)->encode('png');
            Storage::put("public/imgs/categories/{$imgName}", $resize->__toString());
            //delete the file as a temp
            File::delete('imgs/categories/' . $imgName);
        } else {
            $imgName = null;
        }

        $category = CategoryMdl::create([
            'name_en' => Str::title($req->nameEn),
            'name_ar' => $req->nameAr,
            'logo' => $imgName,
        ]);

        CategoryUpdatesMdl::create([
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
            'action_en' => 'Submitted On System',
            'action_ar' => 'تسجيل بالنظام',
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Submit New Category ' . $category->name_en,
            'action_ar' => 'تسجيل تصنيف جديدة  ' . $category->name_ar,
        ]);

        Toastr()->success(__('general.Added Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function show(CategoryMdl $category)
    {
        $category::find($category);
        return view('category.show', compact('category'));
    }
    /**
     * ============================
     * ============================
     */
    public function history(CategoryMdl $category)
    {
        $category::find($category);

        $actions = CategoryUpdatesMdl::where('category_id', $category->id)->orderBy('id', 'DESC')->paginate(pageCount);

        return view('category.history', compact('category', 'actions'));
    }
    /**
     * ============================
     * ============================
     */
    public function edit(CategoryMdl $category)
    {
        $category::find($category);
        return view('category.edit', compact('category'));
    }
    /**
     * ============================
     * ============================
     */
    public function update(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'category' => 'required|integer|exists:categories,id',
            'nameAr' => 'required|string|unique:countries,name_ar,' . $req->category . '|regex:/^[؀-ۿ\s]+$/',
            'nameEn' => 'required|string|unique:countries,name_ar,' . $req->category . '|regex:/^[a-zA-z\s]+$/',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ], [
            'category.required' => __('general.This Field Is Required'),
            'category.integer'  => __('general.Format Not Matching'),
            'category.exists'    => __('category.This Category Not Exists'),

            'nameAr.required' => __('general.This Field Is Required'),
            'nameAr.string'    => __('general.Format Not Matching'),
            'nameAr.unique'   => __('general.This Field Is Already Exists'),
            'nameAr.regex'    => __('general.This Field Accept Arabic Letters Only'),

            'nameEn.required' => __('general.This Field Is Required'),
            'nameEn.string'    => __('general.Format Not Matching'),
            'nameEn.unique'   => __('general.This Field Is Already Exists'),
            'nameEn.regex'    => __('general.This Field Accept English Letters Only'),

            'logo.image'        => __('general.The file you uploaded is Not Image'),
            'logo.mimes'       => __('general.Image formate is not allowed'),
        ]);

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput($req->all());
        }

        if ($req->hasFile('logo')) {
            $img = $req->file('logo');
            $imgName = rand() . '.' . $img->getClientOriginalExtension();
            //save file like a temp
            $img->move(('imgs/categories'), $imgName);
            //RESIZING THE PICTURE AND MAKE A COPY WITH NEW SIZE IN STOREAGE FILE
            $resize = Image::make("imgs/categories/{$imgName}")->resize(500, 500)->encode('png');
            Storage::put("public/imgs/categories/{$imgName}", $resize->__toString());
            //delete the file as a temp
            File::delete('imgs/categories/' . $imgName);
            File::delete('storage/app/public/imgs/categories/' . $req->oldLogo);
        } else {
            $imgName = $req->oldLogo;
        }

        $category = CategoryMdl::find($req->category);

        $category->name_en = $req->nameEn;
        $category->name_ar = $req->nameAr;
        $category->logo       = $imgName;
        $category->save();

        CategoryUpdatesMdl::create([
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
            'action_en' => 'Update Information',
            'action_ar' => 'تحديث بيانات',
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Update Category ' . $category->name_en,
            'action_ar' => 'تحديث بيانات تصنيف   ' . $category->name_ar,
        ]);

        Toastr()->success(__('general.Updated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function activate(CategoryMdl $category)
    {
        $category::find($category);

        $category->status = 1;
        $category->save();

        CategoryUpdatesMdl::create([
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
            'action_en' => 'Activate Category',
            'action_ar' => 'تفعيل  التصنيف',
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'Activate Category ' . $category->name_en,
            'action_ar' => 'تفعيل التصنيف    ' . $category->name_ar,
        ]);

        Toastr()->success(__('general.Activated Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
    public function deactivate(CategoryMdl $category)
    {
        $category::find($category);

        $category->status = 0;
        $category->save();

        CategoryUpdatesMdl::create([
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
            'action_en' => 'Deactivate Category',
            'action_ar' => 'إلغاء تفعيل  التصنيف',
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'DEactivate Category ' . $category->name_en,
            'action_ar' => 'إلغاء تفعيل التصنيف    ' . $category->name_ar,
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
            'categoryID' => 'required|integer|exists:categories,id',
        ]);

        if ($valid->fails()) {
            Toastr()->error(__('general.Please Try Again Later'));
            return back();
        }

        $category = CategoryMdl::findOrFail($req->categoryID);

        $category->delete();

        $category = CategoryMdl::withTrashed()->findOrFail($req->categoryID);

        CategoryUpdatesMdl::create([
            'category_id' => $category->id,
            'user_id' => Auth::user()->id,
            'action_en' => 'Delete Category',
            'action_ar' => ' حذف  التصنيف',
        ]);

        UserActivityMdl::create([
            'user_id' => Auth::user()->id,
            'action_en' => 'DEactivate Category ' . $category->name_en,
            'action_ar' => ' حذف التصنيف    ' . $category->name_ar,
        ]);

        Toastr()->success(__('general.Deleted Successfully'));
        return back();
    }
    /**
     * ============================
     * ============================
     */
}
