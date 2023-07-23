<?php
namespace App\Services;

use App\Models\GiftCategory;
use Illuminate\Support\Facades\Auth;

class GiftCategoryServices
{
    public function giftCategory($request) {
        $dataGiftCategory = new GiftCategory();
        return $dataGiftCategory;
    }

    public function query($request)
    {
        $dataGiftCategory = GiftCategory::query();
        return $dataGiftCategory;
    }

    public function get($request)
    {
        $dataGiftCategory = GiftCategory::orderBy('created_at');
        if(isset($request->select)){
            $dataGiftCategory->select($request->select);
        }
        $dataGiftCategory = $dataGiftCategory->get();
        return $dataGiftCategory;
    }

    public function create($params)
    {
        $data = $params->only(['name']);
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $dataGiftCategory = GiftCategory::create($data);

        return $dataGiftCategory;

    }

    public function show($request , $id)
    {
        $dataGiftCategory = GiftCategory::where('id',$id)->first();
        return $dataGiftCategory;
    }

    public function update($request,$id)
    {
        $data = $request->only(['name']);

        $data['updated_by'] = Auth::user()->id;
        $dataGiftCategory = GiftCategory::where('id',$id)->update($data);
        return $dataGiftCategory;

    }

    public function delete($request,$id)
    {
        GiftCategory::whereId($id)->update(['deleted_by'=>Auth::user()->id]);
        $dataGiftCategory = GiftCategory::where('id',$id)->delete($id);

        return $dataGiftCategory;
    }
}
