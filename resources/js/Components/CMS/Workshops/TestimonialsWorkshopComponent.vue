<script setup lang="ts">
import { ref } from 'vue'
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


// const components = {
//     'text': PrimitiveInput,
//     'radio': Radio,
//     'slideBackground': SlideBackground,
//     'corners': Corners,
//     'colorpicker': Colorpicker,
//     'select': Select,
//     'selectFont': SelectFont,
//     'textAlign': TextAlign
// }

const description = ref('hehehehe')
const title = ref('Workcation')
const dataName = ref('Marisca')
const dataPosition = ref('Marisca')

</script>

<template>
    <div class="w-full">
        <section class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:px-8">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20" />
            <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center" />
            
            <div class="mx-auto max-w-2xl lg:max-w-4xl flex flex-col">
                <!-- <img class="mx-auto h-12" src="https://tailwindui.com/img/logos/workcation-logo-indigo-600.svg" alt="" /> -->
                <div class="mx-auto text-indigo-500 flex gap-x-2">
                    <FontAwesomeIcon icon='far fa-umbrella-beach' class='text-4xl' aria-hidden='true' />
                    <Text v-model="title" :key="'title'" :placeholder="`Add your headline`" class="font-semibold text-4xl"/>
                </div>
                <figure class="mt-10">
                    <blockquote class="text-center text-xl leading-8 sm:text-2xl sm:leading-9">
                        <p>
                            <Text v-model="description" :key="'title'" :placeholder="'Enter your headline title'" />
                        </p>
                    </blockquote>
                    <figcaption class="mt-10">
                        <img class="mx-auto h-10 w-10 rounded-full"
                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="" />
                        <div class="mt-4 flex items-center justify-center space-x-3 text-base">
                            <Text v-model="dataName" class="" :placeholder="`Enter your name`" />
                            <svg viewBox="0 0 2 2" width="3" height="3" aria-hidden="true" class="fill-gray-900">
                                <circle cx="1" cy="1" r="1" />
                            </svg>
                            <Text v-model="dataPosition" class="text-gray-600">CEO of Workcation</Text>
                        </div>
                    </figcaption>
                </figure>
            </div>
        </section>
</div></template>

<style scoped></style>