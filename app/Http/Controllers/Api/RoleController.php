<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleValidasi;
use App\Http\Resources\RoleResource;
use App\Services\PermissionServices;
use App\Services\RoleServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function __construct(RoleServices $roleServices, PermissionServices $permissionServices)
    {
        $this->roleServices = $roleServices;
        $this->permissionServices = $permissionServices;
    }

    public function index(Request $request)
    {
        if (!Gate::check('role')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }
        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $dataRole = $this->roleServices->query($request)->with('permissions:id,name')->paginate($perPage, ['*'], 'page', $page);
        return RoleResource::collection($dataRole);
    }

    public function show(Request $request, $id)
    {
        if (!Gate::check('lihat role')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataRole = $this->roleServices->show($request, $id);
        if (!is_null($dataRole)) {
            return response()->json(['status' => true,'data' => $dataRole], 200);
        }
        return response()->json(['status' => false, 'message' => 'Role tidak ditemukan'], 404);
    }

    public function store(StoreRoleValidasi $request)
    {
        if (!Gate::check('tambah role')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        if(count($request->permission)=="0"){
            return response()->json(['success' => false, 'message' => 'Permission harus diisi terlebih dahulu'], 500);
        }

        if ($request->permission[0] == 'all') {
            $dataPermission = $this->permissionServices->query($request)->get();
        } else {
            $dataPermission = $this->permissionServices->query($request)->whereIn('id', $request->permission)->get();
        }
        $dataRole = $this->roleServices->create($request);
        if (!is_null($dataRole)) {
            $dataRole->syncPermissions($dataPermission);
            return response()->json(['status' => true, 'message' => 'Berhasil membuat role', 'data' => $dataRole], 200);
        }
        return response()->json(['success' => false, 'message' => 'gagal membuat role'], 500);
    }

    public function update(StoreRoleValidasi $request, $id)
    {
        if (!Gate::check('edit role')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        if(count($request->permission)=="0"){
            return response()->json(['success' => false, 'message' => 'Permission harus diisi terlebih dahulu'], 500);
        }

        $dataRoles = $this->roleServices->show($request,$id);
        if (is_null($dataRoles)) {
            return response()->json(['status' => false, 'message' => 'Role tidak ditemukan'], 404);
        }

        $this->roleServices->update($request,$id);
        if ($request->permission[0] == 'all') {
            $dataPermission = $this->permissionServices->query($request)->get();
        } else {
            $dataPermission = $this->permissionServices->query($request)->whereIn('id', $request->permission)->get();
        }
        $dataRoles->syncPermissions($dataPermission);
        $updateNewRole = $this->roleServices->query($request)->with('permissions:id,name')->where('id', $id)->first();
        return response()->json(['status' => true,'data' => $updateNewRole], 200);
    }

    public function destroy(Request $request,$id)
    {
        if (!Gate::check('hapus role')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataRole = $this->roleServices->show($request, $id);
        if (is_null($dataRole)) {
            return response()->json(['status' => false, 'message' => 'Role tidak ditemukan'], 404);
        }
        $this->roleServices->delete($request, $id);
        return response()->json(['status' => true,'data' => $dataRole], 200);
    }
}
