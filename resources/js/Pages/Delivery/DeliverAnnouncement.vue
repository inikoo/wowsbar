<script setup lang='ts'>
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import BlankLayout from '@/Layouts/BlankLayout.vue'
import { nextTick, onMounted, provide, ref } from 'vue'

const props = defineProps<{
    announcement_data: {

    }
}>()

defineOptions({ layout: BlankLayout })


onMounted(async () => {
    const height = (document.body.clientHeight || 0) + 'px'; // or other method to get the height
    const dataSendToIframe = {
        height: height,
        // y: props.announcement_data?.container_properties?.position?.y 
    }
    console.log('send to Iframe', dataSendToIframe)
    window.parent.postMessage(dataSendToIframe, '*'); // Send height announcement to change height of iframe
    checkVisitTimes()
    
})

provide('isOnPublishState', true)

const withIframe = new URL(window.location.href).searchParams.get('iframe')


const checkVisitTimes = () => {
    const wowsbarAnn = JSON.parse(localStorage.getItem('wowsbar_announcement') || '{}');
    console.log('wosbarAnn', wowsbarAnn)
    if (!wowsbarAnn.visitedTimes) {
        // If no 'hasVisited' key, it's a new visitor
        wowsbarAnn.visitedTimes = 1;
    } else {
        wowsbarAnn.visitedTimes++;
    }
    localStorage.setItem('wowsbar_announcement', JSON.stringify(wowsbarAnn)); // Set the key for future visits
}
console.log('withIframe', withIframe)
</script>

<template>
    <div v-if="withIframe"
        :style="propertiesToHTMLStyle(announcement_data?.container_properties)"
    >
        <!-- <Promo1 :announcementData="announcement_data" /> -->
        <!-- <pre>{{announcement_data?.container_properties.position.y}}</pre> -->
        <component
            id="announcement_delivery_component"
            :is="getAnnouncementComponent(announcement_data?.template_code)"
            :announcementData="announcement_data"
        />
    </div>

    <component
        v-else
        id="announcement_delivery_component"
        :is="getAnnouncementComponent(announcement_data?.template_code)"
        :announcementData="announcement_data"
    />
    
</template>