<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    name: '',
    email: '',
    member_id: '',
    faculty_or_unit: '',
    address: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    router.post(route('admin.members.store'), form.value);
};
</script>

<template>
    <Head title="Tambah Anggota" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Tambah Anggota Baru
            </h2>
        </template>

        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-5xl sm:tw-px-6 lg:tw-px-8">
                <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-6">
                    <form @submit.prevent="submit">
                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                            <div>
                                <div class="tw-mb-4">
                                    <label for="name" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Nama Lengkap</label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>

                                <div class="tw-mb-4">
                                    <label for="email" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Email</label>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>

                                <div class="tw-mb-4">
                                    <label for="member_id" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">NIM/NIDN/NIP</label>
                                    <input
                                        id="member_id"
                                        v-model="form.member_id"
                                        type="text"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>

                                <div class="tw-mb-4">
                                    <label for="faculty_or_unit" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Fakultas/Unit Kerja</label>
                                    <input
                                        id="faculty_or_unit"
                                        v-model="form.faculty_or_unit"
                                        type="text"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <div class="tw-mb-4">
                                    <label for="address" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Alamat</label>
                                    <input
                                        id="address"
                                        v-model="form.address"
                                        type="text"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>

                                <div class="tw-mb-4">
                                    <label for="phone_number" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Nomor Telepon</label>
                                    <input
                                        id="phone_number"
                                        v-model="form.phone_number"
                                        type="text"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>

                                <div class="tw-mb-4">
                                    <label for="password" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Password</label>
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        type="password"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>

                                <div class="tw-mb-4">
                                    <label for="password_confirmation" class="tw-block tw-text-gray-700 tw-font-medium tw-mb-1">Konfirmasi Password</label>
                                    <input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        type="password"
                                        required
                                        class="tw-w-full tw-border tw-border-gray-300 tw-rounded tw-px-3 tw-py-2 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-outline-none"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="tw-flex tw-justify-end tw-gap-4 tw-mt-6">
                            <Link :href="route('admin.members.index')" class="tw-bg-gray-500 tw-text-white tw-px-4 tw-py-2 tw-rounded hover:tw-bg-gray-600">
                                Batal
                            </Link>
                            <button type="submit" class="tw-bg-blue-500 tw-text-white tw-px-4 tw-py-2 tw-rounded hover:tw-bg-blue-600">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
