<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import { ref } from "vue"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { capitalize } from "@/Composables/capitalize"
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue"
import Slider from "@/Components/Slider/Slider.vue"
import SlidesWorkshopAddMode from "@/Components/Workshop/SlidesWorkshopAddMode.vue"

const props = defineProps<{
    title: string;
    pageHead: object;
    bannerLayout: object;

}>();


console.log('dfsdf',props.bannerLayout)

const test1=()=>{
    console.log(props.bannerLayout)
}
const jumpToIndex = ref(0)

</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead" :dataToSubmit="bannerLayout"></PageHeading>

    <div>
        <!-- First set of components -->
        <div v-if="bannerLayout.components.filter((item) => item.ulid != null).length > 0">
            <Slider :data="bannerLayout" :jumpToIndex="jumpToIndex" />
            <SlidesWorkshop class="clear-both mt-2 p-2.5" :data="bannerLayout" @jumpToIndex="(val) => jumpToIndex = val"/>
        </div>

    <!-- Second set of components -->
    <div v-if="bannerLayout.components.filter((item) => item.ulid != null).length == 0">
      <SlidesWorkshopAddMode :data="bannerLayout" />
    </div>
  </div>



    <div @click="() => {jumpToIndex = 3, console.log(bannerLayout.components)}">chek data</div>
</template>
