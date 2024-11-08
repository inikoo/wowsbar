<script setup lang="ts">
import { ref, watch, computed } from "vue"
import { trans } from "laravel-vue-i18n"
import Button from "@/Components/Elements/Buttons/Button.vue"
import Image from "@/Components/Image.vue"
import InputText from 'primevue/inputtext';
import { notify } from "@kyvg/vue3-notification"
import axios from "axios"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faImage, faPhotoVideo, faLink } from "@fal"
import { routeType } from "@/types/route"
import { cloneDeep, isObject } from "lodash"
import GalleryImages from "@/Components/Workshop/GalleryImages.vue"
import Modal from '@/Components/Utils/Modal.vue'

library.add(faImage, faPhotoVideo, faLink)

// Define props and emits
const props = withDefaults(
	defineProps<{
		modelValue: any
		uploadRoutes: routeType
	}>(),
	{}
)

const emits = defineEmits<{
	(e: "update:modelValue", value: any): void
	(e: "onUpload", value: Files[]): void
	(e: "autoSave"): void
}>()

// Component state
const isOpenGalleryImages = ref(false)
const isDragging = ref(false)
const fileInput = ref(null)
const addedFiles = ref<File[]>([])

// Upload function
const onUpload = async () => {
	try {
		const formData = new FormData()
		Array.from(addedFiles.value).forEach((file, index) => {
			formData.append(`images[${index}]`, file)
		})
		const response = await axios.post(
			route(props.uploadRoutes.name, props.uploadRoutes.parameters),
			formData,
			{
				headers: {
					"Content-Type": "multipart/form-data",
				},
			}
		)
		const updatedModelValue = { ...props.modelValue, source: cloneDeep(response.data.data[0].source) }
		emits("update:modelValue", updatedModelValue)
	} catch (error) {
		notify({
			title: "Failed",
			text: "Error while uploading data",
			type: "error",
		})
	}
}

// Drag and drop handlers
const addComponent = (event) => {
	addedFiles.value = event.target.files
	onUpload()
}

const dragOver = (e) => {
	e.preventDefault()
	isDragging.value = true
}

const dragLeave = () => {
	isDragging.value = false
}

const drop = (e) => {
	e.preventDefault()
	addedFiles.value = e.dataTransfer.files
	isDragging.value = false
	onUpload()
}

// Handle gallery image selection
const onPickImage = (e) => {
	console.log(e)
	isOpenGalleryImages.value = false
	const updatedModelValue = { ...props.modelValue, source: cloneDeep(e.data[0].source) }
	emits("update:modelValue", updatedModelValue)
}

const DeleteImage = () => {
	isOpenGalleryImages.value = false
	const updatedModelValue = { ...props.modelValue, source: null }
	emits("update:modelValue", updatedModelValue)
}

// Open file input
const onClickButton = () => {
	fileInput.value?.click()
}


// Watch for specific changes in modelValue and auto-save
watch(
  () => props.modelValue,
  (newValue) => {
    console.log("Model value changed", newValue)
    emits("autoSave")
  },
  { deep: true }
)

</script>

<template>
	<div>
		<button type="submit" @click="onClickButton"
			class="flex w-full justify-center bg-[#475569] px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-[#FCD34D] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
			Upload Image
		</button>
	</div>

	<div>
		<div class="w-full h-full space-y-2" @dragover="dragOver" @dragleave="dragLeave" @drop="drop">
			<div
				class="relative mt-2 flex justify-center border-dashed border border-[#475569] shadow-lg px-6 py-5 bg-gradient-to-r hover:bg-gray-400/20">
				<label for="fileInput"
					class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-2 focus-within:ring-gray-400 focus-within:ring-offset-0">
					<input type="file" multiple name="file" id="fileInput" class="sr-only" ref="fileInput"
						@change="addComponent" />
				</label>
				<div v-if=" !modelValue?.source" class="text-center">
					<div class="flex text-sm leading-6 justify-center">
						<p class="pl-1">{{ trans("Drag Images Here.") }}</p>
					</div>
					<p class="text-[0.7rem] mb-2.5">
						{{ trans("PNG, JPG, GIF up to 10MB") }}
					</p>
					<div class="mt-2.5 flex items-center justify-center gap-x-2">
						<Button id="gallery" :style="`tertiary`" :icon="'fal fa-photo-video'" label="Gallery" size="xs"
							class="relative hover:text-gray-700" @click="isOpenGalleryImages = true" />
					</div>
				</div>
				<div v-else>
					<Image v-if="isObject(modelValue.source)" :src=" modelValue?.source"
						class="w-full object-cover h-full object-center group-hover:opacity-75">
					</Image>
					<img v-else="modelValue.source" :src="modelValue?.source"
						class="w-full object-cover h-full object-center group-hover:opacity-75">
					</img>

					<div class="absolute top-0 right-0 m-2 flex gap-2">
						<Button id="gallery" :style="`tertiary`" :icon="'fal fa-photo-video'" size="xs"
							class="relative hover:text-gray-700" @click="isOpenGalleryImages = true" />
						<Button id="gallery" :style="`red`" :icon="['far', 'fa-trash-alt']" size="xs"
							class="relative hover:text-gray-700" @click="DeleteImage" />
					</div>
				</div>
			</div>
		</div>
		<div class="text-xs text-gray-400 mt-3">200 x 100 (recomended)</div>
	</div>


	<div class="mt-8">
		<div class="flex justify-between mb-2 text-gray-500 text-xs font-semibold">
			<div>Alternate Text</div>
		</div>
		<InputText v-model="modelValue.alt" class="w-full" />
	</div>


	<!-- Modal: Gallery -->
	<Modal :isOpen="isOpenGalleryImages" @onClose="isOpenGalleryImages = false">
		<div>
			<GalleryImages 
				:addImage="onPickImage" 
				:closeModal="() => isOpenGalleryImages = false"
				:imagesUploadRoute="uploadRoutes"
				:multiple="false"
				:canUploadImage="false"
			/>
		</div>
	</Modal>

</template>

<style scoped></style>
