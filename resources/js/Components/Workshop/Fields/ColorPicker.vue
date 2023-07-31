  
<script  setup lang="ts">
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'
import { set } from 'lodash'
import { ref, watch  } from 'vue'

const props = defineProps<{
    fieldName: string
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
    data : Object
    counter: boolean
}>()

console.log(props)



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
    let target = props.data
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    props.data = { ...target }
};


</script>

<template>
    <div>
        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton :style="{ backgroundColor: color }" class="inline-flex w-10 h-10 justify-center  rounded-full"/>
            </div>

            <transition enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0">
                <MenuItems
                    class="absolute left-0 mt-2 w-56 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div :style="{ background: color }">
                        <!-- Here, we bind the color value to the ColorPicker component using `v-model`. -->
                        <ColorPicker theme="light" v-model="color" :sucker-hide="false" @changeColor="changeColor" />
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>

