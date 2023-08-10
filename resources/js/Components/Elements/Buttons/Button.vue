<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Sun, 30 Oct 2022 15:27:23 Greenwich Mean Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import { faSave as fadSave } from "@/../private/pro-duotone-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core';
import {  } from '@/../private/pro-light-svg-icons';
import { faPlus, faSave, faUpload } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faPlus, faSave, fadSave, faUpload);

const props = defineProps(
    {
        'style': {
            type: String,
            default: 'primary',
        },
        'size': {
            type: String,
            default: 'm',
        },
        'icon': {
            type: String,
            default: ''
        },
        'leftIcon': {
            type: Object,
        },
        'rightIcon': {
            type: Object,
        },
        'action': {
            type: String,
        },
    })

let styleClass = ''
let iconClass = ''
let sizeClass = ''

// Styling depends on the 'style' props
if (props.style == 'primary' || props.style == 'create') styleClass = 'bg-gray-700 dark:bg-gray-300/90 text-white dark:text-gray-700 hover:bg-gray-800 dark:hover:bg-gray-300'
else if (props.style == 'secondary' || props.style == 'edit') styleClass = 'border border-gray-400/80 bg-gray-300 text-gray-700 hover:bg-gray-400/60'
else if (props.style == 'tertiary') styleClass = 'border border-gray-300 dark:border-gray-500 bg-transparent text-gray-700 dark:text-gray-400 hover:bg-gray-200/70 dark:hover:bg-gray-600/90'
else if (props.style == 'delete') styleClass = 'border border-red-400 dark:border-red-600 text-red-500 dark:text-red-600 hover:text-red-800 hover:bg-red-100 dark:hover:bg-red-100/10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'
else if (props.style == 'negative') styleClass = 'border border-red-400 dark:border-red-800 text-red-600 dark:text-red-700 hover:text-red-800 hover:bg-red-100 dark:hover:bg-red-100/10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'
else styleClass = 'border border-gray-300 bg-transparent text-gray-700 dark:text-gray-400 hover:bg-gray-200/70'

// Styling depends on the 'size' props
switch (props.size) {
    case 'xs':
        sizeClass = 'rounded px-2.5 py-1.5 text-xs'
        iconClass = '-ml-0.5 mr-2 h-3 w-3 '
        break
    case 's':
        sizeClass = 'rounded-md px-3 py-2 text-sm'
        iconClass = '-ml-0.5 mr-2 h-4 w-4'
        break
    case 'm':
        sizeClass = 'rounded-md x-4 py-2 text-sm'
        iconClass = '-ml-1 mr-2 '
        break
    case 'l':
        sizeClass = 'rounded-md px-4 py-2 text-base'
        iconClass = '-ml-1 mr-3 h-5 w-5'
        break
    case 'xl':
        sizeClass = 'rounded-md px-6 py-3 text-base'
        iconClass = 'ml-1 mr-3 h-5 w-5'
        break
}

</script>

<template>
    <button type="button" :class="[
        'pl-2 pr-3 sm:pl-4 sm:pr-5 min-w-max inline-flex items-center font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 focus:ring-offset-2',
        styleClass,
        sizeClass
    ]">
        <FontAwesomeIcon v-if="icon"  aria-hidden="true" :icon="icon" size="sm"  class="mr-2" />
        <slot />
    </button>
</template>
