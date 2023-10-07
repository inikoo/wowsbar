<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
-->

<script setup lang="ts">
import {trans} from 'laravel-vue-i18n'
import ColorPicker from "@/Components/Workshop/Fields/ColorPicker.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage, faTimes, faOven, faText } from "@/../private/pro-regular-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { isNull, set } from 'lodash'
import Image from "@/Components/Image.vue"
import { notify } from "@kyvg/vue3-notification"
library.add(faImage, faTimes, faOven, faText);

const props = defineProps<{
    data: Object,
    imagesUploadRoute: Object
}>()

const themeOptions = [
    { name: "Centered", value: "center" },
    { name: "Full", value: "full" },
];


const setData = props.data.layout;


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
                setData.imageLayout =  response.data.id
                setData.imageLayoutSrc = response.data.thumbnail
            }
        } catch (error) {
            notify({
                title: trans("Failed to upload image"),
                text: trans('Please contact support'),
                type: "error"
            });
        }
    }
};

const addfavicon = async (element) => {
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
                setData.favicon =  response.data.id
                setData.faviconSrc = response.data.thumbnail
            }
        } catch (error) {
            notify({
                title: trans('Failed to upload image'),
                text: trans('Please contact support'),
                type: "error"
            });
        }
    }
};


</script>

<template>
  <footer class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto flex md:text-left text-center -mb-10 -mx-4">
    <!-- Blok Kategori 1 -->
    <div class="lg:w-1/6 md:w-1/2 w-full px-4">
      <h2 class="title-font font-medium text-gray-900 tracking-widest text-sm mb-3">CATEGORIES</h2>
      <nav class="list-none mb-10">
        <li><a class="text-gray-600 hover:text-gray-800">First Link</a></li>
        <li><a class="text-gray-600 hover:text-gray-800">Second Link</a></li>
        <li><a class="text-gray-600 hover:text-gray-800">Third Link</a></li>
        <li><a class="text-gray-600 hover:text-gray-800">Fourth Link</a></li>
      </nav>
    </div>
    <!-- ... (lanjutan untuk blok-blok kategori lainnya) ... -->
    <div class="lg:w-1/6 md:w-1/2 w-full px-4">
    <h2 class="title-font font-medium text-gray-900 tracking-widest text-sm mb-3">CATEGORIES</h2>
    <nav class="list-none mb-10">
      <li><a class="text-gray-600 hover:text-gray-800">First Link</a></li>
      <li><a class="text-gray-600 hover:text-gray-800">Second Link</a></li>
      <li><a class="text-gray-600 hover:text-gray-800">Third Link</a></li>
      <li><a class="text-gray-600 hover:text-gray-800">Fourth Link</a></li>
    </nav>
  </div>
  <div class="lg:w-1/6 md:w-1/2 w-full px-4">
  <h2 class="title-font font-medium text-gray-900 tracking-widest text-sm mb-3">CATEGORIES</h2>
  <nav class="list-none mb-10">
    <li><a class="text-gray-600 hover:text-gray-800">First Link</a></li>
    <li><a class="text-gray-600 hover:text-gray-800">Second Link</a></li>
    <li><a class="text-gray-600 hover:text-gray-800">Third Link</a></li>
    <li><a class="text-gray-600 hover:text-gray-800">Fourth Link</a></li>
  </nav>
</div>
<div class="lg:w-1/6 md:w-1/2 w-full px-4">
<h2 class="title-font font-medium text-gray-900 tracking-widest text-sm mb-3">CATEGORIES</h2>
<nav class="list-none mb-10">
  <li><a class="text-gray-600 hover:text-gray-800">First Link</a></li>
  <li><a class="text-gray-600 hover:text-gray-800">Second Link</a></li>
  <li><a class="text-gray-600 hover:text-gray-800">Third Link</a></li>
  <li><a class="text-gray-600 hover:text-gray-800">Fourth Link</a></li>
