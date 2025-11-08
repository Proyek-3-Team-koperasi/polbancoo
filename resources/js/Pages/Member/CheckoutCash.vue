<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    cartItems: {
        type: Array,
        default: () => [],
    },
    pointsPerRupiah: {
        type: Number,
        default: 100000,
    },
    estimatedPoints: {
        type: Number,
        default: 0,
    },
    totalAmount: {
        type: Number,
        default: 0,
    },
    paymentMethod: {
        type: String,
        default: "cash",
    },
});

const totalPembayaran = computed(() => {
    if (props.totalAmount) return props.totalAmount;
    return props.cartItems.reduce(
        (total, item) => total + item.price * item.quantity,
        0,
    );
});

const poinReward = computed(() => {
    if (props.estimatedPoints) return props.estimatedPoints;
    if (!props.cartItems.length) return 0;
    return Math.floor(totalPembayaran.value / props.pointsPerRupiah);
});

function formatCurrency(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(value || 0);
}
</script>

<template>
    <Head title="Checkout Tunai" />

    <AuthenticatedLayout>
        <div
            class="tw-max-w-3xl tw-mx-auto tw-my-10 tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6 tw-space-y-6"
        >
            <div>
                <p class="tw-text-sm tw-uppercase tw-text-gray-500">
                    Metode Dipilih
                </p>
                <h1 class="tw-text-2xl tw-font-semibold">
                    Pembayaran Tunai
                </h1>
            </div>

            <section class="tw-space-y-3">
                <h2 class="tw-text-lg tw-font-semibold">Ringkasan Produk</h2>
                <p
                    v-if="!cartItems.length"
                    class="tw-text-sm tw-text-gray-500 tw-border tw-border-dashed tw-rounded-lg tw-p-4"
                >
                    Belum ada data mock keranjang. Kembali ke halaman utama
                    untuk memilih produk.
                </p>
                <div v-else class="tw-space-y-3">
                    <article
                        v-for="item in cartItems"
                        :key="item.id"
                        class="tw-flex tw-items-center tw-justify-between tw-border tw-rounded-lg tw-p-3"
                    >
                        <div>
                            <p class="tw-font-medium">{{ item.name }}</p>
                            <p class="tw-text-xs tw-text-gray-500">
                                {{ item.quantity }} x
                                {{ formatCurrency(item.price) }}
                            </p>
                        </div>
                        <span class="tw-font-semibold">
                            {{ formatCurrency(item.price * item.quantity) }}
                        </span>
                    </article>
                </div>
            </section>

            <section class="tw-space-y-2 tw-border tw-rounded-xl tw-p-4 tw-bg-orange-50">
                <div class="tw-flex tw-justify-between tw-text-sm">
                    <span>Total Pembayaran</span>
                    <span class="tw-font-semibold">
                        {{ formatCurrency(totalPembayaran) }}
                    </span>
                </div>
                <div class="tw-flex tw-justify-between tw-text-sm tw-text-primary">
                    <span>Estimasi Poin Loyalitas</span>
                    <span class="tw-font-semibold">
                        {{ poinReward.toLocaleString("id-ID") }} poin
                    </span>
                </div>
            </section>

            <p class="tw-text-sm tw-text-gray-600">
                Silakan lanjutkan ke loket koperasi dan tunjukkan ringkasan ini
                kepada petugas untuk menyelesaikan pembayaran tunai.
            </p>

            <div class="tw-flex tw-justify-between tw-gap-3">
                <button
                    type="button"
                    class="tw-w-1/2 tw-border tw-border-gray-300 tw-text-gray-700 tw-py-2 tw-rounded-md hover:tw-bg-gray-50 tw-transition"
                    @click="router.visit(route('member.checkout'))"
                >
                    Kembali
                </button>
                <button
                    type="button"
                    class="tw-flex-1 tw-bg-primary tw-text-white tw-py-2 tw-rounded-md tw-font-semibold hover:tw-bg-primary-dark tw-transition"
                >
                    Konfirmasi Pembayaran Tunai
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
