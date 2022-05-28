<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Validator;

class PermissionController extends Controller
{

    public function fetchPermissions()
    {
        try {
            $permissions = Permission::all();
            return response()->json(['permissions' => $permissions],201);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    public function fetchRoles()
    {
        try {
            $roles = Role::where('name','!=','role 4')->get();
            return response()->json(['roles' => $roles],201);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    public function fetchRolePermissions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'role_id' => 'required'
            ]);

            if($validator->fails()){
                return response()->json(['message' => $validator->errors()],422);
            }

            $role = $request->input('role_id');
            $permissions = Role::where('roles.id',$role)
                    ->join('role_has_permissions','role_has_permissions.role_id','roles.id')
                    ->join('permissions','permissions.id','role_has_permissions.permission_id')
                    ->select('roles.id','permissions.name')
                        ->get();
            return response()->json(['permissions' => $permissions],201);
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    public function createPermission(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],403);
        }

        $input = $request->all();
        Permission::create([
            'name' => $input['name'],
            'guard_name' => 'web'
        ]);

        return response()->json(['message' => 'Permission successfully created'],201);
    }

    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],403);
        }

        $input = $request->all();
        Role::create([
            'name' => $input['name'],
            'guard_name' => 'web'
        ]);

        return response()->json(['message' => 'Role successfully created'],201);
    }

    public function assignRolePermission(Request $request)
    {
//        $validator = Validator::make($request->all(),[
//            'name' => 'required'
//        ]);
//
//        if($validator->fails()){
//            return response()->json(['message' => $validator->errors()],403);
//        }
        $permission = $request->input('permission');
        $role = Role::where('name',$request->input('role'))->first();
        $role->givePermissionTo($permission);

        return response()->json(['message' => 'Role successfully given permission'],201);
    }

    public function assignUserRole(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'role_id' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],422);
        }
        $role = Role::where('id',$request->input('role_id'))->first();
        $user = Admin::where('id',$request->input('user_id'))->first();

        if(empty($user)) {
            return response()->json(['message' => 'You can not assign role to this user'],403);
        }
        if(!empty($role) && !empty($user)){
            $user->assignRole($role->name);
        }
        return response()->json(['message' => 'User successfully assigned to role'],201);
    }

    public function userPermission(Request $request)
    {
//        return $request->input('user_id');
        $user = User::findOrFail($request->input('user_id'));

        return $user->getAllPermissions();
    }

    public function grantRoleMultiplePermission(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'role' => 'required',
            'permissions' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()],422);
        }

        $permissions = $request->input('permissions');
        $roleName = $request->input('role');

        $role = Role::where('id',$roleName)->first();
        $role->givePermissionTo($permissions);

        return response()->json(['message' => 'Role successfully given permission'],201);
    }
}
