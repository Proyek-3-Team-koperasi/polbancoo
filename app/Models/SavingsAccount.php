<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_user_id',
        'principal_saving',
        'mandatory_saving',
        'voluntary_saving',
    ];

    protected $casts = [
        'principal_saving' => 'integer',
        'mandatory_saving' => 'integer',
        'voluntary_saving' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_user_id');
    }

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class);
    }
}
