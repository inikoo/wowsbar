<script setup lang="ts">
import { ref } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import PureTextarea from '@/Components/Pure/PureTextarea.vue'

const textareaValue = ref('')
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
                        <div class="sm:col-span-4">
                            <label for="username" class="block text-sm font-bold leading-6">Contact</label>
                            <div class="mt-2">
                                <div
                                    class="px-2 flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                    <input type="email" name="username" id="username" autocomplete="username"
                                        class="block flex-1 border-0 bg-transparent py-1.5 pl-1 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                        placeholder="janesmith">
                                </div>
                            </div>
                        </div>

                        <!-- Textarea -->
                        <div class="col-span-full">
                            <label for="about" class="block text-sm font-bold leading-6">About</label>
                            <div class="mt-2">
                                <PureTextarea v-model="textareaValue" inputName="inputTextarea"
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