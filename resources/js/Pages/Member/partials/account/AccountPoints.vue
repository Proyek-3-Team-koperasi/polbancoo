<script setup>
import { Coins } from "lucide-vue-next";

const Props = defineProps({
    last3Transactions: {
        type: Array,
        required: true,
        default: () => [
            {
                date: "11/11/25",
                description: "Pembelian Produk A",
                points: "+50 Poin",
            },
            {
                date: "01/11/25",
                description: "Pembelian Produk B",
                points: "+20 Poin",
            },
            {
                date: "01/11/25",
                description: "Akumulasi Tabungan",
                points: "+30 Poin",
            },
        ],
    },
    dateOfPointReset: {
        type: String,
        required: true,
        default: "14 Feb 2026",
    },
    userPoints: {
        type: Number,
        required: true,
        default: 100,
    },
});

function humanizeNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

const toPointHistory = "#";
</script>

<template>
    <h3 class="tw-text-base tw-font-semibold tw-text-slate-800">Point Saya</h3>
    <a href="">
        <div class="tw-mt-4 tw-rounded-md tw-border tw-flex tw-flex-col tw-p-4">
            <span class="tw-text-xs">Total Point Periode Ini</span>
            <div class="tw-flex tw-row tw-items-center tw-mt-1 tw-gap-1">
                <Coins class="tw-text-3xl tw-text-black" />
                <span class="tw-text-3xl tw-font-bold md:tw-font-extrabold">{{
                    humanizeNumber(userPoints)
                }}</span>
                <span
                    class="tw-text-3xl tw-font-normal tw-text-gray-500 tw-ms-3 tw-mb-1"
                    >&gt;</span
                >
            </div>
            <span class="tw-text-xs tw-mt-1 tw-text-gray-500">
                Periode Berikutnya: {{ dateOfPointReset }}
            </span>
        </div>
    </a>
    <div class="tw-flex tw-flex-col tw-mt-3 tw-text-xs">
        <div class="tw-flex tw-flex-row tw-justify-between tw-mt-3">
            <span class="tw-text-sm md:tw-text-base">History Poin</span>
            <a :href="toPointHistory" class="tw-underline">Lihat Detail &gt;</a>
        </div>
        <div class="tw-mt-2 tw-text-gray-600">
            <template
                v-for="(transaction, index) in last3Transactions"
                :key="index"
            >
                <a href="{{ transaction.href }}">
                    <div
                        class="tw-flex tw-items-start tw-gap-3 tw-mb-0 hover:tw-bg-orange-200 tw-p-2 tw-rounded-md"
                    >
                        <div class="tw-w-16 tw-text-xs">
                            {{ transaction.date }}
                        </div>
                        <div class="tw-flex-1 tw-text-xs">
                            {{ transaction.description }}
                        </div>
                        <div class="tw-text-xs tw-text-green-600">
                            {{ humanizeNumber(transaction.points) }}
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
</template>
