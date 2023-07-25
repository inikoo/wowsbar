<script setup lang="ts">
const props = defineProps<{
    form: any,
    fieldName: string,
    options?: any,
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
}>()
console.log(props)

const buttonType = [
  {label : 'Footer', value : 'footer'},
  {label : 'Corner text', value : 'cornerText'},
  {label : 'Link button', value : 'linkButton'},
  {label : 'Text', value : 'text'},
]
</script>


<template>
  <div class="h-64">
    <div class="w-full h-full flex">
      <div class="w-1/2 flex flex-col">
        <div class="border flex-grow hover:bg-blue-200">Top left</div>
        <div class="border flex-grow hover:bg-blue-200">Bottom left</div>
      </div>
      <div class="w-1/2 flex flex-col">
        <div class="border flex-grow hover:bg-blue-200">Top right</div>
        <div class="border flex-grow hover:bg-blue-200">Bottom right</div>
      </div>
    </div>

    <div class="w-full flex mt-3">
      <span class="isolate flex w-full rounded-md shadow-sm">
        <!-- Use v-for to loop through buttonType array -->
        <button
          v-for="item in buttonType"
          :key="item.value"
          type="button"
          class="flex-grow relative inline-flex items-center justify-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10"
        >
          {{ item.label }}
        </button>
      </span>
    </div>


  </div>
</template>
