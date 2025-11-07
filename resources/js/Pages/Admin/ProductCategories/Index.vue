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
import { valueUpdater } from "@/lib/utils"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, Form } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button"

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


export interface Category {
    id: string
    name: string,
}

const data: Category[] = [
    {
        id: "prod_001",
        name: "Makanan",
    },
    {
        id: "prod_002",
        name: "Minuman",
    },
    {
        id: "prod_003",
        name: "Alat Tulis",
    },
    {
        id: "prod_004",
        name: "Elektronik",
    },
    {
        id: "prod_005",
        name: "Pakaian",
    },
    {
        id: "prod_006",
        name: "Kendaraan",
    },
]

const isDialogOpen = ref(false)
const selectedCategory = ref<Category | null>(null)
const isEdit = ref(false)

// Function to open the sheet with a specific product
function openEditProduct(category: Category) {
    selectedCategory.value = category
    isDialogOpen.value = true
    isEdit.value = true
}

function openAddProduct() {
    selectedCategory.value = {
        id: "",
        name: "",
    }
    isDialogOpen.value = true
    isEdit.value = false
}

const columns: ColumnDef<Category>[] = [
    {
        accessorKey: "id",
        header: "ID",
        cell: ({ row }) => h("div", { class: "tw-lowercase tw-font-medium tw-text-gray-800" }, row.getValue("id")),
    },
    {
        accessorKey: "name",
        header: ({ column }) => {
            return h(Button, {
                variant: "nama",
            }, () => ["Name", h("div", { class: "tw-ml-2 tw-h-4 tw-w-4" })])
        },
        cell: ({ row }) => h("div", { class: "tw-lowercase tw-font-medium tw-text-gray-800 tw-capitalize tw-truncate" }, row.getValue("name")),
    }
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

    <Head title="Kelola Kategori" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Kelola Kategori
            </h2>
        </template>

        <div class="tw-bg-white tw-rounded-md tw-p-4">


            <div class="tw-w-full ">
                <div class="tw-flex tw-items-center tw-py-4 ">
                    <Input class="tw-max-w-sm" placeholder="Filter nama..."
                        :model-value="table.getColumn('name')?.getFilterValue() as string"
                        @update:model-value=" table.getColumn('name')?.setFilterValue($event)" />
                    
                            <Button @click="openAddProduct" type="submit" class="tw-ml-auto" >
                                Tambah Kategori
                            </Button>
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
                                    <TableRow class="tw-cursor-pointer" @click="openEditProduct(row.original)">
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

            <Dialog v-model:open="isDialogOpen">

                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Kategori Produk</DialogTitle>
                    </DialogHeader>
                    <Form :action=" isEdit ? 
                        route('admin.product-categories.update', selectedCategory.id): 
                        route('admin.product-categories.store')" 
                        :method="isEdit ? 'PUT' : 'POST'" >

                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-py-4">

                            <div class="tw-flex tw-flex-col tw-space-y-2">
                                <Label>Nama</Label>
                                <Input v-model="selectedCategory.name" />
                            </div>

                        </div>

                        <DialogFooter>

                            <Button type="submit">
                                Simpan
                            </Button>

                            <Button v-if="isEdit"  type="button" variant="destructive">Hapus</Button>

                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>


    </AuthenticatedLayout>

</template>