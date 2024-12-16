<script setup lang="ts">
import Editor2 from '@/Components/Forms/Fields/BubleTextEditor/EditorV2.vue'
import { EditorContent } from '@tiptap/vue-3'
import { get, set } from 'lodash';

const props = defineProps<{
    containerClass?: string
}>()
const model = defineModel<{ text: string }>()

defineOptions({
    inheritAttrs: false
})

</script>

<template>
  <div :class="containerClass" class="w-full">
    <Editor2
      :modelValue="get(model, 'text', '')"
      @update:modelValue="(e) => set(model, 'text', e)"
      v-bind="$attrs"
    >
      <template #editor-content="{ editor }">
        <div class="bg-gray-200 editor-wrapper border-2 border-gray-300 rounded-lg px-3 py-2 shadow-sm focus-within:border-blue-400">
          <EditorContent :editor="editor" class="focus:outline-none" />
        </div>
      </template>
    </Editor2>
  </div>
</template>

<style scoped>
.editor-wrapper {
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

:deep(.editor-class) {
  min-height: 150px;
  font-size: 1rem;
  line-height: 1.5;
}
</style>
