<script setup lang="ts">
import { ref, watch } from "vue"
import { faLink, faEdit, faTrash } from "@/../private/pro-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import Popover from '@/Components/Utils/Popover.vue'
import { upperFirst, cloneDeep } from 'lodash'
import Button from "@/Components/Elements/Buttons/Button.vue"
library.add(faLink, faEdit, faTrash)
const props = defineProps({
	data: {
		type: Object,
		default: () => ({})
	},
	label: String,
	useDelete: {
		type: Boolean,
		default: true
	},
	formList: Object,
	cssClass: {
		type: String,
		default: ''
	}
});

const emits = defineEmits();

const value = ref({ ...props.data })

const handleInputBlur = (path) => {
	props.data[path] = value.value[path]
}

</script>
<template>
	<Popover>
		<template #button>
			<slot name="label">
				<span :class="cssClass">
					{{ data[label] }}
				</span>
			</slot>

		</template>
		<template #content>
			<div class="p-5 w-80">
				<slot name="content" :onRef="{...props, handleInputBlur, value }">
					<div v-for="item of formList" class="m-1">
						<span class="text-sm text-black">{{ upperFirst(item) }}</span>
						<input v-model="value[item]" class="w-full text-black" @blur="() => handleInputBlur(item)" />
					</div>
					<div v-if="useDelete" class="my-2 mx-1">
						<Button @click="emits('OnDelete')" class="w-full flex justify-center bg-red-500">
							Delete
						</Button>
					</div>
				</slot>
			</div>
		</template>
	</Popover>
</template>

<style>
.fade-enter-active {
	transition: opacity 0.3s;
}
</style>
