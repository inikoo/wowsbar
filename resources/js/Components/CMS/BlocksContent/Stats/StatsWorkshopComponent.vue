<script setup lang="ts">
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import StatsOne from '@/Components/CMS/BlocksContent/Stats/StatsOne.vue'
import StatsTwo from '@/Components/CMS/BlocksContent/Stats/StatsTwo.vue'
// import StatsThree from '@/Components/CMS/BlocksContent/Stats/StatsThree.vue'
import { computed, ref, reactive } from 'vue'

const statsTheme = ref(0)

const dataStats = reactive({
    title: "<p style=\"text-align: center\"><strong><span style=\"font-size: 28px; color: rgb(75 85 99)\">Trusted by creators worldwide</span></strong></p>",
    description: "<p style=\"text-align: center\"><span style=\"color: rgb(107 114 128)\">Lorem ipsum dolor sit amet consect adipisicing possimus.</span></p>",
    statsList: [
        {
            id: 1,
            name: '<p><span style="font-size: 12px; color: rgb(72, 78, 99)">Number of deploys</span></p>',
            value: '<p style="text-align: center"><strong><span style="font-size: 24px; color: rgb(99, 102, 241)">405</span></strong></p>',
            icon: 'fal fa-database'
        },
        {
            id: 2,
            name: '<p><span style="font-size: 12px; color: rgb(72, 78, 99)">Average deploy time</span></p>',
            value: '<p style="text-align: center"><strong><span style="font-size: 24px; color: rgb(99, 102, 241)">3.65 mins</span></strong></p>',
            icon: 'fal fa-clock'
        },
        {
            id: 3,
            name: '<p><span style="font-size: 12px; color: rgb(72, 78, 99)">Number of servers</span></p>',
            value: '<p style="text-align: center"><strong><span style="font-size: 24px; color: rgb(99, 102, 241)">3</span></strong></p>',
            icon: 'fal fa-server'
        },
    ]
})

const components = {
    0: StatsOne,
    1: StatsTwo,
    // 2: StatsThree,
}

const component = computed(()=>{
    return components[statsTheme.value]
})

</script>

<template>
    <div class="w-full">
        <ButtonTheme v-model="statsTheme" :label="['Simple', 'Clean']"/>
        <component :is="component" :dataStats="dataStats" />
        <!-- <div @click="()=>console.log('stats',stats,pagehead)">iniii</div> -->
    </div>
</template>