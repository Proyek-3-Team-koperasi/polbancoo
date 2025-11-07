<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from "@inertiajs/vue3";
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    products: Object,
})

const model = ref({
    search: '',
    kategori: 0,
});

const kategoriOptions = [
    { text: 'Option1', value: 0 },
    { text: 'Option2', value: 1 },
    { text: 'Option3', value: 2 },
];
let currentPage =  ref(1);

const numberFormatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' })
</script>

<template>
    <Head title="Kelola Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Kelola Produk
            </h2>
        </template>
        <div class="tw-grid-cols-4 tw-grid tw-bg-white " >
            <div class="tw-col-span-3"></div>
            <div class="tw-flex tw-flex-col-reverse " >
                <Link :href="route('admin.products.create')" >
                    <PrimaryButton class="tw-m-4 tw-text-left" >Tambah Produk +</PrimaryButton>
                </Link>
            </div>
            <van-search v-model="model.search" class="tw-col-span-4" />
        </div>
<!-- 
        <van-pagination v-model="currentPage" force-ellipses :total-items="100" :show-page-size="5">
            <template #prev-text>
                <van-icon name="arrow-left" />
            </template>
            <template #next-text>
                <van-icon name="arrow" />
            </template>
        </van-pagination> -->





        <div class="tw-relative tw-overflow-x-auto">
            <table class="tw-w-full tw-text-sm tw-text-left tw-rtl:text-right tw-text-gray-500 tw-dark:text-gray-400">
                <thead
                    class="tw-text-xs tw-text-gray-700 tw-uppercase tw-bg-gray-50 tw-dark:bg-gray-700 tw-dark:text-gray-400">
                    <tr>
                        <th scope="col" class="tw-px-6 tw-py-3">
                            ID
                        </th>
                        <th scope="col" class="tw-px-6 tw-py-3">
                            Produk
                        </th>
                        <th scope="col" class="tw-px-6 tw-py-3">
                            Nama
                        </th>
                        <th scope="col" class="tw-px-6 tw-py-3">
                            Category
                        </th>
                        <th scope="col" class="tw-px-6 tw-py-3">
                            Stock
                        </th>
                        <th scope="col" class="tw-px-6 tw-py-3">
                            Harga Jual
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in props.products.data" :key="product.id"
                        class="tw-bg-white tw-border-b tw-dark:bg-gray-800 tw-dark:border-gray-700 tw-border-gray-200">
                        <th scope="row"
                            class="tw-px-6 tw-py-4 tw-font-medium tw-text-gray-900 tw-whitespace-nowrap tw-dark:text-white">
                            {{ product.id }}
                        </th>
                        <td class="tw-px-6 tw-py-4">
                            <van-image width="100" height="100" :src="product.image_url" />
                        </td>
                        <td class="tw-px-6 tw-py-4">
                            <Link class="tw-text-blue-500 hover:tw-underline hover:tw-text-blue-700"  :href="route('admin.products.edit', product.id)">
                            {{ product.name }} 
                            </Link>
                        </td>
                        <td class="tw-px-6 tw-py-4">
                            {{ product.category.name }}
                        </td>
                        <td class="tw-px-6 tw-py-4">
                            {{ product.stock }}
                        </td>
                        <td class="tw-px-6 tw-py-4">
                            {{ numberFormatter.format(product.cost_price) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


    </AuthenticatedLayout>
</template>
