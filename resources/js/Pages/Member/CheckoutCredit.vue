<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { showToast } from "vant";

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
        default: "credit",
    },
});

const tenorShortcuts = [1, 3, 6, 12];
const tenor = ref("");
const supportingDocs = ref([]);

const marginStates = ref([]);

watch(
    () => props.cartItems,
    (items) => {
        marginStates.value = (items || []).map((item, index) => {
            const state = createMarginState(item, index);
            state.inputValue = formatNumberWithDots(state.currentMargin);
            return state;
        });
    },
    { immediate: true, deep: true },
);

const totalPokok = computed(() => {
    if (props.totalAmount) return props.totalAmount;
    return props.cartItems.reduce(
        (sum, item) => sum + item.price * (item.quantity ?? 1),
        0,
    );
});

const totalPembiayaan = totalPokok;

const poinReward = computed(() => {
    if (props.estimatedPoints) return props.estimatedPoints;
    if (!props.cartItems.length) return 0;
    return Math.floor(totalPokok.value / props.pointsPerRupiah);
});

const totalMargin = computed(() =>
    marginStates.value.reduce(
        (sum, state) => sum + (Number(state.currentMargin) || 0),
        0,
    ),
);

const totalDenganMargin = computed(() => totalPokok.value + totalMargin.value);

const numericTenor = computed(() => {
    const parsed = parseInt(tenor.value, 10);
    return Number.isFinite(parsed) && parsed > 0 ? parsed : null;
});

const monthlyInstallment = computed(() => {
    if (!numericTenor.value) return 0;
    return Math.ceil(totalDenganMargin.value / numericTenor.value);
});

const monthlyInstallmentRibu = computed(() => {
    if (!monthlyInstallment.value) return 0;
    return Math.ceil(monthlyInstallment.value / 1000);
});

const invoiceDate = new Date();
const firstInstallmentDate = new Date(invoiceDate);
firstInstallmentDate.setMonth(firstInstallmentDate.getMonth() + 1);

const invoiceDateLabel = formatDate(invoiceDate);
const firstPaymentDateLabel = formatDate(firstInstallmentDate);

const invalidMargins = computed(() =>
    marginStates.value.filter(
        (state) =>
            Number(state.currentMargin || 0) < Number(state.minMargin || 0),
    ),
);

function createMarginState(item, index) {
    const defaultMargin = computeDefaultMargin(item);
    return {
        key: item.id ?? `item-${index}`,
        name: item.name || `Produk ${index + 1}`,
        defaultMargin,
        minMargin: Math.ceil(defaultMargin * 0.5),
        currentMargin: defaultMargin,
        inputValue: formatNumberWithDots(defaultMargin),
    };
}

function computeDefaultMargin(item) {
    const base =
        Number(item.marginCredits ?? item.marginCredit ?? item.margin ?? 0) ||
        0;
    const quantity = Number(item.quantity ?? 1);
    const safeQty = Number.isFinite(quantity) && quantity > 0 ? quantity : 1;
    return base > 0 ? base * safeQty : 0;
}

function selectTenor(option) {
    tenor.value = option.toString();
}

function resetMarginFor(key) {
    const target = marginStates.value.find((state) => state.key === key);
    if (target) {
        target.currentMargin = target.defaultMargin;
        target.inputValue = formatNumberWithDots(target.defaultMargin);
    }
}

function handleMarginInput(state, rawValue) {
    if (!state) return;
    const normalized = rawValue.replace(/[^\d]/g, "");
    const numeric = normalized ? parseInt(normalized, 10) : 0;
    state.currentMargin = numeric;
    state.inputValue = normalized ? formatNumberWithDots(numeric) : "";
}

function sanitizeMargin(state) {
    if (!state) return;
    const numeric = Number(state.currentMargin);
    if (!Number.isFinite(numeric) || numeric < 0) {
        state.currentMargin = 0;
    }
    state.inputValue = formatNumberWithDots(state.currentMargin);
}

