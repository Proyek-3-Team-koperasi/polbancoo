<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Form } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";

import { ref } from "vue";
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import FormattedNumberInput from "@/Components/input/FormattedNumberInput.vue";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

const props = defineProps({
    product: Object,
    intent: String,
});

const intent: 'create' | 'edit' = props.intent as any;
const TITLE = intent == "edit" ? "Edit Produk" : "Tambah Produk";

const imgUrl = ref(null)
const fileSelected = ref(null)

const processFile = (file) => {
    // Check if the file is an image
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        return;
    }

    fileSelected.value = file;

    // Use FileReader to create a preview
    const reader = new FileReader();

    reader.onload = (e) => {
        // e.target.result contains the Base64 data URL
        imgUrl.value = e.target.result;
    };

    // Read the file as a data URL
    reader.readAsDataURL(file);
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        processFile(file);
    }
};

</script>
<template>

    <Head :title="TITLE" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
                {{ TITLE }}
            </h2>
        </template>

        <div class="tw-py-6 tw-px-6 tw-bg-white tw-rounded-md">
            <Form
                :action="intent == 'edit' ? route('admin.products.update', product?.id || '3') : route('admin.products.store')"
                method="POST">
                <input type="hidden" name="_method" :value="intent == 'edit' ? 'PUT' : 'POST'">
                <div class="tw-my-4">
                    <Label for="name">Nama Produk</Label>
                    <Input id="name" placeholder="Minyak Goreng" />
                </div>
                <div class="tw-my-4">
                    <Label for="name">Deskripsi</Label>
                    <Textarea id="name" placeholder="Minyak adalah alat untuk menggoreng" />
                </div>
                <div class="tw-my-4 tw-col-span-full">
                    <label class="tw-block tw-text-sm/6 tw-font-medium">Foto Produk</label>
                    <div
                        class="tw-mt-2 tw-flex tw-justify-center tw-rounded-lg tw-border tw-border-dashed tw-border-black/25 tw-px-6 tw-py-10">
                        <div class="tw-text-center ">
                            <div v-if="imgUrl">
                                <img :src="imgUrl" class="tw-mx-auto tw-text-gray-400" alt="file icon" />
                            </div>
                            <div v-else>
                                <svg viewBox="0 0 24 24" fill="currentColor" data-slot="icon" aria-hidden="true"
                                    class="mx-auto size-12 text-gray-600">
                                    <path
                                        d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="tw-mt-4 tw-flex tw-text-sm/6 tw-text-gray-400 tw-justify-center ">
                                <label for="file-upload"
                                    class="tw-relative tw-cursor-pointer tw-rounded-md tw-bg-transparent tw-font-semibold tw-text-indigo-400 tw-focus-within:outline-2 tw-focus-within:outline-offset-2 tw-focus-within:outline-indigo-500 tw-hover:text-indigo-300  ">
                                    <span class="">Upload file</span>
                                    <input id="file-upload" type="file" name="file-upload" class="tw-sr-only"
                                        @change="handleFileChange" accept="image/*" />
                                </label>
                            </div>
                            <p class="tw-text-xs/5 tw-text-gray-400">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                </div>
                <div class="tw-my-4">
                    <Label for="cash_price">Harga Beli</Label>
                    <FormattedNumberInput id="cash_price" placeholder="10000" name="cash_price" />
                </div>

                <div class="tw-my-4">
                    <Label for="cost_price">Harga Jual</Label>
                    <FormattedNumberInput id="cost_price" placeholder="12000" type="number" name="cost_price" />
                </div>

                <div class="tw-my-4">
                    <Label for="stock">Stok</Label>
                    <Input id="stock" name="stock" placeholder="5" type="number" />
                </div>

                <div class="tw-my-4">
                    <Label for="category">Kategori</Label>
                    <Select>
                        <SelectTrigger>
                            <SelectValue placeholder="Select a fruit" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem value="1">
                                    Makanan
                                </SelectItem>
                                <SelectItem value="2">
                                    Alat Tulis
                                </SelectItem>
                                <SelectItem value="3">
                                    Pakaian
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>

                <div class="tw-my-4 tw-flex tw-gap-2">
                    <Button type="submit">Simpan</Button>
                    <Button v-if="intent == 'edit'" type="button" class="tw-bg-destructive">Hapus</Button>
                </div>



            </Form>
        </div>
    </AuthenticatedLayout>
</template>