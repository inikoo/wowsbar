<script setup lang="ts">
import { ref, computed, reactive } from 'vue'
import { faUmbrellaBeach } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUmbrellaBeach)

import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import HeroOne from '@/Components/CMS/BlocksContent/Hero/HeroOne.vue'
import HeroTwo from '@/Components/CMS/BlocksContent/Hero/HeroTwo.vue'
import HeroThree from '@/Components/CMS/BlocksContent/Hero/HeroThree.vue'

const testimonialTheme = ref(0)

const dataHero = reactive({
    title: '<p style="text-align: center"><strong><span style="font-size: 20px; color: #6366f1; font-family: Arial">Deploy to the cloud with confidence</span></strong></p>',
    description: '<p><strong><span style="font-size: 36px; font-family: Arial">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat fugiat aliqua.</span></strong></p>',
    image: ''
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