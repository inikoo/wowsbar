<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    dataBlogs: any
}>()

</script>


<template>
    <div class="bg-white py-14">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <Text v-model="dataBlogs.title" :key="dataBlogs.title" :placeholder="'Enter your headline value'"
                    :class="``" />
                <Text v-model="dataBlogs.subtitle" :key="dataBlogs.subtitle"
                    :placeholder="'Enter your headline value'" :class="``" />
            </div>

            <div class="mx-auto mt-16 grid grid-cols-3 gap-x-4 gap-y-20 lg:mx-0">
                <article v-for="blog in dataBlogs.blogsList" :key="blog.id"
                    class="flex flex-col items-start h-fit ring-2 ring-gray-400 shadow rounded-lg pb-8"
                >
                    <!-- Blog: Image -->
                    <div class="relative w-full rounded-t-lg overflow-hidden">
                        <img :src="blog.image" alt=""
                            class="aspect-[4/3] w-full  shadow bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2]" />
                        <!-- <div class="absolute inset-0 ring-1 ring-inset ring-gray-900/10" /> -->
                    </div>

                    <!-- Blog: Text -->
                    <div class="w-full px-2">
                        <!-- Name - Role -->
                        <div class="mt-8 flex items-center gap-x-2 text-xs">
                            <Text v-model="blog.author.name" :key="blog.author.name" :placeholder="'Enter the name'" />
                            <Text v-model="blog.author.role" :key="blog.author.role" :placeholder="'Enter the role'" />
                        </div>

                        <!-- Title - Description -->
                        <div class="w-max-full">
                            <Text v-model="blog.title" :key="blog.title" :placeholder="'Enter the headline value'" />
                            <Text v-model="blog.description" :key="blog.description" :placeholder="'Enter the description'" />
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>