<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Toast } from "vant";
import "vant/es/toast/style";

const props = defineProps({
  transactions: Array,
});

const trx = ref(JSON.parse(JSON.stringify(props.transactions)));
const selected = ref(null);
const showConfirm = ref(false);

// buka dialog konfirmasi
const confirmVerify = (transaction) => {
  selected.value = transaction;
  showConfirm.value = true;
};

// kirim ke backend (update status jadi Lunas)
const verifyTransaction = () => {
  if (!selected.value) return;

  const url = route("admin.transactions.verify", selected.value.id);

  router.post(
    url,
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        const index = trx.value.findIndex((t) => t.id === selected.value.id);
        if (index !== -1) {
          trx.value[index].status = "Lunas";
        }
        showConfirm.value = false;

        // tampilkan toast
        Toast({
          message: "Transaksi berhasil diverifikasi",
          position: "bottom",
          duration: 2000,
          className: "custom-toast",
        });
      },
    }
  );
};
</script>

<template>
  <Head title="Verifikasi Transaksi" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="tw-text-xl tw-font-semibold tw-text-gray-800">
        Verifikasi Transaksi
      </h2>
    </template>

    <!-- WRAPPER -->
    <div class="tw-p-6 tw-bg-gray-50 tw-min-h-screen tw-space-y-6">
      <h3 class="tw-text-2xl tw-font-bold tw-text-center tw-text-blue-900">
        Daftar Transaksi Tunai
      </h3>

      <div v-if="trx.length > 0" class="tw-space-y-4 tw-mt-4">
        <div
          v-for="t in trx"
          :key="t.id"
          class="tw-bg-white tw-rounded-2xl tw-shadow-md tw-p-4 tw-flex tw-flex-col md:tw-flex-row tw-items-center md:tw-items-start tw-gap-4 tw-transition hover:tw-scale-[1.01]"
        >
          <!-- FOTO PRODUK -->
          <img
            :src="t.order?.product_image || '/img/no-image.png'"
            alt="Produk"
            class="tw-w-28 tw-h-28 tw-object-cover tw-rounded-xl tw-shadow-sm"
          />

          <!-- INFORMASI -->
          <div class="tw-flex-1 tw-text-left tw-space-y-1">
            <span
              class="tw-inline-block tw-border tw-border-orange-500 tw-text-orange-500 tw-bg-white tw-font-semibold tw-rounded-full tw-px-3 tw-py-1 tw-text-xs"
            >
              {{ t.status }}
            </span>

            <p class="tw-text-xs tw-text-gray-500">Kode Transaksi: #{{ t.id }}</p>
            <p class="tw-font-semibold tw-text-lg tw-text-blue-900 tw-leading-tight">
              {{ t.order?.product_name ?? "Nama Produk Tidak Tersedia" }}
            </p>
            <p class="tw-text-sm tw-text-gray-700">
              Anggota:
              <strong>{{ t.member?.name ?? "Tidak diketahui" }}</strong>
            </p>
            <p class="tw-text-orange-600 tw-font-bold tw-text-base">
              Rp {{ Number(t.selling_price_total).toLocaleString("id-ID") }}
            </p>
          </div>

          <!-- TOMBOL AKSI -->
          <div class="tw-flex tw-gap-2 md:tw-flex-col tw-mt-2 md:tw-mt-0">
            <button
              v-if="t.status === 'Pending'"
              class="tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-font-semibold tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm tw-transition"
              @click="confirmVerify(t)"
            >
              Verifikasi
            </button>

            <span
              v-else
              class="tw-text-sm tw-font-semibold tw-text-blue-900 tw-italic"
            >
              Sudah diverifikasi
            </span>
          </div>
        </div>
      </div>

      <div
        v-else
        class="tw-text-center tw-text-gray-500 tw-font-medium tw-pt-10"
      >
        Tidak ada transaksi yang menunggu verifikasi.
      </div>
    </div>

    <!-- MODAL KONFIRMASI -->
    <van-dialog
      v-model:show="showConfirm"
      title="Konfirmasi"
      show-cancel-button
      confirm-button-text="Ya"
      cancel-button-text="Batal"
      @confirm="verifyTransaction"
    >
      Apakah Anda yakin ingin memverifikasi transaksi ini sebagai
      <span class="tw-font-semibold tw-text-orange-500">LUNAS</span>?
    </van-dialog>
  </AuthenticatedLayout>
</template>

<style>
.custom-toast {
  background-color: rgba(255, 255, 255, 0.95);
  color: #ea580c; 
  font-weight: 600;
  border: 1px solid #f97316;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 8px 16px;
}
</style>
