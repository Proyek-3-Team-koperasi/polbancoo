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
                href: "#",
            },
            {
                date: "02/11/25",
                description: "Produk A",
                nominal: 55000,
                href: "#",
            },
            {
                date: "03/11/25",
                description: "Produk A",
                nominal: 100000,
                href: "#",
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

const toKeuangan = route("member.finances");
</script>

<template>
    <div class="tw-flex tw-flex-row tw-justify-between tw-items-center tw-mb-2">
        <h3
            class="tw-text-sm md:tw-text-base tw-font-semibold tw-text-slate-800"
        >
            History Transaksi Terakhir
        </h3>
        <a :href="toKeuangan" class="tw-text-xs tw-text-gray-500 tw-underline"
            >Lihat Detail &gt;</a
        >
    </div>
    <div class="tw-mt-2 tw-text-gray-600">
        <template
            v-for="(transaction, index) in last5Transactions"
            :key="index"
        >
            <a href="{{ transaction.href }}">
                <div
                    class="tw-flex tw-items-start tw-gap-3 tw-mb-0 hover:tw-bg-orange-200 tw-p-2 tw-rounded-md"
                >
                    <div class="tw-w-16 tw-text-xs">{{ transaction.date }}</div>
                    <div class="tw-flex-1 tw-text-xs">
                        {{ transaction.description }}
                    </div>
                    <div class="tw-text-xs">
                        {{ humanizeNumber(transaction.nominal) }}
                    </div>
                </div>
            </a>
        </template>
    </div>
</template>
