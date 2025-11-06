<template>
    <header
        class="tw-hidden tw-sticky tw-top-0 tw-z-40 tw-border-slate-200 tw-bg-white/95 tw-backdrop-blur md:tw-block"
    >
        <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4">
            <div
                class="tw-border-slate-200 tw-w-full tw-bg-white/95 tw-backdrop-blur tw-px-4 tw-py-3"
            >
                <div
                    class="tw-flex tw-justify-between tw-items-center tw-gap-3"
                >
                    <Link
                        :href="route('member.dashboard')"
                        aria-describedby="member-topbar-home-tooltip"
                        class="group tw-relative tw-inline-flex tw-items-center tw-gap-2 tw-outline-none focus-visible:tw-ring-2 focus-visible:tw-ring-primary/40 focus-visible:tw-ring-offset-2"
                        @mouseenter="showHomeTooltipNow"
                        @mouseleave="hideHomeTooltip"
                        @focus="showHomeTooltipNow"
                        @blur="hideHomeTooltip"
                    >
                        <span class="tw-sr-only">Kembali ke Beranda</span>
                        <h1
                            class="tw-text-xl tw-font-extrabold tw-text-primary"
                        >
                            KopSy Kampus
                        </h1>

                        <span
                            id="member-topbar-home-tooltip"
                            role="tooltip"
                            class="tw-pointer-events-none tw-absolute tw-top-full tw-left-1/2 tw-z-50 tw-inline-flex tw--translate-x-1/2 tw-translate-y-1 tw-scale-95 tw-rounded tw-bg-slate-800 tw-px-3 tw-py-1 tw-text-[11px] tw-text-white tw-shadow-sm tw-opacity-0 tw-transition tw-duration-150"
                            :class="
                                showHomeTooltip
                                    ? 'tw-translate-y-2 tw-scale-100 tw-opacity-100'
                                    : ''
                            "
                        >
                            Kembali ke Beranda
                        </span>
                    </Link>

                    <!-- Search -->
                    <div class="tw-container tw-w-[60%]">
                        <label class="tw-relative tw-flex-1">
                            <span class="tw-sr-only">Cari produk</span>
                            <span
                                class="tw-pointer-events-none tw-absolute tw-inset-y-0 tw-left-4 tw-flex tw-items-center"
                            >
                                <van-icon
                                    name="search"
                                    class="tw-text-lg tw-text-slate-400"
                                />
                            </span>
                            <input
                                v-model="search"
                                @keyup.enter="handleSearch"
                                type="search"
                                name="q"
                                placeholder="Cari produk atau kebutuhan Syariah..."
                                class="tw-h-12 tw-w-full tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-pl-12 tw-pr-5 tw-text-sm tw-text-slate-700 tw-shadow-sm focus:tw-border-primary focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-primary/40"
                            />
                        </label>
                    </div>

                    <!-- Keuangan -->
                    <Link
                        :href="toKeuangan"
                        class="btn-icon tw-relative tw-h-full tw-w-12 tw-flex-shrink-0 tw-flex tw-flex-col"
                    >
                        <Wallet class="tw-text-8xl tw-text-slate-600" />
                        <span class="tw-text-xs">Keuangan</span>
                    </Link>

                    <!-- Status -->
                    <Link
                        :href="toStatus"
                        class="btn-icon tw-relative tw-h-full tw-w-12 tw-flex-shrink-0 tw-flex tw-flex-col"
                    >
                        <LayoutList class="tw-text-3xl tw-text-slate-600" />
                        <span class="tw-text-xs"> Status </span>
                    </Link>

                    <!-- Keranjang -->
                    <Link
                        :href="toCart"
                        class="btn-icon tw-relative tw-h-full tw-w-12 tw-flex-shrink-0 tw-flex tw-flex-col"
                    >
                        <ShoppingCart class="tw-text-3xl tw-text-slate-600" />
                        <span
                            class="tw-absolute tw--right-1 tw--top-1 tw-inline-flex tw-h-5 tw-min-w-[1.25rem] tw-items-center tw-justify-center tw-rounded-full tw-bg-primary tw-px-1 tw-text-[10px] tw-font-semibold tw-text-white"
                        >
                            2
                        </span>
                        <span class="tw-text-xs"> Keranjang </span>
                    </Link>

                    <span class="tw-border-e"> &nbsp; </span>

                    <!-- Akun -->
                    <Link
                        :href="toProfile"
                        class="btn-icon tw-relative tw-h-full tw-w-12 tw-flex-shrink-0 tw-flex tw-flex-col"
                    >
                        <CircleUserRound
                            class="tw-text-6xl tw-text-slate-600"
                        />
                        <span class="tw-text-xs"> Akun </span>
                    </Link>
                </div>
            </div>
            <!-- <div class="tw-flex tw-items-center tw-gap-2"> -->
            <!-- </div> -->
        </div>
    </header>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    CircleUserRound,
    ShoppingCart,
    LayoutList,
    Wallet,
} from "lucide-vue-next";
const props = defineProps({
    links: {
        type: Array,
        default: () => [],
    },
    user: {
        type: Object,
        default: null,
    },
    toKeuangan: {
        type: String,
        default: route("member.finances"),
    },
    toStatus: {
        type: String,
        default: route("member.status"),
    },
    toCart: {
        type: String,
        default: route("member.cart"),
    },
    toProfile: {
        type: String,
        default: route("member.profile"),
    },
});

const search = ref("");
const showHomeTooltip = ref(false);

const handleSearch = () => {
    if (search.value.trim() !== "") {
        router.get(route("member.products"), { q: search.value });
    } else {
        router.get(route("member.products"));
    }
};

const showHomeTooltipNow = () => {
    showHomeTooltip.value = true;
};

const hideHomeTooltip = () => {
    showHomeTooltip.value = false;
};
</script>
