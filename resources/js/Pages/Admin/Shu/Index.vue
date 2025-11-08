<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Form } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { computed, h, ref } from 'vue'
import { CalendarIcon, Minus, Plus, Search, DollarSign, User, CircleDollarSign, BanknoteArrowUp } from "lucide-vue-next";
import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
    DrawerTrigger,
} from '@/components/ui/drawer'
import { Calendar } from "@/components/ui/calendar"
import CalendarMontSelect from "@/Components/input/CalendarMontSelect.vue";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import NumberSummary from "@/Components/Dashboard/NumberSummary.vue";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { DonutChart } from "@/components/ui/chart-donut";
import { formatAsRupiah, valueUpdater } from "@/lib/utils";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { LineChart } from "@/components/ui/chart-line"
import type {
    ColumnDef,
    ColumnFiltersState,
    ExpandedState,
    SortingState,
    VisibilityState,
} from "@tanstack/vue-table"

import {
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from "@tanstack/vue-table"

import { Input } from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import FormattedNumberInput from "@/Components/input/FormattedNumberInput.vue";

const period = [
    ['2024-02-01', '2024-03-01'],
    ['2024-03-01', '2024-04-01'],
    ['2024-04-01', '2024-05-01'],
    ['2024-05-01', '2024-06-01'],
    ['2024-06-01', '2024-07-01'],
    ['2024-07-01', '2024-08-01'],
    ['2024-08-01', '2024-09-01'],
    ['2024-09-01', '2024-10-01'],
    ['2024-10-01', '2024-11-01'],
    ['2024-11-01', '2024-12-01'],
    ['2024-12-01', '2025-01-01'],
    ['2025-01-01', '2025-02-01'],
    ['2025-02-01', '2025-03-01'],
    ['2025-03-01', '2025-04-01'],
    ['2025-04-01', '2025-05-01'],
    ['2025-05-01', '2025-06-01'],
    ['2025-06-01', '2025-07-01'],
]
const selectedPeriodId = ref(period.length - 1) // Set a default
const selectedPeriod = computed(() => period[selectedPeriodId.value])
const keuntungan = ref(0)

function onCalendarUpdate(v) {
    const date = new Date(v.year, v.month - 1, v.day)

    const periodIndex = period.findIndex(([start, end]) => {
        const startDate = new Date(start)
        const endDate = new Date(end)
        return date >= startDate && date <= endDate
    })
    if (periodIndex === -1) return
    selectedPeriodId.value = periodIndex
}

const data = [
    { name: "Pokok", total: 2_000_000 },
    { name: "Wajib", total: 5_720_000 },
    { name: "Sukarela", total: 30_000_000 },
]

const dataAnggotaAktif = [
    { name: "Goklas", banyakTransaksi: 17, total: 3_210_000 },
    { name: "Billy", banyakTransaksi: 13, total: 2_120_000 },
    { name: "Supriadi", banyakTransaksi: 8, total: 980_000 },
    { name: "Eko Kurniawan", banyakTransaksi: 6, total: 690_000 },
    { name: "Eko Prasetyo", banyakTransaksi: 6, total: 420_000 },
]

const penjualanData = Array.from({ length: 30 }, (_, i) => {
    const d = new Date("2025-01-01")
    d.setDate(i + 1)
    return {
        day: d.toLocaleDateString("id-ID"),
        penjualan: Math.floor(Math.random() * 10),
    }
})

const produkPopuler = [
    { name: 'Minyak Goreng', pembelian: 80, profit: 1_000_000 },
    { name: 'Pasta Gigi', pembelian: 80, profit: 700_000 },
    { name: 'Spidol', pembelian: 70, profit: 400_000 },
    { name: 'Kertas', pembelian: 68, profit: 700_000 },
    { name: 'Permen', pembelian: 58, profit: 400_000 },
    { name: 'Telur', pembelian: 10, profit: 200_000 },

]

const valueFormatter = (tick: number | Date) => typeof tick === "number" ? `${formatAsRupiah(tick)}` : ""

export interface Transaksi {
    tanggal: Date
    pembeli: string
    jumlah: number
    verifikator: string
}

const dataPenjualan: Transaksi[] = [
    {
        tanggal: new Date('2025-11-08T09:15:00'), // 8 Nov 2025, 09:15
        pembeli: 'Budi Setiawan',
        jumlah: 1500000,
        verifikator: 'Ahmad S.'
    },
    {
        tanggal: new Date('2025-11-07T14:30:00'), // 7 Nov 2025, 14:30
        pembeli: 'Siti Aminah',
        jumlah: 850000,
        verifikator: 'Rina M.'
    },
    {
        tanggal: new Date('2025-11-07T10:00:00'), // 7 Nov 2025, 10:00
        pembeli: 'Agus Santoso',
        jumlah: 5200000,
        verifikator: 'Ahmad S.'
    },
    {
        tanggal: new Date('2025-11-06T16:45:00'), // 6 Nov 2025, 16:45
        pembeli: 'Dewi Lestari',
        jumlah: 2750000,
        verifikator: 'Joko P.'
    },
    {
        tanggal: new Date('2025-11-06T09:20:00'), // 6 Nov 2025, 09:20
        pembeli: 'Eko Prasetyo',
        jumlah: 1100000,
        verifikator: 'Rina M.'
    },
    {
        tanggal: new Date('2025-11-05T11:11:00'), // 5 Nov 2025, 11:11
        pembeli: 'Hasanudin',
        jumlah: 3000000,
        verifikator: 'Ahmad S.'
    }
];

const columns: ColumnDef<Transaksi>[] = [
    {
        accessorKey: "tanggal",
        header: "Tanggal Transaksi",
        cell: ({ row }) => h("div", { class: "" }, row.original.tanggal.toLocaleDateString("id-ID", { day: "numeric", month: "long", year: "numeric" })),
    },
    {
        accessorKey: "pembeli",
        header: "Pembeli",
        cell: ({ row }) => h("div", { class: "" }, row.getValue("pembeli")),
    },
    {
        accessorKey: "jumlah",
        header: () => h("div", { class: "" }, "jumlah"),
        cell: ({ row }) => {
            return h("div", { class: "tw-font-medium" }, formatAsRupiah(row.getValue("jumlah")))
        },
    },
    {
        accessorKey: "verifikator",
        header: () => h("div", { class: "" }, "Verifikator"),
        cell: ({ row }) => {
            
            return h("div", { class: "tw-font-medium" }, row.original.verifikator)
        },
    }
]
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})
const expanded = ref<ExpandedState>({})

