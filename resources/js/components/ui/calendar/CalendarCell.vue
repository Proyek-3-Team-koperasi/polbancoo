<script setup>
import { reactiveOmit } from "@vueuse/core";
import { CalendarCell, useForwardProps } from "reka-ui";
import { cn } from "@/lib/utils";

const props = defineProps({
  date: { type: null, required: true },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});

const delegatedProps = reactiveOmit(props, "class");

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <CalendarCell
    :class="
      cn(
        'tw-relative tw-p-0 tw-text-center tw-text-sm focus-within:tw-relative focus-within:tw-z-20 [&:has([data-selected])]:tw-rounded-md [&:has([data-selected])]:tw-bg-accent [&:has([data-selected][data-outside-view])]:tw-bg-accent/50',
        props.class,
      )
    "
    v-bind="forwardedProps"
  >
    <slot />
  </CalendarCell>
</template>
