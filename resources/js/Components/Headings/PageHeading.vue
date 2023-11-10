<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 07 Oct 2022 09:34:00 Central European Summer Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from "@inertiajs/vue3"

import { library } from "@fortawesome/fontawesome-svg-core"
import { faMailBulk,faSign,faEdit, faWindowMaximize, faDraftingCompass, faEmptySet, faMoneyCheckAlt, faPeopleArrows, faSlidersH, faSave, faSuitcase, faBroadcastTower, faUpload, faLevelUp, faUserPlus, faTimes, faClock, faSeedling, faTrashAlt as falTrashAlt, faPencil as falPencil} from '@fal/'
import { faRocketLaunch, faPencil, faArrowLeft, faBorderAll, faTrashAlt, faDesktop} from '@far/'
import { faPlus } from '@fas/'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { capitalize } from "@/Composables/capitalize"
import MetaLabel from "@/Components/Headings/MetaLabel.vue";
import Container from "@/Components/Headings/Container.vue";
import Action from "@/Components/Forms/Fields/Action.vue";
import Icon from "@/Components/Icon.vue";
import { Action as ActionTS } from "@/types/Action"

interface Icon {
    icon: string[] | string
}

library.add(faMailBulk,faEdit, faWindowMaximize, faRocketLaunch, faDraftingCompass, faEmptySet, faMoneyCheckAlt, faPeopleArrows, faSlidersH, faPlus,
    faPencil, faArrowLeft, faBorderAll, faTrashAlt, faSave, faSuitcase,
    faBroadcastTower, faUpload, faDesktop,faLevelUp, faUserPlus, faTimes, faClock, faSeedling,faSign, falTrashAlt, falPencil
)

const props = defineProps<{
    data: {
        title: string
        noCapitalise?:boolean
        icon: {
            icon: Icon
            tooltip: string
        }
        actionActualMethod?: string
        meta?: any
        actions?: ActionTS[]
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


if (props.dataToSubmit && props.data.actionActualMethod) {
    props.dataToSubmit['_method'] = props.data.actionActualMethod
}

</script>


<template>
    <div class="mx-4 py-4 md:pb-2 md:pt-3 lg:py-2 grid grid-flow-col justify-between items-center">
        <div>
            <div class="flex leading-none py-1 items-center gap-x-2 font-bold text-gray-700 text-2xl tracking-tight ">
                <div v-if="data.container" class="text-slate-500 text-lg">
                    <Link v-if="data.container.href"
                        :href="route(
                            data.container.href['name'],
                            data.container.href['parameters']
                    )">
                        <Container :data="data.container"/>
                    </Link>
                    <div v-else class="flex items-center gap-x-1">
                        <Container :data="data.container" />
                    </div>
                </div>
                <div v-if="data.icon" class="inline text-gray-400">
                    <FontAwesomeIcon :title="capitalize(data.icon.tooltip ?? '')" aria-hidden="true"
                        :icon="data.icon.icon" size="sm" class=""/>
                    <!-- <FontAwesomeIcon v-if="data.iconBis" :title="capitalize(data.iconBis.tooltip ?? '')" aria-hidden="true"
                        :icon="data.iconBis.icon" size="sm" class="" :class="data.iconBis.class"/> -->
                </div>
                <h2 :class="!data.noCapitalise? 'capitalize':''">{{ data.title }}</h2>
                <FontAwesomeIcon v-if="data.iconRight" :title="capitalize(data.iconRight.tooltip ?? '')" aria-hidden="true"
                    :icon="data.iconRight.icon" class="h-4" :class="data.iconRight.class"/>
            </div>

            <!-- Section: mini Tabs -->
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

        <!-- Button & ButtonGroup -->
        <slot name="button" :dataPageHead="{...props }">
            <div class="flex items-center gap-2">
                <div v-for="action in data.actions">
                    <Action :action="action" :dataToSubmit="dataToSubmit"/>
                </div>
                <slot name="other" :dataPageHead="{...props }"/>
            </div>
        </slot>
    </div>
    <hr class="border-gray-300"/>
</template>



