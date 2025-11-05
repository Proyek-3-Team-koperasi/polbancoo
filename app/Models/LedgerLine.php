<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'account_id',
        'member_user_id',
        'debit',
        'credit',
        'memo',
    ];

    protected $casts = [
        'debit' => 'integer',
        'credit' => 'integer',
    ];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(LedgerJournal::class, 'journal_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(LedgerAccount::class, 'account_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_user_id', 'user_id');
    }
}
