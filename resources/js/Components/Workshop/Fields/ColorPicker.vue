  
<script  setup lang="ts">
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { set } from 'lodash'
import { ref, watch, defineEmits } from 'vue'

const props = defineProps<{
    fieldName: string | []
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
    data: Object
}>()


const emit = defineEmits()


const changeColor = (value) => {
    const { r, g, b, a } = value.rgba
    color.value = `rgba(${r}, ${g}, ${b}, ${a})`
}


const setFormValue = (data: Object, fieldName: String) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName);
    } else {
        return data[fieldName];
    }
}

const getNestedValue = (obj: Object, keys: Array) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === 'object' && key in acc) return acc[key];
        return 'green';
    }, obj);
}


const color = ref(setFormValue(props.data, props.fieldName))

watch(color, (newValue) => {
    // Update the form field value when the value ref changes
    updateFormValue(newValue);
});

const updateFormValue = (newValue) => {
    let target = { ...props.data };
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    // Emit an event to notify the parent component
    emit('input', target);
};

</script>

<template>
    <div class="flex gap-5">
        <div>
            <div class="bg-gray-950 border-black rounded-full w-10 h-10 justify-center" :style="`border: 1px solid`"
                @click="() => changeColor({ rgba: { r: 0, g: 0, b: 0, a: 1 } })" />
            <div class="flex items-center justify-center text-sm font-semibold">Black</div>
        </div>
        <div>
            <div class="bg-white border-black rounded-full w-10 h-10 justify-center" :style="`border: 1px solid`"
                @click="() => changeColor({ rgba: { r: 255, g: 255, b: 255, a: 255 } })" />
            <div class="flex items-center justify-center text-sm font-semibold">White</div>
        </div>

        <Popover v-slot="{ open }">
            <div class="relative">
                <PopoverButton>
                    <div class="bg-gray-950 border-black rounded-full w-10 h-10 justify-center"
                        :style="`background-color: ${color}; border: 1px solid`" />
                    <div class="flex items-center justify-center text-sm font-semibold">Custom</div>
                </PopoverButton>

                <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
                    enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
                    leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
                    <PopoverPanel v-show="open"
                        class="absolute bottom-full left-1/2 z-10 mb-3 -translate-x-1/2 transform px-4 sm:px-0">
                        <div :style="{ background: color }">
                            <ColorPicker theme="light" v-model="color" :sucker-hide="false" @changeColor="changeColor"
                                style="width: 225px;" />
                        </div>
                    </PopoverPanel>
                </Transition>
            </div>
        </Popover>


</div></template>
  