<?php
namespace App\Services;

use App\Models\GiftReview;
use Illuminate\Support\Facades\Auth;

class GiftReviewServices
{
    public function giftReview($request) {
        $dataGiftReview = new GiftReview();
        return $dataGiftReview;
    }

    public function query($request)
    {
        $dataGiftReview = GiftReview::query();
        return $dataGiftReview;
    }

    public function get($request)
    {
        $dataGiftReview = GiftReview::orderBy('created_at');
        if(isset($request->select)){
            $dataGiftReview->select($request->select);
        }
        $dataGiftReview = $dataGiftReview->get();
        return $dataGiftReview;
    }

    public function create($params, $id)
    {
        $data = $params->only(['rating']);
        $data['gift_id'] = $id;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $dataGiftReview = GiftReview::create($data);

        return $dataGiftReview;

    }

    public function show($request , $id)
    {
        $dataGiftReview = GiftReview::where('id',$id)->first();
        return $dataGiftReview;
    }

    public function update($request,$id)
    {
    }

    public function delete($request,$id)
    {
        GiftReview::where('gift_id',$id)->update(['deleted_by'=>Auth::user()->id]);
        $dataGiftReview = GiftReview::where('gift_id',$id)->delete($id);

        return $dataGiftReview;
    }
}
