<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { ref, reactive, Ref } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faChevronDown, faCheckSquare, faSquare } from "@/../private/pro-regular-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faChevronDown, faCheckSquare, faSquare)


const props = defineProps<{
    elements: any
    title: {
        title: string
        leftIcon: any
    }
}>()
// console.log(props.elements)

const emits = defineEmits(['checkboxChanged'])
const isChecked = ref({})   
const selectedGroup = ref(Object.keys(props.elements)[0]) ?? ref('')
const selectedElement: any = reactive({
    [selectedGroup.value]: props.elements[selectedGroup.value]?.elements ? Object.keys(props.elements[selectedGroup.value].elements) : []
})

// to store the props to valid data for query
// props.elements.forEach(item => {
//     const key = item.key;
//     const values = Object.keys(item.elements);

//     isChecked.value[key] = values;
// });

const onClickCheckbox = (element: any, group: string) => {
    if(!selectedElement[group]) selectedElement[group] = []

    if (selectedElement[group].includes(element)) {
        if(selectedElement[group].length > 1) {
            // Can't deselect if current active is one
            selectedElement[group] = selectedElement[group].filter((item: string) => item !== element)
        }
    } else {
        selectedElement[group].push(element);
    }

    emits('checkboxChanged', selectedElement)
}

const onDoubleClickCheckbox = (element: any, group: string) => {
    if(!selectedElement[group]) selectedElement[group] = []

    if (selectedElement[group].includes(element)) {
        selectedElement[group] = [...Object.keys(props.elements[selectedGroup.value].elements)]
    } else {
        selectedElement[group] = [`${element}`]
    }
    emits('checkboxChanged', selectedElement)
}

</script>

<template>
    <!-- If props.element not empty -->
    <div v-if="!!selectedGroup" class="px-4 py-2 -mt-2 flex items-center text-xs justify-between border-b border-gray-200">
        <div class="text-2xl flex items-center py-1 gap-x-2">
            <FontAwesomeIcon v-if="title.leftIcon" :icon="title.leftIcon" aria-hidden="true" />
            <p class="inline font-semibold leading-none capitalize">{{ title.title ? (title.title) : '' }}</p>
        </div>
        <div class="flex items-center justify-end border border-gray-200 divide-x divide-gray-200 rounded">
            <!-- List of element (checkbox) -->
            <div class="grid justify-items-center grid-flow-col auto-cols-auto divide-x-1 divide-gray-300 rounded overflow-hidden">
                <div
                    v-for="(value, element, index) of props.elements[selectedGroup]?.elements" :key="element"
                    class="flex items-center gap-x-1 w-full px-3 cursor-pointer py-2 select-none "
                    @click="onClickCheckbox(element, selectedGroup)"
                    @dblclick="onDoubleClickCheckbox(element, selectedGroup)"
                    role="filter"
                    :id="value[0].replace(' ','-')"
                >
                    <FontAwesomeIcon v-if="selectedElement[selectedGroup]?.includes(element)" icon="far fa-check-square" aria-hidden="true" />
                    <FontAwesomeIcon v-else icon="far fa-square" aria-hidden="true" />
                    <div
                        :class="[ isChecked ? 'text-gray-800' : 'text-gray-400',
                            'grid justify-center grid-flow-col items-center capitalize hover:text-gray-600']">
                        {{ value[0] }} ({{ value[1] }})
                    </div>
                </div>
            </div>
            
            <!-- Button: Select state -->
            <Menu as="div" class="relative inline-block text-left" v-slot="{ open }">
                <!-- Initial button -->
                <div v-if="props.elements" class="w-24 min-w-min">
                    <MenuButton class=" inline-flex relative w-full justify-end items-center pr-6 py-2 font-medium text-gray-800 capitalize focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-opacity-75">
                        {{ selectedGroup }}
                        <FontAwesomeIcon icon="far fa-chevron-down" class="absolute left-2.5 transition-all duration-200 ease-in-out" :class="[open ? 'rotate-180' : '']" aria-hidden="true" />
                    </MenuButton>
                </div>

                <!-- List of button -->
                <transition enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0">
                    <MenuItems
                        class="absolute right-0 mt-2 w-40 min-w-min origin-top-right divide-y overflow-hidden divide-gray-100 rounded bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="">
                            <MenuItem v-for="element in props.elements" v-slot="{ active }" @click="selectedGroup = element.key"
                                :class="[selectedGroup == element.key ? 'bg-gray-100' : '']"
                                :disabled="selectedGroup == element.key"
                            >
                                <button :class="[
                                    active ? 'bg-gray-300' : 'text-gray-800',
                                    'group flex w-full items-center pl-4 py-2 capitalize',
                                ]">
                                    <!-- <EditIcon :active="active" class="mr-2 h-5 w-5 text-orange-400" aria-hidden="true" /> -->
                                    {{ element.key }}
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>

        
        <!-- <div v-s -->
    </div>
    
    <!-- <pre>{{ Object.keys(props.elements[selectedGroup].elements) }}
{{ selectedElement }}</pre> -->
</template>
