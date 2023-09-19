<script setup lang="ts">
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import StatsOne from './StatsOne.vue';
import StatsTwo from './StatsTwo.vue';
import StatsThree from './StatsThree.vue'
import { computed, ref } from 'vue';


const props = defineProps<{
    theme: number,
}>()

const statsTheme = ref(0)

const stats = [
  { id: 1, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value: '<p><strong><span style="color: black; font-size: 32px;" >80000</span></strong></p>' },
  { id: 2, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value: '<p><strong><span style="color: black; font-size: 32px;" >80000</span></strong></p>'  },
  { id: 3, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value: '<p><strong><span style="color: black; font-size: 32px;" >80000</span></strong></p>' },
  { id: 4, name: '<p><span style="color: #969696; font-size: 18px;">Creators on the platform</p>', value: '<p><strong><span style="color: black; font-size: 32px;" >80000</span></strong></p>'  },
]

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
        <component :is="component" :data="stats" />
        <div @click="()=>console.log('stats',stats)">iniii</div>
    </div>
</template>


<style scoped></style>