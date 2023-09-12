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
</script>

<template>
	<div class="w-full text-gray-400">
		<footer class="bg-gray-900" aria-labelledby="footer-heading">
			<h2 id="footer-heading" class="sr-only">Footer</h2>
			<div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
				<div class="xl:grid xl:grid-cols-3 xl:gap-20 items-start">

					<!-- Box -->
					<div class="grid justify-center space-y-5 rounded-xl bg-slate-800 border border-indigo-500 py-4 mb-8 xl:mb-0">
						<div class=" flex justify-center">
							<img class="h-24" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
								alt="Company name" />
						</div>
						<p class="px-3 text-center text-sm text-gray-300">
							Making the world a better place through constructing elegant hierarchies.
						</p>
						<div class="flex justify-center">
							<div class="text-gray-100 border-t border-gray-500 w-10/12" />
						</div>
						
						<!-- Box: Icon -->
						<div class="flex justify-center space-x-6">
							<Link :href="social.link" v-for="social in data.social" class="cursor-pointer text-gray-400 hover:text-gray-500" :alt="social.label">
								<span class="sr-only">{{ social.label }}</span>
								<!-- {{ social }} -->
								<FontAwesomeIcon :icon='social.icon' class='h-6 w-6' aria-hidden='true' />
							</Link>
						</div>
					</div>

					<div class="col-span-2">
						<div class="flex gap-x-10 justify-between">
							<!-- Looping: Column -->
							<div v-for="(column, index) in data.column" class="space-y-3 min-w-[20%]">
								<h3 class="font-bold text-gray-300 capitalize">{{ column.title }}</h3>
								
								<!-- If the data type is List -->
								<ul v-if="column.type == 'list'" role="list">
									<li :key="index" v-for="listData in column.data" class="py-1.5">
										<Link :href="listData.link" class="hover:text-gray-200 pr-2 py-2">{{ listData.name }}</Link>
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
											<p class="leading-5">{{ dataInfo.value }}</p>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<!-- Footer: Copyright -->
				<div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24 text-center">
					<div class="text-xs flex justify-center leading-5 text-gray-400">
						&copy; 2023 &nbsp;<Link :href="data.copyRight.link" class="font-semibold">{{ data.copyRight.label }}</Link>. All rights reserved.
					</div>
				</div>
			</div>
		</footer>
	</div>
</template>