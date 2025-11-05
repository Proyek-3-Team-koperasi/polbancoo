<script setup>
const { numberOfTransaction, last5Transactions } = defineProps({
    numberOfTransaction: {
        type: Number,
        default: 5,
    },

    last5Transactions: {
        type: Array,
        default: () => [
            {
                date: "01/11/25",
                description: "Produk A",
                nominal: 50000,
            },
            {
                date: "02/11/25",
                description: "Produk A",
                nominal: 55000,
            },
            {
                date: "03/11/25",
                description: "Produk A",
                nominal: 100000,
            },
        ],
    },
});

const humanizeNumber = (value) => {
    if (value == null) return "";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(value);
};
</script>

<template>
    <div class="tw-flex tw-flex-row tw-justify-between tw-items-center">
        <h3 class="tw-text-base tw-font-semibold tw-text-slate-800">
            History Transaksi Terakhir
        </h3>
        <a href="" class="tw-text-xs tw-text-gray-500 tw-underline"
            >Lihat Detail &gt;</a
        >
    </div>
    <div class="tw-mt-2 tw-text-gray-600">
        <div
            v-for="(transaction, index) in last5Transactions"
            :key="index"
            class="tw-flex tw-items-start tw-gap-3 tw-mb-3"
        >
            <div class="tw-w-16 tw-text-xs">{{ transaction.date }}</div>
            <div class="tw-flex-1 tw-text-xs">
                {{ transaction.description }}
            </div>
            <div class="tw-text-xs">
                {{ humanizeNumber(transaction.nominal) }}
            </div>
        </div>
    </div>
</template>
