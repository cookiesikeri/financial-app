<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ManagesResponse;
use Exception;

class RoleController extends Controller
{
    use ManagesResponse;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name',
                'permission' => 'required',
            ]);

            // return $request;
            if ($validator->fails()) {
                return $this->sendError($validator->errors(),[],422);
            }
        
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permission'));
        
            return $this->sendResponse($role,'Role created successfully');
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        try{
            $roles = Role::orderBy('name','ASC')->paginate(10);
            return $this->sendResponse($roles, '');
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        try{
            $role = Role::find($id);
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->get();

            return $this->sendResponse(['role'=>$role, 'role_permissions'=>$rolePermissions], '');
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'permission' => 'required',
            ]);

            // return $request;
            if ($validator->fails()) {
                return $this->sendError($validator->errors(),[],422);
            }
        
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();
        
            $role->syncPermissions($request->input('permission'));
        
            return $this->sendResponse($role,'Role updated successfully');
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $role = Role::find($id);
            //$role->revokePermission
            $role->delete;
            return $this->sendResponse('success','Role deleted successfully');
        }catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
}
