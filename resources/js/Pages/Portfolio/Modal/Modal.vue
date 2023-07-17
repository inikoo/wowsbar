<script setup>
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'
import { ref, watch } from 'vue'
import { get } from 'lodash'

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  closeModal: {
    type: Function,
    required: true
  },
  data: {
    type: Object,
    required: false
  },
  changeLink : {
    type : Function,
  }
})

const submitForm = () => {
  props.changeLink(props.data,dataForm.value)
}

const dataForm = ref({
  label: null,
  target: null
})

// Watch for changes in props.data and update dataForm accordingly
watch(() => props.data, (newValue) => {
  dataForm.value.label = newValue == null ? null : get(newValue,['link','label'],null) 
  dataForm.value.target = newValue == null ? null : get(newValue,['link','target'],null) 
})

</script>
<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
        leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>    
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95">
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                Edit Banner
              </DialogTitle>
              <form @submit.prevent="submitForm">
              <div class="mt-2">
                <div class="border-b border-gray-900/10 pb-12">
                  <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="col-span-full">
                      <label for="label" class="block text-sm font-medium leading-6 text-gray-900">Label</label>
                      <div class="mt-2">
                        <input type="text" name="label" v-model="dataForm.label"
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" />
                      </div>
                    </div>
                    <div class="col-span-full">
                      <label for="target" class="block text-sm font-medium leading-6 text-gray-900">Link</label>
                      <div class="mt-2">
                        <input type="text" name="target" v-model="dataForm.target"
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
              <div class="mt-4">
                <button type="submit" @click="submitForm"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                  Save
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>



