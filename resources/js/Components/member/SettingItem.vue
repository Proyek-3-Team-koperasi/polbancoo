<script setup>
defineProps({
    icon: String,
    title: String,
    subtitle: String,
    to: String,
    rightText: String,
});
</script>

<template>
    <div
        class="tw-flex tw-items-center tw-justify-between tw-py-3 tw-px-4 tw-border-b tw-border-gray-200 hover:tw-bg-gray-50"
    >
        <div class="tw-flex tw-items-center tw-gap-3">
            <i :class="icon" class="tw-text-xl"></i>
            <div>
                <p class="tw-font-medium tw-text-gray-800">{{ title }}</p>
                <p v-if="subtitle" class="tw-text-sm tw-text-gray-600">
                    {{ subtitle }}
                </p>
            </div>
        </div>
        <div class="tw-flex tw-items-center tw-gap-2">
            <span v-if="rightText" class="tw-text-sm tw-text-gray-600">{{
                rightText
            }}</span>
            <i class="van-icon van-icon-arrow tw-text-gray-400"></i>
        </div>
    </div>
</template>
