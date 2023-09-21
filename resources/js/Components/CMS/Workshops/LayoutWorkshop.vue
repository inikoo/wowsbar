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
import { faImage, faTimes } from "@/../private/pro-regular-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { isNull } from 'lodash'
library.add(faImage, faTimes);

const themeOptions = [
    { name: "Full", value: "full" },
    { name: "Page Margin", value: "margin" },
];

const data = ref({
    layout: "full",
    favicon: 'http://wowsbar.test/favicons/wowsbar-website-favicon-color-180x180.png',
    colorLayout: "rgba(99, 102, 241, 255)",
    imageLayout: null,
    header: {
        color: "rgba(255, 255, 255, 255)",
    },
    content: {
        color: "rgba(255, 255, 255, 255)",
    },
    footer: {
        color: "rgba(255, 255, 255, 255)",
    },
});

const fileInput = ref(null);


const addImage = async (element) => { 
    const file = element.target.files[0];
    if (file) {
        data.value.imageLayout = URL.createObjectURL(file);
      }
};

const addfavicon= async (element) => { 
    const file = element.target.files[0];
    if (file) {
        data.value.favicon = URL.createObjectURL(file);
      }
};


// async function setToFirebase() {
//     const column = "org/websites/layout";
//     try {
//         await setDataFirebase(column, selectedTheme.value);
//     } catch (error) {
//         console.log(error);
//     }
// }

// watch(selectedTheme, setToFirebase, { deep: true });

// setToFirebase();

</script>

<template>
    <div class="flex justify-center items-center w-full mt-3">
        <div class="w-[80%] flex justify-end">
            <button
                v-for="option in themeOptions"
                :key="option.value"
                @click="data.layout = option.value"
                :class="{
                    'bg-gray-700 dark:bg-gray-300/90 text-white dark:text-gray-700 hover:bg-gray-800 dark:hover:bg-gray-300':
                        data.layout === option.value,
                    'bg-gray-300 text-gray-700': data.layout !== option.value,
                }"
                class="px-4 py-2 w-[150px]"
            >
                {{ option.name }}
            </button>
        </div>
    </div>
    <div class="flex justify-center items-center w-full w">
        <div
            class="w-[80%] flex justify-start items-center border-gray-400 rounded-t-md mt-9 bg-gray-200"
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
                    <img :src="data.favicon" class="w-[20px]"/>
                </label>
                Awa
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center w-full">
        <div
            class="w-[80%] h-96 flex relative justify-center items-center border-[1px] border-gray-400 rounded-b-md"
            :style="{
                'background-image': `url(${data.imageLayout})`,
                'background-color': `${data.colorLayout}`,
                'background-repeat': 'no-repeat',
                'background-size': 'cover'
            }"
        >
            <div
                class="h-full rounded-b-md relative"
                :class="{
                    'w-full': data.layout === 'full',
                    'w-[60%]': data.layout === 'margin',
                }"
            >
                <div
                    class="h-1/3 border-b-2 flex items-center relative"
                    :style="`background-color: ${data.header.color} ;`"
                >
                    <div class="mx-auto text-3xl font-medium">Header</div>
                    <div
                        class="absolute left-[-20px] bottom-0"
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="data.header.color"
                            @onChange="(value) => (data.header.color = value)"
                            class=""
                        />
                    </div>
                </div>
                <div
                    class="h-1/3 border-b-2 flex items-center relative"
                    :style="`background-color: ${data.content.color} ;`"
                >
                    <div class="mx-auto text-3xl font-medium">Content</div>
                    <div
                        class="absolute left-[-20px] bottom-0"
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="data.content.color"
                            @onChange="(value) => (data.content.color = value)"
                            class=""
                        />
                    </div>
                </div>
                <div
                    class="h-1/3 border-b-2 flex items-center relative"
                    :style="`background-color: ${data.footer.color} ;`"
                >
                    <div class="mx-auto text-3xl font-medium">Footer</div>
                    <div
                        class="absolute left-[-20px] bottom-0"
                        style="transform: scale(0.7)"
                    >
                        <ColorPicker
                            :color="data.footer.color"
                            @onChange="(value) => (data.footer.color = value)"
                            class=""
                        />
                    </div>
                </div>
            </div>
            <div
                v-if="data.layout === 'margin'"
                class="absolute bottom-0 left-0"
                style="transform: scale(0.7)"
            >
                <ColorPicker
                    :color="data.colorLayout"
                    @onChange="(value) => (data.colorLayout = value)"
                    class=""
                    :colorSuggestions="false"
                />

                <div v-if="!isNull(data.imageLayout)" class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center bg-white mt-2 cursor-pointer text-red-500" @click="data.imageLayout = null" >
                    <font-awesome-icon :icon="['fal', 'times']" />
                </div>

                <label
                    for="imageUpload"
                    v-if="isNull(data.imageLayout)"
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
</template>
