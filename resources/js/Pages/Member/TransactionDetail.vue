<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";
import dayjs from "dayjs";

const props = defineProps({
    transaction: Object,
});

const goBack = () => {
    router.visit(route("member.status"));
};

// Hitung periode tenor
const tenorPeriod = computed(() => {
    if (!props.transaction?.tenor || !props.transaction?.approval_date)
        return null;
    const start = dayjs(props.transaction.approval_date);
    const end = start.add(props.transaction.tenor, "month");
    return {
        start: start.format("DD MMM YYYY"),
        end: end.format("DD MMM YYYY"),
    };
});
</script>

<template>
    <Head title="Detail Transaksi" />

    <AuthenticatedLayout>
        <template #header>
            <van-nav-bar
                title="Detail Transaksi"
                left-text="Kembali"
                left-arrow
                @click-left="goBack"
            />
        </template>

        <!-- WRAPPER -->
        <div
            class="tw-p-6 tw-bg-gray-50 tw-min-h-screen tw-text-gray-800 tw-space-y-6"
        >
            <!-- CARD DETAIL -->
            <div
                class="tw-bg-white tw-rounded-2xl tw-shadow-lg tw-flex tw-flex-col md:tw-flex-row tw-gap-6 tw-p-6 tw-text-gray-800"
            >
                <!-- FOTO PRODUK -->
                <div class="tw-flex tw-justify-center md:tw-justify-start">
                    <img
                        :src="
                            transaction?.order?.product_image ||
                            '/img/no-image.png'
                        "
                        alt="Produk"
                        class="tw-w-64 tw-h-64 tw-object-cover tw-rounded-2xl tw-bg-white tw-shadow-md"
                    />
                </div>

                <!-- INFORMASI PRODUK -->
                <div class="tw-flex-1 tw-space-y-3 tw-relative">
                    <!-- STATUS BADGE -->
                    <span
                        class="tw-inline-block tw-border tw-border-orange-500 tw-text-orange-500 tw-bg-white tw-font-semibold tw-rounded-full tw-px-4 tw-py-1 tw-text-sm tw-mb-1"
                    >
                        {{ transaction.status }}
                    </span>

                    <!-- KODE TRANSAKSI -->
                    <p class="tw-text-gray-500 tw-text-sm">
                        Kode Transaksi: #{{ transaction.id }}
                    </p>

                    <!-- NAMA PRODUK -->
                    <h1
                        class="tw-text-2xl tw-font-bold tw-text-blue-900 tw-leading-snug"
                    >
                        {{
                            transaction?.order?.product_name ??
                            "Nama Produk Tidak Tersedia"
                        }}
                    </h1>

                    <!-- HARGA -->
                    <p class="tw-text-orange-600 tw-font-bold tw-text-lg">
                        Rp
                        {{
                            Number(
                                transaction.selling_price_total,
                            ).toLocaleString("id-ID")
                        }}
                    </p>

                    <!-- DETAIL INFORMASI -->
                    <div class="tw-space-y-1.5 tw-text-sm tw-mt-2">
                        <p>
                            <strong>Jenis Pembayaran:</strong>
                            {{
                                transaction.order?.payment_method ??
                                "Tidak diketahui"
                            }}
                        </p>
                        <p>
                            <strong>Tanggal Transaksi:</strong>
                            {{
                                new Date(
                                    transaction.created_at,
                                ).toLocaleDateString("id-ID")
                            }}
                        </p>

                        <!-- TAMPIL HANYA UNTUK CICILAN -->
                        <template
                            v-if="
                                transaction.order?.payment_method ===
                                'Murabahah'
                            "
                        >
                            <p v-if="transaction.tenor">
                                <strong>Tenor:</strong>
                                {{ transaction.tenor }} bulan
                            </p>
                            <p v-if="tenorPeriod">
                                <strong>Periode Cicilan:</strong>
                                {{ tenorPeriod.start }} – {{ tenorPeriod.end }}
                            </p>
                            <p v-if="transaction.monthly_installment">
                                <strong>Angsuran per Bulan:</strong>
                                Rp
                                {{
                                    Number(
                                        transaction.monthly_installment,
                                    ).toLocaleString("id-ID")
                                }}
                            </p>
                        </template>
                    </div>
                </div>
            </div>

            <!-- TOMBOL KEMBALI -->
            <div class="tw-text-center tw-mt-8">
                <van-button
                    round
                    size="large"
                    plain
                    class="tw-font-semibold tw-border-orange-500 tw-text-orange-500 hover:tw-bg-orange-500 hover:tw-text-white tw-shadow-md tw-transition-all tw-duration-300"
                    @click="goBack"
                >
                    Kembali ke Daftar Transaksi
                </van-button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
