<script setup lang="ts">
import HyperLink from "../Fields/Hyperlink.vue"

const props = defineProps<{
    data: Object
}>()



const changeLogo = async (element) => {
    const file = element.target.files[0];
    if (file) {
        props.data.img = URL.createObjectURL(file);
    }
};

</script>

<template>
    <div class="bg-white">
        <header class="pa">
            <nav class="mx-auto flex  items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <label for="faviconUpload" class="flex justify-center items-center bg-white cursor-pointer">
                        <input type="file" id="faviconUpload" accept="image/*" style="display: none" @change="changeLogo" />
                        <img class="h-8 w-auto" :src="data.img" alt="" />
                    </label>
                </div>
                <div class="flex gap-3">
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <HyperLink :formList="{
                            label: 'label',
                            link: 'link',
                        }" label="label" :data="data.appointment" :useDelete="false"
                            cssClass="text-sm font-semibold leading-6 text-gray-900" />
                    </div>
                    <div class="text-gray-300">|</div>
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="#" class="text-sm font-semibold leading-6 text-gray-900">
                            <HyperLink :formList="{
                                label: 'label',
                                link: 'link',
                            }" label="label" :data="data.logIn" :useDelete="false"
                                cssClass="text-sm font-semibold leading-6 text-gray-900" />
                        </a>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</template>
  
