<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { formatAsRupiah } from '@/lib/utils';

const hasilBagi = [
    {
        name: 'Muhammad Bagus',
        point: 2840,       // Poin partisipasi (simpanan + transaksi)
        persentase: 0.2,   // 20% dari total pool
        nilai: 840_000     // 0.2 * 4,200,000
    },
    {
        name: 'Siti Aminah',
        point: 2130,
        persentase: 0.15,  // 15% dari total pool
        nilai: 630_000     // 0.15 * 4,200,000
    },
    {
        name: 'Budi Setiawan',
        point: 1420,
        persentase: 0.1,   // 10% dari total pool
        nilai: 420_000     // 0.10 * 4,200,000
    },
    {
        name: 'Dewi Lestari',
        point: 1000,
        persentase: 0.07,  // 7% dari total pool
        nilai: 294_000     // 0.07 * 4,200,000
    },
    {
        name: 'Agus Santoso',
        point: 710,
        persentase: 0.05,  // 5% dari total pool
        nilai: 210_000     // 0.05 * 4,200,000
    },
    {
        name: 'Eko Prasetyo',
        point: 500,
        persentase: 0.035, // 3.5% dari total pool
        nilai: 147_000     // 0.035 * 4,200,000
    },
    {
        name: 'Hasanudin',
        point: 450,
        persentase: 0.032, // 3.2% dari total pool
        nilai: 134_400     // ~0.032 * 4,200,000
    },
    {
        name: 'Rina Melati',
        point: 380,
        persentase: 0.027, // 2.7% dari total pool
        nilai: 113_400     // 0.027 * 4,200,000
    },
    {
        name: 'Joko Purnomo',
        point: 300,
        persentase: 0.021, // 2.1% dari total pool
        nilai: 88_200      // 0.021 * 4,200,000
    },
    {
        name: 'Mega Suryani',
        point: 250,
        persentase: 0.018, // 1.8% dari total pool
        nilai: 75_600      // 0.018 * 4,200,000
    }
];

</script>

<template>
    <h1 class="tw-text-xl tw-font-bold tw-text-black tw-mb-6 tw-tracking-wide tw-text-center tw-my-4 ">Hasil Bagi
        Keuntungan</h1>
    <h1 class="tw-text-lg tw-font-bold tw-text-gray-600 tw-mb-6 tw-tracking-wide tw-text-center tw-my-4 ">Periode <br/> 08-11-2024 s/d 08-11-2025</h1>
    
    <div class="tw-max-w-6xl tw-m-auto">

        <Table>
            <TableCaption>List Tabel Pembagian Keuntungan</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]">
                        Nama
                    </TableHead>
                    <TableHead>Point</TableHead>
                    <TableHead>Persentase</TableHead>
                    <TableHead class="text-right">
                        Jumlah
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-for="item in hasilBagi">
                    <TableRow>
                        <TableCell class="font-medium">
                            {{ item.name }}
                        </TableCell>
                        <TableCell>{{ item.point }}</TableCell>
                        <TableCell>{{ item.persentase }}</TableCell>
                        <TableCell class="text-right">
                            {{ formatAsRupiah(item.nilai) }}
                        </TableCell>
                    </TableRow>
                </template>
                <TableRow>
                    <TableCell colspan="3" class="tw-font-semibold">Total</TableCell>
                    <TableCell class="text-right tw-font-semibold">
                        {{ formatAsRupiah(4200000) }}
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>