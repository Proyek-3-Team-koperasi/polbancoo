<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // Data dummy untuk anggota
        $members = [
            [
                'id' => 1,
                'name' => 'Andi',
                'email' => 'andi@example.com',
                'member_id' => '1234567890',
                'faculty_or_unit' => 'Teknik Informatika',
            ],
            [
                'id' => 2,
                'name' => 'Arief',
                'email' => 'arief@example.com',
                'member_id' => '0987654321',
                'faculty_or_unit' => 'Teknik Sipil',
            ],
            [
                'id' => 3,
                'name' => 'Arnold',
                'email' => 'arnold@example.com',
                'member_id' => '1122334455',
                'faculty_or_unit' => 'Teknik Mesin',
            ],
        ];

        return Inertia::render('Admin/Members/Index', [
            'members' => $members,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Members/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Karena ini hanya dummy, kita hanya redirect kembali ke index
        return redirect()->route('admin.members.index')->with('message', 'Anggota berhasil ditambahkan (dummy).');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        // Data dummy untuk detail anggota
        $member = [
            'id' => (int)$id,
            'name' => 'Andi',
            'member_id_number' => '1234567890',
            'faculty_or_unit' => 'Teknik Informatika',
            'address' => 'Jl. Contoh No. 123, Kota, Provinsi',
            'phone_number' => '+62 812-3456-7890',
            'total_savings' => [
                'wajib' => 500000,
                'pokok' => 100000,
                'sukarela' => 250000,
                'total' => 850000,
            ],
            'savings_history' => [
                [
                    'date' => '2025-01-15',
                    'type' => 'Wajib',
                    'amount' => 100000,
                    'description' => 'Simpanan wajib bulan Januari',
                ],
                [
                    'date' => '2025-01-10',
                    'type' => 'Pokok',
                    'amount' => 100000,
                    'description' => 'Simpanan pokok awal',
                ],
                [
                    'date' => '2025-01-05',
                    'type' => 'Sukarela',
                    'amount' => 250000,
                    'description' => 'Simpanan sukarela',
                ],
                [
                    'date' => '2024-12-20',
                    'type' => 'Wajib',
                    'amount' => 100000,
                    'description' => 'Simpanan wajib bulan Desember',
                ],
                [
                    'date' => '2024-11-15',
                    'type' => 'Wajib',
                    'amount' => 100000,
                    'description' => 'Simpanan wajib bulan November',
                ],
            ],
        ];

        $member = collect([
            [
                'id' => 1,
                'name' => 'Andi',
                'member_id_number' => '1234567890',
                'faculty_or_unit' => 'Teknik Informatika',
                'address' => 'Jl. Contoh No. 123, Kota, Provinsi',
                'phone_number' => '+62 812-3456-7890',
                'total_savings' => [
                    'wajib' => 500000,
                    'pokok' => 100000,
                    'sukarela' => 250000,
                    'total' => 850000,
                ],
                'savings_history' => [
                    [
                        'date' => '2025-01-15',
                        'type' => 'Wajib',
                        'amount' => 100000,
                        'description' => 'Simpanan wajib bulan Januari',
                    ],
                    [
                        'date' => '2025-01-10',
                        'type' => 'Pokok',
                        'amount' => 100000,
                        'description' => 'Simpanan pokok awal',
                    ],
                    [
                        'date' => '2025-01-05',
                        'type' => 'Sukarela',
                        'amount' => 250000,
                        'description' => 'Simpanan sukarela',
                    ],
                    [
                        'date' => '2024-12-20',
                        'type' => 'Wajib',
                        'amount' => 100000,
                        'description' => 'Simpanan wajib bulan Desember',
                    ],
                    [
                        'date' => '2024-11-15',
                        'type' => 'Wajib',
                        'amount' => 100000,
                        'description' => 'Simpanan wajib bulan November',
                    ],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Arief',
                'member_id_number' => '0987654321',
                'faculty_or_unit' => 'Manajemen',
                'address' => 'Jl. Contoh No. 456, Kota, Provinsi',
                'phone_number' => '+62 813-9876-5432',
                'total_savings' => [
                    'wajib' => 400000,
                    'pokok' => 100000,
                    'sukarela' => 150000,
                    'total' => 650000,
                ],
                'savings_history' => [
                    [
                        'date' => '2025-01-12',
                        'type' => 'Wajib',
                        'amount' => 100000,
                        'description' => 'Simpanan wajib bulan Januari',
                    ],
                    [
                        'date' => '2025-01-08',
                        'type' => 'Pokok',
                        'amount' => 100000,
                        'description' => 'Simpanan pokok awal',
                    ],
                    [
                        'date' => '2025-01-03',
                        'type' => 'Sukarela',
                        'amount' => 150000,
                        'description' => 'Simpanan sukarela',
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Arnold',
                'member_id_number' => '1122334455',
                'faculty_or_unit' => 'Teknik Mesin',
                'address' => 'Jl. Contoh No. 789, Kota, Provinsi',
                'phone_number' => '+62 814-1122-3344',
                'total_savings' => [
                    'wajib' => 300000,
                    'pokok' => 100000,
                    'sukarela' => 100000,
                    'total' => 500000,
                ],
                'savings_history' => [
                    [
                        'date' => '2025-01-11',
                        'type' => 'Wajib',
                        'amount' => 100000,
                        'description' => 'Simpanan wajib bulan Januari',
                    ],
                    [
                        'date' => '2025-01-09',
                        'type' => 'Pokok',
                        'amount' => 100000,
                        'description' => 'Simpanan pokok awal',
                    ],
                    [
                        'date' => '2025-01-04',
                        'type' => 'Sukarela',
                        'amount' => 100000,
                        'description' => 'Simpanan sukarela',
                    ],
                ],
            ],
            
        ])->firstWhere('id', (int)$id);

        if (!$member) {
            abort(404, 'Anggota tidak ditemukan.');
        }

        return Inertia::render('Admin/Members/Show', [
            'member' => $member,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $members = collect([
            [
                'id' => 1,
                'name' => 'Andi',
                'email' => 'andi@example.com',
                'member_id' => '1234567890',
                'faculty_or_unit' => 'Teknik Informatika',
            ],
            [
                'id' => 2,
                'name' => 'Arief',
                'email' => 'arief@example.com',
                'member_id' => '0987654321',
                'faculty_or_unit' => 'Teknik Sipil',
            ],
            [
                'id' => 3,
                'name' => 'Arnold',
                'email' => 'arnold@example.com',
                'member_id' => '1122334455',
                'faculty_or_unit' => 'Teknik Mesin',
            ],
        ]);

        $member = $members->firstWhere('id', (int)$id);

        if (!$member) {
            abort(404, 'Anggota tidak ditemukan.');
        }

        return Inertia::render('Admin/Members/Edit', [
            'member' => $member, // Sekarang 'email' sudah termasuk
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Karena ini hanya dummy, kita hanya redirect kembali ke index
        return redirect()->route('admin.members.index')->with('message', 'Anggota berhasil diperbarui (dummy).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Karena ini hanya dummy, kita hanya redirect kembali ke index
        return redirect()->route('admin.members.index')->with('message', 'Anggota berhasil dihapus (dummy).');
    }
}