<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NumberSummary from "@/Components/Dashboard/NumberSummary.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { LineChart } from "@/components/ui/chart-line";
import { format, formatDistanceToNow } from 'date-fns';
import { id } from 'date-fns/locale';

import { DollarSign, User, CircleDollarSign, BanknoteArrowUp } from "lucide-vue-next";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { dateSubtractDay } from "@/lib/utils";
import { BarChart } from "@/components/ui/chart-bar"

const data = [
    {
        "date": "2023-01-01",
        "Simpanan": 3000000
    },
    {
        "date": "2023-02-01",
        "Simpanan": 4000000
    },
    {
        "date": "2023-03-01",
        "Simpanan": 5000000
    },
    {
        "date": "2023-04-01",
        "Simpanan": 7000000
    },
    {
        "date": "2023-05-01",
        "Simpanan": 12000000
    },
    {
        "date": "2023-06-01",
        "Simpanan": 12891185
    },
    {
        "date": "2023-07-01",
        "Simpanan": 13681335
    },
    {
        "date": "2023-08-01",
        "Simpanan": 14389018
    },
    {
        "date": "2023-09-01",
        "Simpanan": 15032801
    },
    {
        "date": "2023-10-01",
        "Simpanan": 15631251
    },
    {
        "date": "2023-11-01",
        "Simpanan": 16202935
    },
    {
        "date": "2023-12-01",
        "Simpanan": 16768420
    },
    {
        "date": "2024-01-01",
        "Simpanan": 17346274
    },
    {
        "date": "2024-02-01",
        "Simpanan": 17955062
    },
    {
        "date": "2024-03-01",
        "Simpanan": 18613352
    },
    {
        "date": "2024-04-01",
        "Simpanan": 19339710
    },
    {
        "date": "2024-05-01",
        "Simpanan": 20152603
    },
    {
        "date": "2024-06-01",
        "Simpanan": 21070500
    },
    {
        "date": "2024-07-01",
        "Simpanan": 22111867
    },
    {
        "date": "2024-08-01",
        "Simpanan": 23295171
    },
    {
        "date": "2024-09-01",
        "Simpanan": 24618879
    },
    {
        "date": "2024-10-01",
        "Simpanan": 26081559
    },
    {
        "date": "2024-11-01",
        "Simpanan": 27681678
    },
    {
        "date": "2024-12-01",
        "Simpanan": 29417703
    },
    {
        "date": "2025-01-01",
        "Simpanan": 31288002
    },
    {
        "date": "2025-02-01",
        "Simpanan": 33291040
    },
    {
        "date": "2025-03-01",
        "Simpanan": 35425306
    },
    {
        "date": "2025-04-01",
        "Simpanan": 37699264
    },
    {
        "date": "2025-05-01",
        "Simpanan": 40111383
    },
    {
        "date": "2025-06-01",
        "Simpanan": 37720000
    }
];

const perlu_verifikasi = [
    {
        nama: "Budi Setiawan",
        tipe: "Transaksi",
        tanggal: dateSubtractDay(new Date(), 1),
    },
    {
        nama: "Siti Aminah",
        tipe: "Simpanan",
        tanggal: dateSubtractDay(new Date(), 2),
    },
    {
        nama: "Agus Santoso",
        tipe: "Cicilan",
        tanggal: dateSubtractDay(new Date(), 3),
    },
    {
        nama: "Dewi Lestaro",
        tipe: "Cicilan",
        tanggal: dateSubtractDay(new Date(), 6),
    },
    {
        nama: "Eko Prasetyo",
        tipe: "Simpanan",
        tanggal: dateSubtractDay(new Date(), 12),
    }
]


const barData = [
    { name: "Jan", total: 10},
    { name: "Feb", total: 13},
    { name: "Mar", total: 16},
    { name: "Apr", total: 8},
    { name: "May", total: 20},
    { name: "Jun", total: 11},
    { name: "Jul", total: 14},
]

</script>

<template>

    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Admin Dashboard
            </h2>
        </template>

        <div class="tw-py-12 tw-grid tw-grid-cols-4 md:tw-grid-cols-8 tw-gap-6 ">
            <!-- Summary -->
            <div class="tw-col-span-2">
                <NumberSummary title="Total Simpanan" description="Akumulasi simpanan anggota">
                    <template #icon>
                        <DollarSign />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">Rp. 37.720.000</div>
                    </template>
                </NumberSummary>
            </div>

            <div class="tw-col-span-2">
                <NumberSummary title="Total Anggota" description="Jumlah total anggota yang teregistrasi">
                    <template #icon>
                        <User />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">320</div>
                    </template>
                </NumberSummary>
            </div>

            <div class="tw-col-span-2">
                <NumberSummary title="Total Transaksi" description="Jumlah transaksi yang dilakukan">
                    <template #icon>
                        <CircleDollarSign />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">20</div>
                    </template>
                </NumberSummary>

            </div>
            <div class="tw-col-span-2">
                <NumberSummary title="Total Pembiayaan Aktif" description="Total pembiayaan yang sedang berjalan">
                    <template #icon>
                        <BanknoteArrowUp />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">Rp. 12.420.000</div>
                    </template>
                </NumberSummary>
            </div>
            <!-- Graph -->
            <div class="tw-col-span-3 rw-row-span-2 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Grafik Perkembangan Simpanan</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <LineChart :data="data" index="date" :categories="['Simpanan']" :colors="['#f97316']" :y-formatter="(tick, i) => {
                            return typeof tick === 'number'
                                ? `Rp. ${new Intl.NumberFormat('id-ID').format(tick).toString()}`
                                : ''
                        }" />

                    </CardContent>
                </Card>
            </div>
            <div class="tw-row-span-2 tw-col-span-3 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Pengajuan yang disetujui 6 bulan terakhir</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <BarChart :rounded-corners="4" :data="barData" index="name" :categories="['total']" :colors="['#f97316']" />
                    </CardContent>
                </Card>
            </div>
            <div class="tw-row-span-2 tw-col-span-2 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Perlu Verifikasi</CardTitle>
                        <CardDescription>Daftar Aplikasi yang menunggu persetujuan</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <template v-for="item in perlu_verifikasi">
                                <div class="tw-flex tw-flex-col ">
                                    <p>{{ item.nama }}</p>
                                    <p class="tw-text-sm tw-font-light">{{ formatDistanceToNow(item.tanggal, {
                                        addSuffix:
                                        true, locale: id }) }}</p>
                                </div>
                                <div v-if="item.tipe == 'Transaksi'"
                                    class="tw-text-right  tw-font-semibold tw-text-gray-900 ">{{ item.tipe }}</div>
                                <div v-else-if="item.tipe == 'Cicilan'" class="tw-text-right tw-text-blue-900 ">{{
                                    item.tipe }}</div>
                                <div v-else-if="item.tipe == 'Simpanan'" class="tw-text-right tw-text-green-900 ">{{
                                    item.tipe }}</div>
                            </template>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
