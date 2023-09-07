<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { ref, watchEffect } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import Menu from '@/Components/CMS/Menu/index.vue'
import { faHandPointer, faHandRock, faPlus } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ulid } from 'ulid';
import HyperlinkTools from '@/Components/CMS/Fields/Hyperlinktools.vue'
import { get } from 'lodash'
import IconPicker from '@/Components/CMS/Fields/IconPicker/IconPicker.vue';
library.add(faHandPointer, faHandRock, faPlus)

const Dummy = {
    tools: [
        { name: 'edit', icon: ['fas', 'fa-hand-pointer'] },
        { name: 'grab', icon: ['fas', 'hand-rock'] },
        // { name: 'Heather Grey', icon: ['fas', 'fa-hand-pointer']},
    ],
    theme: [
        { name: 'One', value: '2' },
        { name: 'Two', value: '1' },
    ],
    menuType: [
        { name: 'Group', value: 'group' },
        { name: 'Link', value: 'link' },
    ],
    modeType: [
        { name: 'User', value: 'user' },
        { name: 'Guest', value: 'guest' },
    ],
}
const navigation = ref({
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

const selectedTheme = ref(Dummy.theme[0])
const handtools = ref(Dummy.tools[0])
const selectedNav = ref(null)



watchEffect(() => {
    console.log('selectedNav:', selectedNav.value);
});



const changeMenuType = (value) => {
    const index = navigation.value.categories.findIndex(
        (item) => item.id === selectedNav.value.id
    );

    if (value.value === 'link' && selectedNav.value.type !== 'link') {
        navigation.value.categories[index] = {
            ...navigation.value.categories[index],
            type: 'link',
            link: '',
        };
    }

    if (value.value === 'group' && selectedNav.value.type !== 'group') {
        navigation.value.categories[index] = {
            ...navigation.value.categories[index],
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
    const index = navigation.value.categories.findIndex((item) => item.id === selectedNav.value.id);
    navigation.value.categories[index].featured.push({ name: 'New', id: ulid(), link: '' });
};

const deleteCategory = () => {
    const index = navigation.value.categories.findIndex((item) => item.id == selectedNav.value.id)
    if (index >= 0) {
        navigation.value.categories.splice(index, 1)
        selectedNav.value = null
    }
}

</script>

<template>
    <div class="bg-white">

        <div class="flex">
            <div class="w-1/4 p-6 overflow-y-auto h-[46rem]">
                <form>
                    <!-- tool picker -->
                    <div>
                        <h2 class="text-sm font-medium text-gray-900">Tools</h2>
                        <RadioGroup v-model="handtools" class="mt-2">
                            <RadioGroupLabel class="sr-only">Choose a tool</RadioGroupLabel>
                            <div class="flex items-center space-x-3">
                                <RadioGroupOption as="template" v-for="color in Dummy.tools" :key="color.name"
                                    :value="color" v-slot="{ active, checked }">
                                    <div :class="[
                                        color.tools,
                                        active && checked ? 'ring ring-offset-1' : '',
                                        !active && checked ? 'ring-2' : '',
                                        'relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none',
                                    ]">
                                        <RadioGroupLabel as="span" class="sr-only">{{ color.name }}
                                        </RadioGroupLabel>
                                        <span aria-hidden="true" class="flex items-center justify-center">
                                            <span
                                                class="h-8 w-8 rounded-full border border-black border-opacity-10 flex items-center justify-center">
                                                <span style="line-height: 1">
                                                    <FontAwesomeIcon :icon="color.icon" aria-hidden="true" />
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>
                    </div>
                    <hr class="mt-5" />

                    <!-- theme picker -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between">
                            <h2 class="text-sm font-medium text-gray-900">Theme</h2>
                        </div>

                        <RadioGroup v-model="selectedTheme" class="mt-2">
                            <div class="grid grid-cols-3 gap-3 sm:grid-cols-2">
                                <RadioGroupOption as="template" v-for="theme in Dummy.theme" :key="theme.name"
                                    :value="theme" v-slot="{ active, checked }">
                                    <div :class="[
                                        'cursor-pointer focus:outline-none',
                                        active
                                            ? 'ring-2 ring-indigo-500 ring-offset-2'
                                            : '',
                                        checked
                                            ? 'border-transparent bg-indigo-600 text-white hover:bg-indigo-700'
                                            : 'border-gray-200 bg-white text-gray-900 hover:bg-gray-50',
                                        'flex items-center justify-center rounded-md border py-3 px-3 text-sm font-medium uppercase sm:flex-1',
                                    ]">
                                        <RadioGroupLabel as="span">{{
                                            theme.name
                                        }}</RadioGroupLabel>
                                    </div>
                                </RadioGroupOption>
                            </div>
                        </RadioGroup>
                    </div>
                    <hr class="mt-5" />
                    <!-- colums -->
                    <div class="mt-8" v-if="selectedNav !== null">
                        <div class="flex items-center justify-between">
                            <h2 class="text-sm font-medium text-gray-900">Menu Type</h2>
                        </div>
                        <RadioGroup class="mt-2">
                            <div class="grid grid-cols-3 gap-3 sm:grid-cols-2">
                                <RadioGroupOption as="template" v-for="option in Dummy.menuType" :key="option.value"
                                    :value="option" v-slot="{ active, checked }">
                                    <div @click="changeMenuType(option)" :class="{
                                        'cursor-pointer': get(selectedNav, 'type') === option.value && selectedNav !== null,
                                        'cursor-not-allowed': get(selectedNav, 'type') !== option.value && selectedNav !== null,
                                        'bg-gray-300 text-gray-600': get(selectedNav, 'type') === option.value && selectedNav !== null, // Apply different class when disabled
                                        'ring-2 ring-indigo-500 ring-offset-2': active,
                                        'border-transparent bg-indigo-600 text-white hover:bg-indigo-700': checked && get(selectedNav, 'type') !== option.value && selectedNav !== null,
                                        'border-gray-200 bg-white text-gray-900 hover:bg-gray-50': !checked && get(selectedNav, 'type') !== option.value && selectedNav !== null,
                                        'flex items-center justify-center rounded-md border py-3 px-3 text-sm font-medium uppercase sm:flex-1': true
                                    }">
                                        <RadioGroupLabel as="span">{{ option.name }}</RadioGroupLabel>
                                    </div>
                                </RadioGroupOption>

                            </div>
                        </RadioGroup>
                    </div>

                    <!-- Mode link , label & icon-->
                    <div v-if="selectedNav">
                        <!-- type group -->
                        <div class="mt-8" v-if="selectedNav.type == 'group'">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-medium text-gray-900">{{ `Colums tools ${selectedNav.name}` }}</h2>
                            </div>
                            <div>
                                <div class="flex gap-2 mt-2">
                                    <div style="width:87%;"
                                        class=" shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                        <input type="text" v-model="selectedNav.name"
                                            class=" flex-1 border-0 bg-transparent text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                            placeholder="title" />
                                    </div>
                                    <div>
                                        <button type="submit" @click.prevent="columItemLinkChange()"
                                            class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm ">
                                            <font-awesome-icon :icon="['fas', 'plus']" />
                                        </button>
                                    </div>
                                </div>

                                <div v-for="(set, index) in selectedNav.featured" :key="set.id" class="mt-5">
                                    <div class="flex gap-2 relative">
                                        <div class="flex justify-end items-end">
                                            <span
                                                class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm">
                                                <IconPicker :key="set.id" :data="set" class="text-black" />
                                            </span>
                                        </div>

                                        <HyperlinkTools :data="set" @OnDelete="() => selectedNav.featured.splice(index, 1)"
                                            :formList="{
                                                name: 'name',
                                                link: 'link',
                                            }" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- type link -->

                        <div class="mt-8" v-if="selectedNav.type == 'link'">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-medium text-gray-900">{{ `Colums tools ${selectedNav.name}` }}</h2>
                            </div>
                            <div>
                                <HyperlinkTools :data="selectedNav" @OnDelete="deleteCategory" :formList="{
                                    name: 'name',
                                    link: 'link',
                                }" />
                            </div>
                        </div>

                    </div>

                </form>
            </div>

            <!-- edit navbar area -->
            <div class="w-[90%] bg-gray-200 flex items-center justify-center">
                <div style="transform: scale(0.8);" class="w-full">
                    <Menu :theme="selectedTheme.value" :navigation="navigation" :tool="handtools" :selectedNav="selectedNav"
                        :changeNavActive="changeNavActive" />
                </div>
            </div>
        </div>

    </div>
    <div @click="() => console.log(navigation)">checkdata</div>
</template>
