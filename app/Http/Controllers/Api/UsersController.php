<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DaftarValidasi;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidasi;
use App\Http\Requests\StoreUserValidasi;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Services\UsersServices;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function __construct(UsersServices $usersServices)
    {
        $this->userServices = $usersServices;
    }

    public function postDaftarAdmin(DaftarValidasi $request)
    {
        $dataUser = $this->userServices->createUser($request);
        if (!is_null($dataUser)) {
            $role = Role::findOrCreate('super admin');
            $dataUser->assignRole($role->name);
            return response()->json(['success' => true, 'message' => 'berhasil membuat user', 'data' => $dataUser], 200);
        }
        return response()->json(['success' => false, 'message' => 'gagal membuat user'], 500);
    }

    public function postDaftarUser(DaftarValidasi $request)
    {
        $dataUser = $this->userServices->createUser($request);
        if (!is_null($dataUser)) {
            $role = Role::findOrCreate('user');
            $dataUser->assignRole($role->name);
            return response()->json(['success' => true, 'message' => 'berhasil membuat user', 'data' => $dataUser], 200);
        }
        return response()->json(['success' => false, 'message' => 'gagal membuat user'], 500);
    }

    public function postLogin(LoginValidasi $request)
    {
        $data = $request->only('email', 'password');
        if(!$token = auth()->guard('api')->attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),
            'token'   => $token
        ], 200);
    }

    public function postLogout(Request $request)
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());
        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil!',
            ]);
        }
    }

    public function index(Request $request)
    {
        if (!Gate::check('user')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }
        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $dataUsers = $this->userServices->query($request)->with("roles:id,name")->paginate($perPage, ['*'], 'page', $page);
        return UserResource::collection($dataUsers);
    }

    public function show(Request $request, $id)
    {
        if (!Gate::check('lihat user')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataUsers = $this->userServices->show($request, $id);
        if (!is_null($dataUsers)) {
            $dataUsers['roles'] = $dataUsers->roles;
            return response()->json(['status' => true,'data' => $dataUsers], 200);
        }
        return response()->json(['status' => false, 'message' => 'Users tidak ditemukan'], 404);
    }

    public function store(DaftarValidasi $request)
    {
        if (!Gate::check('tambah user')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataUsers = $this->userServices->create($request);
        if (!is_null($dataUsers)) {
            $role = Role::findOrCreate('user');
            $dataUsers->assignRole($role->name);
            return response()->json(['success' => true, 'message' => 'berhasil membuat user', 'data' => $dataUsers], 200);
        }
        return response()->json(['success' => false, 'message' => 'gagal membuat user'], 500);
    }

    public function update(StoreUserValidasi $request, $id)
    {
        if (!Gate::check('edit user')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataUsers = $this->userServices->show($request, $id);
        if (is_null($dataUsers)) {
            return response()->json(['status' => false, 'message' => 'User tidak ditemukan'], 404);
        }
        $this->userServices->update($request, $id);
        $updateNewUsers = $this->userServices->show($request, $id);
        return response()->json(['status' => true,'data' => $updateNewUsers], 200);
    }

    public function destroy(Request $request,$id)
    {
        if (!Gate::check('hapus user')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataUsers = $this->userServices->show($request, $id);
        if (is_null($dataUsers)) {
            return response()->json(['status' => false, 'message' => 'User tidak ditemukan'], 404);
        }
        $this->userServices->delete($request, $id);
        return response()->json(['status' => true,'data' => $dataUsers], 200);
    }
}
