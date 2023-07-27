<script setup lang="ts">
import { ref } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faImage } from '@/../private/pro-light-svg-icons'
import { ulid } from 'ulid';
library.add(faImage)

const props = defineProps<{
    data: {
        common: {
            centralStage: {
                subtitle?: string
                text?: string
                title?: string
            }
            corners: Corners
        }
        components: Array<
            {
                id: number,
                image_id: number
                image_source: string
                layout: {
                    link?: string,
                    centralStage: {
                        title?: string
                        subtitle?: string
                        // text?: string,
                        // footer?: string
                    }
                }
                corners: Corners
                imageAlt: string
                link: string
                visibility: boolean
            }
        >
        delay: number
    }

}>();

const isDragging = ref(false)
const components = ref(props.data.components)
const fileInput = ref(null)

const onChange = () => {
    let setData = []
    for (const set of fileInput.value?.files) {
        if (set && set instanceof File) {
            setData.push({
                id: null,
                image_id: ulid(),
                image_source: null,
                imageFile: set,
                ulid: ulid(),
                layout: {
                    imageAlt: set.name,
                }
            })
        }
    }
    const newFiles = [...setData]
    components.value = [...components.value, ...newFiles]
    props.data.components = [...components.value]
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
    let setData = []
    for (const set of e.dataTransfer.files) {
        if (set && set instanceof File) {
            setData.push({
                id: null,
                image_id: ulid(),
                image_source: null,
                imageFile: set,
                ulid: ulid(),
                layout: {
                    imageAlt: set.name,
                }
            })
        }
    }
    const newFiles = [...setData]
    components.value = [...components.value, ...newFiles]
    props.data.components = [...components.value]
    isDragging.value = false
}

</script>

<template layout="App">
        <div class="col-span-full p-3" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
              <div class="text-center">
                <font-awesome-icon :icon="['fal', 'image']"  class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                <div class="mt-4 flex text-sm leading-6 text-gray-600">
                  <label for="fileInput" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                    <span>Click</span>
                    <input type="file" multiple name="file" id="fileInput" class="sr-only" @change="onChange" ref="fileInput"/>
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
              </div>
            </div>
          </div>
</template>
