<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
	dataBlogs: any
}>()


</script>

<template>
    <div class="relative isolate bg-gray-50">
        <div class="overflow-hidden absolute w-full h-full">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.100),white)] opacity-20" />
            <div class="absolute inset-y-0 right-1/2 -z-10 mr-16 w-[200%] origin-bottom-left skew-x-[-50deg] bg-white shadow-xl shadow-indigo-600/2s0 ring-1 ring-indigo-50 sm:mr-28 lg:mr-0 xl:mr-16 xl:origin-center" />
        </div>
        
        <div class="mx-auto max-w-7xl px-6 lg:px-8 pt-12 pb-20">
            <div class="mx-auto max-w-2xl text-center">
                <Text v-model="dataBlogs.title" :class="`tracking-tight`" />
                <Text v-model="dataBlogs.subtitle" :class="`leading-8`" />
            </div>
            
            <div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <!-- Card Looping -->
                <article v-for="(blog, index) in dataBlogs.blogsList" :key="blog.id"
                    :class="`z-${(50 - (index*10))}`"
                    class="relative isolate flex flex-col justify-end shadow-lg rounded-full bg-gray-100 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
                    
                    <!-- Background: Card -->
                    <img :src="blog.image" alt="" class="absolute rounded-2xl inset-0 -z-10 h-full w-full object-cover" />
                    <div class="absolute rounded-b-2xl inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40" />
                    <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10" />


                    <div class="flex flex-wrap items-center gap-y-1 text-sm leading-6 text-gray-300 ">
                        <!-- <time :datetime="blog.datetime" class="mr-8">{{ blog.date }}</time> -->
                        <div class=" flex items-center gap-x-4">
                            <!-- <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                                <circle cx="1" cy="1" r="1" />
                            </svg> -->
                            <div class="flex gap-x-2.5 items-center">
                                <img :src="blog.author.image" alt=""
                                    class="h-6 w-6 flex-none rounded-full bg-white/10" />
                                <!-- <a :href="blog.href" class="bg-gray-100/20 absolute inset-0 " /> -->
                                <Text v-model="blog.author.name" />
                            </div>
                        </div>
                    </div>
                    <h3 class="mt-3 text-lg font-semibold text-white">
                        <Text v-model="blog.title" class="leading-6"/>
                    </h3>
                    
                </article>
            </div>
        </div>
    </div>
</template>