<script setup lang="ts">
import { ref } from 'vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    dataHero: any
}>()

</script>
<template>
	<div class="">
		<div class="relative isolate overflow-hidden pt-5">
			<img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2830&q=80&blend=111827&sat=-100&exp=15&blend-mode=multiply"
				alt="" class="absolute inset-0 -z-10 h-full w-full object-cover" />
			<div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
				aria-hidden="true">
				<div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
					style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
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
			<div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
				aria-hidden="true">
				<div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
					style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
			</div>
		</div>
	</div>
</template>