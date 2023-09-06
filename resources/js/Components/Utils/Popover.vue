<script setup>
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'

const props = defineProps({
  width: {
    type: String,
    default: 'w-4/5'
  },
});

</script>

<template>
  <Popover :popover-placement="'bottom-start'">
    <PopoverButton>
      <slot name="button"></slot>
    </PopoverButton>

    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="translate-y-1 opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="translate-y-1 opacity-0"
    >
      <PopoverPanel
        class="absolute left-[100] z-[99] mt-3 -translate-x-1/2 transform px-4 sm:px-0 bg-white w-fit"
      >
        <slot name="content"></slot>
      </PopoverPanel>
    </transition>
  </Popover>
</template>
