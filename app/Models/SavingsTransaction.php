<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'savings_account_id',
        'admin_user_id',
        'amount',
        'type',
        'description',
        'transaction_date',
        'bukti_transfer'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(SavingsAccount::class, 'savings_account_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}

