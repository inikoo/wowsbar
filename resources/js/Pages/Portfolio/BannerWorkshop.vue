<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import { cloneDeep } from 'lodash'
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue";
import Slider from "@/Components/Slider/Slider.vue";
import SlidesWorkshopAddMode from "@/Components/Workshop/SlidesWorkshopAddMode.vue";

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

    <div>
    <!-- First set of components -->
    <div v-if="data.components.filter((item) => item.ulid != null).length > 0">
      <Slider :data="data" />
      <SlidesWorkshop class="clear-both mt-2 p-2.5" :data="data" />
    </div>

    <!-- Second set of components -->
    <div v-if="data.components.filter((item) => item.ulid != null).length == 0">
      <SlidesWorkshopAddMode :data="data" />
    </div>
  </div>

   

    <div @click="test1">chek data</div>
</template>
