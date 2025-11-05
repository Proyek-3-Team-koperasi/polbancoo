<script setup>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
    import { Head, router } from "@inertiajs/vue3";
    import { ref, computed } from "vue";

    const props = defineProps({
    totalSaldo: Number,
    simpanan: Object,
    lastUpdate: String,
    historytanggalsimpanan: Object,
    historynominalsimpanan: Object,
    historytopup: Object
    });

    const allHistory = computed(() => {
        const diterima = props.historytopup.historyditerima?.map(item => ({
            nominal: item.amount,
            tanggal: new Date(item.transaction_date),
            status: 'Diterima',
            admin_user_id: item.admin_user_id
        })) || [];

        const ditolak = props.historytopup.historyditolak?.map(item => ({
            nominal: item.amount,
            tanggal: new Date(item.transaction_date),
            status: 'Ditolak',
            admin_user_id: item.admin_user_id
        })) || [];

        const pending = props.historytopup.historypending?.map(item => ({
            nominal: item.amount,
            tanggal: new Date(item.transaction_date),
            status: 'Pending',
            admin_user_id: item.admin_user_id
        })) || [];

        // Spread Operator //
        return [...pending, ...diterima, ...ditolak].sort((a, b) =>
            new Date(b.tanggal) - new Date(a.tanggal)
        );
    });

    const isOpen = ref(false);

    function toggleMenu() {
    isOpen.value = !isOpen.value;
    }

    function tambahWajib() {
        router.visit('/member/finances/add/wajib');
    }

    function tambahSukarela() {
        router.visit('/member/finances/add/sukarela');
    }
</script>


