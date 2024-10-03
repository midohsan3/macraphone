<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /*
    *
    * Display a listing of the resource.*
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:roles|roles-list', ['only' => ['index', 'store']]);
        $this->middleware('permission:roles|roles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles|roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles|roles-delete', ['only' => ['destroy']]);
        $this->middleware('auth');
    }

    /*
    *
    * Display a listing of the resource.*
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $roles = Role::orderBy('name', 'ASC')->paginate(pageCount);
        $rolesCount = Role::count();

        return view('roles.index', compact('roles', 'rolesCount'));
    }

    /*** Show the form for creating a new resource.*
     * * @return \Illuminate\Http\Response*/
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.create', compact('permissions'));
    }

    /*
    ** Store a newly created resource in storage.*
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permission'));

        toastr()->success(__('role.Role created successfully'));

        return redirect()->route('role.index');
    }

    /*
    *
    * Display the specified resource.*
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")->where("role_has_permissions.role_id", $id)->orderBy('permissions.name', 'ASC')->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /*
    *
    * Show the form for editing the specified resource.*
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $role = Role::find($id);

        $permissions = Permission::orderBy('permissions.name')->get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /*
    *
    * Update the specified resource in storage.*
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        toastr()->success(__('role.Role updated successfully'));

        return redirect()->route('role.index');
    }

    /*
    *
    * Remove the specified resource from storage.*
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $req)
    {
        $valid = Validator::make($req->all(), [
            'roleID' => 'required|numeric',
        ]);
        if ($valid->fails()) {
            toastr()->error(trans('roles.Cant finish your request now, please try again later'));
            return back();
        }
        DB::table("roles")->where('id', $req->roleID)->delete();

        toastr()->success(trans('roles.Role deleted successfully'));

        return redirect()->route('role.index');
    }
}