<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import { ref, reactive } from "vue"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { capitalize } from "@/Composables/capitalize"
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue"
import Slider from "@/Components/Slider/Slider.vue"
import SlidesWorkshopAddMode from "@/Components/Workshop/SlidesWorkshopAddMode.vue"
import { cloneDeep } from 'lodash'
const props = defineProps<{
    title: string;
    pageHead: object;
    bannerLayout: object;
    imagesUploadRoute: {
        name: string
        parameters?: Array<string>
    }

}>();

// console.log('DataFormDB',props.bannerLayout)

const data = reactive(cloneDeep(props.bannerLayout))

// console.log('SendData',data)

const jumpToIndex = ref(0)

</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead" :dataToSubmit="data"></PageHeading>

    <div>
        <!-- First set of components -->
        <div v-if="data.components.filter((item) => item.ulid != null).length > 0">
            <Slider :data="data" :jumpToIndex="jumpToIndex" />
            <SlidesWorkshop class="clear-both mt-2 p-2.5" :data="data" @jumpToIndex="(val) => jumpToIndex = val"/>
        </div>

    <!-- Second set of components -->
    <div v-if="data.components.filter((item) => item.ulid != null).length == 0">
        <SlidesWorkshopAddMode :data="data" />
    </div>
</div>



    <div @click="() => {jumpToIndex = 3, console.log(data)}">Check data</div>
</template>
