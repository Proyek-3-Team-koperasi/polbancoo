<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "vue-sonner";

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

// update status jadi Lunas
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
        toast.success("Transaksi berhasil diverifikasi!");
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
          <img
            :src="t.order?.product_image || '/img/no-image.png'"
            alt="Produk"
            class="tw-w-28 tw-h-28 tw-object-cover tw-rounded-xl tw-shadow-sm"
          />

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

      <div v-else class="tw-text-center tw-text-gray-500 tw-font-medium tw-pt-10">
        Tidak ada transaksi yang menunggu verifikasi.
      </div>
    </div>

    <!-- MODAL KONFIRMASI -->
    <div
      v-if="showConfirm"
      class="tw-fixed tw-inset-0 tw-bg-black/50 tw-flex tw-justify-center tw-items-center tw-z-50"
    >
      <div class="tw-bg-white tw-rounded-2xl tw-shadow-lg tw-p-6 tw-w-[90%] md:tw-w-[400px] tw-text-center">
        <h3 class="tw-font-semibold tw-text-lg tw-mb-4">Konfirmasi</h3>
        <p class="tw-mb-6">
          Apakah Anda yakin ingin memverifikasi transaksi ini sebagai
          <span class="tw-font-semibold tw-text-orange-500">LUNAS</span>?
        </p>
        <div class="tw-flex tw-justify-center tw-gap-3">
          <button
            class="tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-font-semibold tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm"
            @click="verifyTransaction"
          >
            Ya
          </button>
          <button
            class="tw-bg-gray-300 hover:tw-bg-gray-400 tw-text-gray-800 tw-font-semibold tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm"
            @click="showConfirm = false"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
