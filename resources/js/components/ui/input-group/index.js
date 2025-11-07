import { cva } from "class-variance-authority";

export { default as InputGroup } from "./InputGroup.vue";
export { default as InputGroupAddon } from "./InputGroupAddon.vue";
export { default as InputGroupButton } from "./InputGroupButton.vue";
export { default as InputGroupInput } from "./InputGroupInput.vue";
export { default as InputGroupText } from "./InputGroupText.vue";
export { default as InputGroupTextarea } from "./InputGroupTextarea.vue";

export const inputGroupAddonVariants = cva(
  'tw-text-muted-foreground tw-flex tw-h-auto tw-cursor-text tw-items-center tw-justify-center tw-gap-2 tw-py-1.5 tw-text-sm tw-font-medium tw-select-none [&>svg:not([class*=\'size-\'])]:tw-size-4 [&>kbd]:tw-rounded-[calc(var(--radius)-5px)] tw-group-data-[disabled=true]/input-group:opacity-50',
  {
    variants: {
      align: {
        "inline-start":
          'tw-order-first tw-pl-3 has-[>button]:tw-ml-[-0.45rem] has-[>kbd]:tw-ml-[-0.35rem]',
        "inline-end":
          'tw-order-last tw-pr-3 has-[>button]:tw-mr-[-0.45rem] has-[>kbd]:tw-mr-[-0.35rem]',
        "block-start":
          'tw-order-first tw-w-full tw-justify-start tw-px-3 tw-pt-3 [.border-b]:tw-pb-3 tw-group-has-[>input]/input-group:pt-2.5',
        "block-end":
          'tw-order-last tw-w-full tw-justify-start tw-px-3 tw-pb-3 [.border-t]:tw-pt-3 tw-group-has-[>input]/input-group:pb-2.5',
      },
    },
    defaultVariants: {
      align: "inline-start",
    },
  },
);

export const inputGroupButtonVariants = cva(
  'tw-text-sm tw-shadow-none tw-flex tw-gap-2 tw-items-center',
  {
    variants: {
      size: {
        xs: 'tw-h-6 tw-gap-1 tw-px-2 tw-rounded-[calc(var(--radius)-5px)] [&>svg:not([class*=\'size-\'])]:tw-size-3.5 has-[>svg]:tw-px-2',
        sm: 'tw-h-8 tw-px-2.5 tw-gap-1.5 tw-rounded-md has-[>svg]:tw-px-2.5',
        "icon-xs":
          'tw-size-6 tw-rounded-[calc(var(--radius)-5px)] tw-p-0 has-[>svg]:tw-p-0',
        "icon-sm": 'tw-size-8 tw-p-0 has-[>svg]:tw-p-0',
      },
    },
    defaultVariants: {
      size: "xs",
    },
  },
);
