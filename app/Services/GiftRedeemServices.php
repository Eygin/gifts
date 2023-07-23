<?php
namespace App\Services;

use App\Models\Gift;
use App\Models\GiftRedeem;
use App\Models\GiftStock;
use Illuminate\Support\Facades\Auth;

class GiftRedeemServices
{
    public function giftRedeem($request) {
        $dataGiftRedeem = new GiftRedeem();
        return $dataGiftRedeem;
    }

    public function query($request)
    {
        $dataGiftRedeem = GiftRedeem::query();
        return $dataGiftRedeem;
    }

    public function get($request)
    {
        $dataGiftRedeem = GiftRedeem::orderBy('created_at');
        if(isset($request->select)){
            $dataGiftRedeem->select($request->select);
        }
        $dataGiftRedeem = $dataGiftRedeem->get();
        return $dataGiftRedeem;
    }

    public function create($params, $id)
    {
        $data = $params->only(['qty']);
        $dataGift = Gift::where('id', $id)->first();
        $dataStock = GiftStock::where('gift_id', $id)->first();
        if ($data['qty'] > $dataStock->stock) {
            return false;
        }
        $data['price_item'] = $dataGift->price;
        $data['total'] = $dataGift->price * $data['qty'];
        $data['gift_id'] = $id;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $dataGiftRedeem = GiftRedeem::create($data);

        return $dataGiftRedeem;

    }

    public function show($request , $id)
    {
        $dataGiftRedeem = GiftRedeem::where('id',$id)->first();
        return $dataGiftRedeem;
    }

    public function update($request,$id)
    {
    }

    public function delete($request,$id)
    {
        GiftRedeem::where('gift_id',$id)->update(['deleted_by'=>Auth::user()->id]);
        $dataGiftRedeem = GiftRedeem::where('gift_id',$id)->delete($id);

        return $dataGiftRedeem;
    }
}
