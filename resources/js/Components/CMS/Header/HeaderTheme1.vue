<script setup lang="ts">
import { ref } from 'vue'
import HyperLink from "../Fields/Hyperlink.vue"

const props = defineProps<{
    data: Object
}>()

</script>

<template>
    <div class="bg-white">
        <header class="pa">
            <nav class="mx-auto flex  items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img class="h-8 w-auto" :src="data.img" alt="" />
                    </a>
                </div>
                <div class="flex gap-3">
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <!-- <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Appointment</a> -->
                        <HyperLink :formList="{
                            label: 'label',
                            link: 'link',
                        }" label="label" :data="data.appointment" :useDelete="false"
                            cssClass="text-sm font-semibold leading-6 text-gray-900" />
                    </div>
                    <div class="text-gray-300">|</div>
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Log in <span
                                aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</template>
  
