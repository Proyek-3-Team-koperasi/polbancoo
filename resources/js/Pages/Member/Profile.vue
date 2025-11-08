<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import * as LucideIcons from "lucide-vue-next";
import { computed, ref, watch } from "vue";
import { Uploader } from "vant";

const page = usePage();

const props = defineProps({
    user: Object,
    member: Object,
});

const localUser = ref({ ...props.user });

watch(
    () => props.user,
    (newUser) => {
        localUser.value = { ...newUser };
    },
    { deep: true },
);

const getInitial = (name) => {
    return name?.charAt(0).toUpperCase() || "?";
};

const logout = () => {
    router.post(route("logout"));
};

const profileActions = computed(() => [
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

const handleQuickAction = (action) => {
    if (action?.href && !action?.comingSoon) {
        router.visit(action.href);
    }
};

const resolveIcon = (icon) => {
    if (!icon) {
        return LucideIcons.LayoutGrid ?? null;
    }

    if (typeof icon === "string") {
        return LucideIcons[icon] ?? LucideIcons.LayoutGrid;
    }

    return icon;
};

// --- State dan Fungsi Upload Foto ---
const selectedFile = ref(null);
const uploadMessage = ref("");
const isEditingAvatar = ref(false);
const showEditIcon = ref(false);
const fileList = ref([]); // Untuk Vant Uploader
const showPreviewAvatar = ref(false);

/**
 * Handler untuk perubahan file pada Vant Uploader.
 * @param {Object|File} fileObj - Objek file dari Vant atau langsung File object.
 */
const handleFileChange = (file) => {
    console.log("📸 File diterima:", file);

    // Validasi ukuran
    if (file.file.size > 2 * 1024 * 1024) {
        uploadMessage.value = "Ukuran file maksimal 2MB.";
        return;
    }

    selectedFile.value = file.file;
    uploadMessage.value = "";

    const previewUrl = file.content || URL.createObjectURL(file.file);

    fileList.value = [
        {
            url: previewUrl,
            isImage: true,
        },
    ];
};

const uploadAvatar = () => {
    if (!selectedFile.value) {
        uploadMessage.value = "Silakan pilih file terlebih dahulu.";
        return;
    }

    const formData = new FormData();
    formData.append("avatar", selectedFile.value);

    router.post(route("member.profile.upload.avatar"), formData, {
        onSuccess: (page) => {
            uploadMessage.value = "Foto profil berhasil diperbarui!";
            // Reset state
            selectedFile.value = null;
            fileList.value = []; // Reset fileList Vant
            isEditingAvatar.value = false;

            // Perbarui URL avatar di state lokal jika ada di props
            if (page.props?.user?.avatar_url) {
                localUser.value.avatar_url = page.props.user.avatar_url;
            }
        },
        onError: (errors) => {
            console.error("Upload error:", errors); // Untuk debugging
            uploadMessage.value = errors.avatar || "Gagal mengunggah foto.";
        },
    });
};

const openPreviewAvatar = () => {
    if (localUser.value.avatar_url) {
        showPreviewAvatar.value = true;
    } else {
        triggerUploadModal();
    }
};

const triggerUploadModal = () => {
    showPreviewAvatar.value = false;
    selectedFile.value = null;
    fileList.value = [];
    uploadMessage.value = "";
    isEditingAvatar.value = true;
};
</script>

<template>
    <Head title="Member Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="tw-text-xl tw-font-semibold tw-leading-tight tw-text-gray-800"
            >
                Member Profile
            </h2>
        </template>

        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-3xl sm:tw-px-6 lg:tw-px-8">
                <div class="tw-bg-gray-50 tw-shadow-lg tw-rounded-xl tw-p-6 tw-border tw-border-gray-200">
                    <div
                        class="tw-bg-gradient-to-r tw-from-orange-500 tw-to-orange-400 tw-rounded-2xl tw-w-full tw-text-white tw-p-6 tw-shadow-lg tw-mb-6"
                    >
                        <div class="tw-flex tw-items-center tw-gap-6">
                            <!-- Foto Profil -->
                            <div class="tw-relative tw-flex-shrink-0">
                                <div
                                    class="tw-relative tw-flex tw-items-center tw-justify-center tw-h-16 tw-w-16 tw-rounded-full tw-overflow-hidden tw-cursor-pointer"
                                    @click="openPreviewAvatar"
                                    @mouseenter="showEditIcon = true"
                                    @mouseleave="showEditIcon = false"
                                >
                                    <!-- Jika user memiliki avatar_url, tampilkan foto -->
                                    <img
                                        v-if="localUser.avatar_url"
                                        :src="localUser.avatar_url"
                                        :alt="localUser.name"
                                        class="tw-w-full tw-h-full tw-object-cover"
                                    />
                                    <!-- Jika tidak, tampilkan huruf pertama nama -->
                                    <div
                                        v-else
                                        class="tw-flex tw-items-center tw-justify-center tw-w-full tw-h-full tw-bg-white/20 tw-text-white tw-text-xl tw-font-bold"
                                    >
                                        {{ getInitial(localUser.name) }}
                                    </div>

                                    <!-- Icon edit foto profil -->
                                    <div
                                        v-if="showEditIcon"
                                        class="tw-absolute tw-bottom-1 tw-right-1 tw-bg-black/30 tw-p-1 tw-rounded-full tw-shadow-md tw-z-[9999]"
                                    >
                                        <LucideIcons.Edit
                                            class="tw-h-4 tw-w-4 tw-text-white"
                                        />
                                    </div>
                                </div>
                            </div>
                            <!-- Nama & Email -->
                            <div class="tw-flex-1">
                                <h3
                                    class="tw-text-lg tw-font-semibold tw-text-white"
                                >
                                    {{ localUser.name }}
                                </h3>
                                <p class="tw-text-white/90">
                                    {{ localUser.email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Fitur-Fitur -->
                    <div
                        class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-6 tw-mb-6"
                    >
                        <div
                            class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 tw-gap-4"
                        >
                            <Link
                                v-for="action in profileActions"
                                :key="action.label"
                                :href="action.href || undefined"
                                :aria-disabled="!!action.comingSoon"
                                class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center tw-rounded-xl tw-p-3 tw-border tw-border-gray-200 tw-transition hover:tw-bg-gray-50 md:tw-h-[8rem]"
                                :class="{
                                    'tw-cursor-not-allowed tw-opacity-60 hover:tw-bg-slate-50':
                                        !!action.comingSoon,
                                }"
                                @click="
                                    (event) => {
                                        if (action.comingSoon) {
                                            event.preventDefault();
                                            return;
                                        }
                                        handleQuickAction(action);
                                    }
                                "
                            >
                                <span
                                    class="tw-flex tw-h-14 tw-w-14 tw-items-center tw-justify-center tw-rounded-3xl tw-bg-white tw-text-primary tw-shadow-sm"
                                >
                                    <component
                                        :is="resolveIcon(action.icon)"
                                        class="tw-h-7 tw-w-7"
                                    />
                                </span>
                                <p
                                    class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-slate-700 tw-mt-1 tw-leading-tight tw-text-balance"
                                >
                                    {{ action.label }}
                                </p>
                            </Link>
                        </div>
                    </div>

                    <!-- Ganti Password & Logout -->
                    <div class="tw-bg-white tw-shadow-sm tw-rounded-lg tw-p-6">
                        <div class="tw-flex tw-flex-col tw-gap-1">
                            <div
                                class="tw-flex tw-items-center tw-justify-between tw-py-2.5 tw-px-4 tw-border-b tw-border-gray-200 tw-cursor-pointer hover:tw-bg-gray-50"
                                @click="router.visit(route('password.request'))"
                            >
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <p class="tw-text-sm md:tw-text-base tw-font-medium tw-text-gray-800">
                                        Ganti Password
                                    </p>
                                </div>
                                <i class="van-icon van-icon-arrow tw-text-gray-400 tw-text-sm"></i>
                            </div>

                            <div
                                class="tw-flex tw-items-center tw-justify-between tw-py-2.5 tw-px-4 tw-border-b tw-border-gray-200 tw-cursor-pointer hover:tw-bg-gray-50"
                                @click="logout"
                            >
                                <div class="tw-flex tw-items-center tw-gap-3">
                                    <p class="tw-text-sm md:tw-text-base tw-font-medium tw-text-gray-800">
                                        Logout
                                    </p>
                                </div>
                                <i class="van-icon van-icon-arrow tw-text-gray-400 tw-text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <!-- Preview Foto Profil -->
            <div
                v-if="showPreviewAvatar"
                class="tw-fixed tw-inset-0 tw-bg-black/70 tw-flex tw-items-center tw-justify-center tw-z-[9999]"
            >
                <div
                    class="tw-relative tw-bg-white tw-rounded-2xl tw-shadow-2xl tw-w-11/12 tw-max-w-md tw-overflow-hidden"
                >
                    <div
                        class="tw-bg-gradient-to-r tw-from-orange-500 tw-to-orange-400 tw-p-4"
                    >
                        <h3
                            class="tw-text-lg tw-font-semibold tw-text-center tw-text-white tw-mb-0"
                        >
                            Preview Foto Profil
                        </h3>
                    </div>

                    <!-- Preview Foto -->
                    <div
                        class="tw-flex tw-items-center tw-justify-center tw-p-4 tw-pt-6 tw-pb-6"
                    >
                        <img
                            :src="localUser.avatar_url"
                            :alt="localUser.name"
                            class="tw-max-h-[60vh] tw-max-w-full tw-rounded-lg tw-shadow-md"
                            @error="
                                () => {
                                    /* Optional: handle broken image */
                                }
                            "
                        />
                    </div>

                    <div
                        class="tw-p-4 tw-pt-0 tw-flex tw-justify-between tw-gap-3"
                    >
                        <!-- Tombol Kembali -->
                        <button
                            @click="showPreviewAvatar = false"
                            class="tw-flex-1 tw-bg-gray-200 tw-text-gray-800 tw-font-medium tw-rounded-lg tw-px-4 tw-py-2 hover:tw-bg-gray-300 tw-transition tw-duration-200 tw-text-sm"
                        >
                            Kembali
                        </button>

                        <!-- Tombol Ganti Foto -->
                        <button
                            @click="triggerUploadModal"
                            class="tw-flex-1 tw-bg-gradient-to-r tw-from-orange-500 tw-to-orange-400 tw-text-white tw-font-medium tw-rounded-lg tw-px-4 tw-py-2 hover:tw-brightness-110 tw-transition tw-duration-200 tw-flex tw-items-center tw-justify-center tw-text-sm"
                        >
                            <i class="van-icon van-icon-camera tw-mr-2"></i>
                            Ganti Foto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upload Foto Profil -->
            <div
                v-if="isEditingAvatar"
                class="tw-fixed tw-inset-0 tw-bg-black/50 tw-flex tw-items-center tw-justify-center tw-z-[9000]"
            >
                <div
                    class="tw-bg-white tw-rounded-xl tw-shadow-xl tw-w-11/12 tw-max-w-md tw-overflow-hidden"
                >
                    <div
                        class="tw-bg-gradient-to-r tw-from-orange-500 tw-to-orange-400 tw-p-4"
                    >
                        <h3
                            class="tw-text-lg tw-font-semibold tw-text-center tw-text-white tw-mb-0"
                        >
                            Upload Foto Profil
                        </h3>
                    </div>
                    <div class="tw-p-6">
                        <div class="tw-text-left tw-mb-6">
                            <label
                                class="tw-block tw-text-gray-700 tw-font-medium tw-mb-2 tw-text-sm"
                                >Pilih Foto (Maksimal 2MB)</label
                            >
                            <Uploader
                                v-model="fileList"
                                :after-read="handleFileChange"
                                :max-count="1"
                                accept="image/*"
                            />
                        </div>

                        <!-- Pesan error atau sukses -->
                        <div
                            v-if="uploadMessage"
                            class="tw-text-center tw-mb-4"
                        >
                            <p
                                :class="{
                                    'tw-text-sm': true,
                                    'tw-text-red-500':
                                        !uploadMessage.includes('berhasil'),
                                    'tw-text-green-500':
                                        uploadMessage.includes('berhasil'),
                                }"
                            >
                                {{ uploadMessage }}
                            </p>
                        </div>

                        <div class="tw-flex tw-justify-between tw-gap-3">
                            <button
                                @click="isEditingAvatar = false"
                                class="tw-flex-1 tw-bg-gray-200 tw-text-gray-800 tw-font-medium tw-rounded-lg tw-px-4 tw-py-2 hover:tw-bg-gray-300 tw-transition tw-duration-200"
                            >
                                Batal
                            </button>
                            <button
                                @click="uploadAvatar"
                                :disabled="!selectedFile"
                                class="tw-flex-1 tw-bg-gradient-to-r tw-from-orange-500 tw-to-orange-400 tw-text-white tw-font-medium tw-rounded-lg tw-px-4 tw-py-2 hover:tw-brightness-110 tw-transition tw-duration-200 disabled:tw-opacity-50"
                            >
                                Simpan Foto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AuthenticatedLayout>
</template>
