<script setup>
import { trans } from "laravel-vue-i18n"
import Button from "@/Components/Elements/Buttons/Button.vue"


const props = defineProps(['form'])

const generateThumbnail = (fileOrUrl) => {
    if (fileOrUrl instanceof File) {
        let fileSrc = URL.createObjectURL(fileOrUrl);
        setTimeout(() => { URL.revokeObjectURL(fileSrc) }, 1000);
        return fileSrc;
    } else if (typeof fileOrUrl === 'string') {
        return fileOrUrl;
    }
};

const onFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        props.form.image_source = file;
    }
};
</script>

<template>
    <div class="w-full">
        <!-- Avatar Button: Small view -->
        <!-- <div class="mt-1 lg:hidden">
            <div class="flex items-center">
                <div class="inline-block h-12 w-12 flex-shrink-0 overflow-hidden rounded-full" aria-hidden="true">
                    <img id="avatar_mobile" class="h-full w-full rounded-full"
                        :src="generateThumbnail(props.form.image_source)" />
                </div>
                <div class="ml-5 rounded-md shadow-sm">
                    <div
                        class="group relative flex items-center justify-center rounded-md border border-gray-300 py-2 px-3 focus-within:ring-2 focus-within:ring-sky-500 focus-within:ring-offset-2 hover:bg-gray-50">
                        <label for="input-avatar-small"
                            class="pointer-events-none relative text-sm font-medium leading-4 text-gray-700">
                            <span>{{ trans("Change") }}</span>
                        </label>
                        <input id="input-avatar-small" name="user-photo" type="file" @change="onFileChange"
                            class="absolute h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Avatar Button: Large view -->
        <div class="w-full relative space-y-4">
            <div class="w-full aspect-[16/4] overflow-hidden relative shadow-md">
                <img class="absolute top-1/2 -translate-y-1/2 w-full" :src="generateThumbnail(props.form.image_source)" alt="" />
            </div>
            
            <label
                class="relative inline-block"
                id="input-slide-large-mask" for="input-slide-large"
            >
                <input type="file" @change="onFileChange" id="input-slide-large" name="input-slide-large" accept="image/*"
                    class="absolute h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
                <Button :style="`tertiary`" class="" size="xs">{{ trans("Change image") }}</Button>
            </label>
        </div>
        <!-- <div v-if="props.form.errors" class="text-red-700">
        {{ props.form.errors }}
    </div> -->
    </div>
</template>
