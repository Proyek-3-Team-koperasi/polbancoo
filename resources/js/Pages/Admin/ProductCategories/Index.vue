<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Kelola Kategori Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Kelola Kategori Produk
            </h2>
        </template>

        <div class="tw-py-12"></div>
    </AuthenticatedLayout>
</template>
