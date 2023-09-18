<script setup lang="ts">
import draggable from "vuedraggable"
import Input from "../Fields/Input.vue"
import TextArea from "../Fields/TextArea.vue"
import HyperLink from "../Fields/Hyperlink.vue"
import IconPicker from "../Fields/IconPicker/IconPicker.vue"
import SocialMediaPicker from "@/Components/CMS/Fields/IconPicker/SocialMediaTools.vue"
import { get } from 'lodash'
const props = defineProps<{
	selectedColums: Function
	columSelected: {
		type: Object,
		required: false,
	}
	tool: Object
	data: Object
}>()


</script>

<template>
	<div class="w-full text-gray-400">
		<footer class="bg-gray-900" aria-labelledby="footer-heading">
			<h2 id="footer-heading" class="sr-only">Footer</h2>
			<div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
				<div class="xl:grid xl:grid-cols-3 xl:gap-20 items-start">

					<!-- Box -->
					<div class="grid justify-center space-y-5 rounded-xl bg-gray-950 border border-indigo-500 py-4 mb-8 xl:mb-0">
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

						<div class="flex justify-center space-x-6">
							<draggable :list="data.social" group="socialMedia" itemKey="id" :class="[
								tool.name === 'grab' ? 'cursor-grab' : 'cursor-pointer',
								'text-gray-400 hover:text-gray-500 flex space-x-6',
							]" :disabled="tool.name !== 'grab'">
								<template #item="{ element: child, index: childIndex }">
									<div>
										<span class="sr-only">{{ child.label }}</span>
										<SocialMediaPicker :data="child" />
									</div>
								</template>
							</draggable>
						</div>
					</div>




					<div class="col-span-2">
						<div class="flex gap-x-10 justify-between">
							<draggable :list="data.column" group="navigation" itemKey="id" :disabled="tool.name !== 'grab'"
								:class="[
									'flex',
									'gap-8',
									'xl:col-span-2',
									tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab',
								]">
								<template #item="{ element, index }">
									<div :class="['space-y-3 min-w-[20%]',
										get(columSelected,'id') !== element.id ? '' : 'border',
										]" @click="props.selectedColums(index)">
										<!-- <h3 class="text-sm font-bold leading-6 text-gray-700 capitalize">{{ element.title }}</h3> -->
										<Input :data="element" keyValue="title" classCss="font-bold text-gray-300 capitalize" />

										<!-- If the data type is List -->
										<div v-if="element.type == 'list'">
											<draggable :list="element.data" group="list" itemKey="name"
												:disabled="tool.name !== 'grab'">
												<template #item="{ element: child, index: childIndex }">
													<ul role="list">
														<li :key="child.name" class="py-1.5" >
															<HyperLink :formList="{
																name: 'name',
																link: 'link',
															}" :useDelete="true" :data="child" label="name" cssClass="hover:text-gray-200 pr-2 py-3"
																@onDelete="() => element.data.splice(childIndex, 1)"/>
														</li>
													</ul>
												</template>
											</draggable>
										</div>

										<!-- If the data type is Description -->
										<div v-if="element.type == 'description'">
											<TextArea :data="element" dataPath="data" />
										</div>

										<!-- If the data type is Info -->
										<div v-if="element.type == 'info'">
											<draggable :list="element.data" group="info"
												itemKey="name" :disabled="tool.name !== 'grab'" class="flex flex-col gap-y-2">
												<template #item="{ element: child, index: childIndex }">
													<div class="grid grid-cols-[auto,1fr] gap-4 items-center justify-start ">
														<div class="w-5 flex items-center justify-center text-gray-400">
															<IconPicker :key="child.title" :data="child" />
														</div>
														<Input :data="child" keyValue="value" class="leading-5"/>
													</div>
												</template>
											</draggable>
										</div>
									</div>
								</template>
							</draggable>
						</div>
					</div>
				</div>
				<div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24 text-center">
					<div class="text-xs  flex justify-center leading-5 text-gray-400">&copy; 2023&nbsp;<span class="font-semibold">
							<HyperLink :useDelete="false" :data="data.copyRight" label="label" :formList="{
								label: 'label',
								link: 'link',
							}" />
						</span>, Inc. All rights reserved.</div>
				</div>
			</div>
		</footer>
	</div>
</template>