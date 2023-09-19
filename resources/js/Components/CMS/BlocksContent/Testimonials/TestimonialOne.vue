<script setup lang="ts">
import { ref } from 'vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUmbrellaBeach } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUmbrellaBeach)

const description = ref('<p><strong><span style="font-size: 24px; font-family: Arial">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo expedita voluptas culpa sapiente alias molestiae. Numquam corrupti in laborum sed rerum et corporis.</span></strong></p>')
const title = ref('<p><span style="font-size: 20px; font-family: Arial">Testimonial</span></p>')
const dataName = ref('<p><strong><span style="text: center">Jane Doe</span></strong></p>')
const dataPosition = ref('Ceo of Workcation')

const dataTestimonial = {
    title: "Testimonial",
    people: [
        {
            name: '<p><strong><span>Jane Doe</span></strong></p>',
            jobs: "Ceo of Company",
            review: '<p><strong><span style="font-size: 24px; font-family: Arial">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo expedita voluptas culpa sapiente alias molestiae. Numquam corrupti in laborum sed rerum et corporis.</span></strong></p>',
            image: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
        }
    ]
}

</script>

<template>
    <section class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20" />
        <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center" />

        <div class="mx-auto max-w-2xl lg:max-w-4xl flex flex-col">
            <!-- <img class="mx-auto h-12" src="https://tailwindui.com/img/logos/workcation-logo-indigo-600.svg" alt="" /> -->
            <div class="mx-auto text-indigo-500 flex items-center gap-x-2">
                <!-- <FontAwesomeIcon icon='far fa-umbrella-beach' class='text-4xl' aria-hidden='true' /> -->
                <Text v-model="title" :key="'title'" :placeholder="`Add your headline`" class="font-semibold text-4xl" />
            </div>
            <figure class="mt-10">
                <blockquote class="">
                    <Text v-model="description" :key="'description'" :placeholder="'Enter your headline title'" :class="``" />
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
                        <Text v-model="dataPosition" class="text-gray-600" />
                    </div>
                </figcaption>
            </figure>
        </div>
    </section>
</template>