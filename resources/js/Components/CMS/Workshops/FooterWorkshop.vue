
<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { ref, reactive, watch } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import Footer from '@/Components/CMS/Footer/index.vue'
import { faHandPointer, faHandRock, faPlus, faAlignJustify, faList, faInfoCircle } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ulid } from "ulid";
import HyperlinkTools from '@/Components/CMS/Fields/Hyperlinktools.vue'
import { get } from 'lodash'
import HyperInfoTools from '@/Components/CMS/Fields/InfoFieldTools.vue'
import SocialMediaPicker from "@/Components/CMS/Fields/IconPicker/SocialMediaTools.vue"
import { getDbRef, getDataFirebase, setDataFirebase } from '@/Composables/firebase'
import ToolInTop from '@/Components/CMS/Footer/ToolsInTop.vue'

library.add(faHandPointer, faHandRock, faPlus, faAlignJustify, faList, faInfoCircle)

const Dummy = {
    images: [
        {
            id: 1,
            imageSrc: 'https://tailwindui.com/img/ecommerce-images/product-page-01-featured-product-shot.jpg',
            imageAlt: "Back of women's Basic Tee in black.",
            primary: true,
        },
    ],
    columsType: [
        { name: 'Description', value: 'description', icon: ['fas', 'align-justify'] },
        { name: 'List', value: 'list', icon: ['fas', 'list'] },
        { name: 'Info', value: 'info', icon: ['fas', 'info-circle'] },
    ],
}

const selectedTheme = ref({ name: 'FooterThemeTwo', value: '2' })
const columsTypeTheme = ref(Dummy.columsType[0])
const tool = ref({ name: 'edit', icon: ['fas', 'fa-hand-pointer'] })

