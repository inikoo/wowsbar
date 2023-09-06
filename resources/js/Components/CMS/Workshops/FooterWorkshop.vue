
<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { ref, watch, reactive } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import Footer from '@/Components/CMS/Footer/index.vue'
import { faHandPointer, faHandRock, faPlus } from '../../../../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { v4 as uuidv4 } from 'uuid';
import HyperlinkTools from '@/Components/CMS/Fields/Hyperlinktools.vue'
import { get } from 'lodash'
import HyperInfoTools from '@/Components/CMS/Fields/InfoFieldTools.vue'
import SocialMediaPicker from "@/Components/CMS/Fields/IconPicker/SocialMediaTools.vue"
library.add(faHandPointer, faHandRock, faPlus)

const Dummy = {
    images: [
        {
            id: 1,
            imageSrc: 'https://tailwindui.com/img/ecommerce-images/product-page-01-featured-product-shot.jpg',
            imageAlt: "Back of women's Basic Tee in black.",
            primary: true,
        },
    ],
    tools: [
        { name: 'edit', icon: ['fas', 'fa-hand-pointer'] },
        { name: 'grab', icon: ['fas', 'hand-rock'] },
        // { name: 'Heather Grey', icon: ['fas', 'fa-hand-pointer']},
    ],
    theme: [
        { name: 'Light', value: '2' },
        { name: 'Dark', value: '1' },
        { name: 'Simple', value: '3' },
    ],
    columsType: [
        { name: 'Description', value: 'description' },
        { name: 'List', value: 'list' },
        { name: 'Info', value: 'info' },
    ],
    columsToolsLink: [
        { name: 'Add Item', value: 'add' },
    ],
    columsToolsInfo: [
        { name: 'Add Item', value: 'add' },
    ],
}
const selectedTheme = ref(Dummy.theme[0])
const columsTypeTheme = ref(Dummy.columsType[0])
const tool = ref(Dummy.tools[0])