const table = useVueTable({
    data: dataPenjualan,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
    onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
    state: {
        get sorting() { return sorting.value },
        get columnFilters() { return columnFilters.value },
        get columnVisibility() { return columnVisibility.value },
        get rowSelection() { return rowSelection.value },
        get expanded() { return expanded.value },
    },
})

export interface Piutang {
    anggota: string
    jumlah: number
    tenor: number
    tenorDibayar: number
}

const dataPiutang: Piutang[] = [
    {
        anggota: 'Budi Setiawan',
        jumlah: 5000000,
        tenor: 12,
        tenorDibayar: 5
    },
    {
        anggota: 'Siti Aminah',
        jumlah: 10000000,
        tenor: 24,
        tenorDibayar: 18
    },
    {
        anggota: 'Agus Santoso',
        jumlah: 2500000,
        tenor: 6,
        tenorDibayar: 1
    },
    {
        anggota: 'Dewi Lestari',
        jumlah: 8000000,
        tenor: 12,
        tenorDibayar: 11
    },
    {
        anggota: 'Eko Prasetyo',
        jumlah: 1500000,
        tenor: 6,
        tenorDibayar: 6 
        // Contoh: Piutang yang baru saja lunas
    }
];

const piutangCol: ColumnDef<Piutang>[] = [
    {
        accessorKey: "anggota",
        header: "Anggota",
        cell: ({ row }) => h("div", { class: "" }, row.getValue("anggota")),
    },
    {
        accessorKey: "jumlah",
        header: () => h("div", { class: "" }, "jumlah"),
        cell: ({ row }) => {
            return h("div", { class: "tw-font-medium" }, formatAsRupiah(row.getValue("jumlah")))
        },
    },
    {
        accessorKey: "tenor",
        header: () => h("div", { class: "" }, "tenor"),
        cell: ({ row }) => {
            return h("div", { class: "tw-font-medium" }, `${row.original.tenorDibayar} / ${row.original.tenor}`)
        },
    }
]


