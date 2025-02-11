<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 14 Sept 2022 18:26:10 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->
<script setup>
import { Link } from '@inertiajs/vue3'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEmptySet, faStar, faWrench, faWarehouse, faStore, faCashRegister, faMoneyCheckAlt, faTasks } from '@fal'
import { useLocaleStore } from '@/Stores/locale.js'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { capitalize } from "@/Composables/capitalize"
import { useLayoutStore } from '@/Stores/layout';
library.add(faEmptySet, faStar, faWrench, faWarehouse, faStore, faCashRegister, faMoneyCheckAlt, faTasks)

const props = defineProps(['nodes'])

const locale = useLocaleStore()
</script>

<template>
    <!-- {{ nodes[0] }} -->
    <nav aria-label="Progress" class="py-1 md:py-0">
        <ol v-if="nodes" role="list" class="divide-y divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0">
            <li v-for="(node, nodeIdx) in nodes" :key="node.name" class="relative flex flex-1 items-center">
                <!-- Main Tree -->
                <Link :href="route(node.href['name'], node.href['parameters'])" class="group flex-1 items-center">
                    <div class="flex items-center px-4 text-lg xl:px-6 py-4 font-medium gap-x-4">
                        <FontAwesomeIcon size="lg" :icon="node.icon" class="flex-shrink-0 text-gray-400" aria-hidden="true" />
                        <p class="md:leading-none md:text-sm lg:text-base inline capitalize font-medium text-gray-500 group-hover:text-gray-600">
                            <span class="hidden lg:inline">{{ node.name }}</span>
                            <span class="inline lg:hidden">{{ node.shortName ? node.shortName : node.name }}</span>
                        </p>

                        <!-- Bars and count -->
                        <span v-if="node.index" class="font-medium whitespace-nowrap text-gray-400">
                            <FontAwesomeIcon icon="fal fa-bars" class="mr-1" />
                            <span v-if="node.index.number">{{ locale.number(node.index.number) }}</span>
                            <FontAwesomeIcon v-else icon="fal fa-empty-set" />
                        </span>
                    </div>
                </Link>

                <!-- Sublink on right each section (Marketplace) -->
                <div v-if="node.rightSubLink" class="pr-4 " :title="capitalize(node.rightSubLink.tooltip)">
                    <!-- {{ importIcon(node.rightSubLink.icon) }} -->
                    <Link :href="route(
                        node.rightSubLink.href['name'],
                        node.rightSubLink.href['parameters'])"
                        class="w-9 h-9 flex flex-0 justify-center items-center border-2 text-gray-500 rounded-md cursor-pointer hover:text-white"
                        :class="useLayoutStore().appName === 'org' ? 'hover:bg-org-500 border-org-500' : 'hover:bg-gray-500 border-gray-500'"
                    >
                        <FontAwesomeIcon :icon="node.rightSubLink.icon" class="flex-shrink-0 " aria-hidden="true" />
                    </Link>
                </div>

                <template v-if="nodeIdx !== nodes.length - 1">
                    <!-- Arrow separator for lg screens and up -->
                    <div class="hidden h-full w-5 md:block" aria-hidden="true">
                        <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
                            <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </template>
            </li>
        </ol>
    </nav>
</template>
