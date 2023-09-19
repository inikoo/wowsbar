<script setup lang="ts">
import { ref, computed, reactive } from 'vue'
import { faUmbrellaBeach } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUmbrellaBeach)

import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import TestimonialOne from '@/Components/CMS/BlocksContent/Testimonials/TestimonialOne.vue'
import TestimonialTwo from '@/Components/CMS/BlocksContent/Testimonials/TestimonialTwo.vue'
import TestimonialThree from '@/Components/CMS/BlocksContent/Testimonials/TestimonialThree.vue'

const testimonialTheme = ref(0)

const dataTestimonial = reactive({
    title: '<p style="text-align: center"><strong><span style="font-size: 20px; color: #6366f1; font-family: Arial">Testimonial</span></strong></p>',
    description: '<p><strong><span style="font-size: 36px; font-family: Arial">We have worked with thousands of amazing people</span></strong></p>',
    people: [
        {
            name: '<p><strong><span>Jane Doe</span></strong></p>',
            jobs: "Ceo of Company A",
            review: '<p><strong><span style="font-size: 24px; font-family: Arial">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo expedita voluptas culpa sapiente alias molestiae. Numquam corrupti in laborum sed rerum et corporis.</span></strong></p>',
            image: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
        },
        {
            name: '<p><strong><span>Jane Smith</span></strong></p>',
            jobs: "Ceo of Company B",
            review: '<p><strong><span style="font-size: 24px; font-family: Arial">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo expedita voluptas culpa sapiente alias molestiae. Numquam corrupti in laborum sed rerum et corporis.</span></strong></p>',
            image: "https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=80"
        },
        {
            name: '<p><strong><span>John Doe</span></strong></p>',
            jobs: "Ceo of Company C",
            review: '<p><strong><span style="font-size: 24px; font-family: Arial">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo expedita voluptas culpa sapiente alias molestiae. Numquam corrupti in laborum sed rerum et corporis.</span></strong></p>',
            image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=80"
        },
    ]
})

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
        <component :is="component" :dataTestimonial="dataTestimonial"/>
    </div>
</template>