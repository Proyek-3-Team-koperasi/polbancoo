<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'financing_application_id',
        'bill_number',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'ledger_journal_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'integer',
    ];

    public function financingApplication()
    {
        return $this->belongsTo(FinancingApplication::class);
    }

    public function journal()
    {
        return $this->belongsTo(LedgerJournal::class, 'ledger_journal_id');
    }
}
