<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreGiftCategoryValidasi;
use App\Services\GiftCategoryServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GiftCategoryResource;
use Illuminate\Support\Facades\Gate;

class GiftCategoryController extends Controller
{
    public function __construct(GiftCategoryServices $giftCategoryServices)
    {
        $this->giftCategoryServices = $giftCategoryServices;
    }

    public function index(Request $request)
    {
        if (!Gate::check('gift kategori')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $dataGiftCategory = $this->giftCategoryServices->query($request)->paginate($perPage, ['*'], 'page', $page);
        return GiftCategoryResource::collection($dataGiftCategory);
    }

    public function store(StoreGiftCategoryValidasi $request)
    {
        if (!Gate::check('tambah gift kategori')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGiftCategory = $this->giftCategoryServices->create($request);
        if (!is_null($dataGiftCategory)) {
            return response()->json(['status' => true, 'message' => 'Berhasil membuat kategori', 'data' => $dataGiftCategory], 200);
        }

        return response()->json(['status' => false, 'message' => 'Gagal membuat kategori'], 500);
    }

    public function show(Request $request, $id)
    {
        if (!Gate::check('lihat gift kategori')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGiftCategory = $this->giftCategoryServices->show($request, $id);
        if (!is_null($dataGiftCategory)) {
            $dataGiftCategory->created_by = !empty($dataGiftCategory->get_created_by) ? $dataGiftCategory->get_created_by->name : '-';
            $dataGiftCategory->updated_by = !empty($dataGiftCategory->get_updated_by) ? $dataGiftCategory->get_updated_by->name : '-';
            return response()->json(['status' => true,'data' => $dataGiftCategory], 200);
        }
        return response()->json(['status' => false, 'message' => 'Kategori tidak ditemukan'], 404);
    }

    public function update(StoreGiftCategoryValidasi $request, $id)
    {
        if (!Gate::check('edit gift kategori')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGiftCategory = $this->giftCategoryServices->show($request, $id);
        if (is_null($dataGiftCategory)) {
            return response()->json(['status' => false, 'message' => 'Kategori tidak ditemukan'], 404);
        }

        $this->giftCategoryServices->update($request, $id);
        $updateNewGiftCategory = $this->giftCategoryServices->show($request, $id);
        return response()->json(['status' => true,'data' => $updateNewGiftCategory], 200);
    }

    public function destroy(Request $request,$id)
    {
        if (!Gate::check('hapus gift kategori')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGiftCategory = $this->giftCategoryServices->show($request, $id);
        if (is_null($dataGiftCategory)) {
            return response()->json(['status' => false, 'message' => 'Kategori tidak ditemukan'], 404);
        }
        $this->giftCategoryServices->delete($request, $id);
        return response()->json(['status' => true,'data' => $dataGiftCategory], 200);
    }
}
