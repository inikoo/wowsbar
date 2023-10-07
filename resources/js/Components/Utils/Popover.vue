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
    <Popover :popover-placement="'bottom-start'" v-slot="{ open }">
        <PopoverButton>
            <slot name="button" :isOpen="open"></slot>
        </PopoverButton>

        <slot name="popup">
            <transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
                enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
                <PopoverPanel class="absolute z-[99] mt-3 right-0 transform py-3 px-4 bg-white rounded-md shadow-md w-fit">
                    <slot name="content"></slot>
                </PopoverPanel>
            </transition>
        </slot>
    </Popover>
</template>
