<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from "@inertiajs/vue3";
import { computed, ref, watch } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';

const props = defineProps({
    product: Object,
})

const input = ref({
    text: '',
    tel: '',
    digit: '',
    number: '',
    password: '',
    harga_beli: 0,
    keuntungan: 0,
    kategori: '',
    kategoriShow: false
});

const tipe_keuntungan = ref('rupiah')

watch(tipe_keuntungan, async (newValue, oldVal) => {
    if (newValue === 'persentase') {
        return input.value.keuntungan = (input.value.keuntungan / input.value.harga_beli) * 100
    }

    if (newValue === 'rupiah') {
        return input.value.keuntungan = input.value.harga_beli * (input.value.keuntungan / 100)
    }
})

const harga_jual = computed(() => {
    // 1. Safely parse values, defaulting to 0 if they are empty or invalid
    const beli = parseFloat(input.value.harga_beli) || 0;
    const untung = parseFloat(input.value.keuntungan) || 0;
    const tipe = tipe_keuntungan.value;

    // 2. Calculate based on type
    if (tipe === 'persentase') {
        // 3. This is the CORRECTED logic for selling price
        // harga_beli + (harga_beli * (persentase / 100))
        const profitAmount = beli * (untung / 100);
        return beli + profitAmount;
    }

    if (tipe === 'rupiah') {
        // This logic was already correct
        return beli + untung;
    }

    // 4. Default return value
    // If no profit type is selected, just show the base price
    return beli;
});

const formatter = (value) => value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

const numberFormatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' })
let formatterfn = numberFormatter.format
console.log(props.product)
let dropdownState = false;
</script>

<template>

    <Head title="Edit Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                Edit Produk
            </h2>
        </template>

        <van-cell-group inset v-bind:title="'Informasi produk'">
            <van-field v-model="input.text" label="Nama" />
            <van-field v-model="input.text" rows="2" type="textarea" label="Deskripsi" maxlength="1024"
                show-word-limit />
            <van-field v-model="input.harga_beli" label="Harga beli" type="number" />
            <van-field name="radio" label="Tipe keuntungan">
                <template #input>
                    <van-radio-group v-model="tipe_keuntungan" direction="horizontal">
                        <van-radio name="rupiah">Rupiah</van-radio>
                        <van-radio name="persentase">Persentase</van-radio>
                    </van-radio-group>
                </template>
            </van-field>
            <!-- TODO: Formatter -->
            <van-field v-model="input.keuntungan" type="number">
                <template #label>
                    <span>Keuntungan</span>
                </template>

                <template #right-icon>
                    {{ tipe_keuntungan === 'persentase' ? '%' : '' }}
                </template>
            </van-field>
            <van-field v-model="harga_jual" disabled label="Harga Jual" type="number"></van-field>
            <van-field v-model="input.kategori" label="Kategori" is-link readonly placeholder="Pilih kategori"
                @click="input.kategoriShow = true" />



        </van-cell-group>




    </AuthenticatedLayout>
</template>
