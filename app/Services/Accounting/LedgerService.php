<?php

namespace App\Services\Accounting;

use App\Models\LedgerJournal;
use App\Models\LedgerLine;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LedgerService
{
    /**
     * @var array<int, string>
     */
    protected array $supportedReferenceTables = [
        'savings_transactions',
        'orders',
        'installment_bills',
    ];

    public function postJournal(array $payload): LedgerJournal
    {
        $lines = Arr::get($payload, 'lines', []);
        if (count($lines) < 2) {
            throw new InvalidArgumentException('Ledger journal requires at least two lines.');
        }

        $createdBy = Arr::get($payload, 'created_by');
        if (empty($createdBy)) {
            throw new InvalidArgumentException('Ledger journal requires a creator.');
        }

        $journalDate = Arr::get($payload, 'journal_date');
        if (empty($journalDate)) {
            throw new InvalidArgumentException('Ledger journal requires a journal date.');
        }

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $index => $line) {
            $accountId = Arr::get($line, 'account_id');
            if (empty($accountId)) {
                throw new InvalidArgumentException("Ledger line #{$index} is missing account reference.");
            }

            $debit = $this->normalizeAmount(Arr::get($line, 'debit'), $index, 'debit');
            $credit = $this->normalizeAmount(Arr::get($line, 'credit'), $index, 'credit');

            if ($debit < 0 || $credit < 0) {
                throw new InvalidArgumentException("Ledger line #{$index} contains negative amount.");
            }

            if ($debit > 0 && $credit > 0) {
                throw new InvalidArgumentException("Ledger line #{$index} cannot have both debit and credit values.");
            }

            if ($debit === 0 && $credit === 0) {
                throw new InvalidArgumentException("Ledger line #{$index} requires a debit or credit value.");
            }

            $totalDebit += $debit;
            $totalCredit += $credit;
        }

        if ($totalDebit !== $totalCredit) {
            throw new InvalidArgumentException('Ledger journal is not balanced.');
        }

        $referenceType = Arr::get($payload, 'reference.type');
        $referenceId = Arr::get($payload, 'reference.id');
        if ($referenceType && ! in_array($referenceType, $this->supportedReferenceTables, true)) {
            throw new InvalidArgumentException("Reference type {$referenceType} is not supported.");
        }

        return DB::transaction(function () use ($payload, $journalDate, $createdBy, $lines, $referenceType, $referenceId) {
            $journalNumber = Arr::get($payload, 'journal_number')
                ?: $this->generateJournalNumber(Carbon::parse($journalDate));

            $journal = LedgerJournal::query()->create([
                'journal_number' => $journalNumber,
                'journal_date' => $journalDate,
                'description' => Arr::get($payload, 'description'),
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'created_by' => $createdBy,
                'posted_at' => Carbon::now(),
            ]);

            foreach ($lines as $line) {
                $debit = $this->normalizeAmount(Arr::get($line, 'debit'), null, 'debit');
                $credit = $this->normalizeAmount(Arr::get($line, 'credit'), null, 'credit');

                LedgerLine::query()->create([
                    'journal_id' => $journal->id,
                    'account_id' => Arr::get($line, 'account_id'),
                    'member_user_id' => Arr::get($line, 'member_user_id'),
                    'debit' => $debit,
                    'credit' => $credit,
                    'memo' => Arr::get($line, 'memo'),
                ]);
            }

            if ($referenceType && $referenceId) {
                DB::table($referenceType)
                    ->where('id', $referenceId)
                    ->update(['ledger_journal_id' => $journal->id]);
            }

            return $journal->load('lines');
        });
    }

    protected function generateJournalNumber(Carbon $date): string
    {
        $prefix = $date->format('Ymd');
        $sequence = LedgerJournal::query()
            ->whereDate('journal_date', $date->toDateString())
            ->count() + 1;

        return sprintf('JRN-%s-%04d-%s', $prefix, $sequence, Str::upper(Str::random(2)));
    }

    /**
     * Normalise nominal rupiah (stored as bigint).
     *
     * @param mixed $value
     */
    protected function normalizeAmount(mixed $value, ?int $lineIndex, string $field): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_INT);
        if ($filtered === false) {
            $lineInfo = $lineIndex !== null ? "#{$lineIndex} " : '';
            throw new InvalidArgumentException("Ledger line {$lineInfo}field {$field} must be an integer amount in rupiah.");
        }

        return (int) $filtered;
    }
}
