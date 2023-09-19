<script setup lang="ts">
import { ref, computed } from 'vue'
import { faUmbrellaBeach } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUmbrellaBeach)

import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import TestimonialOne from '@/Components/CMS/BlocksContent/Testimonials/TestimonialOne.vue'
import TestimonialTwo from '@/Components/CMS/BlocksContent/Testimonials/TestimonialTwo.vue'
import TestimonialThree from '@/Components/CMS/BlocksContent/Testimonials/TestimonialThree.vue'

const testimonialTheme = ref(0)

const components = {
    0: TestimonialOne,
    1: TestimonialTwo,
    2: TestimonialThree
}

const component = computed(()=>{
    return components[testimonialTheme.value]
})

</script>

<template>
    <div class="w-full">
        <ButtonTheme v-model="testimonialTheme" :label="['Clean', 'Simple', 'Forest']"/>
        <component :is="component" />
    </div>
</template>