<template>
    <Head title="Keuangan Anggota" />

    <AuthenticatedLayout>
        <template #header>
        <h2 class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800">
            Keuangan Saya
        </h2>
        </template>

        <!-- Total Saldo -->
        <div class="tw-bg-gray-50 tw-p-5 tw-flex tw-flex-col tw-items-center tw-gap-5">
            <div class="tw-bg-gradient-to-r tw-from-orange-500 tw-to-orange-400 tw-rounded-2xl tw-w-full tw-text-white tw-p-6 tw-shadow-lg" >
                <h3 class="tw-text-lg tw-font-medium">Total Saldo</h3>
                <p class="tw-text-3xl tw-font-bold tw-mt-2">
                Rp {{ totalSaldo.toLocaleString("id-ID") }}
                </p>
                <p class="tw-text-sm tw-opacity-80 tw-mt-1">Update: {{ lastUpdate }}</p>
            </div>

            <!-- Simpanan -->
            <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-w-full">
                <!-- Simpanan Pokok -->
                <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-4 tw-flex tw-justify-between tw-items-center" >
                    <div>
                        <span class="tw-text-gray-700 tw-font-medium">Simpanan Pokok</span>
                    </div>
                    <div class="tw-text-right">
                        <p class="tw-text-orange-600 tw-font-semibold">
                        Rp {{ props.simpanan.pokok.toLocaleString("id-ID") }}
                        </p>
                    </div>
                </div>

                <!-- Simpanan Wajib -->
                <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-4 tw-flex tw-justify-between tw-items-center" >
                    <div>
                        <span class="tw-text-gray-700 tw-font-medium">Simpanan Wajib</span>
                    </div>
                    <div class="tw-text-right">
                        <p class="tw-text-orange-600 tw-font-semibold">
                        Rp {{ props.simpanan.wajib.toLocaleString("id-ID") }}
                        </p>
                    </div>
                </div>

                <!-- Simpanan Sukarela -->
                <div class="tw-bg-white tw-rounded-xl tw-shadow-sm tw-p-4 tw-flex tw-justify-between tw-items-center" >
                    <div>
                        <span class="tw-text-gray-700 tw-font-medium">Simpanan Sukarela</span>
                    </div>
                    <div class="tw-text-right">
                        <p class="tw-text-orange-600 tw-font-semibold">
                        Rp {{ props.simpanan.sukarela.toLocaleString("id-ID") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="tw-p-5 tw-flex tw-flex-col tw-items-center tw-gap-5"> 
            <div class="tw-rounded-2xl tw-w-full tw-p-6 tw-shadow-xl"> 
                <h1 class="tw-text-xl tw-font-bold tw-text-center tw-text-black tw-mb-6 tw-tracking-wide"> 
                History Top Up Simpanan
                </h1> 

                <div class="tw-max-h-64 tw-overflow-y-auto tw-space-y-2">
                    <!-- Ambil Semua History Top Up -->
                    <div
                    v-for="(item, index) in allHistory"
                    :key="index"
                    class="tw-rounded-xl tw-p-4 tw-shadow-md tw-flex tw-justify-between tw-items-center tw-mb-3 tw-text-white"
                    :class="{
                        'tw-bg-green-600': item.admin_user_id !== null && item.admin_user_id !== 0,
                        'tw-bg-yellow-500': item.admin_user_id === null,
                        'tw-bg-red-600': item.admin_user_id === 0
                    }"
                    >
                        <div>
                            <p class="tw-font-semibold tw-text-lg">Rp {{ Number(item.nominal).toLocaleString('id-ID') }}</p>
                            <p class="tw-text-sm tw-opacity-90">{{ item.tanggal.toLocaleDateString('id-ID') }}</p>
                        </div>
                        <span class="tw-capitalize tw-font-medium">{{ item.status }}</span>
                    </div>
                </div>
            </div> 
        </div>

        <div class="tw-p-5 tw-flex tw-flex-col tw-items-center tw-gap-5"> 
            <div class="tw-rounded-2xl tw-w-full tw-text-white tw-p-6 tw-shadow-xl"> 
                <h1 class="tw-text-xl tw-font-bold tw-text-center tw-text-black tw-mb-6 tw-tracking-wide"> 
                    History Simpanan Anggota 
                </h1>
                <!-- History yang udah diterima (Udah masuk ke DB savings_accounts) -->
                <van-cell-group>
                    <van-cell 
                        title="Simpanan Pokok" 
                        :value= "'Rp ' + Number(props.historynominalsimpanan.nominalhistorypokok ?? 0).toLocaleString('id-ID')" 
                        :label="props.historytanggalsimpanan.historypokok" 
                    /> 
                    <van-cell 
                        title="Simpanan Wajib" 
                        :value= "'Rp ' + Number(props.historynominalsimpanan.nominalhistorywajib ?? 0).toLocaleString('id-ID')" 
                        :label="props.historytanggalsimpanan.historywajib" 
                    /> 
                    <van-cell 
                        title="Simpanan Sukarela" 
                        :value= "'Rp ' + Number(props.historynominalsimpanan.nominalhistorysukarela ?? 0).toLocaleString('id-ID')" 
                        :label="props.historytanggalsimpanan.historysukarela" 
                    /> 
                </van-cell-group>
            </div> 
        </div>

        <div>
            <!-- Floating Action Button -->
            <button class="tw-fixed tw-bottom-20 tw-right-6 tw-bg-orange-500 tw-text-white tw-rounded-full tw-w-14 tw-h-14 tw-flex tw-items-center tw-justify-center tw-shadow-lg tw-cursor-pointer tw-transition-transform hover:tw-scale-110 tw-z-50"
            @click="toggleMenu">
                <span class="tw-text-3xl tw-font-bold tw-transition-transform tw-duration-300"
                >+</span>
            </button>

            <!-- Menu tambahan -->
            <transition name="fade">
                <div v-if="isOpen"
                    class="tw-fixed tw-bottom-36 tw-right-6 tw-flex tw-flex-col tw-items-center tw-space-y-3 tw-z-50 tw-rounded-full"
                >
                    <van-button
                    type="primary"
                    icon="paid"
                    class="tw-w-14 tw-h-14 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-shadow-lg tw-transition hover:tw-scale-110"
                    @click="tambahWajib"
                    >
                    </van-button>
                    <van-button
                    type="success"
                    icon="peer-pay"
                    class="tw-w-14 tw-h-14 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-shadow-lg tw-transition hover:tw-scale-110"
                    @click="tambahSukarela"
                    >
                    </van-button>
                </div>
            </transition>
        </div>
    </AuthenticatedLayout>
</template>