<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Checkout Tunai" />

    <AuthenticatedLayout>
        <div class="tw-max-w-3xl tw-mx-auto tw-my-10 tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-6">
            <h1 class="tw-text-2xl tw-font-semibold tw-mb-4">
                Pembayaran Tunai
            </h1>
            <p class="tw-text-gray-600 tw-mb-6">
                Silakan periksa kembali pesanan Anda sebelum mengonfirmasi pembayaran
                tunai di loket koperasi.
            </p>
            <button
                type="button"
                class="tw-bg-primary tw-text-white tw-px-4 tw-py-2 tw-rounded-md tw-font-semibold hover:tw-bg-primary-dark tw-transition"
                @click="router.visit(route('member.checkout'))"
            >
                Kembali ke Keranjang
            </button>
        </div>
    </AuthenticatedLayout>
</template>
