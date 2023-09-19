<script setup lang="ts">
import StatsOne from './StatsOne.vue';
import StatsTwo from './StatsTwo.vue';
import StatsThree from './StatsThree.vue'
import { computed } from 'vue';


const props = defineProps<{
    theme: number,
}>()


const stats = [
  { id: 1, name: 'Creators on the platform', value: '8,000+' },
  { id: 2, name: 'Flat platform fee', value: '3%' },
  { id: 3, name: 'Uptime guarantee', value: '99.9%' },
  { id: 4, name: 'Paid out to creators', value: '$70M' },
]

const components = {
    1: StatsOne,
    2: StatsTwo,
    3: StatsThree,
}


const component = computed(()=>{
    return components[props.theme]
})

</script>


<template>
    <div class="w-full">
        <component :is="component" :data="stats" />
    </div>
</template>


<style scoped></style>