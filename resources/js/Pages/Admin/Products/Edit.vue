<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    product: Object,
});

const input = ref({
    text: "",
    tel: "",
    digit: "",
    number: "",
    password: "",
    tipe_keuntungan: "",
    kategori: "",
    kategoriShow: false,
});

const formatter = (value) => value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

const numberFormatter = new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
});
let formatterfn = numberFormatter.format;
console.log(props.product);
</script>
<template>
    <Head title="Edit Produk" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Edit Produk
            </h2>
        </template>

        <van-cell-group inset v-bind:title="'Informasi produk'">
            <van-field v-model="input.text" label="Nama" />
            <van-field
                v-model="input.text"
                rows="2"
                type="textarea"
                label="Deskripsi"
            />
            <van-field v-model="input.text" label="Harga beli" />
            <van-field
                name="radio"
                label="Tipe keuntungan"
                show-word-limit="true"
                maxlength="1024"
            >
                <template #input>
                    <van-radio-group
                        v-model="input.tipe_keuntungan"
                        direction="horizontal"
                    >
                        <van-radio name="rupiah">Rupiah</van-radio>
                        <van-radio name="persentase">Persentase</van-radio>
                    </van-radio-group>
                </template>
            </van-field>
            <!-- TODO: Formatter -->
            <van-field v-model="input.text">
                <template #label>
                    <span>Keuntungan</span>
                </template>

                <template #right-icon>
                    {{ input.tipe_keuntungan === "persentase" ? "%" : "" }}
                </template>
            </van-field>
            <van-field
                v-model="input.kategori"
                label="Kategori"
                is-link
                readonly
                placeholder="Pilih kategori"
                @click="input.kategoriShow = true"
            />

            <van-popup v-model="input.kategoriShow" round position="bottom">
                <van-cascader
                    v-model="input.kategori"
                    title="Pilih Kategori"
                    :options="[
                        { text: 'Foo', value: 'Foo' },
                        { text: 'Bar', value: 'Bar' },
                    ]"
                    @close="input.kategoriShow = false"
                />
            </van-popup>
        </van-cell-group>
    </AuthenticatedLayout>
</template>