<script setup lang="ts">
import { ref, computed, Ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUmbrellaBeach } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUmbrellaBeach)
import PrimitiveInput from '@/Components/Forms/Fields/Primitive/PrimitiveInput.vue'
import Select from '@/Components/Forms/Fields/Primitive/PrimitiveSelect.vue'
import Radio from '@/Components/Forms/Fields/Primitive/PrimitiveRadio.vue'
import SlideBackground from "@/Components/Workshop/Fields/SlideBackground.vue"
import Corners from "@/Components/Workshop/Fields/Corners.vue"
import Colorpicker from '@/Components/Workshop/Fields/ColorPicker.vue'
import SelectFont from '@/Components/Workshop/Fields/SelectFont.vue'
import TextAlign from '@/Components/Workshop/Fields/TextAlign.vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'
import TestimonialOne from '@/Components/CMS/BlocksContent/Testimonials/TestimonialOne.vue'
import TestimonialTwo from '@/Components/CMS/BlocksContent/Testimonials/TestimonialTwo.vue'
import TestimonialThree from '@/Components/CMS/BlocksContent/Testimonials/TestimonialThree.vue'


const testimonialTheme: Ref<number> = ref(0)


// const props = defineProps<{
//     theme: number,
// }>()


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