<script setup lang="ts">
import { ref } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import PureTextarea from '@/Components/Pure/PureTextarea.vue'
import PureInput from '@/Components/Pure/PureInput.vue'

const props = defineProps<{
    data: {
        textareaValue: string
        inputValue: string
    }
}>()

</script>

<template>
    <div class="grid grid-cols-2 divide-x divide-gray-300">
        <div class="flex items-center justify-center">
            <Button label="Appointment"></Button>
        </div>

        <div class="flex px-7 py-4">
            <form>
                <div class="space-y-4">
                    <p class="mt-1 text-sm leading-6 text-gray-500 italic">
                        This information will be displayed publicly so be careful what you share.
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Contact email -->
                        <div class="sm:col-span-6">
                            <label for="inputContact" class="block text-sm font-bold leading-6">Contact</label>
                            <div class="mt-2">
                                <PureInput v-model="data.inputValue"
                                    inputName="inputContact"
                                    type="email"
                                    placeholder="Jane Smith"
                                    :maxLength="50"
                                    :copyButton="true"
                                />
                            </div>
                        </div>

                        <!-- Textarea -->
                        <div class="col-span-full">
                            <label for="inputTextarea" class="block text-sm font-bold leading-6">About</label>
                            <div class="mt-2">
                                <PureTextarea v-model="data.textareaValue" inputName="inputTextarea"
                                    placeholder="Write a few sentences about yourself">
                                </PureTextarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <Button size="xs">Submit</Button>
                </div>
            </form>

        </div>
    </div>
</template>