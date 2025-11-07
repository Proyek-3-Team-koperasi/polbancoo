<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div
    data-slot="input-group"
    role="group"
    :class="
      cn(
        'tw-group/input-group tw-border-input dark:tw-bg-input/30 tw-relative tw-flex tw-w-full tw-items-center tw-rounded-md tw-border tw-shadow-xs tw-transition-[color,box-shadow] tw-outline-none',
        'tw-h-9 tw-min-w-0 has-[>textarea]:tw-h-auto',

        // Variants based on alignment.
        'has-[>[data-align=inline-start]]:[&>input]:tw-pl-2',
        'has-[>[data-align=inline-end]]:[&>input]:tw-pr-2',
        'has-[>[data-align=block-start]]:tw-h-auto has-[>[data-align=block-start]]:tw-flex-col has-[>[data-align=block-start]]:[&>input]:tw-pb-3',
        'has-[>[data-align=block-end]]:tw-h-auto has-[>[data-align=block-end]]:tw-flex-col has-[>[data-align=block-end]]:[&>input]:tw-pt-3',

        // Focus state.
        'has-[[data-slot=input-group-control]:focus-visible]:tw-ring-ring has-[[data-slot=input-group-control]:focus-visible]:tw-ring-1',

        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
