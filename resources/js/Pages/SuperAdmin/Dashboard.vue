<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Super Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Kelola Admin
            </h2>
        </template>

        <div class="tw-py-12">
            <div
                class="tw-mx-auto tw-max-w-5xl tw-space-y-6 sm:tw-px-6 lg:tw-px-8"
            >
                <van-card
                    class="tw-rounded-xl tw-shadow-sm"
                    :title="`Selamat datang, ${user?.name ?? ''}`"
                >
                    <template #desc>
                        <p class="tw-mb-4 tw-text-gray-700">
                            Kelola akun admin, atur peran, dan pantau aktivitas
                            pengurus koperasi dari satu tempat.
                        </p>
                        <div class="tw-flex tw-flex-wrap tw-gap-3">
                            <Link :href="route('superadmin.admins.index')">
                                <van-button type="primary" icon="manager-o">
                                    Kelola Admin
                                </van-button>
                            </Link>
                            <van-button type="default" icon="setting-o">
                                Pengaturan Sistem
                            </van-button>
                        </div>
                    </template>
                </van-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
