<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 03 Mar 2023 13:49:56 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { Link } from "@inertiajs/vue3"
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

import { library } from "@fortawesome/fontawesome-svg-core"
import { faBriefcase,faPuzzlePiece} from "@/../private/pro-light-svg-icons"
import { useLayoutStore } from "@/Stores/layout.js"
import { computed } from "vue";

library.add(faBriefcase,faPuzzlePiece)

const layout = useLayoutStore()


const isHover = ref(false)

const currentIndexModule = computed(() => {
	return Object.keys(layout.navigation).indexOf(layout.currentModule)
})

onMounted(() => {
  window.addEventListener('keydown', handleKey)
})
onUnmounted(() => {
	window.removeEventListener('keydown', handleKey)
})

const handleKey = (event: any) => {
	// If Arrow Up key is pressed and the element is hovered and not the first index
	if (event.key === 'ArrowUp' && isHover.value && currentIndexModule.value != 0) {
		const prevTab = ref(layout.navigation[Object.keys(layout.navigation)[currentIndexModule.value-1]])
		router.get(route(prevTab.value.route, prevTab.value.routeParameters))
	}
	// If Arrow Down key is pressed and the element is hovered and not the last index
	else if (event.key === 'ArrowDown' && isHover.value && currentIndexModule.value != Object.keys(layout.navigation).length -1) {
		const nextTab = ref(layout.navigation[Object.keys(layout.navigation)[currentIndexModule.value+1]])
		router.get(route(nextTab.value.route, nextTab.value.routeParameters))
	}
}

</script>

<template>
	<div class="w-8/12 mt-11 fixed md:flex md:flex-col md:inset-y-0 md:w-10 lg:mt-10 xl:w-56
		bg-gray-50 dark:bg-gray-800 dark:text-gray-100"
		@mouseenter="isHover = true" @mouseleave="isHover = false"
	>
		<div class="flex flex-grow flex-col h-full overflow-y-auto custom-hide-scrollbar border-r border-gray-200 dark:border-gray-500 pb-4">

			<div class="flex flex-grow flex-col pb-16">
				<nav class="flex-1 space-y-1" aria-label="Sidebar">
					<!-- LeftSide Links -->
                    <Link v-for="(item, itemKey) in layout.navigation"
                        :key="itemKey"
                        :href="route(item.route)"
                        :class="[
							itemKey === layout.currentModule
								? 'tabNavigationActive dark:border-gray-100 dark:bg-gray-600 '
								: 'tabNavigation dark:hover:bg-dark-700',
							'group flex items-center border-l-4 text-sm font-medium px-0 xl:px-3 py-2',
						]"
                        :aria-current="itemKey === layout.currentModule ? 'page' : undefined"
                    >
                        <div>
                            <img v-if="item.name == 'dashboard'" src="@/../art/logo/png/logo-no-background.png" alt="" class="h-4 aspect-square"
								:class="[ itemKey === layout.currentModule
											? 'text-orange-500'
											: 'text-gray-400 group-hover:text-gray-600',
										'ml-2 mr-3 flex-shrink-0 h-4 w-4'
							]">
                            <FontAwesomeIcon
                                v-else
                                aria-hidden="true"
                                class="text-gray-400 dark:text-gray-200 ml-2 mr-3 flex-shrink-0 h-4 w-4"
                                :icon="item.icon" />
                        </div>
                        <span class="md:hidden xl:block capitalize">{{ item.label }}</span>
                    </Link>
				</nav>
			</div>
		</div>
	</div>
</template>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.custom-hide-scrollbar::-webkit-scrollbar {
	display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.custom-hide-scrollbar {
	-ms-overflow-style: none;  /* IE and Edge */
	scrollbar-width: none;  /* Firefox */
}</style>
