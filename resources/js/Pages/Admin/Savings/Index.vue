<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ImagePreview } from "vant";
import { ref } from "vue";

    const transactions = ref([
    {
        nama: "Member Demo",
        jenis: "Pokok",
        tanggal: "10 September 2025",
        nominal: 99000,
        bukti: "/storage/img/bukti_transfer/sample1.jpg",
    },
    {
        nama: "Member Demo",
        jenis: "Wajib",
        tanggal: "19 September 2025",
        nominal: 199000,
        bukti: "/storage/img/bukti_transfer/sample2.jpg",
    },
    {
        nama: "Member Demo",
        jenis: "Pokok",
        tanggal: "10 September 2025",
        nominal: 799000,
        bukti: "/storage/img/bukti_transfer/sample1.jpg",
    },
    {
        nama: "Member Demo",
        jenis: "Wajib",
        tanggal: "19 September 2025",
        nominal: 100000,
        bukti: "/storage/img/bukti_transfer/sample2.jpg",
    },
    {
        nama: "Member Demo",
        jenis: "Pokok",
        tanggal: "10 September 2025",
        nominal: 150000,
        bukti: "/storage/img/bukti_transfer/sample1.jpg",
    },
    {
        nama: "Member Demo",
        jenis: "Wajib",
        tanggal: "19 September 2025",
        nominal: 9999999999,
        bukti: "/storage/img/bukti_transfer/sample2.jpg",
    },
    ]);

    function previewImage(src) {
    ImagePreview([src]);
    }
</script>

<template>
    <Head title="Verifikasi Simpanan" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Verifikasi Simpanan
            </h2>
        </template>

        <div>
            <div
            class="tw-bg-orange-500 tw-text-white tw-grid tw-grid-cols-7 tw-py-3 tw-px-6 tw-font-semibold"
            >
                <div>No.</div>
                <div>Nama Member</div>
                <div>Jenis Simpanan</div>
                <div>Nominal</div>
                <div>Tanggal Request</div>
                <div>Bukti Transfer</div>
                <div class="tw-text-center">Aksi</div>
            </div>

            <div
                v-for="(trx, index) in transactions"
                :key="trx.id"
                class="tw-grid tw-grid-cols-7 tw-items-center tw-py-4 tw-px-6 tw-border-b tw-border-gray-200"
            >
                <div class="tw-font-medium">{{ index + 1 }}.</div>
                <div>{{ trx.nama }}</div>
                <div>{{ trx.jenis }}</div>
                <div>Rp {{ trx.nominal }}</div>
                <div>{{ trx.tanggal }}</div>
                

                <div>
                <van-image
                    width="60"
                    height="60"
                    fit="cover"
                    round
                    :src="trx.bukti"
                    @click="previewImage(trx.bukti)"
                    class="tw-cursor-pointer tw-border tw-rounded-lg"
                />
                </div>

                <div class="tw-flex tw-gap-2 tw-justify-center">
                <van-button type="success" size="small" @click="approve(trx.id)" class="hover:tw-green-100">Terima</van-button>
                <van-button type="danger" size="small" @click="reject(trx.id)" class="hover:tw-red-100">Tolak</van-button>
                </div>
            </div>

            
        </div>
    </AuthenticatedLayout>
</template>
