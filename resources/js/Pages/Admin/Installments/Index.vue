<script setup lang="ts">
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
import { h, ref } from "vue"
import { formatAsRupiah, valueUpdater } from "@/lib/utils"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'
import { Input } from "@/components/ui/input"
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"


export interface Cicilan {
    id: string
    username: string
    tenor: number
    dibayar: number
    jumlah: number
    status: string
    tanggalPengajuan: Date
}

const data: Cicilan[] = [
    {
        id: "1",
        username: "User 1",
        tenor: 12,
        dibayar: 0,
        jumlah: 1000000,
        status: "Ditolak",
        tanggalPengajuan: new Date(),
    },
    {
        id: "2",
        username: "User 2",
        tenor: 24,
        dibayar: 3,
        jumlah: 1500000,
        status: "Aktif",
        tanggalPengajuan: new Date("2023-01-01"),
    },
    {
        id: "3",
        username: "User 3",
        tenor: 36,
        dibayar: 0,
        jumlah: 2000000,
        status: "Menunggu Verifikasi",
        tanggalPengajuan: new Date("2023-02-01"),
    }
]

const isSheetOpen = ref(false)
const selectedProduct = ref<Cicilan | null>(null)

// Function to open the sheet with a specific product
function openCicilanDetail(product: Cicilan) {
    selectedProduct.value = product
    isSheetOpen.value = true
    console.log(product)
}

const columns: ColumnDef<Cicilan>[] = [
    {
        accessorKey: "username",
        header: "Nama Peminjam",
        cell: ({ row }) => h("div", { class: "" }, row.getValue("username")),
    },
    {
        accessorKey: "status",
        header: "Status",
        cell: ({ row }) => h("div", { class: "" }, row.getValue("status")),
    },
    {
        accessorKey: "tenor",
        header: () => h("div", { class: "" }, "Tenor"),
        cell: ({ row }) => {
            const tenor = row.original.status === 'Ditolak' ? '-' : `${row.original.dibayar} / ${row.getValue('tenor')}`
            return h("div", { class: "tw-font-medium" }, tenor)
        },
    },
    {
        accessorKey: "jumlah",
        header: () => h("div", { class: "" }, "Jumlah"),
        cell: ({ row }) => {
            const amount = Number.parseFloat(row.getValue("jumlah"))


            const formatted = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(amount)

            return h("div", { class: "tw-font-medium" }, formatted)
        },
    },
    {
        id: "tanggalPengajuan",
        header: "Tanggal Pengajuan",
        cell: (data) => {
            // console.log(data)

            const date = data.row.original.tanggalPengajuan

            const formattedDate = date.toLocaleDateString("id-ID", {
                day: "numeric",
                month: "long",
                year: "numeric",
            })

            return h("div", {}, formattedDate)
        },
    },
]
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})
const expanded = ref<ExpandedState>({})

const table = useVueTable({
    data,
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
</script>

<template>

    <Head title="Kelola Cicilan" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Kelola Cicilan
            </h2>
        </template>

        <div class="tw-bg-white tw-rounded-md tw-p-4">


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
                                    <TableRow class="tw-cursor-pointer" @click="openCicilanDetail(row.original)">
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

        <Dialog v-model:open="isSheetOpen">

            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Status Cicilan <Badge class="tw-p-0.5">{{ selectedProduct.status }}</Badge></DialogTitle>
                </DialogHeader>

                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-py-4">
                    <div class="tw-flex tw-flex-col">
                        <div class="tw-flex tw-flex-col tw-gap-2">
                            <div class="tw-flex tw-flex-col">
                                <span class="tw-text-sm">Jumlah</span>
                                <span class="tw-text-lg tw-font-bold">{{ formatAsRupiah(selectedProduct.jumlah)
                                    }}</span>
                            </div>
                            <div class="tw-flex tw-flex-col">
                                <span class="tw-text-sm">Tenor</span>
                                <span class="tw-text-lg tw-font-bold"> {{ selectedProduct.dibayar }} / {{
                                    selectedProduct.tenor
                                    }}</span>
                            </div>
                            <div class="tw-flex tw-flex-col">
                                <span class="tw-text-sm">Diajukan pada Tanggal</span>
                                <span class="tw-text-lg tw-font-bold">{{
                                    selectedProduct.tanggalPengajuan.toLocaleDateString('id-ID', {
                                        day: "numeric",
                                    month: "long",
                                    year: "numeric",
                                    })}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <DialogFooter>

                    <Button asChild>
                        <Link :href="route('admin.installments.show', selectedProduct.id)">
                        Detail
                        </Link>
                    </Button>

                </DialogFooter>
            </DialogContent>
        </Dialog>

    </AuthenticatedLayout>

</template>