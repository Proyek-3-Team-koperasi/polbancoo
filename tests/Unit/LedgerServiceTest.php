<?php

namespace Tests\Unit;

use App\Models\LedgerAccount;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\SavingsTransaction;
use App\Models\User;
use App\Services\Accounting\LedgerService;
use Database\Seeders\LedgerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class LedgerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_journal_creates_posted_journal_and_links_reference(): void
    {
        $this->seed(LedgerSeeder::class);

        $admin = User::factory()->create();
        $memberUser = User::factory()->create();

        $member = new Member();
        $member->forceFill([
            'user_id' => $memberUser->id,
            'member_id_number' => 'MEM0001',
            'point' => 0,
            'status' => 'Active',
        ])->save();

        $account = SavingsAccount::query()->create([
            'member_user_id' => $memberUser->id,
            'principal_saving' => 0,
            'mandatory_saving' => 0,
            'voluntary_saving' => 0,
        ]);

        $transaction = SavingsTransaction::query()->create([
            'savings_account_id' => $account->id,
            'admin_user_id' => $admin->id,
            'amount' => 150000,
            'type' => 'Principal',
            'description' => 'Setoran awal',
            'transaction_date' => now()->toDateString(),
        ]);

        $cashAccount = LedgerAccount::query()->where('code', '1110')->firstOrFail();
        $equityAccount = LedgerAccount::query()->where('code', '3110')->firstOrFail();

        $service = app(LedgerService::class);
        $journal = $service->postJournal([
            'journal_date' => now()->toDateString(),
            'description' => 'Setoran simpanan pokok anggota',
            'created_by' => $admin->id,
            'lines' => [
                [
                    'account_id' => $cashAccount->id,
                    'debit' => 150000,
                    'credit' => 0,
                    'member_user_id' => $memberUser->id,
                ],
                [
                    'account_id' => $equityAccount->id,
                    'debit' => 0,
                    'credit' => 150000,
                ],
            ],
            'reference' => [
                'type' => 'savings_transactions',
                'id' => $transaction->id,
            ],
        ]);

        $this->assertSame(2, $journal->lines()->count());
        $this->assertNotNull($journal->posted_at);
        $this->assertEquals($journal->id, $transaction->fresh()->ledger_journal_id);
    }

    public function test_post_journal_requires_balanced_lines(): void
    {
        $this->seed(LedgerSeeder::class);

        $admin = User::factory()->create();
        $cashAccount = LedgerAccount::query()->where('code', '1110')->firstOrFail();
        $equityAccount = LedgerAccount::query()->where('code', '3110')->firstOrFail();

        $service = app(LedgerService::class);

        $this->expectException(InvalidArgumentException::class);

        $service->postJournal([
            'journal_date' => now()->toDateString(),
            'created_by' => $admin->id,
            'lines' => [
                [
                    'account_id' => $cashAccount->id,
                    'debit' => 100000,
                    'credit' => 0,
                ],
                [
                    'account_id' => $equityAccount->id,
                    'debit' => 0,
                    'credit' => 50000,
                ],
            ],
        ]);
    }
}
