<script setup lang="ts">
import { ref } from "vue"
import draggable from "vuedraggable"
import HyperLink from '@/Components/CMS/Fields/Hyperlink.vue'
import SubMenu from "../SubMenu.vue"
import { get } from 'lodash'
import IconPicker from "@/Components/CMS/Fields/IconPicker/IconPicker.vue";
import { faUser, faHeart, faShoppingCart, faSignOut } from '../../../../../private/pro-solid-svg-icons';
import { library } from "@fortawesome/fontawesome-svg-core";

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
	layout : Object
});

const openNav = ref(null)

</script>

<template>
	<div class="bg-white">	
		<!-- Desktop -->
		<header class="relative z-10">
			<nav aria-label="Top">
				<div :class="`bg-${layout.colorScheme}-500`">
					<draggable :list="navigation.items" group="topMenu" itemKey="id" :disabled="tool.name !== 'grab'"
						class="flex h-full justify-center space-x-8 align-middle">
						<template v-slot:item="{ element: category, index }">
							<div :class="[get(selectedNav, 'id') == category.id ? 'outline outline-gray-400' : '',  tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab']">
								<div v-if="category.type === 'group'" class="p-2.5" @click="() => { openNav = category.id, changeNavActive(index) }">
									<div :key="category.id" class="flex">
										<div class="relative flex p-2.5">
											<div :class="[openNav == category.id ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-900 hover:text-gray-900', 'relative z-10 -mb-px flex items-center border-b-2 pt-px text-sm font-medium transition-colors duration-200 ease-out']">
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
