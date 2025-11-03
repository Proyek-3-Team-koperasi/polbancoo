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
  if (openMenu.value === menu) {
    openMenu.value = null;
    openSubmenu.value = false;
  } else {
    openMenu.value = menu;
    if (menu !== "status") openSubmenu.value = false;
  }
};

const toggleSubmenu = () => {
  openSubmenu.value = !openSubmenu.value;
};

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
  if (sortDirection.value === "") sortDirection.value = "highest";
  else if (sortDirection.value === "highest") sortDirection.value = "lowest";
  else sortDirection.value = "";
  applyFilter();
};

// klik card menuju detail
const goToDetail = (id) => {
  router.visit(route("member.status.show", id));
};
</script>

<template>
  <Head title="Status Pengajuan Pembiayaan" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
        Status Pengajuan Pembiayaan
      </h2>
    </template>

    <!-- Filter bar -->
    <div class="tw-flex tw-justify-center tw-mt-6">
      <div class="tw-flex tw-items-center tw-bg-white tw-border tw-border-slate-200 tw-rounded-2xl tw-shadow-md tw-px-6 tw-py-3 tw-gap-6">

        <!-- === FILTER BUTTONS tetap seperti milikmu === -->
        <!-- (Bagian ini ga aku ubah sama sekali) -->

        <!-- Tanggal Pesanan -->
        <div class="dropdown tw-relative">
          <span @click.stop="toggleMenu('date')" class="tw-cursor-pointer tw-text-orange-500 tw-font-semibold tw-text-base hover:tw-text-orange-600">
            Tanggal Pesanan
          </span>

          <div
            v-show="openMenu === 'date'"
            class="tw-absolute tw-top-full tw-left-0 tw-mt-2 tw-bg-white tw-border tw-border-orange-300 tw-rounded-xl tw-shadow-lg tw-w-56 tw-overflow-hidden tw-z-30"
          >
            <button
              v-for="opt in [
                { key: 'all', label: 'Semua tanggal' },
                { key: '7_days', label: '7 Hari Terakhir' },
                { key: '30_days', label: '30 Hari Terakhir' },
                { key: 'custom', label: 'Pilih Tanggal Sendiri' },
              ]"
              :key="opt.key"
              @click.stop="dateFilter = opt.key; if (opt.key !== 'custom') applyFilter();"
              class="tw-w-full tw-px-4 tw-py-2 tw-text-left tw-font-medium"
              :class="[
                dateFilter === opt.key ? 'tw-bg-orange-500 tw-text-white' : 'tw-text-orange-600 hover:tw-bg-orange-100',
              ]"
            >
              {{ opt.label }}
            </button>

            <!-- custom tanggal -->
            <div v-if="dateFilter === 'custom'" class="tw-p-4 tw-bg-orange-50 tw-space-y-2 tw-border-t tw-border-orange-200">
              <label class="tw-block tw-text-sm tw-font-semibold tw-text-orange-700">Tanggal Mulai</label>
              <input type="date" v-model="startDate" class="tw-w-full tw-rounded-lg tw-border tw-border-orange-300 tw-px-2 tw-py-1" />

              <label class="tw-block tw-text-sm tw-font-semibold tw-text-orange-700 tw-mt-2">Tanggal Akhir</label>
              <input type="date" v-model="endDate" class="tw-w-full tw-rounded-lg tw-border tw-border-orange-300 tw-px-2 tw-py-1" />

              <button @click.stop="applyCustomDateFilter"
                class="tw-mt-3 tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg tw-w-full">
                Terapkan
              </button>
            </div>
          </div>
        </div>

        <!-- Garis -->
        <div class="tw-h-6 tw-w-px tw-bg-orange-300"></div>

        <!-- Status filter -->
        <div class="dropdown tw-relative">
          <span @click.stop="toggleMenu('status')" class="tw-cursor-pointer tw-text-orange-500 tw-font-semibold tw-text-base hover:tw-text-orange-600">
            Status
          </span>

          <div
            v-show="openMenu === 'status'"
            class="tw-absolute tw-top-full tw-left-0 tw-mt-2 tw-bg-white tw-border tw-border-orange-300 tw-rounded-xl tw-shadow-lg tw-min-w-[180px] tw-z-40"
          >
            <button
              @click.stop="statusFilter = 'Tunai'; applyFilter(); openMenu = null;"
              class="tw-w-full tw-px-4 tw-py-2 tw-text-left tw-font-medium"
              :class="[statusFilter === 'Tunai' ? 'tw-bg-orange-500 tw-text-white' : 'tw-text-orange-600 hover:tw-bg-orange-100']"
            >
              Tunai (Lunas)
            </button>

            <div class="tw-relative">
              <button @click.stop="toggleSubmenu"
                class="tw-w-full tw-px-4 tw-py-2 tw-text-left tw-font-medium tw-flex tw-justify-between tw-items-center hover:tw-bg-orange-100"
                :class="openSubmenu ? 'tw-bg-orange-100' : ''">
                <span class="tw-text-orange-600">Cicilan</span>
                <span class="tw-text-orange-400">{{ openSubmenu ? '▼' : '›' }}</span>
              </button>

              <div
                v-show="openSubmenu"
                class="tw-absolute tw-left-[180px] tw-top-0 tw-bg-white tw-border tw-border-orange-300 tw-rounded-xl tw-shadow-lg tw-min-w-[150px] tw-z-50"
              >
                <button
                  v-for="opt in ['Approved', 'Pending', 'Rejected', 'Active', 'Lunas']"
                  :key="opt"
                  @click.stop="statusFilter = opt; applyFilter(); openSubmenu = false; openMenu = null;"
                  class="tw-w-full tw-px-4 tw-py-2 tw-text-left tw-font-medium"
                  :class="[statusFilter === opt ? 'tw-bg-orange-500 tw-text-white' : 'tw-text-orange-600 hover:tw-bg-orange-100']"
                >
                  {{ opt }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="tw-h-6 tw-w-px tw-bg-orange-300"></div>

        <!-- Sort -->
        <span
          @click="toggleSort"
          class="tw-cursor-pointer tw-text-orange-500 tw-font-semibold tw-text-base hover:tw-text-orange-600 tw-flex tw-items-center tw-gap-1"
        >
          Total Harga
          <span v-if="sortDirection === 'highest'">▲</span>
          <span v-else-if="sortDirection === 'lowest'">▼</span>
        </span>
      </div>
    </div>

    <!-- LIST TRANSAKSI -->
    <div class="tw-p-6 tw-bg-gradient-to-r tw-from-blue-900 tw-to-orange-500 tw-rounded-2xl tw-text-white tw-shadow-xl tw-mt-8">
      <h3 class="tw-text-2xl tw-font-bold tw-mb-6 tw-text-center">
        Daftar Transaksi Anda
      </h3>

      <div v-if="applications.length > 0" class="tw-space-y-4">
        <div
          v-for="app in applications"
          :key="app.id"
          @click="goToDetail(app.id)"
          class="tw-bg-white tw-rounded-2xl tw-shadow-md tw-flex tw-flex-row tw-items-center tw-gap-4 tw-p-4 tw-text-gray-800 hover:tw-scale-[1.01] hover:tw-shadow-lg tw-transition tw-cursor-pointer"
        >
          <img
            :src="app.order?.product_image || '/img/no-image.png'"
            class="tw-w-24 tw-h-24 tw-object-cover tw-rounded-xl"
          />

          <div class="tw-flex-1 tw-text-left tw-flex tw-flex-col tw-gap-1">

            <span class="tw-inline-block tw-border tw-border-orange-500 tw-text-orange-500 tw-bg-white tw-font-semibold tw-rounded-full tw-px-3 tw-py-1 tw-text-xs">
              {{ app.status }}
            </span>

            <p class="tw-text-xs tw-text-gray-500">Kode Produk: #{{ app.id }}</p>

            <p class="tw-font-semibold tw-text-lg tw-text-blue-900 tw-leading-tight">
              {{ app.order?.product_name ?? 'Nama Produk Tidak Tersedia' }}
            </p>

            <p class="tw-text-orange-600 tw-font-bold tw-text-base">
              Rp {{ Number(app.selling_price_total).toLocaleString("id-ID") }}
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
