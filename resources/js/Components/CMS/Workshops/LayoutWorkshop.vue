<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
-->

<script setup lang="ts">
import { ref, watch } from "vue";
import {
    getDbRef,
    getDataFirebase,
    setDataFirebase,
} from "@/Composables/firebase";
import ColorPicker from "@/Components/Workshop/Fields/ColorPicker.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage, faTimes, faOven } from "@/../private/pro-regular-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { isNull, set } from 'lodash'
import Image from "@/Components/Image.vue"
import { notify } from "@kyvg/vue3-notification"
library.add(faImage, faTimes, faOven);

const props = defineProps<{
	data: Object,
    imagesUploadRoute : Object
}>()

const themeOptions = [
    { name: "Full", value: "full" },
    { name: "Page Margin", value: "margin" },
];


const setData = ref(props.data.layout);

console.log('data',setData.value)

const addImage = async (element) => { 
    const file = element.target.files[0];
    if (file) {
        try {
            const response = await axios.post(
                route(
                    props.imagesUploadRoute.name,
                    props.imagesUploadRoute.parameters
                ),
                { images: file },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );
            if(response.data.thumbnail){
                setData.value.imageLayout = response.data.thumbnail
            }
        } catch (error) {
            console.log(error);
            notify({
                title: "Failed to Update Banner",
                text: error,
                type: "error"
            });
        }
    }
};

const addfavicon= async (element) => { 
    const file = element.target.files[0];
    if (file) {
        try {
            const response = await axios.post(
                route(
                    props.imagesUploadRoute.name,
                    props.imagesUploadRoute.parameters
                ),
                { images: file },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );
            if(response.data.thumbnail){
                setData.value.favicon = response.data.thumbnail
            }
        } catch (error) {
            console.log(error);
            notify({
                title: "Failed to Update Banner",
                text: error,
                type: "error"
            });
        }
    }
};


async function setToFirebase() {
    const column = "org/websites/layout";
    try {
        await setDataFirebase(column, setData.value);
    } catch (error) {
        console.log(error);
    }
}

watch(setData, setToFirebase, { deep: true });

setToFirebase();

</script>

