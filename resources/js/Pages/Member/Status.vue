<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const props = defineProps({
    applications: Array,
    filters: Object,
});

const dateFilter = ref(props.filters?.date_filter || "all");
const statusFilter = ref(props.filters?.status_filter || "");
const sortDirection = ref(props.filters?.sort_price || "");

const startDate = ref("");
const endDate = ref("");

const openMenu = ref(null);
const openSubmenu = ref(false);

const toggleMenu = (menu) => {
    openMenu.value = openMenu.value === menu ? null : menu;
    if (menu !== "status") openSubmenu.value = false;
};

const toggleSubmenu = () => (openSubmenu.value = !openSubmenu.value);

onMounted(() => {
    window.addEventListener("click", (e) => {
        if (!e.target.closest(".dropdown")) {
            openMenu.value = null;
            openSubmenu.value = false;
        }
    });
});

const applyFilter = () => {
    router.get(
        route("member.status"),
        {
            date_filter: dateFilter.value,
            status_filter: statusFilter.value,
            sort_price: sortDirection.value,
        },
        { preserveState: true }
    );
};

const applyCustomDateFilter = () => {
    if (!startDate.value || !endDate.value) {
        alert("Pilih tanggal mulai dan akhir terlebih dahulu!");
        return;
    }
    router.get(
        route("member.status"),
        {
            date_filter: "custom",
            start_date: startDate.value,
            end_date: endDate.value,
            status_filter: statusFilter.value,
            sort_price: sortDirection.value,
        },
        { preserveState: true }
    );
    openMenu.value = null;
};

const toggleSort = () => {
    sortDirection.value =
        sortDirection.value === ""
            ? "highest"
            : sortDirection.value === "highest"
            ? "lowest"
            : "";
    applyFilter();
};

const goToDetail = (id) => {
    router.visit(route("member.status.show", id));
};

const shorten = (text, max = 20) => {
    if (!text) return "-";
    return text.length > max ? text.substring(0, max) + "…" : text;
};

const isMobile = ref(false);

onMounted(() => {
    const check = () => {
        isMobile.value = window.innerWidth <= 768;
    };
    check();
    window.addEventListener("resize", check);
});

const getMobileName = (items) => {
    if (!items?.length) return "-";
    return shorten(items[0].name);
};

const getDesktopName = (items) => {
    if (!items?.length) return "-";
    const names = items.map((i) => i.name);
    if (names.length <= 3) return names.join(", ");
    return `${names[0]}, ${names[1]}, ${names[2]}, +${names.length - 3} lainnya`;
};

const getProductName = (items) =>
    isMobile.value ? getMobileName(items) : getDesktopName(items);

const getTotalProducts = (items) => items?.length || 0;
</script>

