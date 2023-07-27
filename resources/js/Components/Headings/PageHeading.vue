<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 07 Oct 2022 09:34:00 Central European Summer Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup>
import { Link } from "@inertiajs/vue3";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faDraftingCompass,faEmptySet, faMoneyCheckAlt, faPeopleArrows, faSlidersH ,faSave} from "@/../private/pro-light-svg-icons";
import { faPencil, faArrowLeft, faBorderAll, faTrashAlt } from "@/../private/pro-regular-svg-icons";

import { faPlus } from "@/../private/pro-solid-svg-icons";
import Button from "@/Components/Elements/Buttons/Button.vue";
import { capitalize } from "@/Composables/capitalize";
import { useLocaleStore } from "@/Stores/locale.js";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { trans } from "laravel-vue-i18n";

library.add(faDraftingCompass,faEmptySet, faMoneyCheckAlt, faPeopleArrows, faSlidersH, faPlus, faPencil, faArrowLeft, faBorderAll, faTrashAlt,faSave);
const props = defineProps(["data", "dataToSubmit","dataToSubmitIsDirty"])
const locale = useLocaleStore()

const getActionLabel = function (action) {
    if (action.hasOwnProperty("label")) {
        return action.label
    } else {
        switch (action.style) {
            case "edit":
                return trans("edit")
            case "save":
                return trans("save")
            case "exit":
                return trans("exit")
            case "cancel":
                return trans("cancel")
            case "delete":
                return trans("delete")
            case "clearMulti":
                return trans("clear")
            default:
                return ""
        }
    }
};


const getActionIcon = (action) => {
    if (action.hasOwnProperty("icon")) {
        return action.icon
    } else {
        switch (action.style) {
            case "edit":
                 return ["far", "fa-pencil"]
             case "save":
                return ["fa", "fa-save"]
            case "cancel":
            case "exit":
                return ["far", "fa-arrow-left"]
             case "create":
                 return ["fas", "fa-plus"]
            case "delete":
                return ["far", "fa-trash-alt"]
            case "withMulti":
                return ["far", "fa-border-all"]
            default:
                return null
        }
    }
}

</script>


<template>
    <div class="mx-4 my-4 md:my-2 grid grid-flow-col justify-between items-center">
        <div>
            <h2 class="font-bold text-gray-700 dark:text-gray-300 text-2xl tracking-tight capitalize">
                <span v-if="data.container" class="text-orange-500 font-medium mr-2">
                    <FontAwesomeIcon v-if="data.container.icon" :title="capitalize(data.container.tooltip)"
                        aria-hidden="true" :icon="data.container.icon" size="xs" />
                    {{ data.container.label }}
                </span>

                <FontAwesomeIcon v-if="data.icon" :title="capitalize(data.icon.tooltip ?? '')" aria-hidden="true"
                    :icon="data.icon.icon" size="sm" class="pr-2 text-gray-400" />
                <span>{{ data.title }}</span>
                <FontAwesomeIcon v-if="data.iconRight" :title="capitalize(data.iconRight.tooltip ?? '')" aria-hidden="true"
                    :icon="data.iconRight.icon" class="pl-1 h-4 mb-0.5" />
            </h2>
            <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                    <div v-for="item in data.meta" :key="item.name" class="mt-2 flex items-center text-sm text-gray-500">
                        <FontAwesomeIcon v-if="item['leftIcon']" :title="capitalize(item['leftIcon']['tooltip'])"
                            aria-hidden="true" :icon="item['leftIcon']['icon']" size="lg" class="text-gray-400 pr-2" />
                        <Link v-if="item.href" :href="route(item.href[0], item.href[1])">
                        <span v-if="item.number">{{ locale.number(item.number) }}</span>
                        <FontAwesomeIcon v-else icon="fal fa-empty-set" />
                        {{ item.name }}
                        </Link>
                        <template v-else-if="item['emptyWithCreateAction']">
                            <FontAwesomeIcon icon="fal fa-empty-set" class="mr-2" />
                            <Button type="submit" size="xs" action="create">
                                {{ item["emptyWithCreateAction"]["label"] }}
                            </Button>
                        </template>
                        <span v-else><span v-if="item.number">{{ locale.number(item.number) }}</span> {{ item.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span v-for="action in data.actions">
                {{ action.final }}
                <!-- Button -->
                <Link v-if="action.type === 'button'"
                      :href="route(action['route']['name'], action['route']['parameters'])"
                      :method="action.method ?? 'get'"
                      :data="dataToSubmit"
                      as="button"
                >
                    <Button size="xs"

                        :style="action.style"
                        class="capitalize inline-flex items-center rounded-md text-sm font-medium shadow-sm gap-x-2">
                        <!--
                        <FontAwesomeIcon v-if="action.icon && action.icon == 'fad fa-save'" aria-hidden="true" :icon="['fad', 'save']" style="--fa-primary-color: #f3f3f3; --fa-secondary-color: #ff6600; --fa-secondary-opacity: 1;" size="sm" :class="[iconClass]" />
                        -->
                        <FontAwesomeIcon  :icon="getActionIcon(action)"
                            aria-hidden="true" />
                        {{ getActionLabel(action) }}
                    </Button>
                </Link>

                <!-- Button Group () -->
                <!--suppress HtmlUnknownTag -->
                <div v-if="action.type === 'buttonGroup'" class="first:rounded-l-md overflow-hidden last:rounded-r-md">
                    <Link v-for="button in action.buttons"
                        :href="route(button['route']['name'], button['route']['parameters'])" class="">
                        <Button size="xs" :style="button.style"
                            class="capitalize inline-flex items-center rounded-none  text-sm font-medium shadow-sm ">
                            <div class="">
                                <FontAwesomeIcon v-if="getActionIcon(button)" :icon="getActionIcon(button)" class=""
                                    aria-hidden="true" />
                                <span v-if="button.label" class="ml-2">{{ getActionLabel(button) }}</span>
                            </div>
                        </Button>
                    </Link>
                </div>
            </span>
        </div>
    </div>
    <hr class="border-gray-300 dark:border-gray-500"/>
</template>

<style></style>


