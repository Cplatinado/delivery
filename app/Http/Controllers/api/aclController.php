<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class aclController extends Controller
{
    public function storeRole(Request $request)
    {
        $role =  new Role();
        $role->name = $request->role;
        $role->save();
        return response()->json($role);
    }

    public function storePermission(Request $request)
    {
        $permission =  new Permission();
        $permission->name = $request->permission;
        $permission->save();
        return response()->json('success');
    }

    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function getPermissions(Request $request)
    {
        $permissions = Permission::all();
        $role = Role::find($request->role);

        foreach ($permissions as $permission) {
            if($role->hasPermissionTo($permission->name)){
                $permission->can = true;
            }else{
                $permission->can = false;
            }
        }
        return response()->json($permissions);
    }

    public function permissionSync(Request $request)
    {
        $permissionsRequest = $request->except(['_token','_method']);

        $permissions = [];
        foreach($permissionsRequest['list'] as $permission){
            if($permission != null){
                $permissions[] = Permission::find( $permission['id']);
            }
        }

        $role = Role::find($permissionsRequest['role']);
        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }else{
            $role->syncPermissions(null);

        }

        return response()->json('success');




    }

    public function rolesSync(Request $request)
    {
         $user = User::find($request->user);
         $role = Role::find($request->role);

         if(!empty($role)){
             $user->syncRoles($role);
             $user->office = $role->name;
             $user->save();
         }else{
            $user->syncRoles(null);

         }

         return response()->json($user->office);



    }

    public function getAdmins()
    {
        $roles = Role::orderBy('name')->take(4)->get() ;
        return response()->json($roles);
    }




}
