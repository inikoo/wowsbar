<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 30 Oct 2022 15:27:23 Greenwich Mean Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { trans } from 'laravel-vue-i18n'
import { faSave as fadSave } from "@/../private/pro-duotone-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core';
import { faDownload } from '@/../private/pro-light-svg-icons';
import { faArrowLeft } from '@/../private/pro-regular-svg-icons';
import { faPlus, faSave, faUpload } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = withDefaults(defineProps<{
    'style'?: string
    'size'?: string
    'icon'?: string | string[]
    'leftIcon'?: Object
    'rightIcon'?: Object
    'action'?: string
    'label'?: string
    'full'?: boolean
}>(), {
    style: 'primary',
    size: 'm'
})

interface Icon {
    icon: string[] | string
}

library.add(faPlus, faSave, fadSave, faUpload, faDownload, faArrowLeft)

let styleClass = ''
let sizeClass = ''

// Styling the Button depends on the 'style' props
if (props.style == 'primary' || props.style == 'create' || props.style == 'save') styleClass = 'bg-gray-800 bg-gradient-to-r from-gray-600 to-gray-800 text-white dark:text-gray-700 hover:bg-none'
else if (props.style == 'secondary' || props.style == 'edit') styleClass = 'bg-gray-300 bg-gradient-to-r from-gray-100 to-gray-300 border border-gray-400/80 text-gray-600 hover:bg-none'
else if (props.style == 'tertiary') styleClass = 'bg-transparent border border-gray-300 text-gray-700 dark:text-gray-400 hover:bg-gray-200/70 dark:hover:bg-gray-600/90'
else if (props.style == 'delete') styleClass = 'border border-red-400 dark:border-red-600 text-red-500 dark:text-red-600 hover:text-red-800 hover:bg-red-100 dark:hover:bg-red-100/10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'
else if (props.style == 'positive') styleClass = 'border border-emerald-400 dark:border-emerald-600 text-emerald-500 dark:text-emerald-600 hover:text-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-100/10 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2'

else if (props.style == 'negative' || props.style == 'cancel') styleClass = 'border border-red-400 dark:border-red-800 text-red-600 dark:text-red-700 hover:text-red-800 hover:bg-red-100 dark:hover:bg-red-100/10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'
else if (props.style == 'disabled') styleClass = 'cursor-not-allowed border border-gray-300 dark:border-gray-500 bg-transparent text-gray-700 dark:text-gray-400 hover:bg-gray-200/70 dark:hover:bg-gray-600/90'
else styleClass = 'border border-gray-300 bg-transparent text-gray-700 dark:text-gray-400 hover:bg-gray-200/70'

// Styling depends on the 'size' props
switch (props.size) {
    case 'xs':
        sizeClass = 'rounded px-2.5 py-1.5 text-xs'
        break
    case 's':
        sizeClass = 'rounded-md px-3 py-2 text-sm'
        break
    case 'm':
        sizeClass = 'rounded-md px-4 py-2 text-sm'
        break
    case 'l':
        sizeClass = 'rounded-md px-4 py-2 text-base'
        break
    case 'xl':
        sizeClass = 'rounded-md px-6 py-3 text-base'
        break
}

// Auto add label for several conditions
const getActionLabel = (label: string | undefined) => {
    if (label) {
        return trans(label)
    } else {
        switch (props.style) {
            case "edit":
                return trans("edit")
            case "save":
                return trans("save")
            case "create":
                return trans("create")
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
}

// Auto add icon for several conditions
const getActionIcon = (icon: any) => {
    if (icon) {
        return icon
    } else {
        switch (props.style) {
            case "edit":
                return ["far", "fa-pencil"]
            case "save":
                return ["fas", "fa-save"]
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
    <button type="button"
        class="leading-4 inline-flex items-center gap-x-2 font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
        :class="[
            icon ? 'px-2 sm:px-4' : 'px-3 sm:px-5 ',
            full ? 'w-full justify-center' : 'min-w-max',
            styleClass,
            sizeClass
        ]"
        :disabled="style == 'disabled'">
        <slot>
            <slot name="icon">
                <FontAwesomeIcon fixed-width v-if="getActionIcon(icon)" :icon="getActionIcon(icon)" class="" aria-hidden="true"/>
            </slot>
            <span v-if="getActionLabel(label)" class="">{{ getActionLabel(label) }}</span>
        </slot>
    </button>
</template>
