<script  setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage } from "@/../private/pro-solid-svg-icons"
import { faTrash } from "@/../private/pro-light-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core";
import {get} from 'lodash'
import Modal from '../Modal/Modal.vue'
library.add(faImage, faTrash)
const props = defineProps<{
  files: Array
  filesChange : Function
  changeLink : Function
}>()

const isDragging = ref(false)
const files = ref(props.files)
const fileInput = ref(null)
const open = ref(false)
const fileEdit = ref(null)
const dropZoneRef = ref(null);

const onChange = () => {
  let setData = []
  for (const set of fileInput.value.files) {
    if (set && set instanceof File) {
      setData.push({
        file: set,
        link: { label: "set", target: "#" },
        imageAlt: set.name,
        imageSrc: ''
      })
    }
  }
  const newFiles = [...setData]
  files.value = [...files.value, ...newFiles]
  props.filesChange([...files.value])
}

const generateThumbnail = (file) => {
  if(file.file && file.file instanceof File ){
    let fileSrc = URL.createObjectURL(file.file)
  setTimeout(() => {
    URL.revokeObjectURL(fileSrc)
  }, 1000)
  return fileSrc
  }else{
    return file.imageSrc
  }
}

const generateName = (file) => {
    return file.imageAlt
  }

const remove = (i) => {
  files.value.splice(i, 1)
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

const openEditModal=(file)=>{
  fileEdit.value = file
  open.value = true
}

const closeEditModal=()=>{
  fileEdit.value = null
  open.value = false
}

const changeLink=(file,value)=>{
  console.log('sdfsdf',file,value)
  open.value = false
  props.changeLink(file,value)
}

</script>

<template>
  <div class="main">
  <Modal :isOpen="open" :closeModal="closeEditModal" :data="fileEdit" :changeLink="changeLink"/>
    <div
      class="dropzone-container"
      @dragover="dragover"
      @dragleave="dragleave"
      @drop="drop"
    >
      <input
        type="file"
        multiple
        name="file"
        id="fileInput"
        class="hidden-input"
        @change="onChange"
        ref="fileInput"
        accept=".pdf,.jpg,.jpeg,.png"
      />

      <label for="fileInput" class="file-label">
      <div style="font-size: 40px;" class="text-indigo-600"><font-awesome-icon :icon="['fass', 'image']" /></div>
        <div>Click or drag file to this area to upload</div>
      </label>

    </div>
    <div class="container-preview" v-if="files.length">
      <div v-for="file in files" :key="file.name" class="preview-card" >
        <div class="img"  @click="openEditModal(file)">
          <img class="preview-img" :src="generateThumbnail(file)" />
        </div>
        <div class="title">
          <div>{{generateName(file) }}</div>
          <div  class="text-xs text-gray-300">{{get(file,['link','target'],'https//:....')}}</div>
        </div>
        <div class="flex justify-center items-center">
          <button
            class="ml-2 text-rose-500"
            type="button"
            @click="remove(files.indexOf(file))"
            title="Remove file"
          >
          <font-awesome-icon :icon="['fal', 'trash']" />
          </button>
        </div>
      </div>
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
    width: 30%;
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
.title{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    padding: 0 8px;
    line-height: 1.5714285714285714;
    flex: auto;
    transition: all 0.3s;
}

.container-preview{
    width: 70%;
    padding: 0px 0px 10px 10px;
}

</style>
