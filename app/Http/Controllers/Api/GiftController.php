<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGiftRedeemValidasi;
use App\Http\Requests\StoreGiftReviewValidasi;
use App\Http\Requests\StoreGiftValidasi;
use App\Http\Resources\GiftResource;
use App\Services\GiftDetailServices;
use App\Services\GiftRedeemServices;
use App\Services\GiftReviewServices;
use App\Services\GiftServices;
use App\Services\GiftStockServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class GiftController extends Controller
{
    public function __construct(GiftServices $giftServices, GiftDetailServices $giftDetailServices, GiftStockServices $giftStockServices, GiftRedeemServices $giftRedeemServices, GiftReviewServices $giftReviewServices)
    {
        $this->giftServices = $giftServices;
        $this->giftDetailServices = $giftDetailServices;
        $this->giftStockServices = $giftStockServices;
        $this->giftRedeemServices = $giftRedeemServices;
        $this->giftReviewServices = $giftReviewServices;
    }

    private function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function index(Request $request)
    {
        if (!Gate::check('gift')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $dataGift = $this->giftServices->query($request)->with(['detail:id,gift_id,description','stock:id,gift_id,stock', 'review:id,gift_id,rating'])->orderBy('id')->get();
        $dataGift->map(function ($item) {
            $rating5 = $item->review->where('rating', 5)->count() * 5;
            $rating4 = $item->review->where('rating', 4)->count() * 4;
            $rating3 = $item->review->where('rating', 3)->count() * 3;
            $rating2 = $item->review->where('rating', 2)->count() * 2;
            $rating1 = $item->review->where('rating', 1)->count() * 1;

            $totalReviewer = $item->review->count();
            $totalRating = $totalReviewer == 0 ? 0 : ($rating5 + $rating4 + $rating3 + $rating2 + $rating1) / $totalReviewer;
            $item['reviewer'] = $totalReviewer;
            $item['rating'] = round($totalRating * 2) / 2;
            return $item;
        });

        $dataGift = collect($dataGift)->sortByDesc('rating');
        $dataGift = $this->paginate($dataGift, $perPage, $page, ['path' => url()->current()]);
        return GiftResource::collection($dataGift);
    }

    public function store(StoreGiftValidasi $request)
    {
        if (!Gate::check('tambah gift')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGift = $this->giftServices->create($request);
        if (!is_null($dataGift)) {
            $request['gift_id'] = $dataGift->id;
            $this->giftDetailServices->create($request);
            $this->giftStockServices->create($request);
            return response()->json(['status' => true, 'message' => 'Berhasil membuat Gift', 'data' => $dataGift->with('detail:id,gift_id,description','stock:id,gift_id,stock')->where('id',$dataGift->id)->first()], 200);
        }
        return response()->json(['status' => false, 'message' => 'Gagal membuat Gift'], 500);
    }

    public function show(Request $request, $id)
    {
        if (!Gate::check('lihat gift')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGift = $this->giftServices->show($request, $id);
        if (!is_null($dataGift)) {
            foreach($dataGift->review as $item) {
                $rating5 = $item->where('rating', 5)->count() * 5;
                $rating4 = $item->where('rating', 4)->count() * 4;
                $rating3 = $item->where('rating', 3)->count() * 3;
                $rating2 = $item->where('rating', 2)->count() * 2;
                $rating1 = $item->where('rating', 1)->count() * 1;

                $totalReviewer = $item->count();
                $totalRating = $totalReviewer == 0 ? 0 : ($rating5 + $rating4 + $rating3 + $rating2 + $rating1) / $totalReviewer;
                $dataGift['reviewer'] = $totalReviewer;
                $dataGift['rating'] = round($totalRating * 2) / 2;
            };

            $dataGift['detail'] = $dataGift->detail;
            $dataGift['stock'] = $dataGift->stock;
            return response()->json(['status' => true,'data' => $dataGift], 200);
        }
        return response()->json(['status' => false, 'message' => 'Gift tidak ditemukan'], 404);
    }

    public function update(StoreGiftValidasi $request, $id)
    {
        if (!Gate::check('edit gift')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGift = $this->giftServices->show($request, $id);
        if (is_null($dataGift)) {
            return response()->json(['status' => false, 'message' => 'Gift tidak ditemukan'], 404);
        }

        $this->giftServices->update($request, $id);
        $this->giftDetailServices->update($request, $id);
        $this->giftStockServices->update($request, $id);
        $updateNewGiftCategory = $this->giftServices->query($request)->with('detail:id,gift_id,description','stock:id,gift_id,stock')->where('id',$dataGift->id)->where('id', $id)->first();
        return response()->json(['status' => true,'data' => $updateNewGiftCategory], 200);
    }

    public function destroy(Request $request,$id)
    {
        if (!Gate::check('hapus gift')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGift = $this->giftServices->show($request, $id);

        if (is_null($dataGift)) {
            return response()->json(['status' => false, 'message' => 'Gift tidak ditemukan'], 404);
        }

        $dataAfterDelete = $dataGift->with('detail:id,gift_id,description','stock:id,gift_id,stock')->where('id',$dataGift->id)->first();
        $this->giftDetailServices->delete($request, $id);
        $this->giftServices->delete($request, $id);
        $this->giftStockServices->delete($request, $id);
        return response()->json(['status' => true,'data' => $dataAfterDelete], 200);
    }

    public function redeem(StoreGiftRedeemValidasi $request, $id){
        if (!Gate::check('redeem')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGiftRedeem = $this->giftRedeemServices->create($request, $id);
        if ($dataGiftRedeem) {
            $request['out_stock'] = $request['qty'];
            $this->giftStockServices->update($request, $id);
            return response()->json(['status' => true, 'message' => 'Berhasil Redeem Gift', 'data' => $dataGiftRedeem->with('gift:id,name,picture,price')->where('id', $dataGiftRedeem->id)->first()], 200);
        }
        return response()->json(['status' => false, 'message' => !$dataGiftRedeem ? 'Stock tidak mencukupi untuk redeem' : 'Gagal Membuat Redeem'], 500);
    }

    public function rating(StoreGiftReviewValidasi $request, $id) {
        if (!Gate::check('rating')) {
            return response()->json(['status' => false, 'message' => 'Tidak punya akses untuk aksi ini (Ditolak)'], 403);
        }

        $dataGiftReview = $this->giftReviewServices->create($request, $id);
        if (!is_null($dataGiftReview)) {
            return response()->json(['status' => true, 'message' => 'Berhasil memberi rating', 'data' => $dataGiftReview->with('gift:id,name,picture,price')->where('id', $dataGiftReview->id)->first()], 200);
        }
        return response()->json(['status' => false, 'message' => 'Gagal membuat review untuk gift'], 500);
    }
}
