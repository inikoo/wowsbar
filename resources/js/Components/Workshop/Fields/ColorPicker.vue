  
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
        return '#f3f4f6';
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
    <div>
        <Popover v-slot="{ open }" class="relative">
            <div>
                <PopoverButton :class="{
                    'bg-black bg-opacity-20 hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75': true,
                    'rounded-full w-10 h-10 justify-center': true
                }" :style="`background-color: ${color};`" />

                <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
                    enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
                    leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
                    <PopoverPanel v-show="open"
                        class="absolute left-0 mt-2 w-56 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div :style="{ background: color }">
                            <ColorPicker theme="light" v-model="color" :sucker-hide="false" @changeColor="changeColor"
                                style="width: 225px;" />
                        </div>
                    </PopoverPanel>
                </Transition>
            </div>
        </Popover>
    </div>
</template>
  