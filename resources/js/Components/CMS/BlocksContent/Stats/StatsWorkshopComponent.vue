<script setup lang="ts">
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import StatsOne from './StatsOne.vue'
import StatsTwo from './StatsTwo.vue'
import StatsThree from './StatsThree.vue'
import { computed, ref, reactive } from 'vue'


const statsTheme = ref(0)

const dataStats = reactive({
    title: "<p style=\"text-align: center\"><strong><span style=\"font-size: 28px\">Trusted by creators worldwide</span></strong></p>",
    description: "<p style=\"text-align: center\">Lorem ipsum dolor sit amet consect adipisicing possimus.</p>",
    subtitle:  "<p><strong><span style=\"font-size: 24px; color: #c639ba\">iste dolor cupiditate blanditiis ratione.</span></strong></p>",
    statsList: [
        {
            id: 1,
            name: '<p><span style="color: #484e63; font-size: 18px;">Number of deploys</p>',
            value: "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: rgb(99, 102, 241)\">405</span></strong></p>",
            icon: 'fal fa-database'
        },
        {
            id: 2,
            name: '<p><span style="color: #484e63; font-size: 18px;">Average deploy time</p>',
            value: "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: rgb(99, 102, 241)\">3.65 mins</span></strong></p>",
            icon: 'fal fa-database'
        },
        {
            id: 3,
            name: '<p><span style="color: #484e63; font-size: 18px;">Number of servers</p>',
            value: "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: rgb(99, 102, 241)\">3</span></strong></p>",
            icon: 'fal fa-database'
        },
    ]

})

const components = {
    0: StatsOne,
    1: StatsTwo,
    2: StatsThree,
}


const component = computed(()=>{
    return components[statsTheme.value]
})

</script>


<template>
    <div class="w-full">
        <ButtonTheme v-model="statsTheme" :label="['Simple', 'wide', 'image']"/>
        <component :is="component" :dataStats="dataStats" />
        <!-- <div @click="()=>console.log('stats',stats,pagehead)">iniii</div> -->
    </div>
</template>


<style scoped></style>