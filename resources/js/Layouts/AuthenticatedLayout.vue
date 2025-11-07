<script setup>
import { computed, nextTick, ref, watch } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { Settings } from "lucide-vue-next";

import AdminSidebar from "@/Components/navigation/AdminSidebar.vue";
import AdminMobileNav from "@/Components/navigation/AdminMobileNav.vue";
import MemberTopBar from "@/Components/navigation/MemberTopBar.vue";
import MemberBottomNav from "@/Components/navigation/MemberBottomNav.vue";
import MemberMobileSearchBar from "@/Components/navigation/MemberMobileSearchBar.vue";
import MemberMobileTopBar from "@/Components/navigation/MemberMobileTopBar.vue";
import CartIcon from "@/Components/CartIcon.vue";
import { toast, Toaster } from "vue-sonner";
import 'vue-sonner/style.css'

const page = usePage();

const roles = computed(() => new Set(page.props.auth?.roles ?? []));
const user = computed(() => page.props.auth?.user ?? null);

const hasRole = (role) => roles.value.has(role);

const isSuperAdmin = computed(() => hasRole("Super Admin"));
const isAdminLike = computed(() => isSuperAdmin.value || hasRole("Admin"));



const superAdminLinks = [
    {
        name: "superadmin.admins.index",
        label: "Kelola Admin",
        icon: "manager-o",
        patterns: ["superadmin.admins.*"],
    },
    {
        name: "superadmin.system-configuration.index",
        label: "Konfigurasi Sistem",
        icon: "manager-o",
        patterns: ["superadmin.system-configuration.*"],
    },
    {
        name: "profile.edit",
        label: "Profile",
        icon: "user-o",
        patterns: ["profile.*"],
    },
];

const memberLinks = [
    {
        name: "member.dashboard",
        label: "Home",
        icon: "wap-home-o",
        patterns: ["member.dashboard"],
    },
    {
        name: "member.finances",
        label: "Keuangan",
        icon: "balance-list-o",
        iconFilled: "balance-list",
        patterns: ["member.finances", "member.finances.*"],
    },
    {
        name: "member.status",
        label: "Status",
        icon: "todo-list-o",
        iconFilled: "todo-list",
        patterns: ["member.status", "member.status.*"],
    },
    {
        name: "profile.edit",
        label: "Profile",
        icon: "user-o",
        iconFilled: "user",
        patterns: ["profile.edit"],
    },
];

const adminLinks = [
    {
        name: "admin.dashboard",
        label: "Dashboard",
        icon: "location-o",
        patterns: ["admin.dashboard"],
    },
    {
        name: "admin.members.index",
        label: "Kelola Anggota",
        icon: "user-o",
        patterns: ["admin.members.*"],
    },
    {
        name: "admin.product-categories.index",
        label: "Kelola Kategori Produk",
        icon: "apps-o",
        patterns: ["admin.product-categories.*"],
    },
    {
        name: "admin.products.index",
        label: "Kelola Produk",
        icon: "send-gift-o",
        patterns: ["admin.products.*"],
    },
    {
        name: "admin.transactions.index",
        label: "Verifikasi Transaksi",
        icon: "orders-o",
        patterns: ["admin.transactions.*"],
    },
    {
        name: "admin.installments.index",
        label: "Verifikasi Cicilan",
        icon: "credit-pay",
        patterns: ["admin.installments.*"],
    },
    {
        name: "admin.savings.index",
        label: "Verifikasi Simpanan",
        icon: "balance-o",
        patterns: ["admin.savings.*"],
    },
    {
        name: "admin.shu.index",
        label: "SHU",
        icon: "description-o",
        patterns: ["admin.shu.*"],
    },
    {
        name: "profile.edit",
        label: "Profile",
        icon: "user-o",
        patterns: ["profile.edit"],
    },
];

const sidebarOpen = ref(false);

const isActive = (patterns) =>
    (patterns ?? []).some((pattern) => route().current(pattern));

const currentSidebarLinks = computed(() =>
    (isSuperAdmin.value ? superAdminLinks : adminLinks).map((link) => ({
        ...link,
        href: route(link.name),
        active: isActive(link.patterns ?? [link.name]),
    })),
);

const sidebarTitle = computed(() =>
    isSuperAdmin.value ? "KopSy Super Admin" : "KopSy Admin",
);

const curTitle = computed(() => {
    const activeLink = currentSidebarLinks.value.find((link) => link.active);
    return activeLink ? activeLink.label : "Dashboard";
});

