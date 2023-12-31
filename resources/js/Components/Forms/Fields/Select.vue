<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 10 May 2023 09:18:00 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faExclamationCircle, faCheckCircle } from '@fas'
import { library } from "@fortawesome/fontawesome-svg-core"
library.add(faExclamationCircle, faCheckCircle)
const props = defineProps<{
    form?: any
    fieldName: any
    options: string[] | object
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: "multiple" | "single" | "tags"
		searchable?: boolean
    }
}>()
// console.log(props)
</script>

<template>
	<div class="">
		<div class="relative">
			<Multiselect
				v-model="form[fieldName]"
                @change="form.errors[fieldName] = ''"
				:class="{ 'pr-8': form.errors[fieldName] || form.recentlySuccessful }"
				:options="props.options"
				:placeholder="props.fieldData?.placeholder ?? 'Select your option'"
				:canClear="!props.fieldData?.required"
				:mode="props.fieldData?.mode ? props.fieldData?.mode : 'single'"
				:closeOnSelect="props.fieldData?.mode == 'multiple' ? false : true"
				:canDeselect="!props.fieldData?.required"
				:hideSelected="false"
				:searchable="!!props.fieldData?.searchable" />
			<div
				v-if="form.errors[fieldName] || form.recentlySuccessful"
				class="absolute inset-y-2/4 right-0 pr-3 flex items-center pointer-events-none bg-red-500">
				<FontAwesomeIcon
					icon="fas fa-exclamation-circle"
					v-if="form.errors[fieldName]"
					class="h-5 w-5 text-red-500"
					aria-hidden="true" />
				<FontAwesomeIcon
					icon="fas fa-check-circle"
					v-if="form.recentlySuccessful"
					class="mt-1.5 h-5 w-5 text-green-500"
					aria-hidden="true" />
			</div>
		</div>
		<p v-if="form.errors[fieldName]" class="mt-2 text-sm text-red-600" id="email-error">
			{{ form.errors[fieldName] }}
		</p>
	</div>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>

<style>
/* Style for multiselect globally */
.multiselect-option.is-selected,
.multiselect-option.is-selected.is-pointed {
	@apply bg-gray-500 text-white;
}

.multiselect-option.is-selected.is-disabled {
	@apply bg-gray-200 text-white;
}

.multiselect.is-active {
	border: var(--ms-border-width-active, var(--ms-border-width, 1px)) solid
		var(--ms-border-color-active, var(--ms-border-color, #787878)) !important;
	box-shadow: 0 0 0 var(--ms-ring-width, 3px) var(--ms-ring-color, rgba(42, 42, 42, 0.188)) !important;
	/* box-shadow: 4px 0 0 0 calc(4px + 4px) rgba(42, 42, 42, 1); */
}

/* .multiselect-option.is-open {
	@apply outline-none border-none ring-transparent;
} */
</style>
