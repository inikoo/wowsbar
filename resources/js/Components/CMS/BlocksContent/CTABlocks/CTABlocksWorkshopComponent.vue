<script setup lang="ts">
import CTAThree from './CTAThree.vue';
import CTAOne from './CTAOne.vue';
import CTATwo from './CTATwo.vue';
import { computed, ref } from 'vue';
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'

const props = defineProps<{}>()

const CTATheme = ref(0)

const components = {
    0: CTAOne,
    1: CTATwo,
    2: CTAThree
}

const data = {
    title : '<p style=\"text-align: center\"><strong><span style=\"font-size: 44px; color: #ffffff\">We’re here to help</span></strong></p>',
    subtitle :'Award winning support',
    description :'<p style="text-align: center"><span style="font-size: 20px; color: #d6d6d6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Et, egestas tempus tellus etiam sed. Quam a scelerisque amet ullamcorper eu enim et fermentum, augue. Aliquet amet volutpat quisque ut interdum tincidunt duis.</span></p>'
}


const component = computed(()=>{
    return components[CTATheme.value]
})

</script>

<template>
    <div class="w-ful">
        <ButtonTheme v-model="CTATheme" :label="['Simple', 'wide', 'image']"/>
        <component :is="component" :data="data"/>
    </div>
    <div @click="()=>console.log(data)">sdfff</div>
</template>

<style scoped></style>