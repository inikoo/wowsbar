<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faHandPointer,
    faText,
    faSearch,
    faImage,
    faTrash,
    faBars,
} from "@/../private/pro-solid-svg-icons";
import ToolsTop from "@/Components/CMS/Header/ToolsTop.vue";
import Header from "@/Components/CMS/Header/index.vue";
import Image from "@/Components/Image.vue"

library.add(faHandPointer, faText, faSearch, faImage, faTrash, faBars);
const props = defineProps<{
    data: Object;
}>();



const set = ref({...props.data.header});

const changeLogo = async (element) => {
    const file = element.target.files[0];
    if (file) {
        set.value.img = URL.createObjectURL(file);
    }
};
</script>

<template>
    <div class="bg-white">
        <div class="flex">
            <div
                class="w-[200px] p-6 overflow-y-auto overflow-x-hidden h-[46rem]"
            >
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium text-gray-900">Logo</h2>
                </div>
                <label
                    for="faviconUpload"
                    class="flex justify-center items-center bg-white cursor-pointer"
                >
                    <input
                        type="file"
                        id="faviconUpload"
                        accept="image/*"
                        style="display: none"
                        @change="changeLogo"
                    />
                    <Image :src="set.logo" class="inline-block h-14 w-auto rounded-md my-2"/>
                </label>

                <hr class="mt-5" />
            </div>

            <!-- editing area -->
            <div class="w-full bg-gray-200 overflow-hidden">
                <ToolsTop
                    :theme="set.theme"
                    @click="(e) => e.stopPropagation()"
                />
                <div style="transform: scale(0.8)" class="w-full">
                    <Header :theme="set.component" :data="set" />
                </div>
            </div>
        </div>
    </div>
</template>
