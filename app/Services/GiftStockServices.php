<?php
namespace App\Services;

use App\Models\GiftStock;
use Illuminate\Support\Facades\Auth;

class GiftStockServices
{
    public function giftStock($request) {
        $dataGiftStock = new GiftStock();
        return $dataGiftStock;
    }

    public function query($request)
    {
        $dataGiftStock = GiftStock::query();
        return $dataGiftStock;
    }

    public function get($request)
    {
        $dataGiftStock = GiftStock::orderBy('created_at');
        if(isset($request->select)){
            $dataGiftStock->select($request->select);
        }
        $dataGiftStock = $dataGiftStock->get();
        return $dataGiftStock;
    }

    public function create($params)
    {
        $data = $params->only(['gift_id', 'stock']);
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $dataGiftStock = GiftStock::create($data);

        return $dataGiftStock;

    }

    public function show($request , $id)
    {
        $dataGiftStock = GiftStock::where('id',$id)->first();
        return $dataGiftStock;
    }

    public function update($request,$id)
    {
        $in_qty = !is_null($request->in_stock) ? $request->in_stock : 0;
        $out_qty = !is_null($request->out_stock) ? $request->out_stock : 0;
        $stock = GiftStock::select('stock')->where('gift_id', $id)->first();
        $total_stock = $stock['stock'] + $in_qty - $out_qty;
        $data['stock'] = $total_stock;
        $data['updated_by'] = Auth::user()->id;
        $dataGiftStock = GiftStock::where('gift_id',$id)->update($data);
        return $dataGiftStock;

    }

    public function delete($request,$id)
    {
        GiftStock::where('gift_id',$id)->update(['deleted_by'=>Auth::user()->id]);
        $dataGiftStock = GiftStock::where('gift_id',$id)->delete($id);

        return $dataGiftStock;
    }
}
