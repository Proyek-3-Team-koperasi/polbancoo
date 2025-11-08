<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Checkout Berhasil" />

    <AuthenticatedLayout>
        <div
            class="tw-flex tw-items-center tw-justify-center tw-min-h-[60vh] tw-px-4"
        >
            <div
                class="tw-bg-white tw-border tw-rounded-3xl tw-shadow-lg tw-max-w-xl tw-w-full tw-p-8 tw-text-center tw-space-y-4"
            >
                <div
                    class="tw-flex tw-items-center tw-justify-center tw-w-16 tw-h-16 tw-rounded-full tw-bg-primary/10 tw-mx-auto"
                >
                    <span class="tw-text-primary tw-font-bold tw-text-2xl"
                        >✓</span
                    >
                </div>
                <h1 class="tw-text-2xl tw-font-bold tw-tracking-widest">
                    TERKIRIM
                </h1>
                <p class="tw-text-sm tw-text-gray-600 md:tw-text-base">
                    Terima kasih telah melakukan checkout. Pesanan Anda sedang
                    diproses. Silakan cek menu status secara berkala untuk
                    melihat hasil verifikasi oleh admin.
                </p>
                <div class="tw-pt-4">
                    <Link
                        :href="route('member.dashboard')"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-px-4 tw-py-2 tw-rounded-lg tw-bg-primary tw-text-white tw-font-semibold hover:tw-bg-primary-dark tw-transition"
                    >
                        Kembali
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
