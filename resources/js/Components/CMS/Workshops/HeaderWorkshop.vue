<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { ref, reactive, watch } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core';
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { faHandPointer, faHandRock, faPlus, faText, faSearch, faImage, faTrash, faBars } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ulid } from "ulid";
import { get } from 'lodash'
import Input from '@/Components/CMS/Fields/Input.vue';
import Layout from '../Header/Layout.vue';
import ToolsTop from '@/Components/CMS/Header/ToolsTop.vue';
import draggable from "vuedraggable"
import { getDbRef, getDataFirebase, setDataFirebase } from '@/Composables/firebase'
library.add(faHandPointer, faText, faSearch, faImage, faTrash, faBars)

const Dummy = {
    tools: [
        { name: 'edit', icon: ['fas', 'fa-hand-pointer'] },
        { name: 'grab', icon: ['fas', 'hand-rock'] },
        // { name: 'Heather Grey', icon: ['fas', 'fa-hand-pointer']},
    ],
    addContent: [
        { name: 'Text', value: 'text', icon: "fas fa-text" },
        { name: 'Image', value: 'image', icon: "fas fa-image" },
        { name: 'Search', value: 'search', icon: "fas fa-search" },
    ],
}

const data = reactive(
    [
        {
            name: "Tel: +44 (0) 1142729165   info@aw-fulfilment.co.uk",
            id: ulid(),
            type: "text",
            style: {
                top: '111px',
                left: '936px',
                fontSize: "11px"
            },

        },
        {
            name: "We Can Fulfil Your Orders",
            id: ulid(),
            type: "text",
            style: {
                top: '45px',
                left: '934px',
                fontSize: "11px"
            },
        },
        {
            name: "search",
            id: ulid(),
            type: "search",
            style: {
                top: '65px',
                left: '933px',
                fontSize: "34px"
            }
        },
        {
            name: "Your UK's Best Fulfilment Warehouse",
            id: ulid(),
            type: "text",
            style: {
                top: '46px',
                left: '336px',
                fontSize: "28px",
                color: "rgba(255, 128, 0, 1)"
            }
        },
        {
            name: "Storage - Pick & Pack - Distribution",
            id: ulid(),
            type: "text",
            style: {
                top: '83px',
                left: '400px',
                fontSize: "22px",
                color: "rgba(255, 218, 0, 1)"
            },
        }
    ]
)

const layerActive = ref(null)

const layout = ref({
    right: 0,
    bottom: 0,
    height: "200px",
    width: '1233px',
    left: 0,
    top: 0,
})


const setActive = (id) => {
    const index = data.findIndex((item) => item.id == id)
    if (index >= 0) layerActive.value = index
    else layerActive.value = null
}

const createContent = (value) => {
    let setData = {}
    if (value == 'text')
        setData = {
            name: 'Title',
            id: ulid(),

            type: 'text',
            style: { top: '75px', left: '536px', fontSize: '34px', },
        }
    if (value == 'search')
        setData = {
            name: 'search',
            id: ulid(),
            type: 'search',
            style: { top: '75px', left: '536px', fontSize: '34px', },
        }

    data.splice(0, 0, setData)
}

const fileInput = ref(null)
const Uploadimage = () => {
    let setData = {}
    for (const set of fileInput.value[0].files) {
        setData = {
            name: 'image',
            id: ulid(),
            type: 'image',
            style: { top: '0px', left: '0px', height : '200px' , width : '200px' },
            file: set
        }
    }
    data.splice(0, 0, setData)
}

const deleteContent = (index) => [
    data.splice(index, 1)
]

const setUpDataBeforeSend = (setData) => {
    for(const item of data){
        delete item.ref;
    }
    return setData

};

async function setToFirebase() {
  const column = 'org/websites/header';
  const dataSend = setUpDataBeforeSend({...data})
  try {
    await setDataFirebase(column, { data : dataSend, layout : layout.value });
  } catch (error) {
    console.log(error)
  }
}

watch(data, setToFirebase, { deep: true })
watch(layout, setToFirebase, { deep: true })
setToFirebase()

</script>

