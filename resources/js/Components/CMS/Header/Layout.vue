<script setup lang="ts">
import VueResizable from "vue-resizable"
import { ref } from "vue"
import { set, get } from "lodash"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core';
import { faHandPointer, faHandRock, faPlus, faText, faSearch, faImage } from '@/../private/pro-solid-svg-icons';
import Input from "../Fields/Input.vue"
library.add(faHandPointer, faText, faSearch, faImage)
const props = defineProps<{
	data: Array
	layout: Object
	setActive: Function
	layerActive: Object
}>()


function setdragElement(ref, value) {
	value.ref = ref
	dragElement(ref, value)
}

function dragElement(elmnt, dataSet) {
	let pos3 = 0, pos4 = 0

	const dragMouseDown = (e) => {
		e = e || window.event
		e.preventDefault()

		pos3 = e.clientX
		pos4 = e.clientY

		document.onmouseup = closeDragElement
		document.onmousemove = elementDrag
	}

	const elementDrag = (e) => {
		e = e || window.event
		e.preventDefault()

		let pos1 = pos3 - e.clientX
		let pos2 = pos4 - e.clientY
		pos3 = e.clientX
		pos4 = e.clientY

		const containerLeft = containerSize.value.left
		const containerTop = containerSize.value.top
		const containerRight = containerSize.value.right
		const containerBottom = containerSize.value.bottom

		// Calculate the new position of the dragged element
		let newTop = elmnt.offsetTop - pos2
		let newLeft = elmnt.offsetLeft - pos1

		// Check if the new position is within the container boundaries
		if (
			newTop >= containerTop &&
			newLeft >= containerLeft &&
			newLeft + elmnt.offsetWidth <= containerRight &&
			newTop + elmnt.offsetHeight <= containerBottom
		) {
			elmnt.style.top = newTop + "px"
			elmnt.style.left = newLeft + "px"
		}
		set(dataSet, 'style', { ...dataSet.style, top: `${newTop}px`, left: `${newLeft}px` })


	}

	const closeDragElement = () => {
		document.onmouseup = null
		document.onmousemove = null
	}

	if (elmnt) elmnt.querySelector(".draggable-handle").addEventListener("mousedown", dragMouseDown)
}

const containerSize = ref(null)
const eHandler = (data) => {
	containerSize.value = { ...data, right: data.left + data.width, bottom: data.top + data.height }
}

const generateThumbnail = (file) => {
	if (file.file && file.file instanceof File) {
		let fileSrc = URL.createObjectURL(file.file)
		setTimeout(() => {
			URL.revokeObjectURL(fileSrc)
		}, 1000)
		return fileSrc
	} else {
		return file.imageSrc
	}
}
</script>

<template>
	<div>
		<vue-resizable class="container bg-white" :minHeight="200" :maxWidth="1233" :minWidth="1233" :height="layout.height" :maxHeight="200"
		:left="layout.left" :top="layout.top" @mount="eHandler" @resize:move="eHandler" @resize:start="eHandler"
		@resize:end="eHandler" @drag:move="eHandler" @drag:start="eHandler" @drag:end="eHandler">
			<div v-for="(item, index) in props.data.slice().reverse()" :key="item.id" :fit-parent="true">
				<div v-if="item.type == 'text'" :ref="(refValue) => setdragElement(refValue, item)"
					class="col-sm-10 draggable-component" :style="{ ...item.style }">
					<div :class="['draggable-handle', { border: get(data[layerActive], 'id') === item.id }]"
						@click="(e) => { e.stopPropagation(); props.setActive(item.id) }">
						<Input :data="item" keyValue="name" :styleCss="item.style" />
					</div>
				</div>

				<div v-if="item.type == 'search'" :ref="(refValue) => setdragElement(refValue, item)"
					class="col-sm-10 draggable-component" :style="{ ...item.style }">
					<div :class="['draggable-handle', { border: get(data[layerActive], 'id') === item.id }]"
						@click="(e) => { e.stopPropagation(); props.setActive(item.id) }">
						<div class="relative">
							<input
								class="dark:bg-gray-600 block w-full pl-9 pr-9 text-sm rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-400 border-gray-300 dark:border-gray-500"
								placeholder="search" type="text" name="global" :readonly="true">
							<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
								<FontAwesomeIcon icon="far fa-search" class="h-4 w-4 text-gray-400" aria-hidden="true" />
							</div>

						</div>
					</div>
				</div>


				<div v-if="item.type == 'image'" :ref="(refValue) => setdragElement(refValue, item)"
					class="col-sm-10 draggable-component" :style="{ ...item.style }">
					<div :class="['draggable-handle', { border: get(data[layerActive], 'id') === item.id }]"
						@click="(e) => { e.stopPropagation(); props.setActive(item.id) }">
						<img :class="['draggable-handle', { border: get(data[layerActive], 'id') === item.id }]"
							class="preview-img" :src="generateThumbnail(item)" :style="`width: ${item.style.width}; height: ${item.style.height};`" />
					</div>
				</div>
			</div>

			<!-- <vue-resizable
          :key="item.id"
          :maxWidth="layout.height"
          :maxHeight="1233"
          v-if="item.type == 'image'" 
          :ref="(refValue) => setdragElement(refValue.$el, item)" 
          class="col-sm-10 draggable-component" 
          :style="{ ...item.style }"
        >

            <img :class="['draggable-handle', { border: get(data[layerActive], 'id') === item.id }]"  @click="(e) => { e.stopPropagation(); props.setActive(index) }" class="preview-img" :src="generateThumbnail(item)" style="width: 100%; height: 100%;"/>

        </vue-resizable> -->


		</vue-resizable>
	</div>
</template>


<style>
.draggable-component {
	position: absolute;
	z-index: 9;
	text-align: center;
	cursor: move;
}

.draggable-handle {
	padding: 10px;
	z-index: 10;
}
</style>
