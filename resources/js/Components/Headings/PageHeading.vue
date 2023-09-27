<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 07 Oct 2022 09:34:00 Central European Summer Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from "@inertiajs/vue3"

import { library } from "@fortawesome/fontawesome-svg-core"
import { faEdit, faWindowMaximize, faDraftingCompass, faEmptySet, faMoneyCheckAlt, faPeopleArrows, faSlidersH, faSave, faSuitcase, faBroadcastTower, faUpload } from "@/../private/pro-light-svg-icons"
import { faRocketLaunch, faPencil, faArrowLeft, faBorderAll, faTrashAlt, faDesktop } from "@/../private/pro-regular-svg-icons"
import { faPlus } from "@/../private/pro-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"

import Button from "@/Components/Elements/Buttons/Button.vue"
import { capitalize } from "@/Composables/capitalize"
// import { useLocaleStore } from "@/Stores/locale.js"
import { trans } from "laravel-vue-i18n"
import MetaLabel from "@/Components/Headings/MetaLabel.vue";

interface Icon {
    icon: string[] | string
}

library.add(faEdit, faWindowMaximize, faRocketLaunch, faDraftingCompass, faEmptySet, faMoneyCheckAlt, faPeopleArrows, faSlidersH, faPlus,
    faPencil, faArrowLeft, faBorderAll, faTrashAlt, faSave, faSuitcase,
    faBroadcastTower, faUpload, faDesktop
);

const props = defineProps<{
    data: {
        title: string
        icon: {
            icon: Icon
            tooltip: string
        }
        actionActualMethod?: string
        meta?: any
        actions?: any
        iconRight?: {
            title: string
            icon: string
            tooltip?: string
        }
        container: {
            tooltip: any
            icon: Icon
            label: string
        }
    },
    dataToSubmit?: any
    dataToSubmitIsDirty?: any
}>()

// const locale = useLocaleStore()

if (props.dataToSubmit && props.data.actionActualMethod) {
    props.dataToSubmit['_method'] = props.data.actionActualMethod
    // console.log(props.dataToSubmit)
}

</script>


<template>
    <div class="mx-4 py-4 md:pb-2 md:pt-3 lg:py-2 grid grid-flow-col justify-between items-center">
        <div class="">
            <h2 class="font-bold text-gray-700 dark:text-gray-300 text-2xl tracking-tight capitalize">
                <span v-if="data.container" class="text-black-500 font-medium mr-2">
                    <FontAwesomeIcon v-if="data.container.icon" :title="capitalize(data.container.tooltip)"
                        aria-hidden="true" :icon="data.container.icon" size="xs"/>
                    {{ trans(data.container.label) }}
                </span>

                <span class="text-gray-400">
                <FontAwesomeIcon v-if="data.icon" :title="capitalize(data.icon.tooltip ?? '')" aria-hidden="true"
                    :icon="data.icon.icon" size="sm" class="pr-2"/>
                <FontAwesomeIcon v-if="data.iconBis" :title="capitalize(data.iconBis.tooltip ?? '')" aria-hidden="true"
                    :icon="data.iconBis.icon" size="sm" class="pr-2" :class="data.iconBis.class"/>
                </span>

                <span>{{ trans(data.title) }}</span>
                <FontAwesomeIcon v-if="data.iconRight" :title="capitalize(data.iconRight.tooltip ?? '')" aria-hidden="true"
                    :icon="data.iconRight.icon" class="pl-1 h-4 mb-0.5" :class="data.iconRight.class"/>
            </h2>
            <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                    <div v-for="item in data.meta" class="mt-2 flex items-center text-xs text-gray-500">
                        <FontAwesomeIcon v-if="item['leftIcon']"
                            :title="capitalize(item['leftIcon']['tooltip'])"
                            aria-hidden="true" :icon="item['leftIcon']['icon']"  class="text-gray-400 pr-2"/>
                        <Link v-if="item.href" :href="`${route(item.href['name'], item.href['parameters'])}`">
                            <MetaLabel :item=item />
                        </Link>
                        <span v-else>
                            <MetaLabel :item=item />
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- To replace the Button, call template in Parent -->
        <slot name="button" :dataPageHead="{...props }">
            <div class="flex items-center gap-2">
                <div v-for="action in data.actions">
                    {{ action.final }}

                    <!-- Button -->
                    <Link v-if="action.type === 'button'" as="button"
                        :href="`${route(action['route']['name'], action['route']['parameters'])}`"
                        :method="action.method ?? 'get'"
                        :data="action.method !== 'get' ? dataToSubmit : null"
                    >

                        <Button size="xs" :style="action.style" :label="action.label"
                            class="capitalize inline-flex items-center rounded-md text-sm font-medium shadow-sm gap-x-2"
                        >
                        </Button>
                    </Link>

                    <!--suppress HtmlUnknownTag -->
                    <!-- Button Group () -->
                    <div v-if="action.type === 'buttonGroup'" class="first:rounded-l last:rounded-r overflow-hidden ring-1 ring-gray-300 flex">
                        <slot v-for="(button, index) in action.buttons" :name="'button' + index">
                            <Link
                                    :href="`${route(button['route']['name'], button['route']['parameters'])}`" class="">
                                <Button :style="button.style" :label="button.label" :icon="button.icon"
                                        class="capitalize inline-flex items-center rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                                    
                                </Button>
                            </Link>
                        </slot>
                    </div>

                    <slot v-if="action.type === 'modal'" name="modal" :data="{...props }"/>
                </div>
                <slot name="other" :dataPageHead="{...props }"/>
            </div>
        </slot>
    </div>
    <hr class="border-gray-300 dark:border-gray-500"/>
</template>



