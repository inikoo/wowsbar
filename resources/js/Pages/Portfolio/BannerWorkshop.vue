<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import { cloneDeep } from 'lodash'
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue";
import Slider from "@/Components/Slider/Slider.vue";


const props = defineProps<{
    title: string;
    pageHead: object;
    bannerLayout: object;

}>();


const data = ref(cloneDeep(props.bannerLayout));
console.log(props.bannerLayout)
const test1=()=>{
    console.log(data.value.components)
}

</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead" :dataToSubmit="data"></PageHeading>

    <div v-if="data.components.length > 0">
    <Slider :data="data" />
    <SlidesWorkshop class="clear-both mt-2 p-2.5"
        :data="data"
    />
    </div>

    <!-- add new file -->
    <!-- <div v-if="data.components.length == 0">
        <div class="col-span-full">
            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
              <div class="text-center">
                <PhotoIcon class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                  <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                    <span>In Progress</span>
                    <input id="file-upload" name="file-upload" type="file" class="sr-only" />
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
              </div>
            </div>
          </div>
    </div> -->

    <div @click="test1">chek data</div>
</template>
