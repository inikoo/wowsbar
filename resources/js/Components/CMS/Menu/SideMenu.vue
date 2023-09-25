<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, watch, reactive } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import Menu from '@/Components/CMS/Menu/index.vue'
import { faHandPointer, faHandRock, faPlus, faLink, faObjectGroup } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ulid } from 'ulid';
import HyperlinkTools from '@/Components/CMS/Fields/Hyperlinktools.vue'
import { get, isNull } from 'lodash'
import IconPicker from '@/Components/CMS/Fields/IconPicker/IconPicker.vue';
import { getDbRef, getDataFirebase, setDataFirebase } from '@/Composables/firebase'
import ToolInTop from '@/Components/CMS/Menu/ToolsInTop.vue'
library.add(faHandPointer, faHandRock, faPlus, faLink, faObjectGroup)
const props = defineProps<{
    data: Object;
    selectedMenu: Number
}>();

const toolsData = {
    menuType: [
        { name: 'Group', value: 'group', icon: ['fas', 'object-group'] },
        { name: 'Link', value: 'link', icon: ['fas', 'link'] },
    ],
}

const changeMenuType = (value) => {
    const index = props.data.items.findIndex((item) => item.id === props.data.items[props.selectedMenu].id);

    if (value.value === 'link' && props.data.items[props.selectedMenu].type !== 'link') {
        props.data.items[index] = {
            ...props.data.items[index],
            type: 'link',
            href: '#',
        };
    }

    if (value.value === 'group' && props.data.items[props.selectedMenu].type !== 'group') {
        props.data.items[index] = {
            ...props.data.items[index],
            type: 'group',
            items: [
                {
                    label: 'New item',
                    icon: 'far fa-dot-circle',
                    id: ulid(),
                    href: '#',
                },
            ],
        };
    }
};

const addNewSubMenu = () => {
    if (props.data.items[props.selectedMenu].type == 'group') {
        props.data.items[props.selectedMenu].items.push({
            label: 'New item',
            icon: 'far fa-dot-circle',
            id: ulid(),
            href: '#',

        })
    }
}

</script>
  
<template>
    <div class="bg-white">
        <div class="w-[200px] p-6 overflow-y-auto overflow-x-hidden h-[46rem]">
            <div class="mt-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium text-gray-900">Menu Type</h2>
                </div>
                <RadioGroup class="mt-2">
                    <div class="flex justify-start gap-3">
                        <RadioGroupOption as="template" v-for="option in toolsData.menuType" :key="option.value"
                            :value="option" :title="option.name">
                            <div @click="changeMenuType(option)" :class="{
                                'cursor-not-allowed': get(data.items[selectedMenu], 'type') !== option.value && selectedMenu !== null,
                                'bg-gray-300 text-gray-600': get(data.items[selectedMenu], 'type') === option.value && selectedMenu !== null,
                                'border-gray-200 bg-white text-gray-900 hover:bg-gray-50': !checked && get(data.items[selectedMenu], 'type') !== option.value && selectedMenu !== null,
                                'flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit cursor-pointer': true
                            }">
                                <RadioGroupLabel as="span"><font-awesome-icon :icon="option.icon" /></RadioGroupLabel>
                            </div>
                        </RadioGroupOption>

                    </div>
                </RadioGroup>
            </div>

            <hr class="mt-5" />

            <div v-if="!isNull(selectedMenu)">
                <!-- type group -->
                <div class="mt-8" v-if="data.items[selectedMenu].type == 'group'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">{{ `${data.items[selectedMenu].label}` }}</h2>
                    </div>
                    <div>
                        <div class="flex gap-2 mt-2">
                            <div class="w-[90%]">
                                <div
                                    class="shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md rounded-md">
                                    <input type="text" v-model="data.items[selectedMenu].label"
                                        class="flex-1 border-0 bg-transparent text-gray-900 text-xs placeholder:text-gray-400 focus:ring-0 text-xs sm:text-sm sm:leading-6 w-full overflow-hidden"
                                        placeholder="title" />
                                </div>
                            </div>

                            <div>
                                <button type="submit" @click.prevent="addNewSubMenu"
                                    class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm ">
                                    <font-awesome-icon :icon="['fas', 'plus']" />
                                </button>
                            </div>
                        </div>

                        <div v-for="(set, index) in data.items[selectedMenu].items" :key="set.id" class="mt-5">
                            <div class="flex gap-2 relative">
                                <HyperlinkTools :data="set"
                                    @OnDelete="() => data.items[selectedMenu].items.splice(index, 1)" :formList="{
                                        label: 'label',
                                        href: 'href',
                                    }" />
                            </div>
                        </div>


                    </div>
                </div>
                <!-- type link -->

                <div class="mt-8" v-if="data.items[selectedMenu].type == 'link'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">{{ `${data.items[selectedMenu].label}` }}
                        </h2>
                    </div>
                    <div>
                        <HyperlinkTools :data="data.items[selectedMenu]" @OnDelete="deleteCategory" :formList="{
                            label: 'label',
                            href: 'href',
                        }" />
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>
  