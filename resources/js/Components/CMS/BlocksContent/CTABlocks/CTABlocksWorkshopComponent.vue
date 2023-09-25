<script setup lang="ts">
import { computed, ref, reactive } from 'vue'
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

const dataCTA = reactive({
    title: '<p style="text-align: center"><strong><span style="font-size: 44px; color: #171717">Boost your career</span></strong></p>',
    subtitle: '<p style="text-align: center"><span style="font-size: 24px; color: rgb(74, 74, 74)">Elevate your career with a leading company. Uncover your full potential and embark on a rewarding journey with us.</span></p>',
    image: {
        link: 'https://images.unsplash.com/photo-1525130413817-d45c1d127c42?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1920&q=60',
        color: 'rgba(129, 140, 248, 1)'
    },
    button: {
        background: 'rgba(55, 65, 81, 1)',
        label: '<p><span style="color: #c0c0c0">Join now!</span></p>',
        full: false,
        link: 'https://google.com'
    },
})


const component = computed(()=>{
    return components[CTATheme.value]
})

</script>

<template>
    <div class="w-full space-y-4">
        <ButtonTheme v-model="CTATheme" :label="['Simple', '2 Grids', 'Elevating']"/>
        <component :is="component" :dataCTA="dataCTA" class="shadow-md"/>
    </div>
</template>