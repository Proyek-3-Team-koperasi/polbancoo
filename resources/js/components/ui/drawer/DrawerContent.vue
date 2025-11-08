<script setup>
import { reactiveOmit } from "@vueuse/core";
import { useForwardPropsEmits } from "reka-ui";
import { DrawerContent, DrawerPortal } from "vaul-vue";
import { cn } from "@/lib/utils";
import DrawerOverlay from "./DrawerOverlay.vue";

const props = defineProps({
  forceMount: { type: Boolean, required: false },
  disableOutsidePointerEvents: { type: Boolean, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});
const emits = defineEmits([
  "escapeKeyDown",
  "pointerDownOutside",
  "focusOutside",
  "interactOutside",
  "openAutoFocus",
  "closeAutoFocus",
]);

const delegatedProps = reactiveOmit(props, "class");
const forwardedProps = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
  <DrawerPortal>
    <DrawerOverlay />
    <DrawerContent
      v-bind="forwardedProps"
      :class="
        cn(
          'tw-fixed tw-inset-x-0 tw-bottom-0 tw-z-50 tw-mt-24 tw-flex tw-h-auto tw-flex-col tw-rounded-t-[10px] tw-border tw-bg-background',
          props.class,
        )
      "
    >
      <div class="tw-mx-auto tw-mt-4 tw-h-2 tw-w-[100px] tw-rounded-full tw-bg-muted" />
      <slot />
    </DrawerContent>
  </DrawerPortal>
</template>
