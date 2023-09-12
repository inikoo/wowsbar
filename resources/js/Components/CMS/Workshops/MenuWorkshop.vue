<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { ref, watch, reactive } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import Menu from '@/Components/CMS/Menu/index.vue'
import { faHandPointer, faHandRock, faPlus, faLink, faObjectGroup} from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ulid } from 'ulid';
import HyperlinkTools from '@/Components/CMS/Fields/Hyperlinktools.vue'
import { get } from 'lodash'
import IconPicker from '@/Components/CMS/Fields/IconPicker/IconPicker.vue';
import { getDbRef, getDataFirebase, setDataFirebase } from '@/Composables/firebase'
import ToolInTop from '@/Components/CMS/Menu/ToolsInTop.vue'
library.add(faHandPointer, faHandRock, faPlus, faLink,faObjectGroup)

const toolsData = {
    menuType: [
        { name: 'Group', value: 'group', icon:['fas','object-group'] },
        { name: 'Link', value: 'link',  icon:['fas','link']  },
    ],
}

const navigation = reactive({
    categories: [
        {
            name: 'Home',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'group',
            featured: [
                {
                    name: 'About us',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Contact',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'ShowRoom',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Trems & Conditions',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Delivery',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Operation Hours',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Freedom Fund',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Business Ethics',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Catalogue',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Retruns Policy',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Dropshiping Sevices',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Working with local businesses',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'sustainable palm oil',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Privacy Policy',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Cookies Policy',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Travel Blog',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
            ],
        },
        {
            name: 'Departements',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'group',
            featured: [
                {
                    name: 'New Arrivals',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Basic Tees',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Accessories',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Carry',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
            ],
        },
        {
            name: 'Incentives & Inspiration',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'group',
            featured: [
                {
                    name: 'New Arrivals',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Basic Tees',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Accessories',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Carry',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
            ],
        },
        {
            name: 'Delivery',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'group',
            featured: [
                {
                    name: 'New Arrivals',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Basic Tees',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Accessories',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
                {
                    name: 'Carry',
                    id: ulid(),
                    icon: 'far fa-dot-circle',
                    link: '#',
                },
            ],
        },
        {
            name: 'Test link 1',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'link',
            link: '#',
        },
        {
            name: 'Test link 2',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'link',
            link: '#',
        },
    ],
})

const selectedTheme = ref({ name: 'One', value: '1' },)
const handtools = ref({ name: 'edit', icon: ['fas', 'fa-hand-pointer'] },)
const selectedNav = ref(null)



const changeMenuType = (value) => {
    const index = navigation.categories.findIndex(
        (item) => item.id === navigation.categories[selectedNav.value].id
    );

    if (value.value === 'link' && navigation.categories[selectedNav.value].type !== 'link') {
        navigation.categories[index] = {
            ...navigation.categories[index],
            type: 'link',
            link: '',
        };
    }

    if (value.value === 'group' && navigation.categories[selectedNav.value].type !== 'group') {
        navigation.categories[index] = {
            ...navigation.categories[index],
            type: 'group',
            featured: [
                {
                    name: 'New item',
                    icon: 'far fa-dot-circle',
                    id: ulid(),
                    link: '#',
                },
            ],
        };
    }
    selectedNav.value = null
};


const changeNavActive = (value) => {
    selectedNav.value = value
}

const columItemLinkChange = () => {
    const index = navigation.categories.findIndex((item) => item.id === navigation.categories[selectedNav.value].id);
    navigation.categories[index].featured.push({ name: 'New', id: ulid(), link: '' });
};

const deleteCategory = () => {
    const index = navigation.categories.findIndex((item) => item.id == navigation.categories[selectedNav.value].id)
    if (index >= 0) {
        navigation.categories.splice(index, 1)
        selectedNav.value = null
    }
}

async function setToFirebase() {
  const column = 'org/websites/menu';
  try {
    await setDataFirebase(column, { navigation : navigation, theme : selectedTheme.value });
  } catch (error) {
    console.log(error)
  }
}

watch(navigation, setToFirebase, { deep: true })

setToFirebase()

</script>

<template>
    <div class="bg-white">

        <div class="flex">
            <div class="w-[250px] p-6 overflow-y-auto h-[46rem]">
                    <!-- colums -->
                    <div class="mt-2" v-if="selectedNav !== null">
                        <div class="flex items-center justify-between">
                            <h2 class="text-sm font-medium text-gray-900">Menu Type</h2>
                        </div>
                        <RadioGroup class="mt-2">
                            <div class="flex justify-start gap-3">
                                <RadioGroupOption as="template" v-for="option in toolsData.menuType" :key="option.value"
                                    :value="option" :title="option.name" v-slot="{ active, checked }">
                                    <div @click="changeMenuType(option)" :class="{
                                        'cursor-not-allowed': get(navigation.categories[selectedNav], 'type') !== option.value && selectedNav !== null,
                                        'bg-gray-300 text-gray-600': get(navigation.categories[selectedNav], 'type') === option.value && selectedNav !== null,
                                        'border-gray-200 bg-white text-gray-900 hover:bg-gray-50': !checked && get(navigation.categories[selectedNav], 'type') !== option.value && selectedNav !== null,
                                        'flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit cursor-pointer': true
                                    }">
                                        <RadioGroupLabel as="span"><font-awesome-icon :icon="option.icon" /></RadioGroupLabel>
                                    </div>
                                </RadioGroupOption>

                            </div>
                        </RadioGroup>
                    </div>

                    <!-- Mode link , label & icon-->
                    <div v-if="selectedNav">
                        <!-- type group -->
                        <div class="mt-8" v-if="navigation.categories[selectedNav].type == 'group'">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-medium text-gray-900">{{ `${navigation.categories[selectedNav].name}` }}</h2>
                            </div>
                            <div>
                                <div class="flex gap-2 mt-2">
                                <div class="w-[90%]">
                                    <div 
                                        class="shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md rounded-md">
                                        <input type="text" v-model="navigation.categories[selectedNav].name"
                                            class="flex-1 border-0 bg-transparent text-gray-900 text-xs placeholder:text-gray-400 focus:ring-0 text-xs sm:text-sm sm:leading-6 w-full overflow-hidden"
                                            placeholder="title" />
                                    </div>
                                </div>
                              
                                    <div>
                                        <button type="submit" @click.prevent="columItemLinkChange()"
                                            class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm ">
                                            <font-awesome-icon :icon="['fas', 'plus']" />
                                        </button>
                                    </div>
                                </div>

                                <div v-for="(set, index) in navigation.categories[selectedNav].featured" :key="set.id" class="mt-5">
                                    <div class="flex gap-2 relative">
                                        <!-- <div class="flex justify-end items-center">
                                            <span
                                                class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm">
                                                <IconPicker :key="set.id" :data="set" class="text-black" />
                                            </span>
                                        </div> -->

                                        <HyperlinkTools :data="set" @OnDelete="() => navigation.categories[selectedNav].featured.splice(index, 1)"
                                            :formList="{
                                                name: 'name',
                                                link: 'link',
                                            }" />
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                        <!-- type link -->

                        <div class="mt-8" v-if="navigation.categories[selectedNav].type == 'link'">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-medium text-gray-900">{{ `Colums tools ${navigation.categories[selectedNav].name}` }}</h2>
                            </div>
                            <div>
                                <HyperlinkTools :data="navigation.categories[selectedNav]" @OnDelete="deleteCategory" :formList="{
                                    name: 'name',
                                    link: 'link',
                                }" />
                            </div>
                        </div>

                    </div>
            </div>

            <!-- edit navbar area -->
            <div class="w-full bg-gray-200">
                <ToolInTop :tool="handtools" :theme="selectedTheme" :columSelected="selectedNav"
                @setColumnSelected="changeNavActive"  :navigation="navigation"/>
                <div style="transform: scale(0.8);" class="w-full">
                {{ selectedNav }}
                    <Menu :theme="selectedTheme.value" :navigation="navigation" :tool="handtools" :selectedNav="navigation.categories[selectedNav]"
                        :changeNavActive="changeNavActive" />
                </div>
            </div>
        </div>

    </div>
    <div @click="() => console.log(navigation)">checkdata</div>
</template>
