<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { showToast } from "vant";

defineProps({
    product: Object,
    recommendations: Array,
});

const quantity = ref(1);

const increaseQty = () => {
    if (quantity.value < product.stock) quantity.value++;
};
const decreaseQty = () => {
    if (quantity.value > 1) quantity.value--;
};

const addToCart = () => {
    showToast({
        message: `Produk "${product.name}" (${quantity.value}x) ditambahkan ke keranjang.`,
        type: "success",
        duration: 1500,
    });
};

const goToDetail = (id) => {
    router.visit(route("member.products.show", id));
};
</script>

<template>
    <Head :title="product.name" />

    <AuthenticatedLayout>
        <template #header>
            <van-nav-bar
                title="Detail Produk"
                left-text="Kembali"
                left-arrow
                @click-left="$inertia.visit(route('member.products'))"
            />
        </template>

        <!-- Detail Produk -->
        <div class="tw-px-6 tw-py-8 tw-text-gray-800">
            <!-- Foto Produk -->
            <div class="tw-flex tw-justify-center tw-items-center tw-mb-6">
                <van-image
                    :src="product.image_url || '/img/no-image.png'"
                    width="320"
                    height="320"
                    fit="cover"
                    radius="12"
                    class="tw-border tw-border-gray-200"
                />
            </div>

            <!-- Nama Produk -->
            <h1
                class="tw-text-3xl tw-font-extrabold tw-text-blue-900 tw-text-left"
            >
                {{ product.name }}
            </h1>

            <!-- Harga -->
            <p
                class="tw-text-2xl tw-text-orange-600 tw-font-bold tw-mt-2 tw-text-left"
            >
                Rp {{ Number(product.cash_price).toLocaleString("id-ID") }}
            </p>

            <!-- Deskripsi Produk -->
            <p
                class="tw-text-base tw-leading-relaxed tw-text-gray-700 tw-mt-4 tw-text-justify"
            >
                {{
                    product.description ||
                    "Tidak ada deskripsi untuk produk ini."
                }}
            </p>

            <!-- Kuantitas Produk -->
            <div class="tw-mt-8 tw-space-y-6">
                <div class="tw-flex tw-flex-col tw-items-start tw-gap-2">
                    <span class="tw-font-semibold tw-text-lg">Kuantitas:</span>
                    <!-- stepper muncul di sini -->
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <van-stepper
                            v-model="quantity"
                            min="1"
                            :max="product.stock"
                        />
                    </div>
                    <span class="tw-text-sm tw-text-gray-500">
                        Stok tersedia: {{ product.stock }}
                    </span>
                </div>

                <div class="tw-flex tw-justify-start">
                    <van-button
                        type="primary"
                        color="#f97316"
                        round
                        size="large"
                        icon="cart-o"
                        class="tw-text-white tw-font-semibold"
                        @click="addToCart"
                    >
                        Tambah ke Keranjang
                    </van-button>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Produk -->
        <div class="tw-mt-12 tw-px-6 tw-pb-12">
            <h3 class="tw-text-2xl tw-font-semibold tw-text-gray-800 tw-mb-6">
                Rekomendasi Produk Lainnya
            </h3>

            <div
                class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6"
            >
                <van-card
                    v-for="rec in recommendations"
                    :key="rec.id"
                    :title="rec.name"
                    :desc="rec.description || 'Tanpa deskripsi'"
                    :thumb="rec.image_url || '/img/no-image.png'"
                    :price="Number(rec.cash_price).toLocaleString('id-ID')"
                    currency="Rp"
                    centered
                    @click="goToDetail(rec.id)"
                    class="tw-rounded-xl tw-shadow hover:tw-shadow-lg hover:tw-scale-[1.02] tw-transition tw-cursor-pointer"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
