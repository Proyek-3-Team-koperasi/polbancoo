<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    member: Object,
});
</script>

<template>
    <Head :title="`Detail Anggota - ${member.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Detail Anggota
            </h2>
        </template>

        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-4xl sm:tw-px-6 lg:tw-px-8">
                <div class="tw-bg-white tw-shadow-lg tw-rounded-xl tw-p-6 tw-border tw-border-gray-200">
                    <div class="tw-flex tw-items-center tw-gap-6 tw-mb-8">
                        <div class="tw-flex-shrink-0">
                            <div class="tw-flex tw-items-center tw-justify-center tw-h-16 tw-w-16 tw-rounded-full tw-bg-blue-500 tw-text-white tw-text-xl tw-font-bold">
                                {{ member.name?.charAt(0).toUpperCase() || '?' }}
                            </div>
                        </div>

                        <div class="tw-flex-1">
                            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">{{ member.name }}</h3>
                            <p class="tw-text-gray-600">NIM/NIDN/NIP: {{ member.member_id_number }}</p>
                        </div>
                    </div>

                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-mb-8">
                        <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                            <h4 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mb-2">Informasi Pribadi</h4>
                            <div class="tw-space-y-2">
                                <p><span class="tw-font-medium tw-text-gray-600">Fakultas/Unit Kerja:</span> {{ member.faculty_or_unit }}</p>
                                <p><span class="tw-font-medium tw-text-gray-600">Alamat:</span> {{ member.address }}</p>
                                <p><span class="tw-font-medium tw-text-gray-600">Nomor Telepon:</span> {{ member.phone_number }}</p>
                            </div>
                        </div>

                        <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                            <h4 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mb-2">Total Simpanan</h4>
                            <div class="tw-space-y-2">
                                <p><span class="tw-font-medium tw-text-gray-600">Wajib:</span> Rp {{ member.total_savings.wajib.toLocaleString('id-ID') }}</p>
                                <p><span class="tw-font-medium tw-text-gray-600">Pokok:</span> Rp {{ member.total_savings.pokok.toLocaleString('id-ID') }}</p>
                                <p><span class="tw-font-medium tw-text-gray-600">Sukarela:</span> Rp {{ member.total_savings.sukarela.toLocaleString('id-ID') }}</p>
                                <p class="tw-pt-2 tw-border-t tw-border-gray-300"><span class="tw-font-bold tw-text-gray-800">Total:</span> Rp {{ member.total_savings.total.toLocaleString('id-ID') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="tw-mb-6">
                        <h4 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mb-4">History Simpanan</h4>
                        <div class="tw-overflow-x-auto">
                            <table class="tw-min-w-full tw-bg-white tw-border tw-border-gray-200">
                                <thead>
                                    <tr class="tw-bg-gray-100">
                                        <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Tanggal</th>
                                        <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Jenis</th>
                                        <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Jumlah</th>
                                        <th class="tw-py-2 tw-px-4 tw-border-b tw-text-left">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(record, index) in member.savings_history" :key="index">
                                        <td class="tw-py-2 tw-px-4 tw-border-b">{{ record.date }}</td>
                                        <td class="tw-py-2 tw-px-4 tw-border-b">{{ record.type }}</td>
                                        <td class="tw-py-2 tw-px-4 tw-border-b">Rp {{ record.amount.toLocaleString('id-ID') }}</td>
                                        <td class="tw-py-2 tw-px-4 tw-border-b">{{ record.description }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tw-flex tw-justify-end">
                        <Link :href="route('admin.members.index')" class="tw-bg-gray-500 tw-text-white tw-px-4 tw-py-2 tw-rounded-lg hover:tw-bg-gray-600">
                            Kembali ke Daftar
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>