</nav>
</div>
  </div>
  
  <!-- Bagian Form dan Tombol Sosial Media -->
  <div class="border-t border-gray-200">
    <div class="container px-5 py-8 flex mx-auto items-center">
      <form style="margin: 0;">
        <!-- Input Form -->
        <div class="flex md:flex-nowrap justify-center items-end md:justify-start">
          <div class="relative sm:w-64 w-40 sm:mr-4 mr-2">
            <label for="footer-field" class="leading-7 text-sm text-gray-600">Placeholder</label>
            <input type="text" id="footer-field" name="footer-field" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:ring-2 focus:bg-transparent focus:ring-indigo-200 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" required>
          </div>
          <!-- Tombol Submit dan Deskripsi -->
          <button type="submit" class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Button</button>
          <p class="text-gray-500 text-sm md:ml-6 md:mt-0 mt-2 sm:text-left text-center">Bitters chicharrones fanny pack<br class="lg:block hidden">waistcoat green juice</p>
        </div>
      </form>
      
      <!-- Tombol Sosial Media -->
      <span class="inline-flex lg:ml-auto lg:mt-0 mt-6 w-full justify-center md:justify-start md:w-auto">
        <a class="text-gray-500"><svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg></a>
        <a class="ml-3 text-gray-500"><svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path></svg></a>
        <a class="ml-3 text-gray-500"><svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path></svg></a>
        <a class="ml-3 text-gray-500"><svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24"><path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2" stroke="none"></circle></svg></a>
      </span>
    </div>
  </div>
  
  <!-- Bagian Hak Cipta dan Deskripsi -->
  <div class="bg-gray-100">
    <div class="container mx-auto py-4 px-5 flex flex-col sm:flex-row">
      <p class="text-gray-500 text-sm text-center sm:text-left">© 2020 Tailblocks — <a href="https://twitter.com/knyttneve" class="text-gray-600 ml-1" target="_blank" rel="noopener noreferrer">@knyttneve</a></p>
      <span class="sm:ml-auto sm:mt-0 mt-2 sm:w-auto w-full sm:text-left text-center text-gray-500 text-sm">Enamel pin tousled raclette tacos irony</span>
    </div>
  </div>
