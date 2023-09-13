<script setup lang="ts">
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faConstruction } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faConstruction)
</script>

<template>
    <div class="grid min-h-full place-items-center">
        <div class="text-center">
            <p class="text-base font-semibold text-gray-600">
            </p>
            <FontAwesomeIcon icon='fal fa-construction' class='h-12' aria-hidden='true' />
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-700 sm:text-5xl">
                Page is under maintenance.
            </h1>
            <p class="mt-4 text-base leading-7 text-gray-400">
                Sorry, we couldn’t provide the page you’re looking for.
            </p>

            <slot name="button">
                <!-- <div class="mt-10 flex items-center justify-center gap-x-6">
                    <Button :style="`primary`" href="#"
                        class="rounded-md px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Go
                        back home
                    </Button>
                    <a href="#" class="text-sm font-semibold text-gray-700">
                        Contact support
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div> -->
            </slot>
        </div>
    </div>
</template>

<style scoped></style>