<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MemberQuickActions from "@/Components/member/MemberQuickActions.vue";
import ProductRecommendationCard from "@/Components/member/ProductRecommendationCard.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import AccountSummary from "./partials/account/AccountSummary.vue";
import AccountTransactions from "./partials/account/AccountTransactions.vue";
import AccountSavings from "./partials/account/AccountSavings.vue";
import {
    BanknotesIcon,
    CreditCardIcon,
    ListBulletIcon,
} from "@heroicons/vue/24/solid";
import {
    BanknotesIcon as BanknotesIcon_o,
    CreditCardIcon as CreditCardIcon_o,
    ListBulletIcon as ListBulletIcon_o,
} from "@heroicons/vue/24/outline";

const page = usePage();
const user = computed(() => page.props.auth.user);

const quickActions = computed(() => [
    {
        label: "Keuangan",
        icon: "Wallet",
        href: route("member.finances"),
    },
    {
        label: "Status Pembiayaan",
        icon: "FileText",
        href: route("member.status"),
    },
    {
        label: "Lihat Produk",
        icon: "ShoppingBag",
        href: route("member.products"),
        comingSoon: false,
    },
    {
        label: "Ajukan Murabahah",
        icon: "HandCoins",
        href: null,
        comingSoon: true,
    },
    {
        label: "Setor Simpanan",
        icon: "PiggyBank",
        href: null,
        comingSoon: true,
    },
    {
        label: "Tagihan Cicilan",
        icon: "Receipt",
        href: null,
        comingSoon: true,
    },
]);

const avatar = "/img/avatar.png";

const accountTabs = [
    {
        deactiveIcon: BanknotesIcon_o,
        activeIcon: BanknotesIcon,
        value: "savings",
        label: "Tabungan",
        component: AccountSummary,
    },
    {
        deactiveIcon: ListBulletIcon_o,
        activeIcon: ListBulletIcon,
        value: "transactions",
        label: "Transaksi",
        component: AccountTransactions,
    },
    {
        deactiveIcon: CreditCardIcon_o,
        activeIcon: CreditCardIcon,
        value: "installments",
        label: "Cicilan",
        component: AccountSavings,
    },
];

const activeAccountTab = ref(accountTabs[0].value);

const activeAccountComponent = computed(() => {
    const tab = accountTabs.find(
        (item) => item.value === activeAccountTab.value,
    );

    return tab?.component ?? null;
});

const resolveTabIcon = (tab) => {
    if (!tab) {
        return null;
    }

    const isActive = activeAccountTab.value === tab.value;

    return isActive
        ? (tab.activeIcon ?? tab.deactiveIcon ?? null)
        : (tab.deactiveIcon ?? tab.activeIcon ?? null);
};

const tabIconClasses = (tab) =>
    activeAccountTab.value === tab.value
        ? "tw-text-primary"
        : "tw-text-slate-400";

const recommendedProducts = [
    {
        id: 1,
        title: "iPad Pro 12.9 256GB",
        description:
            "Tablet premium untuk menunjang produktivitas dan kreatifitas harian.",
        price: "20.000.000",
        currency: "Rp. ",
        quantity: 2,
        thumbnail: "https://fastly.jsdelivr.net/npm/@vant/assets/ipad.jpeg",
        tag: "Cash Only",
        tagType: "danger",
    },
    {
        id: 2,
        title: "Laptop Kerja Syariah",
        description:
            "Laptop ringan dengan cicilan murabahah khusus anggota aktif.",
        price: "12.500.000",
        currency: "Rp. ",
        quantity: 1,
        thumbnail: "https://fastly.jsdelivr.net/npm/@vant/assets/ipad.jpeg",
        tag: "Cicilan",
        tagType: "primary",
    },
    {
        id: 3,
        title: "Laptop Kerja Syariah",
        description:
            "Laptop ringan dengan cicilan murabahah khusus anggota aktif.",
        price: "12.500.000",
        currency: "Rp. ",
        quantity: 1,
        thumbnail: "https://fastly.jsdelivr.net/npm/@vant/assets/ipad.jpeg",
        tag: "Cicilan",
        tagType: "primary",
    },
];

const handleQuickAction = (action) => {
    if (action?.href && !action?.comingSoon) {
        router.visit(action.href);
    }
};
</script>

<template>
    <Head title="Member Dashboard" />

    <AuthenticatedLayout>
        <div class="tw-px-6 lg:tw-px-64">
            <div class="tw-mx-auto tw-w-full tw-flex tw-gap-3 md:tw-gap-9">
                <img
                    :src="avatar"
                    alt=""
                    class="tw-rounded-full tw-w-12 md:tw-w-24 tw-h-12 md:tw-h-24"
                />
                <div class="tw-flex-col tw-flex">
                    <span class="tw-text-xl tw-font-extrabold md:tw-text-3xl">
                        {{ user?.name ?? "" }}
                    </span>
                    <span class="tw-text-sm"> 100 Poin Loyalitas </span>
                </div>
            </div>

            <div
                class="tw-mt-5 tw-rounded-xl tw-bg-white tw-p-4 tw-shadow-sm tw-ring-1 tw-ring-slate-100"
            >
                <div
                    class="tw-flex tw-flex-col tw-gap-3 md:tw-flex-row md:tw-items-center md:tw-justify-between"
                >
                    <span class="tw-font-extrabold">Rekening</span>
                    <div
                        class="tw-flex tw-flex-wrap tw-gap-2 tw-border-b tw-pb-[3px] tw-justify-between"
                    >
                        <button
                            v-for="tab in accountTabs"
                            :key="tab.value"
                            type="button"
                            class="tw-inline-flex tw-flex-col tw-items-center tw-gap-2 tw-px-3 tw-py-1 tw-text-sm tw-transition"
                            :class="
                                activeAccountTab === tab.value
                                    ? 'tw-text-primary'
                                    : 'tw-text-slate-600 hover:tw-text-primary'
                            "
                            @click="activeAccountTab = tab.value"
                        >
                            <component
                                v-if="resolveTabIcon(tab)"
                                :is="resolveTabIcon(tab)"
                                :class="['tw-h-8 tw-w-8', tabIconClasses(tab)]"
                            />
                            <span class="tw-text-xs tw-font-semibold">{{
                                tab.label
                            }}</span>
                        </button>
                    </div>
                </div>
                <div class="tw-my-3">
                    <component :is="activeAccountComponent" />
                </div>
            </div>
            <MemberQuickActions
                :actions="quickActions"
                class="tw-mt-6"
                @action-click="handleQuickAction"
            />
            <div
                class="tw-mx-auto tw-mt-6 tw-grid tw-w-full tw-max-w-5xl tw-grid-cols-2 tw-gap-3 md:tw-grid-cols-3"
            >
                <ProductRecommendationCard
                    v-for="product in recommendedProducts"
                    :key="product.id"
                    :title="product.title"
                    :description="product.description"
                    :price="product.price"
                    :currency="product.currency"
                    :quantity="product.quantity"
                    :thumbnail="product.thumbnail"
                    :tag="product.tag"
                    :tag-type="product.tagType"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
