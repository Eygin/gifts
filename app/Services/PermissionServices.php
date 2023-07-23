<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionServices
{
    public function permission($request) {
        $dataPermission = new Permission();
        return $dataPermission;
    }

    public function query($request)
    {
        $dataPermission = Permission::query();
        return $dataPermission;
    }

    public function get($request)
    {
        $dataPermission = Permission::orderBy('created_at');
        if(isset($request->select)){
            $dataPermission->select($request->select);
        }
        $dataPermission = $dataPermission->get();
        return $dataPermission;
    }

    public function create($params)
    {
        $data = $params->only(['name']);
        $dataPermission = Permission::create($data);

        return $dataPermission;

    }

    public function show($request , $id)
    {
        $dataPermission = Permission::where('id',$id)->first();
        return $dataPermission;
    }

    public function update($request,$id)
    {
        $data = $request->only(['name']);

        $dataPermission = Permission::where('id',$id)->update($data);
        return $dataPermission;

    }

    public function delete($request,$id)
    {
        $dataPermission = Permission::where('id',$id)->delete($id);

        return $dataPermission;
    }
}
