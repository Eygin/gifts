<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftRedeem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gift_redeem';
    protected $fillable = ['gift_id','qty','price_item','total','created_by','updated_by','deleted_by','created_at', 'updated_at', 'deleted_at'];

    public function get_created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function get_updated_by()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->withTrashed();
    }

    public function get_deleted_by()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by')->withTrashed();
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id');
    }
}
