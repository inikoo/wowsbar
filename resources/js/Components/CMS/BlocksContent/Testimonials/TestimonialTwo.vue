<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'

const props = defineProps<{
    dataTestimonial: {
        title: string
        description: string
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
    <div class="bg-gray-100 py-16 text-gray-600">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-xl text-center">
                <Text v-model="dataTestimonial.title" :key="'title'" :placeholder="`Add your headline`" />
                <Text v-model="dataTestimonial.description" :key="'title'" :placeholder="`Add your description`" />
            </div>
            <div class="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
                <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                    <div v-for="(person, index) in dataTestimonial.people" :key="index"
                        class="pt-8 sm:inline-block sm:w-full sm:px-4">
                        <figure class="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                            <blockquote class="">
                                <Text v-model="person.review" :key="'title'" :placeholder="`Add your headline`" />
                            </blockquote>
                            <figcaption class="mt-6 flex items-center gap-x-4">
                                <img class="h-10 w-10 rounded-full bg-gray-50" :src="person.image" alt="" />
                                <div>
                                    <Text v-model="person.name" :placeholder="`Enter your name`" />
                                    <Text v-model="person.jobs" />
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>