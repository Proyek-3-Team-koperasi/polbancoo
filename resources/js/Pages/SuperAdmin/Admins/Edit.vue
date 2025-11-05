<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { reactive } from "vue";

const props = defineProps({
    admin: Object,
});

const form = reactive({
    name: props.admin.name,
    email: props.admin.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    router.put(route("superadmin.admins.update", props.admin.id), form);
};
</script>

<template>
    <Head title="Edit Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Edit Admin
            </h2>
        </template>

        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-2xl sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-bg-white tw-p-4 tw-shadow sm:tw-rounded-lg sm:tw-p-8"
                >
                    <form @submit.prevent="submit" class="tw-space-y-6">
                        <div>
                            <label
                                for="name"
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Nama</label
                            >
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                class="tw-mt-1 tw-block tw-w-full tw-p-2 tw-border tw-rounded"
                            />
                        </div>

                        <div>
                            <label
                                for="email"
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Email</label
                            >
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="tw-mt-1 tw-block tw-w-full tw-p-2 tw-border tw-rounded"
                            />
                        </div>

                        <div>
                            <label
                                for="password"
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Password Baru (kosongkan jika tidak ingin
                                diubah)</label
                            >
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="tw-mt-1 tw-block tw-w-full tw-p-2 tw-border tw-rounded"
                            />
                        </div>

                        <div>
                            <label
                                for="password_confirmation"
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Konfirmasi Password</label
                            >
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="tw-mt-1 tw-block tw-w-full tw-p-2 tw-border tw-rounded"
                            />
                        </div>

                        <div class="tw-flex tw-justify-between tw-items-center">
                            <Link
                                :href="route('superadmin.admins.index')"
                                class="tw-text-gray-600 hover:tw-underline"
                            >
                                Kembali
                            </Link>
                            <button
                                type="submit"
                                class="tw-bg-blue-500 tw-text-white tw-px-4 tw-py-2 tw-rounded"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
