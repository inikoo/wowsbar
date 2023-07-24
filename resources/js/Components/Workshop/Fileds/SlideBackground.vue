<script setup>
import { trans } from "laravel-vue-i18n"
import { ref } from "vue"

const props = defineProps(['form'])

const generateThumbnail = (fileOrUrl) => {
  if (fileOrUrl instanceof File) {
    let fileSrc = URL.createObjectURL(fileOrUrl);
    setTimeout(() => { URL.revokeObjectURL(fileSrc) }, 1000);
    return fileSrc;
  } else if (typeof fileOrUrl === 'string') {
    return getImageUrl(fileOrUrl);
  }
};

const getImageUrl = (name) => {
  return new URL(`@/../../../../../art/banner/` + name, import.meta.url).href;
};

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    props.form.imageSrc = file;
  }
};
</script>

<template>
  <div class="w-fit">
    <!-- Avatar Button: Small view -->
    <div class="mt-1 lg:hidden">
      <div class="flex items-center">
        <div class="inline-block h-12 w-12 flex-shrink-0 overflow-hidden rounded-full" aria-hidden="true">
          <img id="avatar_mobile" class="h-full w-full rounded-full" :src="generateThumbnail(props.form.imageSrc)" />
        </div>
        <div class="ml-5 rounded-md shadow-sm">
          <div class="group relative flex items-center justify-center rounded-md border border-gray-300 py-2 px-3 focus-within:ring-2 focus-within:ring-sky-500 focus-within:ring-offset-2 hover:bg-gray-50">
            <label for="input-avatar-small" class="pointer-events-none relative text-sm font-medium leading-4 text-gray-700">
              <span>{{ trans("Change") }}</span>
            </label>
            <input id="input-avatar-small" name="user-photo" type="file" @change="onFileChange" class="absolute h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
          </div>
        </div>
      </div>
    </div>

    <!-- Avatar Button: Large view -->
    <div class="relative hidden overflow-hidden rounded-full lg:block">
      <img class="relative h-40 w-40 rounded-full" :src="generateThumbnail(props.form.imageSrc)" alt="" />
      <label id="input-avatar-large-mask" for="input-avatar-large" class="absolute inset-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 text-sm font-medium text-white opacity-0 hover:opacity-100">
        <span>{{ trans("Change") }}</span>
        <input type="file" @change="onFileChange" id="input-avatar-large" name="input-avatar-large" accept="image/*" class="absolute inset-0 h-full w-full cursor-pointer rounded-md border-gray-300 opacity-0" />
      </label>
    </div>
    <div v-if="props.form.errors.avatar" class="text-red-700">
      {{ props.form.errors.avatar }}
    </div>
  </div>
</template>