const tablePiutang = useVueTable({
    data: dataPiutang,
    columns: piutangCol,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    state: {
        get sorting() { return sorting.value },
        get columnFilters() { return columnFilters.value },
        get columnVisibility() { return columnVisibility.value },
        get rowSelection() { return rowSelection.value },
        get expanded() { return expanded.value },
    },
})

function handlePrint() {
  // 1. Get the URL of your new print route.
  //    (This assumes you are using something like Ziggy to resolve routes)
  const url = route('admin.shu.kalkulator') 

  // 2. Create an iframe
  const iframe = document.createElement('iframe');
  
  // 3. Make it hidden
  iframe.style.position = 'absolute';
  iframe.style.width = '0';
  iframe.style.height = '0';
  iframe.style.border = 'none';

  // 4. Set the source to your print route
  iframe.src = url;

  // 5. Add it to the page
  document.body.appendChild(iframe);

  setTimeout(() => {
    iframe.contentWindow.focus(); // Focus for some browsers
    iframe.contentWindow.print(); // THE MAGIC!
  }, 1000);

  // 6. Wait for it to load, then tell it to print
//   iframe.onload = () => {
//     try {
//       iframe.contentWindow.focus(); // Focus for some browsers
//       iframe.contentWindow.print(); // THE MAGIC!
//     } catch (e) {
//       console.error('Print failed:', e);
//     }
    
//     // 7. Remove the iframe after printing (or after a delay)
//     setTimeout(() => {
//       document.body.removeChild(iframe);
//     }, 1000);
//   };
}

</script>

