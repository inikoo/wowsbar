<script setup lang="ts">
import BannerWorkshopComponent from '@/Components/Workshop/BannerWorkshopComponent.vue'

const props = defineProps<{
    selectedComponent: any
    modelValue: any
}>()

defineEmits<{
    (e: 'update:modelValue'): string
}>()


const dummyData = {
    data: {
        "delay": 5000,
        "common": {
            "corners": {
                "bottomLeft": {
                    "type": "slideControls"
                }
            }
        },
        "components": [
            {
                "image": {
                    "desktop": {
                        "id": 10,
                        "mime_type": "image/png",
                        "name": "plant 4",
                        "size": "822 KB",
                        "slug": "plant-4",
                        "source": {
                            "original": "http://localhost:8080/signature/bG9jYWw6Ly8vd293c2Jhci9hcHAvbWVkaWEvMTAvYzhhNzMxLnBuZw",
                            "webp": "http://localhost:8080/signature/bG9jYWw6Ly8vd293c2Jhci9hcHAvbWVkaWEvMTAvYzhhNzMxLnBuZw.webp"
                        },
                        "thumbnail": {
                            "original": "http://localhost:8080/signature/rs::0:48::/bG9jYWw6Ly8vd293c2Jhci9hcHAvbWVkaWEvMTAvYzhhNzMxLnBuZw",
                            "original_2x": "http://localhost:8080/signature/rs::0:96::/bG9jYWw6Ly8vd293c2Jhci9hcHAvbWVkaWEvMTAvYzhhNzMxLnBuZw",
                            "webp": "http://localhost:8080/signature/rs::0:48::/bG9jYWw6Ly8vd293c2Jhci9hcHAvbWVkaWEvMTAvYzhhNzMxLnBuZw.webp",
                            "webp_2x": "http://localhost:8080/signature/rs::0:96::/bG9jYWw6Ly8vd293c2Jhci9hcHAvbWVkaWEvMTAvYzhhNzMxLnBuZw.webp"
                        }
                    }
                },
                "layout": {
                    "centralStage": {
                        "subtitle": "aaaaa",
                        "title": "ffffff"
                    },
                    "imageAlt": "plant 4"
                },
                "ulid": "01HA9AZV5E7YTN0J139WJ9FNXX",
                "visibility": true
            }
        ],
        "hash": "5f2b0deec33a501be9c595dc03269dc6"
    },
    route: {
        "name": "tenant.portfolio.websites.show.banners.workshop.images.store",
        "arguments": {
            "portfolioWebsite": "hello",
            "banner": "test1"
        }
    },
    user: 'vika'
}

</script>

<template>
    <div class="flex justify-center items-center">
        <BannerWorkshopComponent v-if="selectedComponent.component === 'banner'" :data="dummyData.data" :imagesUploadRoute="dummyData.route" :user="dummyData.user" />
    </div>
</template>

<style>
</style>