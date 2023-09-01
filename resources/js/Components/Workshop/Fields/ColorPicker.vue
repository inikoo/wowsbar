  
<script  setup lang="ts">
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { set } from 'lodash'
import { ref, watch } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPaintBrushAlt } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faPaintBrushAlt)

const props = defineProps<{
    fieldName: string | []
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
    data: Object
}>()
console.log(props)

const emit = defineEmits()


const changeColor = (value) => {
    const { r, g, b, a } = value.rgba
    color.value = `rgba(${r}, ${g}, ${b}, ${a})`
}


const setFormValue = (data: Object, fieldName: String) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName)
    } else {
        return data[fieldName]
    }
}

const getNestedValue = (obj: Object, keys: string[]) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === 'object' && key in acc) return acc[key]
        return 'rgb(55 65 81)'
    }, obj)
}


const color = ref(setFormValue(props.data, props.fieldName))

watch(color, (newValue) => {
    // Update the form field value when the value ref changes
    updateFormValue(newValue)
})

const updateFormValue = (newValue) => {
    let target = { ...props.data }
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue)
    } else {
        target[props.fieldName] = newValue
    }
    // Emit an event to notify the parent component
    emit('input', target)
}

</script>

<template>
    <div class="flex gap-3">
        <Popover v-slot="{ open }">
            <div class="relative">
                <PopoverButton>
                    <div class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center"
                        :style="`background-color: ${color}`" >
                        <FontAwesomeIcon icon='far fa-paint-brush-alt' class='text-gray-300 text-lg' aria-hidden='true' />
                    </div>
                </PopoverButton>

                <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
                    enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
                    leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
                    <PopoverPanel v-show="open" class="absolute bottom-full left-1/2 z-10 mb-3 -translate-x-1/2 transform px-4 sm:px-0">
                        <ColorPicker theme="light" v-model="color" @changeColor="changeColor" style="width: 225px;" />
                    </PopoverPanel>
                </Transition>
            </div>
        </Popover>

        <!--  -->
        <div class="flex gap-x-1 items-center">
            <div class="bg-gray-700 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 55, g: 65, b: 81, a: 1 } })" />
            <div class="bg-white border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 255, g: 255, b: 255, a: 255 } })" />
            <div class="bg-yellow-300 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 253, g: 224, b: 71, a: 255 } })" />
            <div class="bg-emerald-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 16, g: 185, b: 129, a: 255 } })" />
            <div class="bg-sky-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 14, g: 165, b: 233, a: 255 } })" />
            <div class="bg-fuchsia-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 99, g: 102, b: 241, a: 255 } })" />
            <div class="bg-rose-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 244, g: 63, b: 94, a: 255 } })" />
        </div>

        


    </div>
</template>

<style lang="scss">
.colors {
    @apply hidden
}


</style>

