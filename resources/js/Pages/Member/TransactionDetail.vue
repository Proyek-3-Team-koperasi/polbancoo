<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed } from "vue";
import dayjs from "dayjs";

const props = defineProps({
  transaction: Object,
});

const goBack = () => {
  router.visit(route("member.status"));
};

const items = computed(() => props.transaction.order?.items || []);

const totalSell = computed(() =>
  items.value.reduce((sum, i) => sum + Number(i.qty) * Number(i.sell_price), 0)
);

const tenorPeriod = computed(() => {
  if (!props.transaction?.tenor || !props.transaction?.approval_date) return null;
  const start = dayjs(props.transaction.approval_date);
  const end = start.add(props.transaction.tenor, "month");
  return {
    start: start.format("DD MMM YYYY"),
    end: end.format("DD MMM YYYY"),
  };
});

const tenorProgress = computed(() => {
  if (!props.transaction?.tenor || !props.transaction?.approval_date) return null;
  const start = dayjs(props.transaction.approval_date);
  const now = dayjs();
  const diffMonths = now.diff(start, "month");
  const current = Math.min(diffMonths + 1, props.transaction.tenor);
  return {
    current,
    total: props.transaction.tenor,
    percent: Math.round((current / props.transaction.tenor) * 100),
  };
});
</script>

<template>
  <Head title="Detail Transaksi" />

  <AuthenticatedLayout>
    <template #header>
      <van-nav-bar title="Detail Transaksi" left-text="Kembali" left-arrow @click-left="goBack" />
    </template>

    <div class="tw-p-6 tw-bg-gray-50 tw-min-h-screen tw-text-gray-800 tw-space-y-10">

      <section class="tw-space-y-4">
        <span class="tw-inline-block tw-border tw-border-orange-500 tw-text-orange-500 tw-font-semibold tw-rounded-full tw-px-4 tw-py-1 tw-text-base">
          {{ transaction.status }}
        </span>

        <p class="tw-text-gray-500 tw-text-lg">Kode Transaksi: #{{ transaction.id }}</p>

        <p class="tw-text-blue-900 tw-font-extrabold tw-text-2xl">
          Rp {{ Number(transaction.selling_price_total || totalSell).toLocaleString("id-ID") }}
        </p>

        <div class="tw-space-y-2 tw-text-lg">
          <p>
            <strong>Jenis Pembayaran:</strong>
            {{ transaction.order?.payment_method ?? "Tidak diketahui" }}
          </p>

          <p>
            <strong>Tanggal Transaksi:</strong>
            {{
              new Date(transaction.created_at).toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric"
              })
            }}
          </p>

          <template v-if="transaction.order?.payment_method === 'Murabahah'">
            <p v-if="transaction.tenor"><strong>Tenor:</strong> {{ transaction.tenor }} bulan</p>
            <p v-if="tenorPeriod"><strong>Periode Cicilan:</strong> {{ tenorPeriod.start }} – {{ tenorPeriod.end }}</p>
            <p v-if="transaction.monthly_installment">
              <strong>Angsuran per Bulan:</strong>
              Rp {{ Number(transaction.monthly_installment).toLocaleString("id-ID") }}
            </p>
          </template>
        </div>
      </section>

      <hr class="tw-border-gray-300" />

      <section class="tw-space-y-6">
        <h3 class="tw-font-bold tw-text-2xl tw-text-orange-500">Daftar Produk</h3>

        <div v-for="(item, i) in items" :key="i" class="tw-pb-5 tw-border-b tw-border-gray-300">
          <div class="tw-flex tw-items-center tw-gap-4">
            <img
              :src="item.image || '/img/no-image.png'"
              class="tw-w-20 tw-h-20 tw-object-cover tw-rounded-xl"
            />

            <div class="tw-flex-1">
              <p class="tw-text-xl tw-font-semibold tw-text-blue-900">
                {{ item.name }}
              </p>
            </div>
          </div>

          <div class="tw-mt-3 tw-flex tw-flex-wrap tw-gap-x-8 tw-gap-y-2 tw-text-lg tw-text-gray-700">
            <p><strong>Qty:</strong> {{ item.qty }}</p>

            <p>
              <strong>Harga:</strong>
              Rp {{ Number(item.sell_price).toLocaleString("id-ID") }}
            </p>

            <p>
              <strong>Subtotal:</strong>
              Rp {{
                (Number(item.qty) * Number(item.sell_price)).toLocaleString("id-ID")
              }}
            </p>
          </div>
        </div>

        <div class="tw-text-right tw-font-extrabold tw-text-orange-600 tw-text-2xl">
          Total:
          Rp {{ Number(transaction.selling_price_total || totalSell).toLocaleString("id-ID") }}
        </div>
      </section>

      <hr class="tw-border-gray-300" />

      <section v-if="tenorProgress" class="tw-space-y-4">
        <h3 class="tw-font-bold tw-text-2xl tw-text-gray-800">Progress Cicilan</h3>

        <p class="tw-text-lg">
          Sudah berlangsung
          <strong>{{ tenorProgress.current }}</strong>
          dari
          <strong>{{ tenorProgress.total }}</strong> bulan
        </p>

        <div class="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-4 tw-overflow-hidden">
          <div
            class="tw-bg-orange-500 tw-h-full"
            :style="{ width: tenorProgress.percent + '%' }"
          ></div>
        </div>
      </section>

      <div class="tw-text-center tw-pt-4">
        <van-button
          round
          size="large"
          class="tw-font-semibold tw-bg-orange-500 tw-text-white tw-px-8 tw-py-3 hover:tw-opacity-90 tw-shadow-md tw-transition-all"
          @click="router.visit(route('member.products'))"
        >
          Lihat Produk Lainnya
        </van-button>
      </div>

    </div>
  </AuthenticatedLayout>
</template>
