<script setup lang="ts">
import { ref, watch, defineEmits, onMounted } from "vue"
import { trans } from "laravel-vue-i18n"
import RadioButton from "primevue/radiobutton"
import PureInput from "@/Components/Pure/PureInput.vue"
import SelectQuery from "@/Components/SelectQuery.vue"
import { get, set } from "lodash"


interface Link {
	type: string,
	href: string,
	workshop : string | null,
	id : string | null | number,
	target : string
}

// const props = defineProps({
//     modelValue: {
//         type: Object,
//         required: true,
//     },
// });

const modelValue = defineModel()



// const options = ref([
// 	{ label: "Internal", value: "internal" },
// 	{ label: "External", value: "external" },
// ])

const targets = ref([
	{ label: trans("Replace page"), value: "_parent" },
	{ label: trans("New page"), value: "_blank" },
])

// watch(localModel, (newValue) => {
// 	console.log('masukkk')
// 	const data = {
// 		type: newValue.type,
// 		href: newValue.href,
// 		workshop: newValue.workshop,
// 		id: newValue.id,
// 		target: newValue.target
// 	}
// 	emit('update:modelValue',data)
// },{deep : true})


// onMounted(() => {
//     if (props.modelValue) {
//         localModel.value = { ...props.modelValue, data : props.modelValue };
//     }
// });

</script>

<template>
	<div>
		<div>
			<div class="text-gray-500 text-xs tracking-wide mb-2">{{ trans("Target") }}</div>
			<div class="mb-3 border border-gray-300 rounded-md w-full px-4 py-2">
				<div class="flex flex-wrap justify-between w-full gap-x-6 gap-y-1">
					<div v-for="(option, indexOption) in targets" class="flex items-center gap-2">
						<RadioButton
							:modelValue="get(modelValue, 'target', undefined)"
							@update:modelValue="(e: string) => set(modelValue, 'target', e)"
							:inputId="`${option.value}${indexOption}`"
							name="target"
							size="small"
							:value="option.value"
						/>
						<label @click="() => set(modelValue, 'target', option.value)" :for="`${option.value}${indexOption}`" class="cursor-pointer">{{ option.label }}</label>
					</div>
				</div>
			</div>
		</div>

		<!-- <div>
			<div class="text-gray-500 text-xs tracking-wide mb-2">{{ trans("Type") }}</div>
			<div class="mb-3 border border-gray-300 rounded-md w-full px-4 py-2">
				<div class="flex flex-wrap justify-between w-full">
					<div v-for="(option, indexOption) in options" class="flex items-center gap-2">
						<RadioButton
							:modelValue="localModel.type"
							@update:modelValue="(e: string) => set(localModel, 'type', e)"
							:inputId="`${option.value}${indexOption}`"
							name="type"
							size="small"
							:value="option.value"
						/>
						<label @click="() => localModel.type = option.value" :for="`${option.value}${indexOption}`" class="cursor-pointer">{{ option.label }}</label>
					</div>
				</div>
			</div>
		</div> -->

		
		
		<div>
			<div class="my-2 text-gray-500 text-xs tracking-wide mb-2">{{ trans("Destination") }}</div>
			<PureInput
				:modelValue="get(modelValue, 'href', '')"
				@update:modelValue="(e) => set(modelValue, 'href', e)"
				placeholder="https://www.anotherwebsite.com/page"
			/>
			
			<!-- <SelectQuery
				v-if="localModel?.type == 'internal'"
				:object="true"
				fieldName="data"
				:value="localModel"
				:closeOnSelect="true"
				label="href" 
				:urlRoute="
					route('grp.org.shops.show.web.webpages.index', {
						organisation: route().params['organisation'],
						shop: route().params['shop'],
						website: route().params['website'],
					})
				"
				/> -->
		</div>
	</div>
</template>

<style lang="scss" scoped></style>
