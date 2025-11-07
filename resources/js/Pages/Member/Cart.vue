<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const selectedProduct = ref([]);
const product = ref([
    { 
        nama: "Kopi Arabica", 
        deskripsi: ".", 
        harga_satuan: 50000, 
        jumlah_sementara: 4,
    },
    { 
        nama: "Bakpao khas Boyolali", 
        deskripsi: "Isi sendiri udah gede", 
        harga_satuan: 15000, 
        jumlah_sementara: 8,
    },
    { 
        nama: "Soto khas Ngawi", 
        deskripsi: "Manja ya", 
        harga_satuan: 200000, 
        jumlah_sementara: 2,
    },
    { 
        nama: "Nasi Uduk", 
        deskripsi: "Nasi uduk", 
        harga_satuan: 10000, 
        jumlah_sementara: 20,
    },
    { 
        nama: "Ban Crispy", 
        deskripsi: "Karet Ban dengan sambal menggoda", 
        harga_satuan: 1500000, 
        jumlah_sementara: 1,
    },
    { 
        nama: "Cireng Batu", 
        deskripsi: "Gabungan batu dan isian sambal", 
        harga_satuan: 1500, 
        jumlah_sementara: 15,
    }
]);
</script>

<template>
    <Head title="Keranjang Belanja" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Keranjang
            </h2>
        </template>

        <div class="tw-space-y-4 tw-mb-24">
            <van-checkbox-group v-model="selectedProduct">
                <div v-for="(barang) in product" class="tw-bg-white tw-rounded-2xl tw-p-4 tw-mb-3 tw-flex tw-items-center tw-justify-between tw-transition">
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <van-checkbox :name="barang.nama" shape="square" />
                        <img
                        src=""
                        alt="Foto"
                        class="tw-w-10 tw-h-10 tw-rounded-lg tw-object-cover"
                        />
                        <div>
                            <h3 class="tw-font-semibold tw-text-gray-800">{{ barang.nama }}</h3>
                            <p class="tw-text-gray-500 tw-text-m">{{ barang.deskripsi }}</p>
                            <p class="tw-text-gray-500 tw-text-sm">Rp {{ barang.harga_satuan }}</p>
                        </div>
                    </div>

                    <div class="tw-flex tw-items-center tw-gap-2">
                        <van-stepper
                            v-model="barang.jumlah_sementara"
                            min="1"
                            :max="100"
                        />
                    </div>
                </div>
            </van-checkbox-group>


            <div class="tw-bottom-12 tw-left-0 tw-right-0 tw-p-4">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
                    <span class="tw-font-medium tw-text-gray-700">Total</span>
                    <span class="tw-font-bold tw-text-orange-600 tw-text-lg">Rp 999.999.999.999</span>
                </div>
                <van-button block color="#ff6600" class="tw-font-semibold hover:tw-bg-orange-300">Pesan</van-button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
