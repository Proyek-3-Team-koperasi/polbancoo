<script setup lang="ts">
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { h, ref } from "vue"
import { formatAsRupiah, valueUpdater } from "@/lib/utils"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, Form } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button"
import { Separator } from "@/components/ui/separator"
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

import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"

const products = [
    {
        name: 'Minyak Goreng',
        jumlah: 2,
        harga: 23000,
        hargaJual: 25000
    },
    {
        name: 'Terigu',
        jumlah: 2,
        harga: 12000,
        hargaJual: 15000
    }, {
        name: 'Telur',
        jumlah: 12,
        harga: 2500,
        hargaJual: 3000
    },
    {
        name: 'Gula',
        jumlah: 3,
        harga: 15000,
        hargaJual: 18000
    }
]

const totalBeli = products.reduce((acc, product) => acc + product.harga * product.jumlah, 0)
const totalJual = products.reduce((acc, product) => acc + product.hargaJual * product.jumlah, 0)


</script>

<template>

    <Head title="Detail Cicilan" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Detail Cicilan
            </h2>
        </template>

        <div class="tw-bg-white tw-rounded-md tw-p-4">
            <div class="tw-flex tw-flex-col">

                <div class="tw-flex-1">
                    <h3 class="tw-text-xl tw-font-semibold tw-text-gray-800">Biodata Peminjam</h3>
                    <Separator />
                    <div class="tw-my-4">
                        <div class="tw-flex tw-flex-col tw-gap-2 ">
                            <p>Nama: User 1</p>
                            <p>Nomor Telepon: 08123456789</p>
                            <p>Alamat: Jl. Contoh, Contoh, Contoh, Contoh</p>
                            <p>NIM/NIDN/NIP: 1234567890</p>
                            <p>Fakultas/Unit Kerja: Teknik Informatika</p>
                            <p>Status Keanggotaan: Aktif sejak 1 Januari 2023</p>
                        </div>

                    </div>
                </div>

                <div class="tw-flex-1 tw-my-2 ">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Pengajuan</h3>
                    <Separator />
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[100px]">
                                    Nama
                                </TableHead>
                                <TableHead>Jumlah</TableHead>
                                <TableHead>Harga Beli</TableHead>

                                <TableHead>Subtotal Beli</TableHead>
                                <TableHead>Harga Jual</TableHead>
                                <TableHead>Subtotal</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="product in products">
                                <TableCell class="font-medium">
                                    {{ product.name }}
                                </TableCell>
                                <TableCell>{{ product.jumlah }}</TableCell>
                                <TableCell>{{ formatAsRupiah(product.harga) }}</TableCell>
                                <TableCell>{{ formatAsRupiah(product.harga * product.jumlah) }}</TableCell>
                                <TableCell>{{ formatAsRupiah(product.hargaJual) }}</TableCell>
                                <TableCell>{{ formatAsRupiah(product.hargaJual * product.jumlah) }}</TableCell>
                            </TableRow>
                            <TableRow>

                                <TableCell class="tw-col-span-3 tw-font-semibold" colspan="3">Total</TableCell>
                                <TableCell class="tw-col-span-3 tw-font-semibold" colspan="2">
                                    {{ formatAsRupiah(totalBeli) }}
                                </TableCell>
                                <TableCell class="tw-col-span-3 tw-font-semibold">
                                    {{ formatAsRupiah(totalJual) }}
                                </TableCell>
                            </TableRow>
                            <TableRow>

                                <TableCell class="tw-col-span-3 tw-font-semibold" colspan="5">Keuntungan</TableCell>
                                <TableCell class="tw-col-span-3 tw-font-semibold">
                                    {{ formatAsRupiah(totalJual - totalBeli) }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="tw-flex-1 tw-my-2 ">
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Tenor</h3>
                    <Separator />
                    <div class="tw-my-4">
                        <p>Tenor: 12 bulan</p>
                        <p>Jatuh tempo pada: {{ new Date().toLocaleDateString("id-ID", { year: "numeric", month: "long", day: "numeric" }) }}</p>
                        <p>Angsuran: {{ formatAsRupiah(1000000 / 12) }} per bulan </p>
                    </div>
                </div>
                <div class="tw-flex tw-my-2 tw-gap-2  ">
                    <Drawer>
                        <DrawerTrigger><Button>Terima</Button></DrawerTrigger>
                        <DrawerContent>
                            <div class="tw-mx-auto w-full max-w-sm">

                                <DrawerHeader>
                                    <DrawerTitle>Terima Pinjaman</DrawerTitle>
                                    <DrawerDescription>Dengan menyetujui pinjaman ini, Anda menyetujui semua ketentuan
                                        dan
                                        syarat yang berlaku.</DrawerDescription>
                                </DrawerHeader>
                                <DrawerFooter>
                                    <Form>
                                        <Button class="tw-w-full" variant="destructive">Setuju</Button>
                                    </Form>
                                    <DrawerClose>
                                        <Button class="tw-w-full" variant="outline">
                                            Batalkan
                                        </Button>
                                    </DrawerClose>
                                </DrawerFooter>
                            </div>
                        </DrawerContent>
                    </Drawer>

                    <Drawer>
                        <DrawerTrigger><Button variant="destructive">Tolak</Button></DrawerTrigger>
                        <DrawerContent>
                            <div class="tw-mx-auto tw-w-full tw-max-w-lg ">
                                <DrawerHeader>
                                    <DrawerTitle>Tolak Pinjaman</DrawerTitle>
                                    <DrawerDescription>Apakah Anda yakin ingin menolak pinjaman ini?</DrawerDescription>
                                </DrawerHeader>
                                <DrawerFooter>
                                    <Form>
                                        <Label for="alasan">Alasan</Label>
                                        <Textarea id="alasan" class="tw-my-2 tw-w-full tw-h-64"
                                            placeholder="Simpanan belum mencukupi..."></Textarea>
                                        <Button class="tw-w-full" variant="destructive">Tolak</Button>
                                    </Form>
                                    <DrawerClose>
                                        <Button class="tw-w-full" variant="outline">
                                            Batalkan
                                        </Button>
                                    </DrawerClose>
                                </DrawerFooter>
                            </div>
                        </DrawerContent>
                    </Drawer>
                </div>


            </div>
        </div>


    </AuthenticatedLayout>

</template>