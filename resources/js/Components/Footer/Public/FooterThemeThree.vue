<script setup lang="ts">
import declareFonts from '@/Components/CMS/Fields/IconPicker/Components/fonts.js'
import { Link } from "@inertiajs/vue3"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

declareFonts // to inheritance the font declaration 

const props = defineProps<{
	data: {
        column: {
            data: {
                name: string
                link: string
            }[]
        }[]
        social: {
            label: string
            icon: string
            link: string
        }[]
        copyRight: {
            link: string
            label: string
        }
    }
}>()

console.log(props.data)
</script>

<template>
    <footer class="bg-slate-800">
        <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
            <!-- Navigations -->
            <nav class="grid grid-cols-2 sm:flex sm:justify-center sm:space-x-5" aria-label="Footer">
                <Link v-for="column in data.column[0].data" :href="column.link" class="font-semibold text-gray-400 hover:text-gray-500">
                    <span class="px-4 sm:px-2 text-sm leading-6 text-gray-300 hover:text-gray-100">{{ column.name }}</span>
                </Link>
            </nav>

            <!-- Social Media -->
            <div class="mt-10 flex justify-center space-x-10">
                <Link v-for="social in data.social" :href="social.link" class="py-1 px-2 text-gray-400 hover:text-gray-300 flex">
                    <span class="sr-only">{{ social.label }}</span>
                    <FontAwesomeIcon :icon='social.icon' class='h-6 w-6' aria-hidden='true' />
                </Link>
            </div>

            <!-- Footer: Copyright -->
            <div class="mt-10 flex justify-center text-center text-xs leading-5 text-gray-400">
                &copy; 2023 &nbsp;<Link :href="data.copyRight.link" class="font-semibold hover:text-gray-300">{{ data.copyRight.label }}</Link>. All rights reserved.
            </div>
        </div>
    </footer>
</template>
