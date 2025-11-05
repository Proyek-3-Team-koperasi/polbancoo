<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'member_id_number',
        'point',
        'status',
    ];

    protected $casts = [
        'point' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savingsAccount()
    {
        return $this->hasOne(SavingsAccount::class, 'member_user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'member_user_id');
    }

    public function financingApplications()
    {
        return $this->hasMany(FinancingApplication::class, 'member_user_id');
    }
}
