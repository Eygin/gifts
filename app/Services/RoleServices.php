<?php
namespace App\Services;

use Spatie\Permission\Models\Role;;
use Illuminate\Support\Facades\Auth;

class RoleServices
{
    public function role($request) {
        $dataRole = new Role();
        return $dataRole;
    }

    public function query($request)
    {
        $dataRole = Role::query();
        return $dataRole;
    }

    public function get($request)
    {
        $dataRole = Role::orderBy('created_at');
        if(isset($request->select)){
            $dataRole->select($request->select);
        }
        $dataRole = $dataRole->get();
        return $dataRole;
    }

    public function create($params)
    {
        $data = $params->only(['name']);
        $dataRole = Role::create($data);

        return $dataRole;

    }

    public function show($request , $id)
    {
        $dataRole = Role::where('id',$id)->first();
        return $dataRole;
    }

    public function update($request,$id)
    {
        $data = $request->only(['name']);
        $dataRole = Role::where('id',$id)->update($data);
        return $dataRole;

    }

    public function delete($request,$id)
    {
        $dataRole = Role::where('id',$id)->delete($id);
        return $dataRole;
    }
}
