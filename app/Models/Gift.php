<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Gift extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gift';
    protected $fillable = ['name','category_id','picture','price','created_by','updated_by','deleted_by','created_at', 'updated_at', 'deleted_at'];

    public function get_created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function get_updated_by()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function get_deleted_by()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }

    public function detail()
    {
        return $this->hasOne(GiftDetail::class, 'gift_id', 'id');
    }

    public function stock()
    {
        return $this->hasOne(GiftStock::class, 'gift_id', 'id');
    }

    public function review()
    {
        return $this->hasMany(GiftReview::class, 'gift_id', 'id');
    }
}
