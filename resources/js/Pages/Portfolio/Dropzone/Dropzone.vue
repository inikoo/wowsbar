<script  setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage } from "@/../private/pro-solid-svg-icons"
import { faTrash } from "@/../private/pro-light-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core";
import draggable from "vuedraggable"
import { get } from 'lodash'
import Modal from '../Modal/Modal.vue'
import { v4 as uuidv4 } from 'uuid';
library.add(faImage, faTrash)
const props = defineProps<{
  files: Array
  filesChange: Function
  changeLink: Function
}>()

const isDragging = ref(false)
const files = ref(props.files)
const fileInput = ref(null)
const open = ref(false)
const fileEdit = ref(null)

const onChange = () => {
  let setData = []
  for (const set of fileInput.value.files) {
    if (set && set instanceof File) {
      setData.push({
        file: set,
        link: { label: "open", target: "" },
        imageAlt: set.name,
        imageSrc: 'img',
        id: uuidv4()
      })
    }
  }
  const newFiles = [...setData]
  files.value = [...files.value, ...newFiles]
  props.filesChange([...files.value])
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

const generateName = (file) => {
  return file.imageAlt
}

const remove = (i) => {
  files.value.splice(i, 1)
  props.filesChange([...files.value])
}

const dragover = (e) => {
  e.preventDefault()
  isDragging.value = true
}

const dragleave = () => {
  isDragging.value = false
}

const drop = (e) => {
  e.preventDefault()
  const newFiles = [...e.dataTransfer.files]
  files.value = [...files.value, ...newFiles]
  isDragging.value = false
}

const openEditModal = (file) => {
  fileEdit.value = file
  open.value = true
}

const closeEditModal = () => {
  fileEdit.value = null
  open.value = false
}

const changeLink = (file, value) => {
  open.value = false
  props.changeLink(file, value)
}

const changeIndex = () => {
  props.filesChange(files.value)
}

</script>

<template>
  <div class="main">
    <Modal :isOpen="open" :closeModal="closeEditModal" :data="fileEdit" :changeLink="changeLink" />
    <div style="width: 30%;">
      <div class="dropzone-container" @dragover="dragover" @dragleave="dragleave" @drop="drop">
      <input type="file" multiple name="file" id="fileInput" class="hidden-input" @change="onChange" ref="fileInput"
        accept=".pdf,.jpg,.jpeg,.png" />

      <label for="fileInput" class="file-label">
        <div style="font-size: 40px;" class="mx-auto h-12 w-12 text-gray-300"><font-awesome-icon
            :icon="['fass', 'image']" /></div>
        <div class="mt-4 flex text-sm leading-6 text-gray-600">Click or drag file to this area to upload</div>
      </label>
    </div>
    <div class="text-gray-500 text-xs px-5 py-2" >* recommended to upload images with a resolution of 300px x 1200 px</div>
    </div>
    

    <div class="container-preview" v-if="files.length">
      <draggable :list="files" group="files" item-key="id" handle=".handle" @change="changeIndex">
        <template #item="{ element: file }">
          <div class="preview-card flex items-center"> <!-- Add "flex" and "items-center" classes here -->
            <font-awesome-icon icon="fa fa-align-justify"
              class="handle p-2.5 text-gray-300 cursor-grab"></font-awesome-icon>
            <div class="img">
              <img class="preview-img" :src="generateThumbnail(file)" />
            </div>
            <div class="title cursor-pointer" @click="openEditModal(file)">
              <div>{{ generateName(file) }}</div>
              <div class="text-xs text-gray-300">{{ get(file, ['link', 'target']) }}</div>
            </div>
            <div class="flex justify-center items-center">
              <button class="ml-2 text-rose-500" type="button" @click="remove(files.indexOf(file))" title="Remove file">
                <font-awesome-icon :icon="['fal', 'trash']" />
              </button>
            </div>
          </div>
        </template>
      </draggable>


    </div>
  </div>
</template>

<style>
.main {
  display: flex;
  flex-grow: 1;
}

.dropzone-container {
  height: 208px;
  width: 100%;
  text-align: center;
  background: rgba(0, 0, 0, 0.02);
  border: 1px dashed #d9d9d9;
  border-radius: 8px;
  cursor: pointer;
  transition: border-color 0.3s;
  display: flex;
  justify-content: center;
  align-items: center;
}

.hidden-input {
  opacity: 0;
  overflow: hidden;
  position: absolute;
  width: 1px;
  height: 1px;
}

.file-label {
  font-size: 20px;
  display: block;
  cursor: pointer;
}

.preview-card {
  display: flex;
  position: relative;
  height: 66px;
  padding: 8px;
  border: 1px solid #d9d9d9;
  border-radius: 8px;
  margin-bottom: 7px;
}

.preview-img {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  width: 48px;
  height: 48px;
  line-height: 60px;
  text-align: center;
  flex: none;
}

.title {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding: 0 8px;
  line-height: 1.5714285714285714;
  flex: auto;
  transition: all 0.3s;
}

.container-preview {
  width: 70%;
  padding: 0px 0px 10px 10px;
}
</style>
