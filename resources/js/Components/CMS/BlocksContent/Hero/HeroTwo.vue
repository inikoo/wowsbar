<script setup lang="ts">
import { ref } from 'vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    dataHero: any
}>()

</script>

<template>
	<div class="">
		<div class="relative isolate overflow-hidden">
			<div class=" absolute -z-50 h-full w-full object-cover before:absolute before:left-0 before:right-0 before:top-0 before:z-10 before:h-full before:w-full before:bg-gray-600 before:opacity-70">
				<img :src="dataHero.image" alt="Image" class="absolute inset-0 -z-10 h-full w-full object-cover " />
			</div>

			<div class="mx-auto max-w-2xl py-32">
				<div class="hidden sm:mb-8 sm:flex sm:justify-center">
					<div class="flex items-center relative rounded-full px-3 py-1 text-sm leading-6 text-gray-400 ring-1 ring-white/10 hover:ring-white/20">
						<Text v-model="dataHero.label1"/>
						<a href="#" class="font-semibold text-white">
							<span class="absolute inset-0" aria-hidden="true" />
								Read more
							<span aria-hidden="true">&rarr;</span>
						</a>
					</div>
				</div>
				<div class="text-center">
					<Text v-model="dataHero.title" :class="`tracking-tight`"/>
					<p class="mt-6">
						<Text v-model="dataHero.description" />
					</p>
					<div class="mt-10 flex items-center justify-center gap-x-6">
						<a href="#" class="rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">
							Get started
						</a>
						<a href="#" class="text-sm font-semibold leading-6 text-white">
							Learn more <span aria-hidden="true">→</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>