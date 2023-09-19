<script setup lang="ts">
import { ref, reactive } from 'vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUmbrellaBeach } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUmbrellaBeach)

const props = defineProps<{
    dataTestimonial: {
        title: string
        people: {
            name: string
            jobs: string
            review: string
            image: string
        }[]
    }
}>()

</script>

<template>
    <section class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-32 lg:px-8">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20" />
        <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-30deg] bg-white shadow-xl shadow-indigo-600/10 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center" />

        <div class="mx-auto max-w-2xl lg:max-w-4xl flex flex-col">
            <!-- <img class="mx-auto h-12" src="https://tailwindui.com/img/logos/workcation-logo-indigo-600.svg" alt="" /> -->
            <div class="mx-auto text-indigo-500 flex items-center gap-x-2">
                <!-- <FontAwesomeIcon icon='far fa-umbrella-beach' class='text-4xl' aria-hidden='true' /> -->
                <Text v-model="dataTestimonial.title" :key="'title'" :placeholder="`Add your headline`" />
            </div>
            <figure class="mt-10">
                <blockquote class="">
                    <Text v-model="dataTestimonial.people[0].review" :key="'review'" :placeholder="'Enter your headline title'" :class="``" />
                </blockquote>
                <figcaption class="mt-10">
                    <img class="mx-auto h-10 w-10 rounded-full"
                        :src="dataTestimonial.people[0].image"
                        alt="" />
                    <div class="mt-4 flex items-center justify-center space-x-3 text-base">
                        <Text v-model="dataTestimonial.people[0].name" class="" :placeholder="`Enter your name`" />
                        <svg viewBox="0 0 2 2" width="3" height="3" aria-hidden="true" class="fill-gray-900">
                            <circle cx="1" cy="1" r="1" />
                        </svg>
                        <Text v-model="dataTestimonial.people[0].jobs" class="text-gray-600" />
                    </div>
                </figcaption>
            </figure>
        </div>
    </section>
</template>