function submitApplication() {
    if (!numericTenor.value) {
        showToast({
            message: "Tentukan tenor cicilan terlebih dahulu.",
            icon: "fail",
            duration: 2500,
        });
        return;
    }

    if (invalidMargins.value.length) {
        const firstInvalid = invalidMargins.value[0];
        showToast({
            message: `Margin ${firstInvalid.name} minimal ${formatCurrency(firstInvalid.minMargin)} (50% dari default).`,
            icon: "fail",
            duration: 3200,
        });
        return;
    }

    showToast({
        message: `Pengajuan ${numericTenor.value} bulan dikirim. Cicilan ≈ ${formatCurrency(
            monthlyInstallment.value,
        )}/bulan.`,
        icon: "success",
        duration: 3000,
    });
}

function formatCurrency(value) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(value || 0);
}

function formatDate(date) {
    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(date);
}

function formatNumberWithDots(value) {
    const numeric = Number(value) || 0;
    return numeric.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function handleSupportingDocs(event) {
    const files = Array.from(event.target.files || []);
    supportingDocs.value = files.slice(0, 5);
}
</script>

<template>
    <Head title="Checkout Kredit" />

    <AuthenticatedLayout>
        <div
            class="tw-max-w-3xl tw-mx-auto tw-my-10 tw-bg-white tw-rounded-xl tw-shadow-xs tw-p-6 tw-space-y-6 md:tw-max-w-5xl md:tw-rounded-3xl md:tw-shadow-md md:tw-p-10 md:tw-space-y-10"
        >
            <div>
                <p
                    class="tw-text-xs tw-uppercase tw-text-gray-500 md:tw-text-sm"
                >
                    Metode Dipilih
                </p>
                <h1 class="tw-text-2xl tw-font-semibold md:tw-text-3xl">
                    Pengajuan Cicilan
                </h1>
                <p class="tw-text-xs tw-text-gray-600 tw-mt-2 md:tw-text-base">
                    Pengajuan Anda akan direview oleh petugas koperasi sebelum
                    pencairan barang.
                </p>
            </div>

            <section class="tw-space-y-3 md:tw-space-y-5">
                <h2 class="tw-text-lg tw-font-semibold">Ringkasan Produk</h2>
                <p
                    v-if="!cartItems.length"
                    class="tw-text-xs tw-text-gray-500 tw-border tw-border-dashed tw-rounded-lg tw-p-4 md:tw-text-sm"
                >
                    Belum ada data mock keranjang. Kembali ke halaman utama
                    untuk memilih produk.
                </p>
                <div v-else class="tw-space-y-4 md:tw-space-y-6">
                    <article
                        v-for="(item, index) in cartItems"
                        :key="marginStates[index]?.key ?? item.id ?? index"
                        class="tw-border tw-rounded-2xl tw-p-4 tw-space-y-4 md:tw-space-y-0 md:tw-grid md:tw-grid-cols-12 md:tw-gap-4 tw-bg-white tw-shadow-xs md:tw-p-6 md:tw-rounded-3xl md:tw-shadow-md"
                    >
                        <div
                            class="tw-flex tw-flex-col tw-gap-1 md:tw-col-span-5 md:tw-gap-2"
                        >
                            <p
                                class="tw-font-semibold tw-text-sm tw-truncate md:tw-text-base"
                            >
                                {{ item.name }}
                            </p>
                            <p
                                class="tw-text-xs tw-text-gray-500 md:tw-text-sm"
                            >
                                {{ item.quantity ?? 1 }} x
                                {{ formatCurrency(item.price) }}
                            </p>
                            <p
                                class="tw-font-semibold tw-text-xs md:tw-text-sm"
                            >
                                Total Produk:
                                {{
                                    formatCurrency(
                                        item.price * (item.quantity ?? 1),
                                    )
                                }}
                            </p>
                        </div>

                        <div
                            v-if="marginStates[index]"
                            class="tw-rounded-xl tw-bg-orange-50 tw-p-3 tw-space-y-2 md:tw-col-span-7 md:tw-p-4"
                        >
                            <div
                                class="tw-flex tw-justify-between tw-text-xs md:tw-text-sm"
                            >
                                <span class="tw-font-semibold"
                                    >Margin Kredit Produk</span
                                >
                                <button
                                    type="button"
                                    class="tw-text-xs tw-text-primary hover:tw-underline"
                                    @click="
                                        resetMarginFor(marginStates[index].key)
                                    "
                                >
                                    Reset default
                                </button>
                            </div>
                            <p
                                class="tw-text-xs tw-text-gray-500 md:tw-text-sm"
                            >
                                Default:
                                {{
                                    formatCurrency(
                                        marginStates[index].defaultMargin,
                                    )
                                }}
                                • Batas banding:
                                {{
                                    formatCurrency(
                                        marginStates[index].minMargin,
                                    )
                                }}
                                (50%).
                            </p>
                            <input
                                type="text"
                                inputmode="numeric"
                                class="tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-primary md:tw-py-3"
                                placeholder="Contoh: 500.000"
                                :value="marginStates[index].inputValue"
                                @input="
                                    handleMarginInput(
                                        marginStates[index],
                                        $event.target.value,
                                    )
                                "
                                @blur="sanitizeMargin(marginStates[index])"
                            />
                            <p
                                v-if="
                                    marginStates[index].currentMargin <
                                    marginStates[index].minMargin
                                "
                                class="tw-text-xs tw-text-red-600 tw-font-medium md:tw-text-sm"
                            >
                                Minimal
                                {{
                                    formatCurrency(
                                        marginStates[index].minMargin,
                                    )
                                }}
                                per produk. Boleh tambah untuk sedekah, tapi
                                tidak boleh dikurangi di bawah batas.
                            </p>
                        </div>
                    </article>
                </div>
            </section>

            <div
                class="tw-grid tw-gap-4 md:tw-grid-cols-2 tw-items-start md:tw-gap-6"
            >
                <section
                    class="tw-space-y-2 tw-border tw-rounded-2xl tw-p-4 tw-bg-white tw-shadow-xs md:tw-h-full md:tw-p-6 md:tw-space-y-3"
                >
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <h3 class="tw-text-sm tw-font-semibold md:tw-text-base">
                            Tenor Cicilan (bulan)
                        </h3>
                    </div>
                    <input
                        type="number"
                        min="1"
                        inputmode="numeric"
                        class="tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-primary md:tw-py-3 md:tw-text-base"
                        placeholder="Masukkan tenor cicilan"
                        v-model="tenor"
                    />
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <label
                            v-for="option in tenorShortcuts"
                            :key="option"
                            class="tw-flex tw-items-center tw-gap-2 tw-border tw-rounded-full tw-px-4 tw-py-1 tw-text-xs tw-cursor-pointer hover:tw-border-primary tw-transition md:tw-text-sm md:tw-px-5 md:tw-py-1.5"
                            :class="
                                numericTenor === option
                                    ? 'tw-bg-primary tw-text-white tw-border-primary'
                                    : 'tw-border-gray-300 tw-text-gray-700'
                            "
                        >
                            <input
                                type="radio"
                                class="tw-hidden"
                                :value="option"
                                :checked="numericTenor === option"
                                @change="selectTenor(option)"
                            />
                            <span>{{ option }} bln</span>
                        </label>
                    </div>
                </section>

                <section
                    class="tw-space-y-2 tw-border tw-rounded-2xl tw-p-4 tw-bg-gradient-to-br tw-from-blue-50 tw-to-white tw-shadow-xs md:tw-h-full md:tw-p-6 md:tw-space-y-3"
                >
                    <div
                        class="tw-flex tw-justify-between tw-text-xs tw-text-primary md:tw-text-sm"
                    >
                        <span>Estimasi Poin Loyalitas</span>
                        <span class="tw-font-semibold">
                            {{ poinReward.toLocaleString("id-ID") }} poin
                        </span>
                    </div>
                    <div
                        class="tw-flex tw-justify-between md:tw-text-base tw-text-xs"
                    >
                        <span>Total Pengajuan</span>
                        <span class="tw-font-semibold">
                            {{ formatCurrency(totalPembiayaan) }}
                        </span>
                    </div>
                    <div
                        class="tw-flex tw-justify-between tw-text-xs md:tw-text-sm"
                    >
                        <span>Margin Final</span>
                        <span class="tw-font-semibold">
                            {{ formatCurrency(totalMargin) }}
                        </span>
                    </div>
                    <div
                        class="tw-flex tw-justify-between tw-text-xs md:tw-text-sm"
                    >
                        <span>Total + Margin</span>
                        <span class="tw-font-semibold">
                            {{ formatCurrency(totalDenganMargin) }}
                        </span>
                    </div>
                    <div
                        class="tw-border-t tw-pt-2 tw-mt-2 tw-flex tw-flex-row tw-justify-between md:tw-flex-col md:tw-gap-1"
                    >
                        <p class="tw-text-xs tw-font-semibold md:tw-text-sm">
                            Estimasi Cicilan
                        </p>
                        <p class="tw-text-sm tw-font-bold md:tw-text-lg">
                            {{
                                numericTenor
                                    ? formatCurrency(monthlyInstallment)
                                    : "Isi tenor untuk melihat simulasi"
                            }}
                            <span class="tw-text-[0.7rem]">/bln</span>
                        </p>
                    </div>
                    <div class="tw-text-xs md:tw-text-sm">
                        <div
                            class="tw-flex tw-justify-between md:tw-items-center"
                        >
                            <span>Tagihan diterbitkan</span>
                            <span class="tw-font-semibold">{{
                                invoiceDateLabel
                            }}</span>
                        </div>
                        <div
                            class="tw-flex tw-justify-between md:tw-items-center"
                        >
                            <span>Pembayaran pertama</span>
                            <span class="tw-font-semibold">
                                {{ firstPaymentDateLabel }}
                            </span>
                        </div>
                        <div
                            class="tw-flex tw-justify-between md:tw-items-center"
                        >
                            <span>Pembayaran terakhir</span>
                            <span
                                class="tw-font-semibold"
                                v-if="numericTenor"
                                >{{
                                    new Date(
                                        new Date().setMonth(
                                            new Date().getMonth() +
                                                numericTenor,
                                        ),
                                    ).toLocaleDateString("id-ID", {
                                        day: "numeric",
                                        month: "long",
                                        year: "numeric",
                                    })
                                }}</span
                            >
                            <span class="tw-font-semibold" v-else>-</span>
                        </div>
                    </div>
                </section>
            </div>

            <section class="tw-space-y-3 md:tw-space-y-4">
                <div>
                    <h3 class="tw-text-base tw-font-semibold md:tw-text-lg">
                        Dokumen Pendukung (Opsional)
                    </h3>
                    <p class="tw-text-xs tw-text-gray-500 md:tw-text-sm">
                        Tambahkan rekening koran atau slip gaji untuk
                        mempercepat proses verifikasi. Maksimal 5 file.
                    </p>
                </div>
                <label
                    class="tw-flex tw-items-center tw-justify-between tw-border tw-rounded-2xl tw-px-4 tw-py-3 tw-cursor-pointer tw-transition tw-bg-primary/5 tw-border-primary/40 hover:tw-bg-primary/10 tw-gap-3 tw-flex-wrap md:tw-flex-nowrap md:tw-px-6 md:tw-py-4"
                >
                    <div class="tw-flex tw-flex-col tw-flex-1">
                        <span
                            class="tw-text-sm tw-font-semibold tw-text-primary md:tw-text-base"
                        >
                            Drop atau pilih dokumen pendukung
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
                        multiple
                        class="tw-hidden"
                        @change="handleSupportingDocs"
                    />
                </label>
                <div v-if="supportingDocs.length" class="tw-space-y-1">
                    <p class="tw-text-xs tw-text-gray-500 md:tw-text-sm">
                        File terpilih:
                    </p>
                    <ul
                        class="tw-text-xs tw-text-green-600 tw-font-medium md:tw-text-sm tw-list-disc tw-list-inside tw-space-y-1"
                    >
                        <li v-for="doc in supportingDocs" :key="doc.name">
                            {{ doc.name }}
                        </li>
                    </ul>
                </div>
            </section>

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
                    @click="submitApplication"
                >
                    Kirim Pengajuan
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
