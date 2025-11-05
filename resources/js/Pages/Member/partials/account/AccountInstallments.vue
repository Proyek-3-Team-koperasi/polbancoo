<script setup>
import { toRefs } from "vue";

const props = defineProps({
    totalInstallments: {
        type: Number,
        required: true,
        default: 1,
    },
    totalAmountDue: {
        type: Number,
        required: true,
        default: 999000,
    },
    dueDate: {
        type: String,
        required: true,
        default: "18 Nov 2025",
    },
});

const { totalInstallments, totalAmountDue, dueDate } = toRefs(props);
function humanizeNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>

<template>
    <div class="tw-flex tw-flex-row tw-justify-between tw-items-center">
        <h3 class="tw-text-base tw-font-semibold tw-text-slate-800">
            Cicilan Saya
        </h3>
        <span class="tw-text-xs tw-text-gray-500"
            >({{ totalInstallments }} Cicilan berlangsung)</span
        >
    </div>
    <a href="">
        <div class="tw-mt-4 tw-rounded-md tw-border tw-flex tw-flex-col tw-p-4">
            <span class="tw-text-xs">Total Nominal Harus Dibayar</span>
            <div class="tw-flex tw-row tw-items-center tw-mt-1">
                <span class="tw-text-3xl tw-font-extrabold"
                    >Rp{{ humanizeNumber(totalAmountDue) }}</span
                >
                <span
                    class="tw-text-3xl tw-font-normal tw-text-gray-500 tw-ms-3 tw-mb-1"
                    >&gt;</span
                >
            </div>
            <span class="tw-text-xs tw-mt-1 tw-text-gray-500"
                >Jatuh Tempo: {{ dueDate }}</span
            >
        </div>
    </a>
</template>
