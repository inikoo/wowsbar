<script setup lang="ts">
import PricingOne from './PricingOne.vue';
import PricingTwo from './PricingTwo.vue';
import PricingThree from './PricingThree.vue';
import { computed, ref } from 'vue';
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'

const props = defineProps<{
    theme: number,
}>()

const tiers = [
  {
    name: 'Freelancer',
    id: 'tier-freelancer',
    href: '#',
    priceMonthly: '$24',
    description: 'The essentials to provide your best work for clients.',
    features: ['5 products', 'Up to 1,000 subscribers', 'Basic analytics', '48-hour support response time'],
    mostPopular: false,
  },
  {
    name: 'Startup',
    id: 'tier-startup',
    href: '#',
    priceMonthly: '$32',
    description: 'A plan that scales with your rapidly growing business.',
    features: [
      '25 products',
      'Up to 10,000 subscribers',
      'Advanced analytics',
      '24-hour support response time',
      'Marketing automations',
    ],
    mostPopular: true,
  },
  {
    name: 'Enterprise',
    id: 'tier-enterprise',
    href: '#',
    priceMonthly: '$48',
    description: 'Dedicated support and infrastructure for your company.',
    features: [
      'Unlimited products',
      'Unlimited subscribers',
      'Advanced analytics',
      '1-hour, dedicated support response time',
      'Marketing automations',
    ],
    mostPopular: false,
  },
]

const pagHead = {
  title : "<p style=\"text-align: center\"><strong><span style=\"font-size: 36px; color: #979191\">Pricing plans for teams of all sizes</span></strong></p>",
  subtitle :  "<p><strong><span style=\"color: #9f32d2\">Pricing</span></strong></p>",
  description :"<p style=\"text-align: center\"><span style=\"font-size: 16px; color: #877d7d\">Distinctio et nulla eum soluta et neque labore quibusdam. Saepe et quasi iusto modi velit ut non voluptas in. Explicabo id ut laborum.</span></p>"
}

const pricingTheme = ref(0)

const components = {
    0: PricingOne,
    1: PricingTwo,
    2: PricingThree
}


const component = computed(()=>{
    return components[pricingTheme.value]
})

</script>

<template>
  <div>
    <ButtonTheme v-model="pricingTheme" :label="['Simple', 'wide', 'image']"/>
      <component :is="component" :data="tiers" :pageHead="pagHead"/>
    
  </div>
  <div @click="()=>console.log('sss',pagHead)">dfdfdf</div>
</template>

<style scoped></style>