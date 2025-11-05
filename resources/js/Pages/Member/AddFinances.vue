<script setup>
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import { ref, computed } from "vue";
  import { usePage, router, Head } from "@inertiajs/vue3";
  import { showToast, showSuccessToast } from "vant";

  const { props } = usePage();
  const title = props.title;
  const type = props.type;
  const fixedNominal = props.fixedNominal;
  const mappedType = type === "wajib" ? "Mandatory" : "Voluntary";

  const nominal = ref(fixedNominal || "");
  const catatan = ref("");
  const buktiTransfer = ref(null);
  const isWajib = computed(() => type === "wajib");

  const presetNominal = [10000, 50000, 100000, 200000, 300000, 500000];

  function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
      buktiTransfer.value = file;
    }
  }

  function simpanData() {
        if (!nominal.value) {
            showToast(
              {
                message: 'Isi Nominal!',
                icon: 'fail',
                duration: 2500
              }
            );
            return;
        }

        if (!buktiTransfer.value) {
          showToast({
            message: "Upload bukti transfer!",
            icon: "fail",
            duration: 2500,
          });
          return;
        }

        const formData = new FormData();
        formData.append("type", mappedType);
        formData.append("amount", nominal.value);
        formData.append("description", catatan.value);
        formData.append("bukti_transfer", buktiTransfer.value);

        router.post("/member/finances/store", formData, {
            onSuccess: () => {
            showSuccessToast('Berhasil!');
            return router.visit("/member/finances");
            },
            onError: (errors) => {
            showFailToast(errors);
            },
        });
    }
</script>

<template>
  <Head title="Tambah Simpanan" />

  <AuthenticatedLayout>
    <div class="tw-min-h-screen tw-bg-gray-50 tw-p-4">
      <div class="tw-flex tw-items-center tw-gap-2 tw-mb-4">
        <van-icon name="arrow-left" @click="$inertia.visit('/member/finances')" />
        <h2 class="tw-text-lg tw-font-semibold tw-text-center">{{ title }}</h2>
      </div>

      <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-md">
        <div class="tw-text-gray-700 tw-font-medium tw-mb-2">Nominal Simpanan</div>

        <div class="tw-flex tw-gap-2 tw-mb-4">
          <!-- Kalau simpanan wajib, field ini gabisa diubah -->
          <van-field
            v-model="nominal"
            label="Rp"
            type="number"
            placeholder="Masukkan nominal"
            :disabled="isWajib" 
            class="tw-w-full"
          />
        </div>

        <!-- Simpanan Sukarela bisa nampilin rekomendasi nominal topup-->
        <div v-if="!isWajib" class="tw-grid tw-grid-cols-3 tw-gap-2 tw-mb-4">
          <van-button
            v-for="amount in presetNominal"
            :key="amount"
            type="default"
            round
            @click="nominal = amount"
            :class="{
              'tw-border-orange-500 tw-text-orange-600': nominal == amount,
            }"
          >
            Rp {{ amount.toLocaleString('id-ID') }}
          </van-button>
        </div>

        <div class="tw-mb-4">
          <div class="tw-text-gray-700 tw-font-medium tw-mb-1">
            Bukti Transfer
          </div>
          <input
            type="file"
            @change="handleFileChange"
            accept="image/*"
            class="tw-border tw-p-2 tw-rounded tw-w-full"
          />
        </div>

        <div>
          <div class="tw-text-gray-700 tw-font-medium tw-mb-1">Catatan</div>
          <van-field
            v-model="catatan"
            placeholder="Tambahkan catatan (opsional)"
            rows="2"
            type="textarea"
            maxlength="50"
            show-word-limit
          />
        </div>
      </div>


      <div class="tw-mt-6">
        <van-button
          block
          type="primary"
          color="#ff6600"
          class="tw-font-semibold"
          @click="simpanData()"
        >
          Simpan
        </van-button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>