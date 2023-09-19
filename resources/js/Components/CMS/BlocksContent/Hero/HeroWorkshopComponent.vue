<script setup lang="ts">
import { ref, computed, reactive } from 'vue'
import {  } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add()

import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import HeroOne from '@/Components/CMS/BlocksContent/Hero/HeroOne.vue'
import HeroTwo from '@/Components/CMS/BlocksContent/Hero/HeroTwo.vue'
import HeroThree from '@/Components/CMS/BlocksContent/Hero/HeroThree.vue'

const testimonialTheme = ref(0)

const dataHero = reactive({
    title: '<p><strong><span style="font-size: 44px; color: rgb(99, 102, 241); font-family: Arial">Deploy to the cloud with confidence</span></strong></p>',
    description: '<p><span style="font-size: 20px; color: #484e63; font-family: Inter">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat fugiat aliqua.</span></p>',
    image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&facepad=2&w=900&h=900&q=80',
    label1: 'Just shipped v1.0'

})

const components = {
    0: HeroOne,
    1: HeroTwo,
    2: HeroThree
}

const component = computed(()=>{
    return components[testimonialTheme.value]
})

</script>

<template>
    <div class="space-y-10 w-full">
        <ButtonTheme v-model="testimonialTheme" :label="['Clean', 'Multi', 'Simple']"/>
        <div class="relative w-full shadow">
            <component :is="component" :dataHero="dataHero"/>
        </div>
    </div>
</template>