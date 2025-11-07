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
import { ArrowUpDown, ChevronDown } from "lucide-vue-next"
import { h, ref } from "vue"
import { valueUpdater } from "@/lib/utils"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
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

import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet';
import Label from "@/components/ui/label/Label.vue"

export interface Product {
  id: string
  image: string
  name: string,
  stock: number,
  cost: number,
}

const data: Product[] = [
  {
    id: "prod_001",
    image: "https://picsum.photos/seed/laptop/400/300",
    name: "Laptop Pro 16 inch",
    stock: 25,
    cost: 21500000,
  },
  {
    id: "prod_002",
    image: "https://picsum.photos/seed/keyboard/400/300",
    name: "Mechanical Keyboard",
    stock: 112,
    cost: 850000,
  },
  {
    id: "prod_003",
    image: "https://picsum.photos/seed/mouse/400/300",
    name: "Wireless Optical Mouse",
    stock: 230,
    cost: 275000,
  },
  {
    id: "prod_004",
    image: "https://picsum.photos/seed/headphones/400/300",
    name: "Bluetooth Headphones",
    stock: 78,
    cost: 1200000,
  },
  {
    id: "prod_005",
    image: "https://picsum.photos/seed/monitor/400/300",
    name: "27-inch 4K Monitor",
    stock: 41,
    cost: 4500000,
  },
  {
    id: "prod_006",
    image: "https://picsum.photos/seed/coffeemug/400/300",
    name: "Insulated Coffee Mug",
    stock: 450,
    cost: 150000,
  },
]

const isSheetOpen = ref(false)
const selectedProduct = ref<Product | null>(null)

// Function to open the sheet with a specific product
function openProductDetails(product: Product) {
  selectedProduct.value = product
  isSheetOpen.value = true
  console.log(product)
}

const columns: ColumnDef<Product>[] = [
  {
    accessorKey: "image",
    header: "",
    cell: ({ row }) => h("img", { class: "tw-h-24 tw-w-24 tw-rounded tw-object-cover", src: row.getValue("image") }),
  },
  {
    accessorKey: "name",
    header: ({ column }) => {
      return h(Button, {
        variant: "nama",
      }, () => ["Name", h("div", { class: "tw-ml-2 tw-h-4 tw-w-4" })])
    },
    cell: ({ row }) => h("div", { class: "tw-lowercase" }, row.getValue("name")),
  },
  {
    accessorKey: "stock",
    header: () => h("div", { class: "tw-text-right" }, "Stok"),
    cell: ({ row }) => h("div", { class: "tw-text-right tw-font-medium" }, row.getValue("stock")),
  },
  {
    accessorKey: "cost",
    header: () => h("div", { class: "tw-text-right" }, "Harga"),
    cell: ({ row }) => {
      const amount = Number.parseFloat(row.getValue("cost"))


      const formatted = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
      }).format(amount)

      return h("div", { class: "tw-text-right tw-font-medium" }, formatted)
    },
  },
  {
    id: "actions",
    enableHiding: false,
    // cell: ({ row }) => {
    //   const payment = row.original

    //   return h(DropdownAction, {
    //     payment,
    //     onExpand: row.toggleExpanded,
    //   })
    // },
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

  <Head title="Kelola Produk" />

  <AuthenticatedLayout>

    <template #header>
      <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
        Kelola Produk
      </h2>
    </template>

    <div class="tw-bg-white tw-rounded-md tw-p-4">


      <div class="tw-w-full ">
        <div class="tw-flex tw-items-center tw-py-4 ">
          <Input class="tw-max-w-sm" placeholder="Filter emails..."
            :model-value="table.getColumn('name')?.getFilterValue() as string"
            @update:model-value=" table.getColumn('name')?.setFilterValue($event)" />
          <Button asChild class="tw-ml-auto"  >
            <Link :href="route('admin.products.create')" >
              Tambah Produk
            </Link>
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
                  <TableRow class="tw-cursor-pointer" @click="openProductDetails(row.original)">
                    <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                      <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />

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
            <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
              Previous
            </Button>
            <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
              Next
            </Button>
          </div>
        </div>
      </div>

      <Dialog v-model:open="isSheetOpen">

        <DialogContent>
          <DialogHeader>
            <DialogTitle>{{ selectedProduct?.name }}</DialogTitle>
          </DialogHeader>

          <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-py-4">
            <div class="tw-flex tw-items-start tw-justify-center">
              <img :src="selectedProduct.image" :alt="selectedProduct.name"
                class="tw-w-full tw-max-w-md tw-rounded-md tw-object-cover tw-shadow-md" />
            </div>

            <div class="tw-flex tw-flex-col">
              <div class="tw-flex tw-flex-col tw-gap-2">
                <div class="tw-flex tw-flex-col">
                  <span class="tw-text-sm">Harga</span>
                  <span class="tw-text-lg tw-font-bold">Rp {{ selectedProduct.cost }}</span>
                </div>
                <div class="tw-flex tw-flex-col">
                  <span class="tw-text-sm">Stok</span>
                  <span class="tw-text-lg tw-font-bold">{{ selectedProduct.stock }}</span>
                </div>
                <div class="tw-flex tw-flex-col">
                  <span class="tw-text-sm">Kategori</span>
                  <span class="tw-text-lg tw-font-bold">Kategori</span>
                </div>
              </div>
            </div>
          </div>

          <DialogFooter>

            <Button asChild>
              <Link :href="route('admin.products.edit', selectedProduct.id)">
              Edit
              </Link>
            </Button>

          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>


  </AuthenticatedLayout>

</template>