<template>
    <Head title="Status Pengajuan Pembiayaan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-xl tw-font-semibold tw-text-gray-800">
                Status Pengajuan Pembiayaan
            </h2>
        </template>

        <!-- FILTER -->
        <div class="tw-flex tw-justify-center tw-gap-3 tw-mt-6 tw-flex-wrap">

            <div class="dropdown tw-relative">
                <button
                    @click.stop="toggleMenu('date')"
                    class="tw-bg-white tw-text-orange-500 tw-border tw-border-orange-500 tw-rounded-lg tw-px-4 tw-py-2 tw-shadow-sm"
                >
                    Tanggal
                </button>

                <div
                    v-if="openMenu === 'date'"
                    class="tw-absolute tw-z-20 tw-bg-white tw-rounded-lg tw-shadow-lg tw-mt-2 tw-w-48 tw-p-3"
                >
                    <p @click="dateFilter = 'all'; applyFilter()" class="tw-py-1 tw-cursor-pointer">Semua</p>
                    <p @click="dateFilter = '7_days'; applyFilter()" class="tw-py-1 tw-cursor-pointer">7 hari terakhir</p>
                    <p @click="dateFilter = '30_days'; applyFilter()" class="tw-py-1 tw-cursor-pointer">30 hari terakhir</p>

                    <hr class="tw-my-2" />

                    <div class="tw-space-y-2">
                        <input v-model="startDate" type="date" class="tw-border tw-rounded tw-w-full tw-px-2 tw-py-1" />
                        <input v-model="endDate" type="date" class="tw-border tw-rounded tw-w-full tw-px-2 tw-py-1" />
                        <button
                            @click="applyCustomDateFilter"
                            class="tw-w-full tw-bg-orange-500 tw-text-white tw-rounded tw-py-1 tw-font-semibold"
                        >
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>

            <div class="dropdown tw-relative">
                <button
                    @click.stop="toggleMenu('status')"
                    class="tw-bg-white tw-text-orange-500 tw-border tw-border-orange-500 tw-rounded-lg tw-px-4 tw-py-2 tw-shadow-sm"
                >
                    Status
                </button>

                <div
                    v-if="openMenu === 'status'"
                    class="tw-absolute tw-z-20 tw-bg-white tw-rounded-lg tw-shadow-lg tw-mt-2 tw-w-48 tw-p-3"
                >
                    <p @click="statusFilter = ''; applyFilter()" class="tw-py-1 tw-cursor-pointer">Semua</p>
                    <p @click="statusFilter = 'Pending'; applyFilter()" class="tw-py-1 tw-cursor-pointer">Pending</p>
                    <p @click="statusFilter = 'Active'; applyFilter()" class="tw-py-1 tw-cursor-pointer">Active</p>
                    <p @click="statusFilter = 'Lunas'; applyFilter()" class="tw-py-1 tw-cursor-pointer">Lunas</p>
                    <p @click="statusFilter = 'Rejected'; applyFilter()" class="tw-py-1 tw-cursor-pointer">Rejected</p>
                </div>
            </div>

            <button
                @click="toggleSort"
                class="tw-bg-white tw-text-orange-500 tw-border tw-border-orange-500 tw-rounded-lg tw-px-4 tw-py-2 tw-shadow-sm"
            >
                Harga
                <span v-if="sortDirection === 'highest'">↑</span>
                <span v-else-if="sortDirection === 'lowest'">↓</span>
            </button>
        </div>

        <!-- LIST -->
        <div
            class="tw-p-6 tw-bg-gradient-to-r tw-from-blue-900 tw-to-orange-500 tw-rounded-2xl tw-text-white tw-shadow-xl tw-mt-8"
        >
            <h3 class="tw-text-2xl tw-font-bold tw-mb-6 tw-text-center">
                Daftar Transaksi Anda
            </h3>

            <div v-if="applications.length > 0" class="tw-space-y-4">
                <div
                    v-for="app in applications"
                    :key="app.id"
                    @click="goToDetail(app.id)"
                    class="tw-bg-white tw-rounded-2xl tw-shadow-md tw-flex tw-items-center tw-gap-4 tw-p-4 tw-text-gray-800 tw-cursor-pointer tw-transition hover:tw-scale-[1.01]"
                >
                    <img
                        :src="app.order?.items?.[0]?.image || '/img/no-image.png'"
                        class="tw-w-20 tw-h-20 tw-object-cover tw-rounded-xl tw-flex-shrink-0"
                    />

                    <div class="tw-flex-1 tw-flex tw-flex-col tw-gap-1 tw-overflow-hidden">
                        <span class="tw-inline-block tw-border tw-border-orange-500 tw-text-orange-500 tw-rounded-full tw-px-3 tw-py-1 tw-text-xs">
                            {{ app.status }}
                        </span>

                        <p class="tw-text-xs tw-text-gray-500">
                            Kode Transaksi: #{{ app.id }}
                        </p>

                        <p class="tw-font-semibold tw-text-lg tw-text-blue-900 tw-truncate">
                            {{ getProductName(app.order?.items) }}
                        </p>

                        <p class="tw-text-orange-500 tw-font-bold tw-text-base">
                            Rp {{ Number(app.selling_price_total).toLocaleString("id-ID") }}
                        </p>

                        <p class="tw-text-sm tw-text-gray-700">
                            {{ getTotalProducts(app.order?.items) }} produk
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="tw-text-center tw-text-white/80 tw-mt-4 tw-font-medium">
                Belum ada transaksi.
            </div>
        </div>
    </AuthenticatedLayout>
</template>
