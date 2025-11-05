<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Pengajuan Murabahah" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Pengajuan Murabahah
            </h2>
        </template>

        <div class="tw-space-y-4">
            <van-card
                class="tw-rounded-xl tw-border tw-border-dashed tw-border-gray-200"
            >
                <template #title>Progres Pembiayaan</template>
                <template #desc>
                    <p class="tw-mb-3 tw-text-sm tw-text-gray-600">
                        Perlihatkan status pesanan dan pembiayaan Murabahah yang
                        sedang berjalan, termasuk jadwal cicilan.
                    </p>
                    <van-button type="primary" icon="todo-list-o">
                        Lihat Status
                    </van-button>
                </template>
            </van-card>
        </div>
    </AuthenticatedLayout>
</template>
