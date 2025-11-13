<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { toast } from "vue-sonner";

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";

import { Badge } from "@/components/ui/badge";

const props = defineProps({
  transactions: Array,
});

const trx = ref(JSON.parse(JSON.stringify(props.transactions)));

const search = ref("");
const statusFilter = ref("Semua");
const dateFilter = ref("Semua");

const selected = ref(null);
const showFullDetail = ref(false);
const detailItems = ref([]);

const calcTotalSell = (items) => {
  if (!items || !Array.isArray(items)) return 0;
  return items.reduce(
    (sum, i) =>
      sum +
      (Number(i.qty) || 0) *
        (Number(i.sell_price) || Number(i.sellPrice) || 0),
    0
  );
};

const calcTotalBuy = (items) => {
  if (!items || !Array.isArray(items)) return 0;
  return items.reduce(
    (sum, i) =>
      sum +
      (Number(i.qty) || 0) *
        (Number(i.buy_price) || Number(i.buyPrice) || 0),
    0
  );
};

const totalSell = computed(() =>
  detailItems.value.reduce(
    (sum, i) => sum + (Number(i.qty) || 0) * (Number(i.sellPrice) || 0),
    0
  )
);

const totalBuy = computed(() =>
  detailItems.value.reduce(
    (sum, i) =>
      sum + (Number(i.qty) || 0) * (Number(i.buyPrice) || 0),
    0
  )
);

const profit = computed(() => totalSell.value - totalBuy.value);

const filteredTransactions = computed(() => {
  const now = new Date();

  return trx.value.filter((t) => {
    const matchName = t.member?.name
      ?.toLowerCase()
      .includes(search.value.toLowerCase());
    const matchStatus =
      statusFilter.value === "Semua" || t.status === statusFilter.value;

    const trxDate = new Date(t.created_at);
    let matchDate = true;

    if (dateFilter.value === "7") {
      const diffDays = (now - trxDate) / (1000 * 60 * 60 * 24);
      matchDate = diffDays <= 7;
    } else if (dateFilter.value === "30") {
      const diffDays = (now - trxDate) / (1000 * 60 * 60 * 24);
      matchDate = diffDays <= 30;
    }

    return matchName && matchStatus && matchDate;
  });
});

const openDetail = (transaction) => {
  selected.value = transaction;

  const itemsSource =
    transaction.items ||
    transaction.order_items ||
    transaction.order?.items ||
    [];

  detailItems.value = itemsSource.map((it) => ({
    name: it.name ?? it.product_name ?? "-",
    qty: Number(it.qty ?? it.quantity ?? 1),
    buyPrice: Number(it.buy_price ?? 0),
    sellPrice: Number(it.sell_price ?? 0),
  }));

  showFullDetail.value = true;
};

