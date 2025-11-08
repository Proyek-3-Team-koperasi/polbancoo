<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div :class="cn('tw-grid tw-gap-1.5 tw-p-4 tw-text-center sm:tw-text-left', props.class)">
    <slot />
  </div>
</template>
