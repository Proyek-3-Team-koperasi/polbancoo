<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { showToast } from "vant";
import { Coins } from "lucide-vue-next";

const props = defineProps({
    cartItems: {
        type: Array,
        required: true,
        default: () => [
            {
                id: 1,
                name: "lorem ipsum dolor sit amet consectetur adipisicing elit",
                price: 100000,
                quantity: 222,
                imageUrl:
                    "https://images.unsplash.com/photo-1630563451961-ac2ff27616ab?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=687",
            },
            {
                id: 2,
                name: "Produk B",
                price: 150000,
                quantity: 1,
                imageUrl:
                    "https://images.unsplash.com/photo-1503602642458-232111445657?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=687",
            },
        ],
    },
    PointsPerRupiah: {
        type: Number,
        required: false,
        default: 100000,
    },
});

const paymentMethods = [
    { value: "cash", label: "Bayar Tunai" },
    { value: "credit", label: "Ajukan Cicilan" },
];

const paymentRouteMap = {
    cash: () => route("member.checkout.cash"),
    credit: () => route("member.checkout.credit"),
};

const selectedPaymentMethod = ref(paymentMethods[0].value);
const isNavigating = ref(false);

function formatCurrency(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(value);
}

function humanizeNumber(value) {
    if (value == null) return "";
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function calculatePoints() {
    let total = 0;
    props.cartItems.forEach((item) => {
        total += item.price * item.quantity;
    });
    return Math.floor(total / props.PointsPerRupiah);
}

function calculateTotalAmount() {
    let total = 0;
    props.cartItems.forEach((item) => {
        total += item.price * item.quantity;
    });
    return total;
}

function proceedToPayment() {
    const selectedMethod = selectedPaymentMethod.value;
    const destination = paymentRouteMap[selectedMethod];

    if (!destination) {
        showToast({
            message: "Metode ini belum tersedia. Silakan pilih opsi lain.",
            icon: "info",
            duration: 2500,
        });
        return;
    }

    isNavigating.value = true;
    router.visit(destination(), {
        onFinish: () => {
            isNavigating.value = false;
        },
    });
}
</script>

<template>
    <Head title="Keranjang Belanja" />

    <AuthenticatedLayout>
        <div class="tw-px-1 md:tw-px-64">
            <div
                class="tw-w-full tw-flex tw-flex-col tw-gap-6 md:tw-gap-9 tw-mt-6"
            >
                <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-4">
                    <div class="tw-text-center tw-hidden md:tw-block tw-mb-6">
                        <span class="tw-text-2xl tw-font-bold tw-text-primary"
                            >Checkout</span
                        >
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <div
                            v-for="item in cartItems"
                            :key="item.id"
                            class="tw-flex tw-flex-row tw-items-center tw-gap-1 tw-mb-0 hover:tw-bg-orange-200 tw-p-2 tw-rounded-md"
                        >
                            <img
                                :src="item.imageUrl"
                                alt=""
                                class="tw-w-12 tw-h-12 tw-object-cover tw-rounded-md"
                            />
                            <div
                                class="tw-flex tw-ms-2 tw-flex-row tw-flex-1 tw-justify-between tw-text-xs md:tw-text-base"
                            >
                                <div
                                    class="tw-font-semibold tw-flex tw-flex-col tw-w-16 md:tw-w-96"
                                >
                                    <span>{{ item.name }}</span>
                                    <span
                                        class="tw-text-gray-500 tw-text-[0.7rem] md:tw-text-xs"
                                    >
                                        {{ formatCurrency(item.price) }}
                                    </span>
                                </div>
                                <div>{{ humanizeNumber(item.quantity) }}x</div>
                                <div class="tw-text-end">
                                    {{
                                        formatCurrency(
                                            item.price * item.quantity,
                                        )
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="tw-border-t tw-pt-4 tw-mt-4 tw-flex tw-justify-between tw-text-sm md:tw-text-base"
                    >
                        <span class="tw-font-semibold">Total Belanja</span>
                        <span class="tw-font-semibold">
                            {{ formatCurrency(calculateTotalAmount()) }}
                        </span>
                    </div>

                    <div class="tw-mt-5">
                        <div
                            class="tw-mt-2 tw-text-sm tw-flex tw-flex-col md:tw-text-base tw-border tw-rounded-md tw-p-2 md:px-4 md:py-2"
                        >
                            <span
                                class="tw-text-primary tw-font-extrabold tw-text-sm md:tw-text-base tw-mb-2"
                                >Reward dalam transaksi ini</span
                            >
                            <div class="tw-flex tw-flex-row tw-items-center">
                                <Coins
                                    class="tw-text-primary tw-me-3 tw-h-5 tw-w-5 md:tw-h-6 md:tw-w-6"
                                />
                                <span
                                    class="tw-font-bold tw-text-base md:tw-text-lg"
                                >
                                    {{ humanizeNumber(calculatePoints()) }}
                                    Poin</span
                                >
                            </div>
                        </div>
                        <div class="tw-mt-2 tw-flex tw-gap-4 tw-flex-wrap">
                            <label
                                v-for="method in paymentMethods"
                                :key="method.value"
                                class="tw-flex tw-flex-1 tw-items-center tw-gap-2 tw-cursor-pointer tw-px-3 tw-py-2 tw-border tw-rounded-md tw-transition-colors hover:tw-bg-orange-100"
                                :class="
                                    selectedPaymentMethod === method.value
                                        ? 'tw-border-primary tw-bg-orange-50 tw-text-primary'
                                        : 'tw-border-gray-200 tw-text-gray-700'
                                "
                            >
                                <input
                                    type="radio"
                                    name="paymentMethod"
                                    class="tw-hidden"
                                    v-model="selectedPaymentMethod"
                                    :value="method.value"
                                />
                                <span>{{ method.label }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="tw-mt-4">
                        <button
                            type="button"
                            class="tw-w-full tw-bg-primary tw-text-white tw-py-2 tw-px-4 tw-rounded-md tw-text-center tw-font-semibold hover:tw-bg-primary-dark transition disabled:tw-opacity-60 disabled:tw-cursor-not-allowed"
                            :disabled="isNavigating"
                            @click="proceedToPayment"
                        >
                            {{
                                isNavigating
                                    ? "Memuat..."
                                    : "Lanjut ke Pembayaran"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