const DummyColums = [
    {
        title: 'add list',
        type: "list",
        id: ulid(),
        data: [
            { name: 'dummy', link: '#' },
            { name: 'dummy2', link: '#' },
        ],
    },
    {
        title: 'add description',
        type: "description",
        id: ulid(),
        data: "Lorem Ipsum is simply dummy te printernto electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    },
    {
        title: 'add info',
        type: 'info',
        id: ulid(),
        data: [
            {
                title: 'location',
                value: 'Ancient Wisdom s.r.o.',
                icon: 'fab fa-facebook',
                id: ulid(),
            },
            {
                title: 'billingAddress',
                value: 'Billin',
                icon: 'fas fa-map',
                id: ulid(),
            },
            {
                title: 'vat',
                value: 'VAT: SK2120525440',
                icon: 'fab fa-facebook',
                id: ulid(),
            },
            {
                title: 'building',
                value: 'Reg: 50920600',
                icon: 'fas fa-building',
                id: ulid(),
            },
            {
                title: 'phone',
                value: '+421 (0)33 558 60 71',
                icon: 'fas fa-phone',
                id: ulid(),
            },
            {
                title: 'email',
                value: 'contact@awgifts.eu',
                icon: 'fas fa-envelope',
                id: ulid(),
            },
        ],
    }
]

const data = reactive({
    column: [
        {
            title: 'company',
            type: "list",
            id: ulid(),
            data: [
                { name: 'About', link: '#', id: ulid() },
                { name: 'Blog', link: '#', id: ulid() },
                { name: 'Jobs', link: '#', id: ulid() },
                { name: 'Press', link: '#', id: ulid() },
                { name: 'Partners', link: '#', id: ulid() },
            ],
        },
        {
            title: 'legal',
            type: "list",
            id: ulid(),
            data: [
                { name: 'Claim', link: '#', id: ulid() },
                { name: 'Privacy', link: '#', id: ulid() },
                { name: 'Terms', link: '#', id: ulid() },
            ],
        },
        {
            title: 'Description',
            type: "description",
            id: ulid(),
            data: "Lorem Ipsum is simply dummy te printernto electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
        },
        {
            title: 'Info',
            type: 'info',
            id: ulid(),
            data: [
                {
                    title: 'location',
                    value: 'Ancient Wisdom s.r.o.',
                    icon: 'fab fa-facebook',
                    id: ulid()
                },
                {
                    title: 'billingAddress',
                    value: 'Billin',
                    icon: 'fas fa-map',
                    id: ulid()
                },
                {
                    title: 'vat',
                    value: 'VAT: SK2120525440',
                    icon: 'fab fa-facebook',
                    id: ulid()
                },
                {
                    title: 'building',
                    value: 'Reg: 50920600',
                    icon: 'fas fa-building',
                    id: ulid()
                },
                {
                    title: 'phone',
                    value: '+421 (0)33 558 60 71',
                    icon: 'fas fa-phone',
                    id: ulid()
                },
                {
                    title: 'email',
                    value: 'contact@awgifts.eu',
                    icon: 'fas fa-envelope',
                    id: ulid(),
                },
            ],
        }
    ],
    social: [
        {
            label: "Facebook",
            link: '#facebook',
            icon: 'fab fa-facebook',
            id: ulid()
        },
        {
            label: "Instagram",
            link: '#instagram',
            icon: 'fab fa-instagram',
            id: ulid()
        },
        {
            label: "Twitter",
            link: '#twitter',
            icon: 'fab fa-facebook',
            id: ulid()
        },
        {
            label: "Github",
            link: '#github',
            icon: 'fab fa-facebook',
            id: ulid()
        },
    ],
    copyRight: { label: 'AW Advantage', link: '*' }
})

async function setToFirebase() {
    const column = 'org/websites/footer';
    try {
        console.log('selectedTheme',selectedTheme)
        await setDataFirebase(column, { data: data, theme: selectedTheme.value });
    } catch (error) {
        console.log(error)
    }
}

watch(data, setToFirebase, { deep: true })
watch(selectedTheme, setToFirebase, { deep: true })

setToFirebase()

const columSelected = ref(null);

const selectedColums = (value) => {
    columSelected.value = value
}

const handleColumsTypeChange = (value) => {
    if (value.title !== data.column[columSelected.value].type) {
        let indexDummy = DummyColums.findIndex((item) => item.type === value.value);
        let indexColums = data.column.findIndex((item) => item.id === data.column[columSelected.value].id);
        const set = { ...DummyColums[indexDummy], id: ulid() }
        data.column[indexColums] = set
        selectedColums(data)
    } else { cosnole.log('salah') }

}

const columItemLinkChange = (value) => {
    const set = data.column
    if (value.value == 'add') {
        const index = set.findIndex((item) => item.id == data.column[columSelected.value].id)
        if (data.column[columSelected.value].type == 'list') set[index].data.push({ name: 'dummy', link: '#' })
        else if (data.column[columSelected.value].type == 'info') set[index].data.push({
            title: 'location',
            value: 'new item',
            icon: 'far fa-dot-circle',
            id: ulid(),
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
            id: ulid()
        },
    )
}

const changeColumnFromSelectedColumn = () => {
    const index = data.column.findIndex((item) => item.id == data.column[columSelected.value].id)
    if (index >= 0) data.column[index] = data.column[columSelected.value]
}




</script>

<template>
    <div class="bg-white flex">
        <div class="w-[250px] p-6 overflow-y-auto overflow-x-hidden  h-[46rem]">
            <!-- Column Type -->
            <div v-if="selectedTheme.name !== 'FooterThemeThree'">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-medium text-gray-900">Column Type</h2>
                </div>
                <RadioGroup v-model="columsTypeTheme" class="mt-2">
                    <div class="flex justify-start gap-3">
                        <RadioGroupOption as="template" v-for="option in Dummy.columsType" :key="option.value">
                            <div :title="option.name" :class="{
                                'cursor-not-allowed': get(data.column[columSelected], 'type') == option.value,
                                'bg-gray-300 text-gray-600': get(data.column[columSelected], 'type') == option.value,
                                'flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit cursor-pointer': true
                            }" @click="handleColumsTypeChange(option)">
                                <RadioGroupLabel as="span" class="w-fit"><font-awesome-icon :icon="option.icon" />
                                </RadioGroupLabel>
                            </div>
                        </RadioGroupOption>
                    </div>
                </RadioGroup>
            </div>
            <!-- end Column Type -->

            <!-- column tools -->
            <div v-if="data.column[columSelected]">
                <!-- column tools list -->
                <div class="mt-2" v-if="data.column[columSelected].type == 'list'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-medium text-gray-900">{{ `${data.column[columSelected].title}` }}</h2>
                    </div>
                    <div>
                        <div class="flex gap-2 mt-2">
                            <div class="w-[90%]">
                                <div
                                    class="shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md rounded-md">
                                    <input type="text" v-model="data.column[columSelected].title"
                                        @input="changeColumnFromSelectedColumn"
                                        class="flex-1 border-0 bg-transparent text-gray-900 text-xs placeholder:text-gray-400 focus:ring-0 text-xs sm:text-sm sm:leading-6 w-full overflow-hidden"
                                        placeholder="xs" />
                                </div>
                            </div>
                            <div class="flex justify-center align-middle">
                                <button type="submit"
                                    @click.prevent="columItemLinkChange({ name: 'Add Item', value: 'add' })"
                                    class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-xs font-semibold text-black shadow-sm">
                                    <font-awesome-icon :icon="['fas', 'plus']" />
                                </button>
                            </div>
                        </div>


                        <div v-for="(set, index) in data.column[columSelected].data" :key="set.id">
                            <HyperlinkTools :data="set" @OnDelete="() => data.column[columSelected].data.splice(index, 1)"
                                :formList="{
                                    name: 'name',
                                    link: 'link',
                                }" />
                        </div>
                    </div>

                </div>
                <!-- column tools info-->
                <div class="mt-2" v-if="data.column[columSelected].type == 'info'">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">{{ `Colums tools
                            ${data.column[columSelected].title}`
                        }}</h2>
                    </div>
                    <div>
                        <div class="flex gap-2 mt-2">
                            <div style="width:85%;"
                                class="shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md rounded-md">
                                <input type="text" v-model="data.column[columSelected].title"
                                    @input="changeColumnFromSelectedColumn"
                                    class="flex-1 border-0 bg-transparent text-gray-900 text-xs placeholder:text-gray-400 focus:ring-0 text-xs sm:text-sm sm:leading-6 w-full overflow-hidden"
                                    placeholder="title" />
                            </div>
                            <div>

                                <button type="submit"
                                    @click.prevent="columItemLinkChange({ name: 'Add Item', value: 'add' })"
                                    class="rounded-md cursor-pointer border ring-gray-300 px-3 py-2 text-sm font-semibold text-black shadow-sm "><font-awesome-icon
                                        :icon="['fas', 'plus']" /></button>
                            </div>

                        </div>

                        <div v-for="(set, index) in data.column[columSelected].data" :key="set.id">
                            <HyperInfoTools :data="set"
                                @OnDelete="() => data.column[columSelected].data.splice(index, 1)" />
                        </div>
                    </div>
                </div>
            </div>


            <hr class="mt-5">
            <!-- social Media -->
            <div class="mt-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-medium text-gray-900">{{ `Social media` }}</h2>
                </div>
                <RadioGroup class="mt-2" style="max-height: 200px;">
                    <div class="flex gap-2 flex-wrap">
                        <RadioGroupOption as="template" v-for="option in data.social" :key="option.value" :value="option"
                            v-slot="{ active, checked }">
                            <div :class="{
                                'flex cursor-pointer items-center justify-center rounded-md border py-1 px-2 text-sm font-sm uppercase w-fit': true
                            }">
                                <RadioGroupLabel as="span">
                                    <SocialMediaPicker :modelValue="option.icon" cssClass="font-sm" :data="option"
                                        :save="saveSocialmedia" />
                                </RadioGroupLabel>
                            </div>
                        </RadioGroupOption>
                        <RadioGroupOption as="span">
                            <div :class="{
                                'flex cursor-pointer items-center justify-center rounded-md border border-dashed py-1 px-2 text-sm font-sm uppercase sm:flex-1 w-fit': true
                            }" @click="addSocial">
                                <RadioGroupLabel as="span">
                                    <FontAwesomeIcon :icon="['fas', 'plus']" />
                                </RadioGroupLabel>
                            </div>
                        </RadioGroupOption>
                    </div>
                </RadioGroup>

            </div>

        </div>

        <div class=" w-full bg-gray-200  items-center justify-center">
            <ToolInTop :tool="tool" :theme="selectedTheme" :columSelected="columSelected"
                @setColumnSelected="selectedColums" />
            <div style="transform: scale(0.8);" class="w-full">
                <Footer class="lg:col-span-2 lg:row-span-2 rounded-lg" :data="data"
                    :columSelected="data.column[columSelected]" :theme="selectedTheme.value"
                    :selectedColums="selectedColums" :tool="tool" />
            </div>
        </div>
    </div>
    <div @click="() => console.log(data)">checkdata</div>
</template>
