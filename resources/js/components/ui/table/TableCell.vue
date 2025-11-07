<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <td
    :class="
      cn(
        'tw-p-2 tw-align-middle [&:has([role=checkbox])]:tw-pr-0 [&>[role=checkbox]]:tw-translate-y-0.5',
        props.class,
      )
    "
  >
    <slot />
  </td>
</template>
