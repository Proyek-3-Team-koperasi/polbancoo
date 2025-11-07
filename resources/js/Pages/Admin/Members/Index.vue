<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    members: Array,
});
</script>

<template>
    <Head title="Kelola Anggota" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Kelola Anggota
            </h2>
        </template>

        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8">
                <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-6">
                    <div class="tw-flex tw-justify-between tw-items-center tw-mb-6">
                        <h3 class="tw-text-lg tw-font-medium tw-text-gray-800">Daftar Anggota</h3>
                        <Link :href="route('admin.members.create')" class="tw-bg-blue-500 tw-text-white tw-px-4 tw-py-2 tw-rounded hover:tw-bg-blue-600">
                            Tambah Anggota
                        </Link>
                    </div>

                    <div class="tw-overflow-x-auto">
                        <table class="tw-min-w-full tw-bg-white tw-border tw-border-gray-200">
                            <thead>
                                <tr>
                                    <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">ID</th>
                                    <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Nama</th>
                                    <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Email</th>
                                    <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">NIM/NIDN/NIP</th>
                                    <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Fakultas/Unit Kerja</th>
                                    <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="member in members" :key="member.id">
                                    <td class="tw-py-2 tw-px-4 tw-border-b">{{ member.id }}</td>
                                    <td class="tw-py-2 tw-px-4 tw-border-b">{{ member.name }}</td>
                                    <td class="tw-py-2 tw-px-4 tw-border-b">{{ member.email }}</td>
                                    <td class="tw-py-2 tw-px-4 tw-border-b">{{ member.member_id }}</td>
                                    <td class="tw-py-2 tw-px-4 tw-border-b">{{ member.faculty_or_unit }}</td>
                                    <td class="tw-py-2 tw-px-4 tw-border-b">
                                        <Link :href="route('admin.members.show', member.id)" class="tw-text-blue-500 tw-mr-4">Detail</Link>
                                        <Link :href="route('admin.members.edit', member.id)" class="tw-text-blue-500 tw-mr-4">Edit</Link>
                                        <Link :href="route('admin.members.destroy', member.id)" method="delete" as="button" type="button" class="tw-text-red-500">Hapus</Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>