const closeFullDetail = () => {
  showFullDetail.value = false;
};

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
        if (index !== -1) trx.value[index].status = "Lunas";
        selected.value.status = "Lunas";
        toast.success("Transaksi berhasil diverifikasi");
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

    <div class="tw-bg-gray-50 tw-min-h-screen tw-p-8">
      <div
        class="tw-bg-white tw-p-4 tw-rounded-xl tw-shadow-sm tw-flex tw-flex-wrap tw-gap-4 tw-items-center tw-mb-6"
      >
        <input
          v-model="search"
          type="text"
          placeholder="Cari nama anggota..."
          class="tw-border tw-border-orange-400 tw-text-orange-600 tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm tw-w-72 focus:tw-ring-1 focus:tw-ring-orange-500"
        />

        <select
          v-model="statusFilter"
          class="tw-border tw-border-orange-400 tw-text-orange-600 tw-rounded-lg tw-px-6 tw-py-2 tw-text-sm"
        >
          <option value="Semua">Semua Status</option>
          <option value="Pending">Pending</option>
          <option value="Lunas">Lunas</option>
        </select>

        <select
          v-model="dateFilter"
          class="tw-border tw-border-orange-400 tw-text-orange-600 tw-rounded-lg tw-px-6 tw-py-2 tw-text-sm"
        >
          <option value="Semua">Semua Tanggal</option>
          <option value="7">7 Hari Terakhir</option>
          <option value="30">30 Hari Terakhir</option>
        </select>
      </div>

      <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-overflow-hidden">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Anggota</TableHead>
              <TableHead>Jumlah</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Tanggal</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow
              v-for="t in filteredTransactions"
              :key="t.id"
              class="tw-cursor-pointer"
              @click="openDetail(t)"
            >
              <TableCell>{{ t.member?.name ?? "-" }}</TableCell>

              <TableCell>
                Rp {{ calcTotalSell(t.items).toLocaleString("id-ID") }}
              </TableCell>

              <TableCell>
                <Badge>{{ t.status }}</Badge>
              </TableCell>

              <TableCell>
                {{
                  new Date(t.created_at).toLocaleDateString("id-ID", {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                  })
                }}
              </TableCell>
            </TableRow>

            <TableRow v-if="filteredTransactions.length === 0">
              <TableCell colspan="4" class="tw-text-center tw-py-6">
                Tidak ada transaksi ditemukan.
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </div>

    <div
      v-if="showFullDetail"
      class="tw-fixed tw-inset-0 tw-bg-black/40 tw-flex tw-justify-center tw-items-center tw-z-50 tw-overflow-y-auto tw-p-4"
      @click.self="closeFullDetail"
    >
      <div
        class="tw-bg-white tw-rounded-xl tw-shadow-lg tw-w-full md:tw-w-[700px] tw-p-6 tw-space-y-6"
      >
        <h3 class="tw-font-semibold tw-text-lg tw-text-gray-800">
          Biodata Peminjam
        </h3>

        <div class="tw-space-y-1 tw-text-sm">
          <p>Nama: {{ selected.member?.name ?? "-" }}</p>
          <p>Nomor Telepon: 08123456789</p>
          <p>Alamat: Jl. Contoh, Contoh, Contoh</p>
          <p>NIM/NIDN/NIP: 1234567890</p>
          <p>Fakultas/Unit Kerja: Teknik Informatika</p>
          <p>Status Keanggotaan: Aktif sejak 1 Januari 2023</p>
        </div>

        <h3 class="tw-font-semibold tw-text-lg tw-text-gray-800">
          Rincian Pengajuan
        </h3>

        <table class="tw-w-full tw-text-sm tw-border-collapse">
          <thead class="tw-bg-orange-50 tw-text-gray-700">
            <tr>
              <th class="tw-text-left tw-px-3 tw-py-2">Nama Barang</th>
              <th class="tw-text-right tw-px-3 tw-py-2">Jumlah</th>
              <th class="tw-text-right tw-px-3 tw-py-2">Harga Beli</th>
              <th class="tw-text-right tw-px-3 tw-py-2">Harga Jual</th>
              <th class="tw-text-right tw-px-3 tw-py-2">Subtotal Beli</th>
              <th class="tw-text-right tw-px-3 tw-py-2">Subtotal Jual</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(item, i) in detailItems"
              :key="i"
              class="tw-border-b tw-border-gray-100"
            >
              <td class="tw-px-3 tw-py-2">{{ item.name }}</td>
              <td class="tw-px-3 tw-py-2 tw-text-right">{{ item.qty }}</td>
              <td class="tw-px-3 tw-py-2 tw-text-right">
                Rp {{ item.buyPrice.toLocaleString("id-ID") }}
              </td>
              <td class="tw-px-3 tw-py-2 tw-text-right">
                Rp {{ item.sellPrice.toLocaleString("id-ID") }}
              </td>
              <td class="tw-px-3 tw-py-2 tw-text-right">
                Rp {{ (item.qty * item.buyPrice).toLocaleString("id-ID") }}
              </td>
              <td class="tw-px-3 tw-py-2 tw-text-right">
                Rp {{ (item.qty * item.sellPrice).toLocaleString("id-ID") }}
              </td>
            </tr>
          </tbody>
        </table>

        <div class="tw-text-right tw-text-sm tw-space-y-1 tw-pt-2">
          <p>Total Beli: Rp {{ totalBuy.toLocaleString("id-ID") }}</p>
          <p>Total Jual: Rp {{ totalSell.toLocaleString("id-ID") }}</p>
          <p class="tw-font-semibold tw-text-orange-600">
            Keuntungan: Rp {{ profit.toLocaleString("id-ID") }}
          </p>
        </div>

        <div class="tw-flex tw-justify-end tw-gap-3 tw-pt-4">
          <button
            class="tw-bg-gray-300 hover:tw-bg-gray-400 tw-text-gray-800 tw-font-medium tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm"
            @click="closeFullDetail"
          >
            Tutup
          </button>

          <button
            v-if="selected.status === 'Pending'"
            class="tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-font-medium tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm"
            @click="verifyTransaction"
          >
            Verifikasi
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
