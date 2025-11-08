<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div :class="cn('tw-mt-auto tw-flex tw-flex-col tw-gap-2 tw-p-4', props.class)">
    <slot />
  </div>
</template>