<template>

    <Head title="SHU" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                SHU
            </h2>
        </template>
        <div class="tw-py-12 tw-grid tw-grid-cols-4 md:tw-grid-cols-8 tw-gap-6 ">



            <div class="tw-col-span-2 tw-row-span-2 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Total Simpanan</CardTitle>
                        <CardDescription>Akumulasi simpanan anggota periode ini</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <DonutChart index="name" :category="'total'" :data="data" :value-formatter="valueFormatter"
                            :colors="['red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'purple']" />
                    </CardContent>
                </Card>
            </div>

            <div class="tw-col-span-2 tw-row-span-2 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Anggota Paling Aktif</CardTitle>
                        <CardDescription>anggota yang paling banyak bertransaksi di periode ini</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]">
                                        Nama
                                    </TableHead>
                                    <TableHead>Banyak Transaksi</TableHead>
                                    <TableHead>Jumlah Transaksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-for="item in dataAnggotaAktif">
                                    <TableRow>
                                        <TableCell>{{ item.name }}</TableCell>
                                        <TableCell>{{ item.banyakTransaksi }}</TableCell>
                                        <TableCell>{{ formatAsRupiah(item.total) }}</TableCell>
                                    </TableRow>
                                </template>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>


            <div class="tw-col-span-2">
                <NumberSummary title="Keuntungan" description="Keuntungan yang didapat periode ini">
                    <template #icon>
                        <User />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">{{ formatAsRupiah(32_000_000) }}</div>
                    </template>
                </NumberSummary>
            </div>

            <div class="tw-col-span-2">
                <NumberSummary title="Total Transaksi" description="Jumlah transaksi yang dilakukan periode ini">
                    <template #icon>
                        <CircleDollarSign />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">20</div>
                    </template>
                </NumberSummary>
            </div>

            <div class="tw-col-span-2">
                <NumberSummary title="Total Pembiayaan Aktif"
                    description="Total pembiayaan yang berjalan pada periode ini">
                    <template #icon>
                        <BanknoteArrowUp />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">Rp. 12.420.000</div>
                    </template>
                </NumberSummary>
            </div>

            <div class="tw-col-span-2">
                <NumberSummary title="Anggota Baru" description="Jumlah total anggota baru yang teregistrasi">
                    <template #icon>
                        <User />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">+ 24</div>
                    </template>
                </NumberSummary>
            </div>

            <!-- <div class="tw-col-span-2">
                <NumberSummary title="Jumlah Cicilan yang diajukan"
                    description="Total pembiayaan yang berjalan pada periode ini">
                    <template #icon>
                        <BanknoteArrowUp />
                    </template>
                    <template #content>
                        <div class="tw-text-3xl tw-font-bold">12</div>
                    </template>
                </NumberSummary>
            </div>
 -->

            <div class="tw-col-span-4 tw-row-span-2 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Grafik Penjualan Bulan Ini</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <LineChart :data="penjualanData" index="day" :categories="['penjualan']" />
                    </CardContent>
                </Card>
            </div>

            <div class="tw-col-span-4 tw-row-span-2 ">
                <Card class="tw-h-full">
                    <CardHeader>
                        <CardTitle>Produk Paling Populer</CardTitle>
                        <CardDescription>anggota yang paling banyak bertransaksi di periode ini</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]">
                                        Nama Produk
                                    </TableHead>
                                    <TableHead>Banyak Transaksi</TableHead>
                                    <TableHead>Keuntungan</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-for="item in produkPopuler">
                                    <TableRow>
                                        <TableCell>{{ item.name }}</TableCell>
                                        <TableCell>{{ item.pembelian }}</TableCell>
                                        <TableCell>{{ formatAsRupiah(item.profit) }}</TableCell>
                                    </TableRow>
                                </template>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </div>
        <!-- Tabel Penjualan -->
        <div class="tw-bg-white tw-rounded-md tw-p-4">
            <h1 class="tw-text-xl tw-font-bold tw-text-black tw-mb-6 tw-tracking-wide" >Laporan Penjualan</h1>
            <div class="tw-w-full ">
                <div class="tw-flex tw-items-center tw-py-4 ">
                    <Input class="tw-max-w-sm" placeholder="Filter nama..."
                        :model-value="table.getColumn('username')?.getFilterValue() as string"
                        @update:model-value=" table.getColumn('username')?.setFilterValue($event)" />
                </div>
                <div class="tw-rounded-md tw-border">

                    <Table>
                        <TableHeader>
                            <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                                <TableHead v-for="header in headerGroup.headers" :key="header.id">
                                    <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                        :props="header.getContext()" />
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="table.getRowModel().rows?.length">
                                <template v-for="row in table.getRowModel().rows" :key="row.id">
                                    <TableRow>
                                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                            <FlexRender :render="cell.column.columnDef.cell"
                                                :props="cell.getContext()" />
                                        </TableCell>
                                    </TableRow>

                                </template>
                            </template>

                            <TableRow v-else>
                                <TableCell :colspan="columns.length" class="tw-h-24 tw-text-center">
                                    No results.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="tw-flex tw-items-center tw-justify-end tw-space-x-2 tw-py-4">
                    <div class="tw-space-x-2">
                        <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()"
                            @click="table.previousPage()">
                            Previous
                        </Button>
                        <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()"
                            @click="table.nextPage()">
                            Next
                        </Button>
                    </div>
                </div>
            </div>


        </div>

        <!-- Tabel Cicilan -->
         <div class="tw-bg-white tw-rounded-md tw-p-4 tw-my-2 ">
            <h1 class="tw-text-xl tw-font-bold tw-text-black tw-mb-6 tw-tracking-wide" >Laporan Piutang Anggota</h1>
            <div class="tw-w-full ">
                <div class="tw-flex tw-items-center tw-py-4 ">
                    <Input class="tw-max-w-sm" placeholder="Filter nama..."
                        :model-value="tablePiutang.getColumn('username')?.getFilterValue() as string"
                        @update:model-value=" tablePiutang.getColumn('username')?.setFilterValue($event)" />
                </div>
                <div class="tw-rounded-md tw-border">

                    <Table>
                        <TableHeader>
                            <TableRow v-for="headerGroup in tablePiutang.getHeaderGroups()" :key="headerGroup.id">
                                <TableHead v-for="header in headerGroup.headers" :key="header.id">
                                    <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                        :props="header.getContext()" />
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="tablePiutang.getRowModel().rows?.length">
                                <template v-for="row in tablePiutang.getRowModel().rows" :key="row.id">
                                    <TableRow>
                                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                            <FlexRender :render="cell.column.columnDef.cell"
                                                :props="cell.getContext()" />
                                        </TableCell>
                                    </TableRow>

                                </template>
                            </template>

                            <TableRow v-else>
                                <TableCell :colspan="piutangCol.length" class="tw-h-24 tw-text-center">
                                    No results.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="tw-flex tw-items-center tw-justify-end tw-space-x-2 tw-py-4">
                    <div class="tw-space-x-2">
                        <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()"
                            @click="table.previousPage()">
                            Previous
                        </Button>
                        <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()"
                            @click="table.nextPage()">
                            Next
                        </Button>
                    </div>
                </div>
            </div>
        </div>


     <div class="tw-bg-white tw-rounded-md tw-p-4">
            <h1 class="tw-text-xl tw-font-bold tw-text-black tw-mb-6 tw-tracking-wide" >Kalkulator SHU</h1>
            <div class="tw-flex tw-flex-col tw-gap-4 tw-max-w-sm tw-m-auto " >
                <div  class="tw-flex tw-flex-col tw-gap-4"  >
                    <Label for="keutungan" >Keuntungan</Label>
                    <FormattedNumberInput id="keuntungan" name="keuntungan" v-model="keuntungan" />
                    
                    <Button @click="handlePrint" type="submit" >Hitung</Button>
                </div>
            </div>
        </div>

        <!-- FAB -->
        <div class="tw-flex tw-flex-row-reverse tw-gap-6">

            <Drawer>
                <DrawerTrigger>
                    <button type="button" class="
      tw-fixed tw-bottom-6 tw-right-6
      tw-w-14 tw-h-14
      tw-bg-blue-600 tw-text-white
      tw-rounded-full
      tw-shadow-lg
      tw-flex tw-items-center tw-justify-center
      tw-text-2xl tw-font-bold
      tw-cursor-pointer
      tw-transition-all
      hover:tw-bg-blue-700
      hover:tw-scale-105
    ">
                        <CalendarIcon />
                    </button>
                </DrawerTrigger>
                <DrawerContent>



                    <div class="tw-mx-auto tw-w-full tw-max-w-2xl ">
                        <DrawerHeader>
                            <DrawerTitle class="tw-text-center">Pilih Periode</DrawerTitle>
                            <DrawerDescription class="tw-text-center">Periode statistik yang akan dipilih
                            </DrawerDescription>
                        </DrawerHeader>
                        <DrawerFooter>
                            <div class="tw-flex tw-items-center tw-justify-center tw-space-x-2">
                                <Button variant="outline" size="icon" class="tw-h-8 tw-w-8 tw-shrink-0 tw-rounded-full 
                                " :disabled="selectedPeriodId <= 0" @click="selectedPeriodId--">
                                    <Minus class="tw-h-4 tw-w-4" />
                                    <span class="tw-sr-only">Decrease</span>
                                </Button>
                                <div class="tw-flex-1 tw-text-center tw-space-y-4">
                                    <div class="tw-text-4xl tw-font-bold tw-tracking-tighter">
                                        {{ selectedPeriod[0] }}
                                    </div>

                                    <div class="tw-text-4xl tw-font-bold tw-tracking-tighter">
                                        {{ selectedPeriod[1] }}
                                    </div>
                                    <div class="tw-flex tw-justify-end">
                                        <Popover>
                                            <PopoverTrigger as-child>
                                                <Button variant="outline">
                                                    <Search />
                                                </Button>
                                            </PopoverTrigger>
                                            <PopoverContent class="w-auto p-0">
                                                <CalendarMontSelect @update:model-value="onCalendarUpdate" />
                                            </PopoverContent>
                                        </Popover>

                                    </div>
                                </div>
                                <Button variant="outline" size="icon" class="tw-h-8 tw-w-8 tw-shrink-0 tw-rounded-full"
                                    :disabled="selectedPeriodId >= period.length - 1" @click="selectedPeriodId++">
                                    <Plus class="tw-h-4 tw-w-4" />
                                    <span class="tw-sr-only">Increase</span>
                                </Button>
                            </div>




                            <DrawerClose>
                                <Button class="tw-w-full" variant="outline">
                                    Pilih
                                </Button>
                            </DrawerClose>
                        </DrawerFooter>
                    </div>
                </DrawerContent>
            </Drawer>

        </div>

    </AuthenticatedLayout>
</template>