<template>
    <div class="flex justify-center items-center w-full mt-3">
        <div class="w-[60%] flex justify-end">
            <button
                v-for="option in themeOptions"
                :key="option.value"
                @click="setData.layout = option.value"
                :class="{
                    'bg-gray-700 dark:bg-gray-300/90 text-white dark:text-gray-700 hover:bg-gray-800 dark:hover:bg-gray-300':
                    setData.layout === option.value,
                    'bg-gray-300 text-gray-700': setData.layout !== option.value,
                }"
                class="px-4 py-2 w-[150px]"
            >
                {{ option.name }}
            </button>
        </div>
    </div>
    <div class="flex justify-center items-center w-full w">
        <div
            class="w-[60%] flex justify-start items-center border-gray-400 rounded-t-md mt-9 bg-gray-200"
        >
            <div class="p-1 w-52 bg-white border-gray-400 border-x-[1px] border-t-[1px] rounded-t-md flex gap-2 justify-start align-middle">
                <label
                    for="faviconUpload"
                    class="flex justify-center items-center bg-white cursor-pointer"
                >
                    <input 
                        type="file"
                        id="faviconUpload"
                        accept="image/*"
                        style="display: none"
                        @change="addfavicon"
                    />
                    <Image :src="setData.favicon" class="w-[20px]"/>
                </label>
                ~ Website ~
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center w-full">
        <div
            class="w-[60%] h-72 flex relative justify-center items-center border-[1px] border-gray-400 rounded-b-md"
            :style="{
                'background-image': `url(${setData.imageLayout?.original})`,
                'background-color': `${setData.colorLayout}`,
                'background-repeat': 'no-repeat',
                'background-size': 'cover'
            }"
        >
            <div
                class="h-full rounded-b-md relative"
                :class="{
                    'w-full': setData.layout === 'full',
                    'w-[60%]': setData.layout === 'margin',
                }"
            >
                <div class="h-1/3 border-b-2 flex items-center relative" :style="`background-color: ${setData?.header?.color};`">
                    <div class="mx-auto text-xl font-medium text-black">Header</div>
                    <div
                        class="absolute left-[-20px] bottom-8"
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="setData.header.color"
                            :colorSuggestions="false"
                            @onChange="(color)=>set(setData,['header','color'],color)"
                            class=""
                        />
                    </div>
                    <div
                        class="absolute right-0 top-8 flex gap-2"
                        style="transform: scale(0.7)"
                    >
                    <div
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="setData?.header?.color"
                            @onChange="(color)=>set(setData,['header','colorScheme'],color)"
                            :colorSuggestions="false"
                            class=""
                        />
                    </div>
                    <div class="font-xs border rounded-lg py-1 px-2 text-black" :style="`background-color: ${setData?.header?.colorScheme};`">Apoointment</div>
                    <div :style="`border-left: 1px solid ${setData?.header?.colorScheme};`"></div>
                    <div class="font-xs border rounded-lg py-1 px-2 text-black" :style="`background-color: ${setData?.header?.colorScheme};`">sign in</div>
                    </div>
                </div>
                <div
                    class="h-1/3 border-b-2 flex items-center relative" :style="`background-color: ${setData?.content?.color};`"
                >
                    <div class="mx-auto text-xl font-medium text-black">Content</div>
                    <div
                        class="absolute left-[-20px] bottom-8"
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="setData?.content?.color"
                            @onChange="(color)=>set(setData,['content','color'],color)"
                            :colorSuggestions="false"
                            class=""
                        />
                    </div>
                    <div
                        class="absolute right-0 top-5 flex gap-2"
                        :style="`color: ${setData?.content?.colorScheme};`"
                    >
                    <div
                        style="transform: scale(0.5)"
                    >
                        <ColorPicker
                            :color="setData?.header?.color"
                            @onChange="(color)=>set(setData,['content','colorScheme'],color)"
                            :colorSuggestions="false"
                            class=""
                        />
                    </div>
                    <font-awesome-icon :icon="['far', 'oven']" class="w-40 h-14" />

                    </div>
                </div>
                <div
                    class="h-1/3 border-b-2 flex items-center relative " :style="`background-color: ${setData?.footer?.color};`"
                >
                    <div class="mx-auto text-xl font-medium text-black" >Footer</div>
                    <div
                        class="absolute left-[-20px] bottom-8"
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="setData?.footer?.color"
                            :colorSuggestions="false"
                            @onChange="(color)=>set(setData,['footer','color'],color)"
                            class=""
                        />
                    </div>
                </div>
            </div>
            <div
                v-if="setData.layout === 'margin'"
                class="absolute bottom-0 left-0"
                style="transform: scale(0.7)"
            >
                <ColorPicker
                    :color="setData?.colorLayout"
                    @onChange="(color)=>set(setData,['colorLayout'],color)"
                    :colorSuggestions="false"
                />

                <div v-if="!isNull(setData.imageLayout)" class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center bg-white mt-2 cursor-pointer text-red-500" @click="setData.imageLayout = null" >
                    <font-awesome-icon :icon="['fal', 'times']" />
                </div>

                <label
                    for="imageUpload"
                    v-if="isNull(setData?.imageLayout)"
                    class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center bg-white mt-2 cursor-pointer"
                >
                    <input 
                        type="file"
                        id="imageUpload"
                        accept="image/*"
                        style="display: none"
                        @change="addImage"
                    />
                    <FontAwesomeIcon icon="far fa-image" aria-hidden="true" />
                </label>
            </div>
        </div>
    </div>
    <notifications
        group="custom-style"
        position="top center"
        classes="n-light"
        dangerously-set-inner-html
        :max="3"
        :width="400"
    />
</template>