const DummyColums = [
    {
        title: 'add list',
        type: "list",
        id: uuidv4(),
        data: [
            { name: 'dummy', link: '#' },
            { name: 'dummy2', link: '#' },
        ],
    },
    {
        title: 'add description',
        type: "description",
        id: uuidv4(),
        data: "Lorem Ipsum is simply dummy te printernto electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    },
    {
        title: 'add info',
        type: 'info',
        id: uuidv4(),
        data: [
            {
                title: 'location',
                value: 'Ancient Wisdom s.r.o.',
                icon: 'fab fa-facebook',
                id: uuidv4(),
            },
            {
                title: 'billingAddress',
                value: 'Billin',
                icon: 'fas fa-map',
                id: uuidv4(),
            },
            {
                title: 'vat',
                value: 'VAT: SK2120525440',
                icon: 'fab fa-facebook',
                id: uuidv4(),
            },
            {
                title: 'building',
                value: 'Reg: 50920600',
                icon: 'fas fa-building',
                id: uuidv4(),
            },
            {
                title: 'phone',
                value: '+421 (0)33 558 60 71',
                icon: 'fas fa-phone',
                id: uuidv4(),
            },
            {
                title: 'email',
                value: 'contact@awgifts.eu',
                icon: 'fas fa-envelope',
                id: uuidv4(),
            },
        ],
    }
]

const data = reactive({
    column : [
    {
        title: 'company',
        type: "list",
        id: uuidv4(),
        data: [
            { name: 'About', link: '#', id: uuidv4() },
            { name: 'Blog', link: '#', id: uuidv4() },
            { name: 'Jobs', link: '#', id: uuidv4() },
            { name: 'Press', link: '#', id: uuidv4() },
            { name: 'Partners', link: '#', id: uuidv4() },
        ],
    },
    {
        title: 'legal',
        type: "list",
        id: uuidv4(),
        data: [
            { name: 'Claim', link: '#', id: uuidv4() },
            { name: 'Privacy', link: '#', id: uuidv4() },
            { name: 'Terms', link: '#', id: uuidv4() },
        ],
    },
    {
        title: 'Description',
        type: "description",
        id: uuidv4(),
        data: "Lorem Ipsum is simply dummy te printernto electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    },
    {
        title: 'Info',
        type: 'info',
        id: uuidv4(),
        data: [
            {
                title: 'location',
                value: 'Ancient Wisdom s.r.o.',
                icon: 'fab fa-facebook',
                id: uuidv4()
            },
            {
                title: 'billingAddress',
                value: 'Billin',
                icon: 'fas fa-map',
                id: uuidv4()
            },
            {
                title: 'vat',
                value: 'VAT: SK2120525440',
                icon: 'fab fa-facebook',
                id: uuidv4()
            },
            {
                title: 'building',
                value: 'Reg: 50920600',
                icon: 'fas fa-building',
                id: uuidv4()
            },
            {
                title: 'phone',
                value: '+421 (0)33 558 60 71',
                icon: 'fas fa-phone',
                id: uuidv4()
            },
            {
                title: 'email',
                value: 'contact@awgifts.eu',
                icon: 'fas fa-envelope',
                id: uuidv4(),
            },
        ],
    }
    ],
    social : [
    {
        label: "Facebook",
        link: '#facebook',
        icon: 'fab fa-facebook',
        id: uuidv4()
    },
    {
        label: "Instagram",
        link: '#instagram',
        icon: 'fab fa-instagram',
        id: uuidv4()
    },
    {
        label: "Twitter",
        link: '#twitter',
        icon: 'fab fa-facebook',
        id: uuidv4()
    },
    {
        label: "Github",
        link: '#github',
        icon: 'fab fa-facebook',
        id: uuidv4()
    },
    ],
    copyRight : { label: 'AW Advantage', link: '*' }
})

const columSelected = ref(null);

const selectedColums = (value) => {
    columSelected.value = {...value}
}

const handleColumsTypeChange = (value) => {
    if (value.title !== columSelected.value.type) {
        let indexDummy = DummyColums.findIndex((item) => item.type === value.value);
        let indexColums = data.column.findIndex((item) => item.id === columSelected.value.id);
        const set = { ...DummyColums[indexDummy], id: uuidv4() }
        data.column[indexColums] = set
        selectedColums(data)
    } else { cosnole.log('salah') }

}

const columItemLinkChange = (value) => {
    const set = data.column
    if (value.value == 'add') {
        const index = set.findIndex((item) => item.id == columSelected.value.id)
        if (columSelected.value.type == 'list') set[index].data.push({ name: 'dummy', link: '#' })
        else if (columSelected.value.type == 'info') set[index].data.push({
            title: 'location',
            value: 'new item',
            icon: 'far fa-dot-circle',
            id: uuidv4(),
        })
    }
    data.column = set
}



const saveSocialmedia = (value) => {
    const index = socials.value.findIndex((item) => item.id == value.column.id)
    if (value.type == 'save') {
        const data = { ...socials.value[index], ...value.value }
        socials.value[index] = data
    } else if (value.type == 'delete') {
        const data = socials.value
        data.splice(index, 1)
    }

}
const addSocial = () => {
    data.social.push(
        {
            label: "new",
            link: '#',
            icon: 'far fa-dot-circle',
            id: uuidv4()
        },
    )
}

const changeColumnFromSelectedColumn =()=>{
    const index = data.column.findIndex((item)=> item.id == columSelected.value.id)
    if(index >= 0)  data.column[index] = columSelected.value
}

</script>

<template>
    <div class="bg-white flex">
        <div class="w-1/10 p-6   overflow-y-auto overflow-x-hidden  max-h-[46rem]">
            <form>
                <div>
                  <!-- tool picker -->
                    <h2 class="text-sm font-medium text-gray-900">Tools</h2>
                    <RadioGroup v-model="tool" class="mt-2">
                        <RadioGroupLabel class="sr-only">Choose a tool</RadioGroupLabel>
                        <div class="flex items-center space-x-3">
                            <RadioGroupOption as="template" v-for="color in Dummy.tools" :key="color.name"
                                :value="color" v-slot="{ active, checked }">
                                <div
                                    :class="[color.tools, active && checked ? 'ring ring-offset-1' : '', !active && checked ? 'ring-2' : '', 'relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none']">
                                    <RadioGroupLabel as="span" class="sr-only">{{ color.name }}
                                    </RadioGroupLabel>
                                    <span aria-hidden="true" class="flex items-center justify-center">
                                        <span
                                            class=" h-8 w-8 rounded-full border border-black border-opacity-10 flex items-center justify-center">
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
                <hr class="mt-5">
                <!-- theme picker -->
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">Theme</h2>
                    </div>

                    <RadioGroup v-model="selectedTheme" class="mt-2">
                        <div class="grid grid-cols-3 gap-3 sm:grid-cols-2">
                            <RadioGroupOption as="template" v-for="theme in Dummy.theme" :key="theme.name"
                                :value="theme" v-slot="{ active, checked }">
                                <div
                                    :class="['cursor-pointer focus:outline-none', active ? 'ring-2 ring-indigo-500 ring-offset-2' : '', checked ? 'border-transparent bg-indigo-600 text-white hover:bg-indigo-700' : 'border-gray-200 bg-white text-gray-900 hover:bg-gray-50', 'flex items-center justify-center rounded-md border py-3 px-3 text-sm font-medium uppercase sm:flex-1']">
                                    <RadioGroupLabel as="span">{{ theme.name }}</RadioGroupLabel>
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>
                </div>
                <hr class="mt-5">
                <!-- column type -->
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">Colums Type</h2>
                    </div>
                    <RadioGroup v-model="columsTypeTheme" class="mt-2">
                        <div class="grid grid-cols-3 gap-3 sm:grid-cols-2">
                            <RadioGroupOption as="template" v-for="option in Dummy.columsType" :key="option.value"
                                :value="option" v-slot="{ active, checked }">
                                <div :class="{
                                    'cursor-pointer': !get(columSelected,'type') == option.value,
                                    'cursor-not-allowed': get(columSelected,'type') == option.value,
                                    'bg-gray-300 text-gray-600': get(columSelected,'type') == option.value, // Apply different class when disabled
                                    'ring-2 ring-indigo-500 ring-offset-2': active,
                                    'border-transparent bg-indigo-600 text-white hover:bg-indigo-700': checked && !get(columSelected,'type') == option.value,
                                    'border-gray-200 bg-white text-gray-900 hover:bg-gray-50': !checked && !get(columSelected,'type') == option.value,
                                    'flex items-center justify-center rounded-md border py-3 px-3 text-sm font-medium uppercase sm:flex-1': true
                                }" @click="handleColumsTypeChange(option)">
                                    <RadioGroupLabel as="span">{{ option.name }}</RadioGroupLabel>
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>
                </div>
                <!-- column tools -->
                <div v-if="columSelected">
                 <!-- column tools list -->
                <div class="mt-8" v-if="columSelected.type == 'list'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">{{ `Colums tools ${columSelected.title}`}}</h2>
                    </div>
                    <div>
                        <div class="flex gap-2 mt-2">
                            <div style="width:85%;"
                                class=" shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input type="text" v-model="columSelected.title" @input="changeColumnFromSelectedColumn"
                                    class=" flex-1 border-0 bg-transparent text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="title" />
                            </div>
                            <div>

                                <button type="submit"
                                    @click.prevent="columItemLinkChange({ name: 'Add Item', value: 'add' })"
                                    class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm ">
                                    <font-awesome-icon :icon="['fas', 'plus']" />
                                    </button>
                            </div>

                        </div>

                        <div v-for="(set,index) in columSelected.data" :key="set.id">
                            <HyperlinkTools 
                            :data="set"
                            @OnDelete="()=>columSelected.data.splice(index,1)"
                            :formList ="{
                                name: 'name',
                                link: 'link',
                            }"
                            />
                        </div>
                    </div>

                </div>
                <!-- column tools info-->
                <div class="mt-8" v-if="columSelected.type == 'info'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">{{ `Colums tools ${columSelected.title}`
                        }}</h2>
                    </div>
                    <div>
                        <div class="flex gap-2 mt-2">
                            <div style="width:85%;"
                                class=" shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                <input type="text" v-model="columSelected.title"  @input="changeColumnFromSelectedColumn"
                                    class=" flex-1 border-0 bg-transparent text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    placeholder="title" />
                            </div>
                            <div>

                                <button type="submit"
                                    @click.prevent="columItemLinkChange({ name: 'Add Item', value: 'add' })"
                                    class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm "><font-awesome-icon :icon="['fas', 'plus']" /></button>
                            </div>

                        </div>

                        <div v-for="(set,index) in columSelected.data" :key="set.id">
                            <HyperInfoTools :data="set"  @OnDelete="()=>columSelected.data.splice(index,1)" />
                        </div>
                    </div>
                </div>
            </div>


                <hr class="mt-5">
                <!-- social Media -->
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">{{ `Social media setting` }}</h2>
                    </div>
                    <RadioGroup class="mt-2">
                        <div class="grid grid-cols-3 gap-3 sm:grid-cols-2">
                            <RadioGroupOption as="template" v-for="option in data.social" :key="option.value"
                                :value="option" v-slot="{ active, checked }">
                                <div :class="{
                                    'flex cursor-pointer items-center justify-center rounded-md border py-3 px-3 text-sm font-medium uppercase sm:flex-1': true
                                }">
                                    <RadioGroupLabel as="span">
                                        <SocialMediaPicker :modelValue="option.icon" cssClass="h-6 w-6" :data="option"
                                            :save="saveSocialmedia" />
                                    </RadioGroupLabel>
                                </div>
                            </RadioGroupOption>
                            <RadioGroupOption as="template">
                                <div :class="{
                                    'flex cursor-pointer items-center justify-center rounded-md border  border-dashed py-3 px-3 text-sm font-medium uppercase sm:flex-1': true
                                }" @click="addSocial">
                                    <RadioGroupLabel as="span">
                                        <FontAwesomeIcon :icon="['fas', 'plus']" />
                                    </RadioGroupLabel>
                                </div>
                            </RadioGroupOption>
                        </div>
                    </RadioGroup>
                </div>
            </form>
        </div>

        <div class="w-[90%] bg-gray-200 flex items-center justify-center">
            <div style="transform: scale(0.8);" class="w-full">
                <Footer class="lg:col-span-2 lg:row-span-2 rounded-lg"
                    :data="data"
                    :columSelected="columSelected"
                    :theme="selectedTheme.value" 
                    :selectedColums="selectedColums" 
                    :tool="tool"
                  />
            </div>
        </div>
    </div>
    <div @click="()=>console.log(data)">checkdata</div>
</template>
