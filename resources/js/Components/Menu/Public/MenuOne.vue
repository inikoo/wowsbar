<script setup lang="ts">
import { ref } from "vue"

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUser, faHeart, faShoppingCart, faSignOut } from '@/../private/pro-solid-svg-icons'
import { library } from "@fortawesome/fontawesome-svg-core"
import Popover from "@/Components/Utils/Popover.vue"
import { Link } from "@inertiajs/vue3"
import { Dialog, DialogPanel, Tab, TabGroup, TabList, TabPanel, TabPanels, TransitionChild, TransitionRoot } from "@headlessui/vue"

library.add(faUser, faHeart, faShoppingCart, faSignOut)

const props = defineProps<{
	data: any
}>()

const openNav = ref(null)
const mobileMenuOpen = ref(false)

</script>

<template>
	<div v-if="openNav" class="absolute w-screen h-screen bg-green-700 opacity-0" @click="openNav = null"></div>
	<div class="bg-white">
		<!-- Mobile menu -->
		<TransitionRoot as="template" :show="mobileMenuOpen">
			<Dialog as="div" class="relative z-40" @close="mobileMenuOpen = false">
				<TransitionChild as="template"
					enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100"
					leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0"
				>
					<div class="fixed inset-0 bg-black bg-opacity-25" />
				</TransitionChild>

				<div class="fixed inset-0 z-40 flex">
					<TransitionChild as="template"
						enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0"
						leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full"
					>
						<DialogPanel class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
							<div class="flex px-4 pb-2 pt-5">
								<button type="button"
									class="-m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400"
									@click="mobileMenuOpen = false">
									<span class="sr-only">Close menu</span>
								</button>
							</div>

							<!-- Links -->
							<TabGroup as="div" class="mt-2">
								<div class="border-b border-gray">
									<TabList class="-mb-px flex space-x-8 px-4 w-304 overflow-x-auto">
										<Tab as="template"
											v-for="category in data.navigation.categories"
											:key="category.name"
											v-slot="{ selected }">
											<button :class="[
														selected
															? 'border-indigo-600 text-indigo-600'
															: 'border-transparent text-gray-900',
														'flex-1 whitespace-nowrap border-b-2 px-1 py-4 text-base font-medium',
												]">
												{{ category.name }}
											</button>
										</Tab>
									</TabList>
								</div>
								<TabPanels as="template">
									<TabPanel v-for="category in data.navigation.categories"
										:key="category.name"
										class="space-y-12 px-4 py-6">
										<div class="grid grid-cols-1 gap-x-4">
											<div v-for="item in category.featured" :key="item.name" class="group relative">
												<a :href="item.href" class="mt-6 block text-sm font-medium text-gray-900">
													<span class="absolute inset-0 z-10" aria-hidden="true" />
													{{ item.name }}
												</a>
											</div>
										</div>
									</TabPanel>
								</TabPanels>
							</TabGroup>

							<div class="space-y-6 border-t border-gray-200 px-4 py-6">
								<div v-for="page in data.navigation.pages" :key="page.name" class="flow-root">
									<a :href="page.href" class="-m-2 block p-2 font-medium text-gray-900">
										{{ page.name }}
									</a>
								</div>
							</div>
						</DialogPanel>
					</TransitionChild>
				</div>
			</Dialog>
		</TransitionRoot>
		<!-- Mobile menu -->

		<!-- Desktop -->
		<header class="relative z-10">
			<nav aria-label="Top">
				<div class="bg-white">
					<div class="mx-auto flex h-10 px-4 sm:px-6 lg:px-8">
						<div class="w-full flex items-center space-x-6 justify-end">
							<div class="flex flex-1 items-center justify-end">
								<div class="flex items-center lg:ml-8">
									<div class="flex space-x-4">
										<!-- Popup: Icon User -->
										<Popover>
											<template #button>
												<div class="flex">
													<a href="#" class="flex justify-center p-1 text-gray-400">
														<span class="sr-only">Account</span>
														<FontAwesomeIcon :icon="['fas', 'user']" />
													</a>
												</div>
											</template>
											<template #content>
												<div class="py-[10px] px-[10px] rounded-lg bg-white text-gray-600">
													<div class="p-1 text-center">
														<div class="flex items-center justify-center mb-2">
															<FontAwesomeIcon :icon="['fas', 'user']" class="text-base mr-2" />
															<span class="text-lg font-semibold">Profile</span>
														</div>
													</div>
													<div class="p-1 text-center">
														<div class="flex items-center justify-center mb-2">
															<FontAwesomeIcon :icon="['fas', 'sign-out']" class="text-base mr-2" />
															<span class="text-lg font-semibold">logout</span>
														</div>
													</div>
												</div>
											</template>
										</Popover>

										<!-- Icon: Heart -->
										<div class="flex">
											<a href="#" class="-m-2 p-2 text-gray-400">
												<span class="sr-only">Account</span>
												<FontAwesomeIcon :icon="['fas', 'heart']" />
											</a>
										</div>
									</div>

									<span class="mx-4 h-6 w-px bg-gray-200 lg:mx-6" aria-hidden="true" />

									<!-- Icon: Cart -->
									<div class="flow-root">
										<a href="#" class="group flex items-center -m-2 p-2 text-gray-400 ">
											<FontAwesomeIcon :icon="['fas', 'shopping-cart']" />
											<span class="ml-2 text-sm font-medium text-gray-400 group-hover:text-gray-800">0</span>
											<span class="sr-only">items in cart, view bag</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Secondary navigation -->
				<div class="bg-gray-100">
					<div class="flex h-full justify-center space-x-8 align-middle">
						<div v-for="category in data.navigation.categories" class="">
							<!-- Type: Group -->
							<div v-if="category.type === 'group'" class="p-2.5 flex relative">
								<div class="relative flex p-2.5" >
									<div :class="[openNav == category.id ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700', 'relative z-10 -mb-px flex items-center cursor-pointer pt-px text-sm font-medium transition-colors duration-200 ease-out']">
										<div class="flex gap-3 items-center">
											<FontAwesomeIcon :icon='category.icon' class='' aria-hidden='true' />
											<div @click="openNav = category.id" >{{ category.name }}</div>
										</div>
									</div>
								</div>

								<!-- Popup: Group clicked -->
								<div v-if="openNav == category.id">
									<div class="absolute inset-x-0 min-w-fit top-full text-sm text-gray-500">
										<!-- <div class="absolute inset-0 top-1/2 bg-white shadow" aria-hidden="true"></div> -->
										<div class="relative bg-gray-100 border border-gray-200 rounded">
											<div class="mx-auto min-w-full max-w-7xl px-4 sm:px-6 lg:px-8">
												<a :href="feature.link" v-for="feature in category.featured" class="grid gap-x-8 gap-y-4">
													<div class="font-medium text-gray-500 p-2 hover:text-gray-700">
														<div class="flex gap-3 items-center">
															<FontAwesomeIcon :icon='feature.icon' class='' aria-hidden='true' />
															<span class="items-center text-sm font-medium whitespace-nowrap">{{ feature.name }}</span>
														</div>
													</div>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Type: Link -->
							<div v-if="category.type === 'link'" class="py-5 px-2.5">
								<div class="flex gap-3 items-center text-sm text-gray-500 hover:text-gray-700">
									<FontAwesomeIcon :icon='category.icon' class='' aria-hidden='true' />
									<Link :href="category.link" class="items-center text-sm font-medium">{{ category.name }}</Link>
								</div>
							</div>
						</div>
					</div>
				</div>


			</nav>
		</header>
	</div>
</template>