const currentMemberLinks = computed(() =>
    memberLinks.map((link) => ({
        ...link,
        href: route(link.name),
        active: isActive(link.patterns ?? [link.name]),
        iconFilled:
            link.iconFilled ??
            (typeof link.icon === "string" && link.icon.endsWith("-o")
                ? link.icon.slice(0, -2)
                : link.icon),
    })),
);

const memberActiveIndex = computed(() => {
    const index = currentMemberLinks.value.findIndex((link) => link.active);

    return index >= 0 ? index : 0;
});


const navigateMember = (index) => {
    const target = currentMemberLinks.value[index];

    if (target) {
        router.visit(target.href);
    }
};

watch(
    () => page.url,
    () => {
        sidebarOpen.value = false;
    },
);

watch(
    () => page.props.flash?.success,
    (msg) => {
        if (msg) {
            console.log(msg)
            nextTick(() => {
                toast.success(msg, {
                    id: new Date().getTime()
                });
            })
        }
    },
    {immediate: true}
)
</script>

<template :currentTitle="curTitle">
    <Toaster richColors />
    <div class="tw-min-h-screen tw-bg-gray-100">
        <div v-if="isAdminLike" class="tw-flex tw-min-h-screen">
            <AdminSidebar
                :links="currentSidebarLinks"
                :title="sidebarTitle"
                :user="user"
            />

            <div class="tw-flex tw-flex-1 tw-flex-col">
                <AdminMobileNav
                    v-model="sidebarOpen"
                    :links="currentSidebarLinks"
                    :title="sidebarTitle"
                    :user="user"
                />

                <header
                    v-if="$slots.header"
                    class="tw-border-b tw-border-slate-200 tw-bg-white"
                >
                    <div class="tw-px-4 tw-py-6 sm:tw-px-6 lg:tw-px-8">
                        <slot name="header" />
                    </div>
                </header>

                <main class="tw-flex-1">
                    <div class="tw-px-4 tw-py-6 sm:tw-px-6 lg:tw-px-8">
                        <slot />
                    </div>
                </main>
            </div>
        </div>

        <div v-else class="tw-flex tw-min-h-screen tw-flex-col">
            <template v-if="route().current('member.dashboard')">
                <MemberMobileSearchBar :cart-route="route('member.cart')" />
            </template>
            <template v-else-if="route().current('member.profile')"> </template>
            <template v-else-if="route().current('member.finances')">
                <MemberMobileTopBar title="Finances" @back="history.back()" />
            </template>
            <template v-else-if="route().current('member.status')">
                <MemberMobileTopBar title="Status" @back="history.back()" />
            </template>
            <template v-else-if="route().current('member.cart')">
                <MemberMobileTopBar
                    title="Keranjang"
                    @back="history.back()"
                    :show-cart="false"
                />
            </template>
            <template v-else>
                <MemberMobileTopBar
                    title="Checkout"
                    @back="history.back()"
                    :show-cart="false"
                />
            </template>
            <MemberTopBar :links="currentMemberLinks" :user="user" />

            <main class="tw-flex-1">
                <template v-if="route().current('member.profile')">
                    <div class="md:tw-hidden tw-bg-primary md:tw-min-h-[25svh]">
                        <div
                            class="tw-items-center tw-w-full tw-flex tw-justify-between tw-sticky tw-top-0 tw-z-40 tw-border-btw-backdrop-blur tw-px-8 tw-py-5 md:tw-hidden"
                        >
                            <h2 class="tw-text-xl tw-font-bold tw-text-white">
                                Profile
                            </h2>
                            <div>
                                <Link
                                    href="#"
                                    class="btn-icon tw-relative tw-h-12 tw-w-12 tw-flex-shrink-0"
                                >
                                    <Settings
                                        class="tw-text-3xl tw-text-white"
                                    />
                                </Link>
                                <CartIcon
                                    buble-class="tw-absolute tw--right-1 tw--top-1 tw-inline-flex tw-h-5 tw-min-w-[1.25rem] tw-items-center tw-justify-center tw-rounded-full tw-bg-white tw-px-1 tw-text-[10px] tw-text-primary tw-font-bold"
                                    icon-class="tw-text-3xl tw-text-white tw-font-bold"
                                />
                            </div>
                        </div>
                    </div>
                </template>
                <div
                    class="tw-px-4 tw-py-6 tw-pb-24 sm:tw-px-6 lg:tw-px-8 md:tw-pb-12"
                >
                    <slot />
                </div>
            </main>

            <MemberBottomNav
                :links="currentMemberLinks"
                :model-value="memberActiveIndex"
                @navigate="navigateMember"
            />
        </div>
    </div>
</template>
