<!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
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
	columSelected: Object
	tool: Object
	data: Object
}>()

</script>

<template>
	<footer class="bg-gray-50 px-6" aria-labelledby="footer-heading">
		<h2 id="footer-heading" class="sr-only">Footer</h2>

		<div class="mx-auto max-w-7xl px-6 pb-8 pt-20 sm:pt-24 lg:px-8 lg:pt-12">
			<div class="xl:grid xl:gap-x-24 xl:px-6">
				<!-- Navigations -->
				<draggable :list="data.column" group="navigation" itemKey="id" :disabled="tool.name !== 'grab'" :class="[
					'flex',
					'gap-8',
					'xl:col-span-2',
					tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab',
				]">
					<template #item="{ element, index }">
						<div :class="[
							'space-y-3',
							'w-1/4',
							get(columSelected,'id') !== element.id ? '' : 'border',
						]" @click="props.selectedColums(element)">
							<Input :data="element" keyValue="title" />
							<div v-if="element.type == 'list'">
								<draggable :list="element.data" group="list" itemKey="name"
									:disabled="tool.name !== 'grab'">
									<template #item="{ element: child, index: childIndex }">
										<ul role="list">
											<li :key="child.name">
												<HyperLink :formList="{
													name: 'name',
													link: 'link',
												}" :useDelete="true" :data="child" label="name"
													cssClass="space-y-3 text-sm leading-6 text-gray-600 hover:text-indigo-500"
													@onDelete="() => element.data.splice(childIndex, 1)" />
											</li>
										</ul>
									</template>
								</draggable>
							</div>

							<div v-if="element.type == 'description'">
								<TextArea :data="element" dataPath="data" />
							</div>

							<div v-if="element.type == 'info'">
								<div class="flex flex-col gap-y-5">
									<draggable :list="element.data" group="info" itemKey="name"
										:disabled="tool.name !== 'grab'">
										<template #item="{ element: child, index: childIndex }">
											<div
												class="grid grid-cols-[auto,1fr] gap-4 items-center justify-start gap-y-3 mb-2.5">
												<div class="w-5 flex items-center justify-center text-gray-400">

													<IconPicker :key="child.title" :data="child" />
												</div>
												<Input :data="child" keyValue="value" />
											</div>
										</template>
									</draggable>
								</div>
							</div>
						</div>
					</template>
				</draggable>
			</div>

			<!-- Social Media -->
			<div
				class="border-t border-gray-900/10 pt-8 sm:mt-10 flex flex-col md:flex-row items-center justify-between mt-16 lg:mt-18 xl:px-3">
				<div class="md:order-2">
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

				<div class="flex">
					<div class="mt-4 text-xs flex gap-1 leading-6 text-gray-500 md:order-1 md:mt-0"> &copy; 2023
						<span class="font-bold">
							<HyperLink :useDelete="false" :data="data.copyRight" label="label" 
							:formList="{
								label: 'label',
								link: 'link',
							}" />
						</span>, Inc. All rights reserved.
					</div>
				</div>
			</div>
		</div>
	</footer>
</template>
