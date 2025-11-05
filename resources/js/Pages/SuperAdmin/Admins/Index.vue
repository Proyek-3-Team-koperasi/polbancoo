<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    admins: Object,
    filters: Object,
});

const search = ref(props.filters.search || "");

const submit = () => {
    router.get(
        route("superadmin.admins.index"),
        { search: search.value },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const deleteAdmin = (id) => {
    console.log("Deleting user ID:", id);
    if (confirm("Yakin ingin menghapus admin ini?")) {
        router.delete(route("superadmin.admins.destroy", id));
    }
};
</script>

<template>
    <Head title="Daftar Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Kelola Admin
            </h2>
        </template>
        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-bg-white tw-p-4 tw-shadow sm:tw-rounded-lg sm:tw-p-8"
                >
                    <div
                        class="tw-flex tw-justify-between tw-items-center tw-mb-6"
                    >
                        <h3 class="tw-text-lg tw-font-medium tw-text-gray-800">
                            Daftar Admin
                        </h3>
                        <Link
                            :href="route('superadmin.admins.create')"
                            class="tw-bg-blue-500 tw-text-white tw-px-4 tw-py-2 tw-rounded"
                        >
                            Tambah Admin
                        </Link>
                    </div>

                    <div class="tw-mb-4">
                        <input
                            v-model="search"
                            @input="submit"
                            placeholder="Cari admin..."
                            class="tw-w-full tw-p-2 tw-border tw-rounded"
                        />
                    </div>

                    <table
                        class="tw-min-w-full tw-bg-white tw-border tw-border-gray-200"
                    >
                        <thead>
                            <tr>
                                <th
                                    class="tw-py-2 tw-px-4 tw-border-b tw-text-left"
                                >
                                    Nama
                                </th>
                                <th
                                    class="tw-py-2 tw-px-4 tw-border-b tw-text-left"
                                >
                                    Email
                                </th>
                                <th
                                    class="tw-py-2 tw-px-4 tw-border-b tw-text-left"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="admin in admins.data" :key="admin.id">
                                <td class="tw-py-2 tw-px-4 tw-border-b">
                                    {{ admin.name }}
                                </td>
                                <td class="tw-py-2 tw-px-4 tw-border-b">
                                    {{ admin.email }}
                                </td>
                                <td class="tw-py-2 tw-px-4 tw-border-b">
                                    <Link
                                        :href="
                                            route(
                                                'superadmin.admins.edit',
                                                admin.id,
                                            )
                                        "
                                        @click="
                                            console.log(
                                                'Editing user ID:',
                                                admin.id,
                                            )
                                        "
                                        class="tw-text-blue-500 tw-mr-2"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteAdmin(admin.id)"
                                        class="tw-text-red-500"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="tw-mt-4">
                        <div
                            v-if="admins.links.length > 3"
                            class="tw-flex tw-flex-wrap tw-gap-1"
                        >
                            <Link
                                v-for="(link, key) in admins.links"
                                :key="key"
                                :href="link.url || '#'"
                                :class="{
                                    'tw-text-gray-500': !link.active,
                                    'tw-text-blue-500 tw-font-bold':
                                        link.active,
                                    'tw-pointer-events-none': !link.url,
                                }"
                                v-html="link.label"
                                class="tw-px-3 tw-py-1 tw-border tw-rounded"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