<template>
    <div class="bg-white">
        <div class="flex" @click="layerActive = null">
            <div class="w-[380px] p-6 overflow-y-auto overflow-x-hidden border border-gray-300 border-1 h-[46rem]">
                <!-- add Content -->
                <div>
                    <h2 class="text-sm font-medium text-gray-900">Content</h2>
                    <div class="mt-2">
                        <div class="flex items-center space-x-3">
                            <div as="template" v-for="item in Dummy.addContent">
                                <div v-if="item.value !== 'image'"
                                    class='relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none'>
                                    <div @click="createContent(item.value)"
                                        :class="['flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit']">
                                        <div as="span">
                                            <FontAwesomeIcon :icon="item.icon" />
                                        </div>
                                    </div>
                                </div>
                                <div v-if="item.value == 'image'"
                                    class='relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none'>
                                    <div
                                        :class="['flex items-center justify-center rounded-md border py-1 px-2 text-sm font-medium uppercase w-fit']">
                                        <input type="file"  name="file" id="fileInput" class="sr-only"
                                            @change="Uploadimage" ref="fileInput" accept=".jpg,.jpeg,.png" />
                                        <label for="fileInput" as="span">
                                            <FontAwesomeIcon :icon="item.icon" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- end add Content -->

                <!-- list data -->
                <hr class="my-5" />
                <div>
                    <h2 class="text-sm font-medium text-gray-900">Content</h2>
                    <div class="mt-2 bg-gray-300 py-2.5 rounded-md ring-2 ring-gray-400">
                        <draggable :list="data" group="content" item-key="id" handle=".handle" class="px-2">
                            <template #item="{ element: item, index }">
                                <div
                                    class="flex gap-3 px-2.5 py-2.5 ring-1 ring-gray-400 rounded-md bg-gray-100 flex-grow h-fit mt-1.5 justify-center">
                                    <div class="flex justify-center items-center  text-gray-600 rounded-md h-fit  py-1">
                                        <font-awesome-icon :icon="['fas', 'bars']" class="handle cursor-grab" />
                                    </div>
                                    <div
                                        class="flex items-center justify-center rounded-md border border-gray-300 py-1 px-1 text-sm w-[15%] h-fit">
                                        <font-awesome-icon :icon="['fas', 'text']" v-if="item.type == 'text'" />
                                        <font-awesome-icon :icon="['fas', 'search']" v-if="item.type == 'search'" />
                                        <font-awesome-icon :icon="['fas', 'image']" v-if="item.type == 'image'" />
                                    </div>
                                    <div v-if="item.type == 'text'"
                                        class="w-full ring-1 ring-gray-300 rounded-md flex justify-center items-center h-fit whitespace-nowrap overflow-hidden overflow-ellipsis px-2">
                                        <Input :data="item" keyValue="name" styleCss="font-size: 12px; border:none; padding:0px" >
                                        <template #buttonMode>
                                        <div class="truncate w-[125px] text-sm"> {{ item.name }}</div>
                                        </template>
                                        </Input>
                                    </div>
                                    <div v-if="item.type == 'search'"
                                        class="w-full ring-1 ring-gray-300 rounded-md flex justify-center items-center h-fit text-sm">
                                        Search
                                    </div>
                                    <div v-if="item.type == 'image'"
                                        class="w-full ring-1 ring-gray-300 rounded-md flex justify-center items-center h-fit text-sm">
                                        Image
                                    </div>
                                    <div class="flex justify-center items-center  text-red-600 rounded-md h-fit  py-1">
                                        <font-awesome-icon :icon="['fas', 'trash']" @click="deleteContent(index)" />
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </div>

                </div>


            </div>

            <!-- editing area -->
            <div class="w-full bg-gray-200 border border-gray-300 overflow-hidden">
                <ToolsTop  :data="data" :layerActive="layerActive" @click="(e)=>e.stopPropagation()" />
                <div class="p-3">
                    <Layout :data="data" :layout="layout" :setActive="setActive" :layerActive="layerActive" />
                </div>
            </div>

        </div>

    </div>
    <div @click="() => console.log(data)">cekdata</div>
</template>

