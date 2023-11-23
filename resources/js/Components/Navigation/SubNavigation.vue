<script setup lang='ts'>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { capitalize } from "@/Composables/capitalize"
import { routeType } from '@/types/route'
import MetaLabel from "@/Components/Headings/MetaLabel.vue";
import { Link } from "@inertiajs/vue3"

const props = defineProps<{
    dataNavigation: {
        leftIcon: {
            icon: string | string[]
            tooltip: string
        }
        href: routeType
        label: string
        number: string
    }[]
}>()

const originUrl = location.origin
</script>

<template>
    <div class="flex flex-col sm:flex-row">
        <div class="flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:gap-x-3 text-gray-500 text-xs">
            <div v-for="item in dataNavigation" class="flex items-center -ml-1.5">
                <Link v-if="item.href" :href="`${route(item.href.name, item.href.parameters)}`" 
                    class="rounded-sm py-1 px-1.5"
                    :class="[
                        $page.url.startsWith((route(item.href.name, item.href.parameters)).replace(new RegExp(originUrl, 'g'), '')) ? 'bg-yellow-400 ring-1 ring-inset ring-yellow-500 text-org-600 font-medium' : 'text-gray-400 hover:bg-gray-100 hover:ring-1 hover:ring-inset hover:ring-yellow-400 hover:text-gray-600'
                    ]"
                >
                    <FontAwesomeIcon v-if="item.leftIcon" :icon="item.leftIcon.icon" :title="capitalize(item.leftIcon.tooltip)" aria-hidden="true" class="pr-2" />
                    <MetaLabel :item=item />
                </Link>
                <span v-else>
                    <FontAwesomeIcon v-if="item.leftIcon" :icon="item.leftIcon.icon" :title="capitalize(item.leftIcon.tooltip)" aria-hidden="true" class="pr-2" />
                    <MetaLabel :item=item />
                </span>
            </div>
        </div>
    </div>
</template>