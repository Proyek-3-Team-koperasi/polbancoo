<?php

namespace Database\Seeders;

use App\Models\LedgerAccount;
use App\Models\LedgerAccountGroup;
use Illuminate\Database\Seeder;

class LedgerSeeder extends Seeder
{
    /**
     * Seed basic ledger account groups and accounts.
     */
    public function run(): void
    {
        $groups = [
            ['code' => '1000', 'name' => 'Aset', 'parent' => null],
            ['code' => '1100', 'name' => 'Aset Lancar', 'parent' => '1000'],
            ['code' => '1200', 'name' => 'Piutang', 'parent' => '1000'],
            ['code' => '2000', 'name' => 'Kewajiban', 'parent' => null],
            ['code' => '3000', 'name' => 'Ekuitas', 'parent' => null],
            ['code' => '4000', 'name' => 'Pendapatan', 'parent' => null],
            ['code' => '5000', 'name' => 'Beban', 'parent' => null],
        ];

        $groupModels = [];
        foreach ($groups as $groupData) {
            $parentId = null;
            if (! empty($groupData['parent'])) {
                $parent = $groupModels[$groupData['parent']] ?? LedgerAccountGroup::query()
                    ->where('code', $groupData['parent'])
                    ->first();
                $parentId = $parent?->id;
            }

            $groupModels[$groupData['code']] = LedgerAccountGroup::query()->firstOrCreate(
                ['code' => $groupData['code']],
                [
                    'name' => $groupData['name'],
                    'parent_id' => $parentId,
                ]
            );
        }

        $accounts = [
            [
                'code' => '1110',
                'name' => 'Kas Operasional',
                'type' => 'Asset',
                'normal_balance' => 'Debit',
                'group' => '1100',
                'parent' => null,
            ],
            [
                'code' => '1120',
                'name' => 'Bank Syariah',
                'type' => 'Asset',
                'normal_balance' => 'Debit',
                'group' => '1100',
                'parent' => null,
            ],
            [
                'code' => '1210',
                'name' => 'Piutang Murabahah',
                'type' => 'Asset',
                'normal_balance' => 'Debit',
                'group' => '1200',
                'parent' => null,
            ],
            [
                'code' => '2110',
                'name' => 'Hutang Dagang',
                'type' => 'Liability',
                'normal_balance' => 'Credit',
                'group' => '2000',
                'parent' => null,
            ],
            [
                'code' => '3110',
                'name' => 'Modal Anggota',
                'type' => 'Equity',
                'normal_balance' => 'Credit',
                'group' => '3000',
                'parent' => null,
            ],
            [
                'code' => '4110',
                'name' => 'Pendapatan Penjualan',
                'type' => 'Revenue',
                'normal_balance' => 'Credit',
                'group' => '4000',
                'parent' => null,
            ],
            [
                'code' => '4120',
                'name' => 'Pendapatan Margin Murabahah',
                'type' => 'Revenue',
                'normal_balance' => 'Credit',
                'group' => '4000',
                'parent' => null,
            ],
            [
                'code' => '5110',
                'name' => 'Beban Pokok Penjualan',
                'type' => 'Expense',
                'normal_balance' => 'Debit',
                'group' => '5000',
                'parent' => null,
            ],
        ];

        foreach ($accounts as $accountData) {
            $groupId = $groupModels[$accountData['group']]?->id ?? null;
            $parentId = null;
            if (! empty($accountData['parent'])) {
                $parent = LedgerAccount::query()->where('code', $accountData['parent'])->first();
                $parentId = $parent?->id;
            }

            LedgerAccount::query()->updateOrCreate(
                ['code' => $accountData['code']],
                [
                    'name' => $accountData['name'],
                    'type' => $accountData['type'],
                    'normal_balance' => $accountData['normal_balance'],
                    'group_id' => $groupId,
                    'parent_id' => $parentId,
                    'is_active' => true,
                ]
            );
        }
    }
}

