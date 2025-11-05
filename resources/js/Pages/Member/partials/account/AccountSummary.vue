<script setup>
import { ArrowDownIcon, ArrowUpIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <div class="tw-border tw-rounded-lg">
        <div class="tw-rounded-t-24 tw-px-6 tw-py-4 tw-flex tw-flex-col">
            <span class="tw-font-semibold">Total Tabungan</span>
            <div class="tw-flex tw-place-items-start tw-gap-2">
                <span class="tw-mt-1">Rp.</span>
                <span class="tw-text-3xl tw-font-extrabold"> 1.500.000 </span>
            </div>
        </div>
    </div>
    <div class="tw-flex tw-tw-flex-row tw-gap-3 tw-w-full tw-mt-2">
        <div
            class="tw-w-full tw-col tw-flex tw-flex-row tw-rounded-md tw-bg-green-200 tw-p-2 tw-items-center tw-gap-3"
        >
            <ArrowDownIcon class="tw-h-5 tw-w-5 tw-text-green-600" />
            <div class="tw-flex-col tw-flex">
                <span class="tw-text-xs">Saldo Masuk</span>
                <span class="tw-text-base tw-font-semibold">1.000.000</span>
            </div>
        </div>
        <div
            class="tw-w-full tw-col tw-flex tw-flex-row tw-rounded-md tw-bg-red-200 tw-p-2 tw-items-center tw-gap-3"
        >
            <ArrowUpIcon class="tw-h-5 tw-w-5 tw-text-red-600" />
            <div class="tw-flex-col tw-flex">
                <span class="tw-text-xs">Saldo Keluar</span>
                <span class="tw-text-base tw-font-semibold">1.000.000</span>
            </div>
        </div>
    </div>
</template>
