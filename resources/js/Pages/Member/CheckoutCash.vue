<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

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

const bankAccounts = [
    {
        label: "BRI - 1234 567 890",
        value: "bri",
        hint: "KopSy POLBAN",
    },
    {
        label: "BNI - 9876 543 210",
        value: "bni",
        hint: "KopSy POLBAN",
    },
    {
        label: "Mandiri - 1122 3344 5566",
        value: "mandiri",
        hint: "KopSy POLBAN",
    },
];

const selectedAccount = ref(bankAccounts[0].value);
const buktiTransfer = ref(null);

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

function handleFileChange(event) {
    const file = event.target.files?.[0];
    buktiTransfer.value = file ?? null;
}

function goBackToCheckout() {
    router.visit(route("member.checkout"));
}

function confirmCashPayment() {
    router.visit(route("member.checkout.success"));
}
</script>

<template>
    <Head title="Checkout Tunai" />

    <AuthenticatedLayout>
        <div
            class="tw-max-w-3xl tw-mx-auto tw-my-10 tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6 tw-space-y-6 md:tw-max-w-5xl md:tw-rounded-3xl md:tw-shadow-md md:tw-p-10 md:tw-space-y-10"
        >
            <div>
                <p
                    class="tw-text-sm tw-uppercase tw-text-gray-500 md:tw-text-base"
                >
                    Metode Dipilih
                </p>
                <h1 class="tw-text-2xl tw-font-semibold md:tw-text-3xl">
                    Pembayaran Tunai
                </h1>
            </div>

            <section class="tw-space-y-3 md:tw-space-y-5">
                <h2 class="tw-text-lg tw-font-semibold">Ringkasan Produk</h2>
                <p
                    v-if="!cartItems.length"
                    class="tw-text-sm tw-text-gray-500 tw-border tw-border-dashed tw-rounded-lg tw-p-4 md:tw-text-base"
                >
                    Belum ada data mock keranjang. Kembali ke halaman utama
                    untuk memilih produk.
                </p>
                <div v-else class="tw-space-y-3 md:tw-space-y-4">
                    <article
                        v-for="item in cartItems"
                        :key="item.id"
                        class="tw-flex tw-items-center tw-justify-between tw-border tw-rounded-lg tw-p-3 md:tw-p-5 md:tw-rounded-2xl md:tw-shadow-sm"
                    >
                        <div>
                            <p class="tw-font-medium md:tw-text-lg">
                                {{ item.name }}
                            </p>
                            <p
                                class="tw-text-xs tw-text-gray-500 md:tw-text-sm"
                            >
                                {{ item.quantity }} x
                                {{ formatCurrency(item.price) }}
                            </p>
                        </div>
                        <span class="tw-font-semibold md:tw-text-lg">
                            {{ formatCurrency(item.price * item.quantity) }}
                        </span>
                    </article>
                </div>
            </section>

            <section
                class="tw-space-y-2 tw-border tw-rounded-xl tw-p-4 tw-bg-orange-50 md:tw-space-y-3 md:tw-rounded-2xl md:tw-p-6"
            >
                <div
                    class="tw-flex tw-justify-between tw-text-sm md:tw-text-base"
                >
                    <span>Total Pembayaran</span>
                    <span class="tw-font-semibold">
                        {{ formatCurrency(totalPembayaran) }}
                    </span>
                </div>
                <div
                    class="tw-flex tw-justify-between tw-text-sm tw-text-primary md:tw-text-base"
                >
                    <span>Estimasi Poin Loyalitas</span>
                    <span class="tw-font-semibold">
                        {{ poinReward.toLocaleString("id-ID") }} poin
                    </span>
                </div>
            </section>

            <section class="tw-space-y-3 md:tw-space-y-4">
                <div>
                    <h3 class="tw-text-base tw-font-semibold md:tw-text-lg">
                        Pilih Rekening Koperasi
                    </h3>
                    <p class="tw-text-xs tw-text-gray-500 md:tw-text-sm">
                        Transfer ke salah satu rekening resmi berikut.
                    </p>
                </div>
                <div class="tw-space-y-2">
                    <label
                        v-for="account in bankAccounts"
                        :key="account.value"
                        class="tw-flex tw-items-start tw-gap-3 tw-border tw-rounded-xl tw-p-3 tw-cursor-pointer hover:tw-border-primary md:tw-p-4"
                        :class="
                            selectedAccount === account.value
                                ? 'tw-border-primary tw-bg-orange-50'
                                : 'tw-border-gray-200'
                        "
                    >
                        <input
                            type="radio"
                            class="tw-sr-only"
                            :value="account.value"
                            v-model="selectedAccount"
                            name="rekening"
                        />
                        <span
                            class="tw-flex tw-items-center tw-justify-center tw-w-5 tw-h-5 tw-rounded-full tw-border tw-transition"
                            :class="
                                selectedAccount === account.value
                                    ? 'tw-border-primary tw-bg-primary/10 tw-ring-2 tw-ring-primary/40'
                                    : 'tw-border-gray-300 tw-bg-white'
                            "
                        >
                            <span
                                v-if="selectedAccount === account.value"
                                class="tw-w-2 tw-h-2 tw-rounded-full tw-bg-primary"
                            ></span>
                        </span>
                        <div class="tw-flex-1">
                            <p
                                class="tw-font-semibold tw-text-sm md:tw-text-base"
                            >
                                {{ account.label }}
                            </p>
                            <p
                                class="tw-text-xs tw-text-gray-500 md:tw-text-sm"
                            >
                                {{ account.hint }}
                            </p>
                        </div>
                    </label>
                </div>
            </section>

            <section class="tw-space-y-2 md:tw-space-y-4">
                <h3 class="tw-text-base tw-font-semibold md:tw-text-lg">
                    Upload Bukti Pembayaran
                </h3>
                <p class="tw-text-xs tw-text-gray-500 md:tw-text-sm">
                    File JPG/PNG/PDF maksimal 5MB.
                </p>
                <label
                    class="tw-flex tw-items-center tw-justify-between tw-border tw-rounded-xl tw-px-4 tw-py-3 tw-cursor-pointer tw-transition tw-bg-primary/5 tw-border-primary/40 hover:tw-bg-primary/10 tw-gap-3 tw-flex-wrap md:tw-flex-nowrap md:tw-px-6 md:tw-py-4"
                >
                    <div class="tw-flex tw-flex-col tw-flex-1">
                        <span
                            class="tw-text-sm tw-font-semibold tw-text-primary md:tw-text-base"
                        >
                            Drop atau pilih bukti transfer
                        </span>
                    </div>
                    <span class="tw-flex tw-items-center tw-gap-2 md:tw-gap-3">
                        <span
                            class="tw-text-xs tw-font-semibold tw-text-primary tw-uppercase tw-border tw-border-primary tw-rounded-md tw-px-3 tw-py-1 md:tw-text-sm md:tw-px-4 md:tw-py-1.5"
                        >
                            Browse
                        </span>
                    </span>
                    <input
                        type="file"
                        accept="image/*,application/pdf"
                        class="tw-hidden"
                        @change="handleFileChange"
                    />
                </label>
                <p
                    v-if="buktiTransfer"
                    class="tw-text-xs tw-text-green-600 tw-font-medium md:tw-text-sm"
                >
                    File terpilih: {{ buktiTransfer.name }}
                </p>
            </section>

            <div class="tw-flex tw-justify-between tw-gap-3 md:tw-gap-5">
                <button
                    type="button"
                    class="tw-w-1/2 tw-border tw-border-gray-300 tw-text-gray-700 tw-py-2 tw-rounded-md hover:tw-bg-gray-50 tw-transition md:tw-text-base md:tw-py-3"
                    @click="goBackToCheckout"
                >
                    Kembali
                </button>
                <button
                    type="button"
                    class="tw-flex-1 tw-bg-primary tw-text-white tw-py-2 tw-rounded-md tw-font-semibold hover:tw-bg-primary-dark tw-transition md:tw-text-base md:tw-py-3"
                    @click="confirmCashPayment"
                >
                    Konfirmasi Pembayaran Tunai
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
