<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    products: Array,
    search: String,
});

// input pencarian
const search = ref(props.search || "");

// fungsi pencarian produk
const searchProducts = () => {
    router.get(
        route("member.products"),
        { q: search.value },
        { preserveState: true },
    );
};

// fungsi buka halaman detail
const goToDetail = (product) => {
    router.visit(route("member.products.show", product.slug));
};
</script>

<template>
    <Head title="Katalog Produk" />

    <AuthenticatedLayout>
        <template #header>
            <van-nav-bar
                title="Katalog Produk"
                left-text="Beranda"
                left-arrow
                @click-left="$inertia.visit(route('member.dashboard'))"
            />
        </template>

        <!-- Search Bar -->
        <div class="tw-px-4 tw-pt-4">
            <van-search
                v-model="search"
                placeholder="Cari produk atau kebutuhan Syariah..."
                shape="round"
                background="#ffffff"
                @search="searchProducts"
                @clear="searchProducts"
            />
        </div>

        <!-- Grid Produk -->
        <div
            class="tw-px-2 tw-py-4 tw-grid tw-grid-cols-2 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-3 sm:tw-gap-4 lg:tw-gap-6"
        >
            <div
                v-for="product in products"
                :key="product.id"
                @click="goToDetail(product)"
                class="tw-bg-white tw-rounded-xl tw-overflow-hidden tw-shadow hover:tw-shadow-lg hover:tw-scale-[1.02] tw-transition tw-cursor-pointer"
            >
                <van-image
                    :src="product.image_url || '/img/no-image.png'"
                    alt="Product Image"
                    width="100%"
                    height="150"
                    fit="cover"
                    class="tw-w-full tw-object-cover"
                />

                <div class="tw-p-3 sm:tw-p-4">
                    <h3
                        class="tw-text-sm sm:tw-text-base tw-font-semibold tw-text-blue-900 tw-leading-tight"
                    >
                        {{ product.name }}
                    </h3>

                    <p
                        class="tw-text-xs sm:tw-text-sm tw-text-gray-600 tw-mt-1"
                    >
                        {{ product.category?.name ?? "Tanpa Kategori" }}
                    </p>

                    <p
                        class="tw-text-orange-600 tw-font-bold tw-mt-1 sm:tw-mt-2"
                    >
                        Rp
                        {{ Number(product.cash_price).toLocaleString("id-ID") }}
                    </p>

                    <p
                        class="tw-text-gray-700 tw-text-xs sm:tw-text-sm tw-mt-2 tw-line-clamp-2"
                    >
                        {{ product.description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Jika kosong -->
        <div
            v-if="products.length === 0"
            class="tw-text-center tw-text-gray-500 tw-mt-6"
        >
            Belum ada produk yang tersedia.
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* biar gambar produk tetep proporsional */
:deep(.van-image__img) {
    border-radius: 0.75rem 0.75rem 0 0;
}

/* efek hover dan transisi */
div[v-for] {
    transition: all 0.2s ease;
}
div[v-for]:hover {
    transform: scale(1.02);
}
</style>
