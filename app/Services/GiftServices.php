<?php
namespace App\Services;

use App\Models\Gift;
use Illuminate\Support\Facades\Auth;

class GiftServices
{
    public function gift($request) {
        $dataGift = new Gift();
        return $dataGift;
    }

    public function query($request)
    {
        $dataGift = Gift::query();
        return $dataGift;
    }

    public function get($request)
    {
        $dataGift = Gift::orderBy('created_at');
        if(isset($request->select)){
            $dataGift->select($request->select);
        }
        $dataGift = $dataGift->get();
        return $dataGift;
    }

    public function create($params)
    {
        $data = $params->only(['name', 'category_id','price']);
        $filename = null;
        if($params->hasFile('picture')){
            $file = $params->file('picture');
            $filename = rand().time(). '.' . $file->getClientOriginalExtension();
            $file->move(public_path().'/upload/gift/', $filename);
        }
        $data['picture'] = $filename;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $dataGift = Gift::create($data);

        return $dataGift;

    }

    public function show($request , $id)
    {
        $dataGift = Gift::where('id',$id)->first();
        return $dataGift;
    }

    public function update($request,$id)
    {
        $data = $request->only(['name', 'category_id', 'price']);
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $filename = rand().time(). '.' . $file->getClientOriginalExtension();
            $file->move(public_path().'/upload/gift/', $filename);

            $old_file = Gift::select('picture')->where('id', $id)->first();
            $old_path = public_path().'/upload/gift/'.$old_file->picture;
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }
        $data['picture'] = $filename;
        $data['updated_by'] = Auth::user()->id;
        $dataGift = Gift::where('id',$id)->update($data);
        return $dataGift;

    }

    public function delete($request,$id)
    {
        Gift::whereId($id)->update(['deleted_by'=>Auth::user()->id]);

        $old_file = Gift::select('picture')->where('id', $id)->first();
        $old_path = public_path().'/upload/gift/'.$old_file->picture;
        if (file_exists($old_path)) {
            unlink($old_path);
        }

        $dataGift = Gift::where('id',$id)->delete($id);

        return $dataGift;
    }
}
