<?php
namespace App\Services;

use App\Models\GiftDetail;
use Illuminate\Support\Facades\Auth;

class GiftDetailServices
{
    public function giftDetail($request) {
        $ddataGiftDetail = new GiftDetail();
        return $ddataGiftDetail;
    }

    public function query($request)
    {
        $ddataGiftDetail = GiftDetail::query();
        return $ddataGiftDetail;
    }

    public function get($request)
    {
        $ddataGiftDetail = GiftDetail::orderBy('created_at');
        if(isset($request->select)){
            $ddataGiftDetail->select($request->select);
        }
        $ddataGiftDetail = $ddataGiftDetail->get();
        return $ddataGiftDetail;
    }

    public function create($params)
    {
        $data = $params->only(['gift_id', 'description']);
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $ddataGiftDetail = GiftDetail::create($data);

        return $ddataGiftDetail;

    }

    public function show($request , $id)
    {
        $ddataGiftDetail = GiftDetail::where('id',$id)->first();
        return $ddataGiftDetail;
    }

    public function update($request,$id)
    {
        $data = $request->only(['gift_id', 'description']);
        $data['updated_by'] = Auth::user()->id;
        $ddataGiftDetail = GiftDetail::where('id',$id)->update($data);
        return $ddataGiftDetail;

    }

    public function delete($request,$id)
    {
        GiftDetail::where('gift_id',$id)->update(['deleted_by'=>Auth::user()->id]);
        $ddataGiftDetail = GiftDetail::where('gift_id',$id)->delete($id);

        return $ddataGiftDetail;
    }
}
