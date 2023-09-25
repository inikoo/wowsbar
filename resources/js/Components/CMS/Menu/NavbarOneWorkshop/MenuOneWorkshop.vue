<script setup lang="ts">
import { ref } from "vue"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import draggable from "vuedraggable"
import HyperLink from '@/Components/CMS/Fields/Hyperlink.vue'
import SubMenu from "../SubMenu.vue"
import { get } from 'lodash'
import IconPicker from "@/Components/CMS/Fields/IconPicker/IconPicker.vue";
import { faUser, faHeart, faShoppingCart, faSignOut } from '../../../../../private/pro-solid-svg-icons';
import { library } from "@fortawesome/fontawesome-svg-core";
import Popover from "@/Components/Utils/Popover.vue";
library.add(faUser, faHeart, faShoppingCart, faSignOut)

const props = defineProps({
	navigation: {
		type: Object,
		required: true,
	},
	tool: {
		type: Object,
		required: true,
	},
	selectedNav: {
		type: Object,
		required: false,
	},
	changeNavActive: {
		type: Function,
		required: true,
	},
});

const openNav = ref(null)
const mobileMenuOpen = ref(false)

</script>

<template>
	<div class="bg-white">
		<!-- Mobile menu -->
		<!-- <TransitionRoot as="template" :show="mobileMenuOpen">
			<Dialog as="div" class="relative z-40 lg:hidden" @close="mobileMenuOpen = false">
				<TransitionChild
					as="template"
					enter="transition-opacity ease-linear duration-300"
					enter-from="opacity-0"
					enter-to="opacity-100"
					leave="transition-opacity ease-linear duration-300"
					leave-from="opacity-100"
					leave-to="opacity-0">
					<div class="fixed inset-0 bg-black bg-opacity-25" />
				</TransitionChild>

				<div class="fixed inset-0 z-40 flex">
					<TransitionChild
						as="template"
						enter="transition ease-in-out duration-300 transform"
						enter-from="-translate-x-full"
						enter-to="translate-x-0"
						leave="transition ease-in-out duration-300 transform"
						leave-from="translate-x-0"
						leave-to="-translate-x-full">
						<DialogPanel
							class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
							<div class="flex px-4 pb-2 pt-5">
								<button
									type="button"
									class="-m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400"
									@click="mobileMenuOpen = false">
									<span class="sr-only">Close menu</span>
									saasd
								</button>
							</div>
							-->

		<!-- Links -->
		<!-- <TabGroup as="div" class="mt-2">
								<div class="border-b border-gray">
									<TabList
										class="-mb-px flex space-x-8 px-4 w-304 overflow-x-auto">
										<Tab
											as="template"
											v-for="category in navigation.categories"
											:key="category.name"
											v-slot="{ selected }">
											<button
												:class="[
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
									<TabPanel
										v-for="category in navigation.categories"
										:key="category.name"
										class="space-y-12 px-4 py-6">
										<div class="grid grid-cols-1 gap-x-4">
											<div
												v-for="item in category.featured"
												:key="item.name"
												class="group relative">
												<a
													:href="item.href"
													class="mt-6 block text-sm font-medium text-gray-900">
													<span
														class="absolute inset-0 z-10"
														aria-hidden="true" />
													{{ item.name }}
												</a>
											</div>
										</div>
									</TabPanel>
								</TabPanels>
							</TabGroup>

							<div class="space-y-6 border-t border-gray-200 px-4 py-6">
								<div
									v-for="page in navigation.pages"
									:key="page.name"
									class="flow-root">
									<a
										:href="page.href"
										class="-m-2 block p-2 font-medium text-gray-900"
										>{{ page.name }}</a
									>
								</div>
							</div>
						</DialogPanel>
					</TransitionChild>
				</div>
			</Dialog>
		</TransitionRoot>  -->
		<!-- Mobile menu -->

		<!-- Desktop -->
		<header class="relative z-10">
			<nav aria-label="Top">
				<div class="bg-gray-100">
					<draggable :list="navigation.items" group="topMenu" itemKey="id" :disabled="tool.name !== 'grab'"
						class="flex h-full justify-center space-x-8 align-middle">
						<template v-slot:item="{ element: category, index }">
							<div :class="[get(selectedNav, 'id') == category.id ? 'outline outline-gray-400' : '',  tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab']">
								<div v-if="category.type === 'group'" class="p-2.5" @click="() => { openNav = category.id, changeNavActive(index) }">
									<div :key="category.id" class="flex">
										<div class="relative flex p-2.5">
											<div :class="[openNav == category.id ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-700 hover:text-gray-800', 'relative z-10 -mb-px flex items-center border-b-2 pt-px text-sm font-medium transition-colors duration-200 ease-out']">
												<div class="flex gap-3">
													<IconPicker :key="category.id" :data="category" />
													<HyperLink :formList="{
														label: 'label',
													}" :useDelete="true" :data="category" label="label"
														@OnDelete="() => { navigation.categories.splice(index, 1) }"
														:cssClass="`items-center text-sm font-medium ${tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab'}`" />
												</div>
											</div>
										</div>

										<div v-if="openNav == category.id">
											<SubMenu :data="category" :saveSubMenu="saveSubMenu"
												@OnClose="() => { changeNavActive(null), openNav = null, console.log(openNav) }" :tool="tool" />
										</div>

									</div>
								</div>
								<div @click="(e) => { changeNavActive(index), openNav = null }"
									v-if="category.type === 'link'" class="py-5 px-2.5 text-black">

									<div class="flex gap-3">
										<IconPicker :key="category.id" :data="category" class="text-black" />
										<HyperLink :formList="{
											label: 'label',
										}" :useDelete="true" :data="category" label="label"
											@OnDelete="() => { navigation.categories.splice(index, 1) }"
											cssClass="items-center text-sm font-medium " />
									</div>
								</div>
							</div>
						</template>
					</draggable>
				</div>


			</nav>
		</header>
	</div>
</template>
