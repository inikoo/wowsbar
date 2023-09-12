<script setup lang="ts">
import { Link } from "@inertiajs/vue3"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import declareFonts from '@/Components/CMS/Fields/IconPicker/Components/fonts.js'

declareFonts // to inheritance the font declaration 

const props = defineProps<{
	data: {
        column: {
            data: {
                name: string
                link: string
				icon: string
				value: string
            }[]
			type: string
			title: string
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

console.log(props.data.column)
</script>

<template>
	<footer class="bg-gray-50 px-6 text-gray-500" aria-labelledby="footer-heading">
		<h2 id="footer-heading" class="sr-only">Footer</h2>

		<div class="mx-auto max-w-7xl px-6 pb-8 pt-20 sm:pt-24 lg:px-8 lg:pt-12">
			<div class="xl:grid xl:gap-x-24 xl:px-6">
				<!-- Navigations -->
				<div class="flex gap-8 xl:col-span-2">
					<div v-for="column in data.column" class="space-y-3 min-w-[20%]" >
						<h3 class="font-bold text-gray-700 capitalize">{{ column.title }}</h3>
						
						<!-- If the data type is List -->
						<ul v-if="column.type == 'list'" role="list">
							<li :key="index" v-for="(listData, index) in column.data" class="py-1.5">
								<Link :href="listData.link" class="space-y-3 hover:text-gray-700 pr-2 py-2">{{ listData.name }}</Link>
							</li>
						</ul>

						<!-- If the data type is Description -->
						<div v-if="column.type == 'description'">
							<p>{{ column.data }}</p>
						</div>

						<!-- If the data type is Info -->
						<div v-if="column.type == 'info'">
							<div class="flex flex-col gap-y-2">
								<div v-for="dataInfo in column.data" class="grid grid-cols-[auto,1fr] gap-4 items-center justify-start gap-y-3 mb-2.5">
									<div class="w-5 flex items-center justify-center text-gray-400">
										<FontAwesomeIcon :icon='dataInfo.icon' class='' aria-hidden='true' />
									</div>
									<p class="leading-5 whitespace-nowrap">{{ dataInfo.value }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Social Media -->
			<div class="border-t border-gray-900/10 pt-8 sm:mt-10 flex flex-col md:flex-row items-center justify-between mt-16 lg:mt-18 xl:px-3">
				<div class="md:order-2">
					<div class="text-gray-400 flex space-x-6">
						<Link :href="dataSocial.link" v-for="dataSocial in data.social" class="hover:text-gray-500 p-1">
							<FontAwesomeIcon :icon='dataSocial.icon' class='h-6 w-6' aria-hidden='true' />
							<span class="sr-only">{{ dataSocial.label }}</span>
						</Link>
					</div>
				</div>

				<!-- Footer: Copyright -->
				<div class="flex">
					<div class="mt-4 text-xs flex leading-6 text-gray-500 md:order-1 md:mt-0"> 
						&copy; 2023&nbsp;<Link :href="data.copyRight.link" class="font-semibold">{{ data.copyRight.label }}</Link>. All rights reserved.
					</div>
				</div>
			</div>
		</div>
	</footer>
</template>