</footer>

    <!-- Button: Title -->
    <!-- <div class="flex justify-center items-center w-full mt-3">
        <div class="border-2 border-gray-300 rounded-md overflow-hidden">
            <button v-for="option in themeOptions" :key="option.value" @click="setData.layout = option.value"
                :class="[setData.layout === option.value ? 'bg-gray-600 text-white hover:bg-gray-700' : 'hover:bg-gray-200 text-gray-700']"
                class="px-4 py-2 min-w-[100px]"
            >
                {{ option.name }}
            </button>
        </div>
    </div>

    <div class="shadow w-11/12 mx-auto mt-10">
        <div class="flex justify-center items-center w-full">
            <div class="w-full flex justify-start items-center border-gray-400 rounded-t-md bg-gray-200">
                <div
                    class="p-1 w-52 bg-white border-gray-400 border-x-[1px] border-t-[1px] rounded-t-md flex gap-2 justify-start align-middle">
                    <label for="faviconUpload" class="flex justify-start items-center bg-white cursor-pointer">
                        <input type="file" id="faviconUpload" accept="image/*" style="display: none" @change="addfavicon" />
                        <Image :src="setData.faviconSrc" class="w-[20px]" />
                    </label>
                    Website Name
                </div>
            </div>
        </div> -->

        <!-- Workshop: Website Page -->
        <!-- <div class="w-full h-72 flex relative justify-center items-center border border-gray-400 rounded-b-md" :style="{
            'background-image': `url(${setData.imageLayout?.original})`,
            'background-color': `${setData.colorLayout}`,
            'background-repeat': 'no-repeat',
            'background-size': 'cover'
        }">
            <div class="h-full rounded-b-md relative bg-indigo-400 divide-y-2 divide-gray-200" :class="{
                'w-full': setData.layout === 'full',
                'w-[75%] border-x-2 border-gray-300': setData.layout === 'center',
            }"> -->
                <!-- Workshop: Header -->
                <!-- <div class="h-1/6 flex items-center relative" :style="`background-color: ${setData?.header?.color};`">
                    <div class="mx-auto text-xl font-medium text-gray-600">Header</div>
                    <div class="absolute left-0 -translate-x-1/2 top-1/2 -translate-y-1/2">
                        <ColorPicker :color="setData.header.color" :colorSuggestions="false"
                            @onChange="(color) => set(setData, ['header', 'color'], color)" class="" />
                    </div>

                    <div class="absolute right-0 top-1/2 -translate-y-1/2 flex gap-2 items-center scale-75">
                        <div>
                            <ColorPicker :color="setData?.header?.colorScheme"
                                @onChange="(color) => set(setData, ['header', 'colorScheme'], color)"
                                :colorSuggestions="false" mode="scheme" class="" />
                        </div>
                        <div class="font-xs border rounded-lg py-2 px-2 text-gray-600 hidden md:block"
                            :class="`bg-${setData?.header?.colorScheme}-500`">Apoointment</div>
                        <div :style="`border-left: 1px solid ${setData?.header?.colorScheme} hidden md:block`"></div>
                        <div class="font-xs border rounded-lg py-2 px-2 text-gray-600 hidden md:block"
                            :class="`bg-${setData?.header?.colorScheme}-500`">sign in</div>
                    </div>
                </div> -->

                <!-- Workshop: Webpage -->
                <!-- <div class="h-4/6 flex items-center relative" :style="`background-color: ${setData?.content?.color};`">
                    <div class="mx-auto text-xl font-medium text-gray-600">Content</div>
                    <div class="absolute left-0 -translate-x-1/2 top-1/2 -translate-y-1/2">
                        <ColorPicker :color="setData?.content?.color"
                            @onChange="(color) => set(setData, ['content', 'color'], color)" :colorSuggestions="false"
                            class="" />
                    </div>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 flex gap-2 items-center"
                        :style="`color: ${setData?.content?.colorScheme};`">
                        <div class="scale-75">
                            <ColorPicker :color="setData?.header?.color"
                                @onChange="(color) => set(setData, ['content', 'colorScheme'], color)"
                                :colorSuggestions="false" mode="scheme" class="" />
                        </div>
                        <FontAwesomeIcon :icon="['far', 'oven']" class="h-8"
                            :class="`text-${setData?.content?.colorScheme}-500  hidden md:block`" />
                    </div>
                </div> -->

                <!-- Workshop: Footer -->
                <!-- <div class="h-1/6 flex items-center relative" :style="`background-color: ${setData?.footer?.color};`">
                    <div class="mx-auto text-xl font-medium text-gray-600">Footer</div>
                    <div class="absolute left-0 -translate-x-1/2 top-1/2 -translate-y-1/2">
                        <ColorPicker :color="setData?.footer?.color" :colorSuggestions="false"
                            @onChange="(color) => set(setData, ['footer', 'color'], color)" class="" />
                    </div>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 flex gap-2 items-center"
                        :style="`color: ${setData?.content?.colorScheme};`">
                        <div class="scale-75">
                            <ColorPicker :color="setData?.header?.color"
                                @onChange="(color) => set(setData, ['footer', 'colorScheme'], color)"
                                :colorSuggestions="false" mode="scheme" class="" />
                        </div>
                        <FontAwesomeIcon :icon="['far', 'text']" class="h-6"
                            :class="`text-${setData?.footer?.colorScheme}-500 hidden md:block`" />
                    </div>
                </div>
            </div>

            <div v-if="setData.layout === 'center'" class="absolute bottom-0 left-0" style="transform: scale(0.7)">
                <ColorPicker :color="setData?.colorLayout" @onChange="(color) => set(setData, ['colorLayout'], color)"
                    :colorSuggestions="false" />
                <div v-if="!isNull(setData.imageLayout)"
                    class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center bg-white mt-2 cursor-pointer text-red-500"
                    @click="setData.imageLayout = null">
                    <FontAwesomeIcon :icon="['fal', 'times']" />
                </div>
                <label for="imageUpload" v-if="isNull(setData?.imageLayout)"
                    class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center bg-white mt-2 cursor-pointer">
                    <input type="file" id="imageUpload" accept="image/*" style="display: none" @change="addImage" />
                    <FontAwesomeIcon icon="far fa-image" aria-hidden="true" />
                </label>
            </div>
        </div>
    </div> -->
</template>
