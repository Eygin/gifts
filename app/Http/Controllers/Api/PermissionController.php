<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionValidasi;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionServices;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function __construct(PermissionServices $permissionServices)
    {
        $this->permissionServices = $permissionServices;
    }

    public function index(Request $request)
    {
        if (!Gate::check('permission')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }
        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $dataPermission = $this->permissionServices->query($request)->paginate($perPage, ['*'], 'page', $page);
        return PermissionResource::collection($dataPermission);
    }

    public function store(StorePermissionValidasi $request)
    {
        if (!Gate::check('tambah permission')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }
        $dataPermission = $this->permissionServices->create($request);
        if (!is_null($dataPermission)) {
            return response()->json(['status' => true, 'message' => 'Berhasil membuat permission', 'data' => $dataPermission], 200);
        }

        return response()->json(['status' => false, 'message' => 'Gagal membuat permission'], 500);
    }

    public function show(Request $request, $id)
    {
        if (!Gate::check('lihat permission')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataPermission = $this->permissionServices->show($request, $id);
        if (!is_null($dataPermission)) {
            return response()->json(['status' => true,'data' => $dataPermission], 200);
        }
        return response()->json(['status' => false, 'message' => 'Permission tidak ditemukan'], 404);
    }

    public function update(StorePermissionValidasi $request, $id)
    {
        if (!Gate::check('edit permission')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataPermission = $this->permissionServices->show($request, $id);
        if (is_null($dataPermission)) {
            return response()->json(['status' => false, 'message' => 'Permission tidak ditemukan'], 404);
        }

        $this->permissionServices->update($request, $id);
        $updateNewGiftCategory = $this->permissionServices->show($request, $id);
        return response()->json(['status' => true,'data' => $updateNewGiftCategory], 200);
    }

    public function destroy(Request $request,$id)
    {
        if (!Gate::check('hapus permission')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataPermission = $this->permissionServices->show($request, $id);
        if (is_null($dataPermission)) {
            return response()->json(['status' => false, 'message' => 'Permission tidak ditemukan'], 404);
        }
        $this->permissionServices->delete($request, $id);
        return response()->json(['status' => true,'data' => $dataPermission], 200);
    }
}
