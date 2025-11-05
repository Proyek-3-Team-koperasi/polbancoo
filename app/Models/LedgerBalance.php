<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'period',
        'opening_balance',
        'debit_total',
        'credit_total',
        'closing_balance',
    ];

    protected $casts = [
        'opening_balance' => 'integer',
        'debit_total' => 'integer',
        'credit_total' => 'integer',
        'closing_balance' => 'integer',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(LedgerAccount::class, 'account_id');
    }
}
