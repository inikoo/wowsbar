<script setup lang="ts">
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import StatsOne from './StatsOne.vue';
import StatsTwo from './StatsTwo.vue';
import StatsThree from './StatsThree.vue'
import { computed, ref } from 'vue';


const props = defineProps<{}>()

const statsTheme = ref(0)

const stats = [
  { id: 1, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value: "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: black\">80000</span></strong></p>"},
  { id: 2, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value:  "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: black\">80000</span></strong></p>"  },
  { id: 3, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value:  "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: black\">80000</span></strong></p>" },
  { id: 4, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value:  "<p style=\"text-align: center\"><strong><span style=\"font-size: 32px; color: black\">80000</span></strong></p>"  },
]

const pagehead = {
    title : "<p style=\"text-align: center\"><strong><span style=\"font-size: 28px\">Trusted by creators worldwide</span></strong></p>",
    description : "<p style=\"text-align: center\">Lorem ipsum dolor sit amet consect adipisicing possimus.</p>",
    subtitle :  "<p><strong><span style=\"font-size: 24px; color: #c639ba\">iste dolor cupiditate blanditiis ratione.</span></strong></p>"
}

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
        <component :is="component" :data="stats" :pagehead="pagehead" />
        <div @click="()=>console.log('stats',stats,pagehead)">iniii</div>
    </div>
</template>


<style scoped></style>