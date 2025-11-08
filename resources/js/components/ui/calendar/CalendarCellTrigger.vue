<script setup>
import { reactiveOmit } from "@vueuse/core";
import { CalendarCellTrigger, useForwardProps } from "reka-ui";
import { cn } from "@/lib/utils";
import { buttonVariants } from '@/components/ui/button';

const props = defineProps({
  day: { type: null, required: true },
  month: { type: null, required: true },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});

const delegatedProps = reactiveOmit(props, "class");

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <CalendarCellTrigger
    :class="
      cn(
        buttonVariants({ variant: 'ghost' }),
        'tw-h-8 tw-w-8 tw-p-0 tw-font-normal',
        '[&[data-today]:not([data-selected])]:tw-bg-accent [&[data-today]:not([data-selected])]:tw-text-accent-foreground',
        // Selected
        'data-[selected]:tw-bg-primary data-[selected]:tw-text-primary-foreground data-[selected]:tw-opacity-100 data-[selected]:hover:tw-bg-primary data-[selected]:hover:tw-text-primary-foreground data-[selected]:focus:tw-bg-primary data-[selected]:focus:tw-text-primary-foreground',
        // Disabled
        'data-[disabled]:tw-text-muted-foreground data-[disabled]:tw-opacity-50',
        // Unavailable
        'data-[unavailable]:tw-text-destructive-foreground data-[unavailable]:tw-line-through',
        // Outside months
        'data-[outside-view]:tw-text-muted-foreground data-[outside-view]:tw-opacity-50 [&[data-outside-view][data-selected]]:tw-bg-accent/50 [&[data-outside-view][data-selected]]:tw-text-muted-foreground [&[data-outside-view][data-selected]]:tw-opacity-30',
        props.class,
      )
    "
    v-bind="forwardedProps"
  >
    <slot />
  </CalendarCellTrigger>
</template>
