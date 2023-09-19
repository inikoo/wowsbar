<script setup lang="ts">
import { reactive } from 'vue'
import BannerWorkshopComponent from '@/Components/Workshop/BannerWorkshopComponent.vue'
import TestimonialsWorkshopComponent from '@/Components/CMS/BlocksContent/Testimonials/TestimonialsWorkshopComponent.vue'
import BlogSection from '@/Components/CMS/BlocksContent/BlogSections/BlockSectionComponent.vue'
import StatsWorkshopComponent from "@/Components/CMS/BlocksContent/Stats/StatsWorkshopComponent.vue"
import HeroWorkshopComponent from '@/Components/CMS/BlocksContent/Hero/HeroWorkshopComponent.vue'

import Hero from '@/Components/CMS/BlocksContent/Hero/index.vue'
import CardContent from '@/Components/CMS/BlocksContent/CardContent/CardContentWorkshopComponent.vue'
import CTABlocks from '@/Components/CMS/BlocksContent/CTABlocks/CTABlocksWorkshopComponent.vue'
import COntentDescription from '@/Components/CMS/BlocksContent/ContentDescription/index.vue'
import Pricing from '@/Components/CMS/BlocksContent/Pricing/PricingWorkshopCOmponent.vue'
import Testimonials from '@/Components/CMS/BlocksContent/Testimonials/index.vue'

const props = defineProps<{
    selectedComponent: any
    modelValue: any
}>()

defineEmits<{
    (e: 'update:modelValue'): string
}>()


const dummyDataBanner = {
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
        "name": "customer.portfolio.websites.show.banners.workshop.images.store",
        "arguments": {
            "portfolioWebsite": "hello",
            "banner": "test1"
        }
    },
    user: 'vika'
}

const dataTheme = reactive({
    text: 1,
    testimonial: 2,
    stats: 2,
    blogSection : 1
})

</script>

<template>
    <div class="relative flex justify-center items-center w-full">
        <TestimonialsWorkshopComponent v-if="selectedComponent.component === 'testimonial'" />
        <BannerWorkshopComponent v-if="selectedComponent.component === 'banner'" :data="dummyDataBanner.data" :imagesUploadRoute="dummyDataBanner.route" :user="dummyDataBanner.user" />
        <StatsWorkshopComponent v-if="selectedComponent.component === 'stats'"/>
        <BlogSection v-if="selectedComponent.component === 'blogSection'" />
        <CTABlocks v-if="selectedComponent.component === 'CTA'" />
        <CardContent v-if="selectedComponent.component === 'CardContent'" />
        <Pricing v-if="selectedComponent.component === 'pricing'" />
        <HeroWorkshopComponent v-if="selectedComponent.component === 'hero'" />
    </div>
   
    <!-- <Testimonials :theme="3"></Testimonials> -->
</template>
