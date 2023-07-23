<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersServices
{
    public function user($request) {
        $dataUser = new User();
        return $dataUser;
    }

    public function query($request)
    {
        $dataUser = User::query();
        return $dataUser;
    }

    public function get($request)
    {
        $dataUser = User::orderBy('created_at');
        if(isset($request->select)){
            $dataUser->select($request->select);
        }
        $dataUser = $dataUser->get();
        return $dataUser;
    }

    public function create($request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);
        $dataUser = User::create($data);
        return $dataUser;
    }

    public function show($request , $id)
    {
        $dataUser = User::where('id',$id)->first();
        return $dataUser;
    }

    public function update($request,$id)
    {
        $data = $request->only(['nama', 'email']);
        $dataUser = User::where('id',$id)->update($data);
        return $dataUser;

    }

    public function delete($request,$id)
    {
        $dataUser = User::where('id',$id)->delete($id);
        return $dataUser;
    }

    public function createUser($request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);
        $dataUser = User::create($data);
        return $dataUser;
    }
}
