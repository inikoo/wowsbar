<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { ref } from 'vue'
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
} from '@headlessui/vue'
import { MinusIcon, PlusIcon } from '@heroicons/vue/24/outline'
import Image from './Image.vue'
import Text from './Text.vue'
import Accordeon from './Accordeon.vue'
import Banner from './Banner.vue'
import Collection from './Collection.vue'
import Pricing from './Pricing.vue'
import Footer from './Footer.vue'
const props = defineProps<{

  data: Object

}>()

const getComponent = (componentName: string) => {
    const components = {
        'text': Text,
        'accordion': Accordeon,
        'banner': Banner,
        'collection' : Collection,
        'pricing': Pricing,
        'image':Image,
        'footer': Footer
    };
    return components[componentName]
};
</script>
  
<template>
  <div :class="`grid grid-cols-${data.dataRow.length} items-start lg:gap-x-8`">
    <div v-for="(item, index) of data.dataRow" :key="index">
      <component :is="getComponent(item['type'])" :data="item"></component>
    </div>
  </div>
</template>
  
  
  