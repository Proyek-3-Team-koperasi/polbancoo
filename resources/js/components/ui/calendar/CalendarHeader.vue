<script setup>
import { reactiveOmit } from "@vueuse/core";
import { CalendarHeader, useForwardProps } from "reka-ui";
import { cn } from "@/lib/utils";

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});

const delegatedProps = reactiveOmit(props, "class");

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <CalendarHeader
    :class="
      cn('tw-relative tw-flex tw-w-full tw-items-center tw-justify-between tw-pt-1', props.class)
    "
    v-bind="forwardedProps"
  >
    <slot />
  </CalendarHeader>
</template>
