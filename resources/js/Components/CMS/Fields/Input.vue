<script setup lang="ts">
import { ref, onMounted } from 'vue'
const props = defineProps<{
  data: Object,
  keyValue:String
  classCss?:{
    type: any,
    default : "text-sm font-bold leading-6 text-gray-700 capitalize"
  }
  styleCss?: any
}>()

const editMode = ref(false)
const inputValue = ref(props.data[props.keyValue])
const inputRef = ref<HTMLInputElement | null>(null)

const changeEditMode = () => {
  editMode.value = true
  setTimeout(() => {
    if (inputRef.value) {
      inputRef.value.focus()
    }
  }, 0)
}

const handleInputBlur = () => {
  editMode.value = false
  props.data[props.keyValue] = inputValue.value
}

onMounted(() => {
  if (editMode.value && inputRef.value) {
    inputRef.value.focus()
  }
})

</script>

<template>
  <div>
    <template v-if="!editMode">
      <div @click="changeEditMode" :class="classCss" :style="styleCss" >{{ data[keyValue] }}</div>
    </template>
    <template v-else>
      <input
        ref="inputRef"
        v-model="inputValue"
        @blur="handleInputBlur"
        class="w-fit h-full"
        :class="classCss" 
        :style="styleCss"
      />
    </template>
  </div>
</template>


