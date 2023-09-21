<script setup lang="ts">
import { computed, ref } from 'vue'
import CTAOne from '@/Components/CMS/BlocksContent/CTABlocks/CTAOne.vue'
import CTATwo from '@/Components/CMS/BlocksContent/CTABlocks/CTATwo.vue'
import CTAThree from '@/Components/CMS/BlocksContent/CTABlocks/CTAThree.vue'
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'


const CTATheme = ref(0)

const components = {
    0: CTAOne,
    1: CTATwo,
    2: CTAThree
}

const dataCTA = {
    title : '<p style="text-align: center"><strong><span style="font-size: 44px; color: #171717">Boost your career</span></strong></p>',
    subtitle :'<p style="text-align: center"><span style="font-size: 24px; color: rgb(74, 74, 74)">Elevate your career with a leading company. Uncover your full potential and embark on a rewarding journey with us.</span></p>',
    button :'Join now!'
}


const component = computed(()=>{
    return components[CTATheme.value]
})

</script>

<template>
    <div class="w-full">
        <ButtonTheme v-model="CTATheme" :label="['Simple', 'wide', 'image']"/>
        <component :is="component" :dataCTA="dataCTA"/>
    </div>
</template>

<style scoped></style>