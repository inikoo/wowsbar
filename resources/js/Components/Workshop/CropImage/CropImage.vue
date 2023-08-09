<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination, Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import CropComponents from "./CropComponents.vue";
import { trans } from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue";

const props = defineProps<{
    data: FileList;
    imagesUploadRoute : object
}>();

const setData2 = () => {
    const data = [];
    for (const set of props.data) {
        data.push({
            originalFile: set,
        });
    }
    return data;
};

const setData = ref(setData2());


const generateThumbnail = (file) => {
    if (
        file.originalFile &&
        file.originalFile instanceof File &&
        !file.imagePosition
    ) {
        let fileSrc = URL.createObjectURL(file.originalFile);
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc);
        }, 1000);
        return fileSrc;
    } else if (file.imagePosition) {
        return file.imagePosition.canvas.toDataURL();
    } else {
        return file.originalFile;
    }
};


const addComponent = async () => {
  const SendData = [];

  const processItem = async (item) => {
    return new Promise((resolve, reject) => {
      if (item.imagePosition) {
        item.imagePosition.canvas.toBlob((blob) => {
          SendData.push(blob);
          resolve();
        });
      } else {
        resolve();
      }
    });
  };

  await Promise.all(setData.value.map(processItem));
  console.log(SendData)
  
    try {
        const response = await axios.post(route(props.imagesUploadRoute.name, props.imagesUploadRoute.arguments),
            { 'images':  SendData},
            {
                headers: { 'Content-Type': 'multipart/form-data' }
            }
        )
        
        console.log(response.data)
               

    } catch (error) {
        // Handle any errors that might occur during the POST request
        console.error(error);
    }
};



const current = ref(0);
const setCurrent = (key) => {
    current.value = key;
};
const swiperRef = ref();
</script>

<template>
    <div
        class="overflow-hidden relative border border-gray-300 shadow-md w-full aspect-[2/1] md:aspect-[3/1] lg:aspect-[4/1]"
    >
        <Swiper
            ref="swiperRef"
            :slideToClickedSlide="true"
            :spaceBetween="-1"
            :slidesPerView="1"
            :centeredSlides="true"
            :loop="true"
            :autoplay="{
                delay: 100000,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
            }"
            :navigation="false"
            :modules="[Autoplay, Pagination, Navigation]"
            class="mySwiper"
        >
            <SwiperSlide v-for="(component, index) in setData" :key="index">
                <div class="relative w-full h-full overflow-hidden">
                    <img
                        :src="generateThumbnail(component)"
                        alt=""
                        class="absolute"
                    />
                </div>
            </SwiperSlide>
        </Swiper>
    </div>
    <div>
        <div
            class="divide-y divide-gray-200 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x overflow-auto h-full"
        >
            <!-- Left Tab: Navigation -->
            <aside class="py-0 lg:col-span-3 lg:h-full">
                <nav role="navigation" class="space-y-1">
                    <ul class="flex justify-between sm:block">
                        <li
                            v-for="(item, key) in setData"
                            @click="setCurrent(key)"
                            :class="[
                                'group cursor-pointer sm:border-l-4 px-6 sm:px-3 py-2 flex items-center justify-center sm:justify-start text-sm font-medium',
                                key == current
                                    ? 'bg-gray-300 sm:bg-gray-100 sm:border-orange-500 sm:hover:bg-gray-100 text-gray-600'
                                    : 'border-transparent hover:bg-gray-300/40 sm:hover:bg-gray-50 text-gray-500 hover:text-gray-700',
                            ]"
                            :aria-current="key === current ? 'page' : undefined"
                        >
                            <span
                                class="hidden sm:inline capitalize truncate"
                                >{{ item.originalFile.name }}</span
                            >
                        </li>
                    </ul>
                </nav>
            </aside>
            <div
                class="px-4 sm:px-6 md:px-4 pt-6 xl:pt-4 col-span-9 flex flex-grow justify-center"
            >
                <div class="flex flex-col w-full">
                    <dl class="pb-4 sm:pb-5 sm:gap-4 w-full">
                        <dd class="flex text-sm text-gray-700 sm:mt-0 w-full">
                            <div class="relative flex-grow">
                                <CropComponents :data="setData[current]" />
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <Button :style="`secondary`" icon="fas fa-upload" class="relative" size="xs" @click="addComponent">
                    {{ trans("Save Image") }}
                  
                </Button>
</template>

<style lang="scss">
.swiper {
    @apply w-full h-full;
}

.swiper-slide {
    @apply bg-gray-200;
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    @apply w-full h-auto;
    display: block;
    object-fit: cover;
}
</style>
