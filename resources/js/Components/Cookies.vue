<script setup lang="ts">
import { onMounted } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCookieBite } from '@fas'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { ref, Ref, computed } from 'vue'
library.add(faCookieBite, faTimes)

// Make it True so the popup is disappear at first load (before Timeout on OnMounted)
const isCookieAccepted = ref(true)

// Save to Cookie (browser)
const setCookie = (cookieName: string, cookieValue: string, expDay: number) => {
    const d = new Date()
    d.setTime(d.getTime() + (expDay * 24 * 60 * 60 * 1000))
    document.cookie = cookieName + "=" + cookieValue + ";expires=" + d.toUTCString() + ";path=/"
}

// Get data from Cookie (on browser), is it exist? if no return empty string
const getCookie = (cookieName: string) => {
    let name = cookieName + "="
    let decodedCookie = decodeURIComponent(document.cookie)
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) == ' ') {
            c = c.substring(1)
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length)
        }
    }
    return ""
}

const checkCookie = (cookieName: string) => {
    return getCookie(cookieName) != "" ? true : false
}

// If click the 'Accept all cookies'
const acceptAllCokies = () => {
    setCookie("isCookieAccepted", 'true', 999999999999)
    isCookieAccepted.value = true
    // Add more function
}

// If click the 'Necessary cookies only'
const acceptNecessaryCokies = () => {
    setCookie("isCookieAccepted", 'true', 999999999999)
    isCookieAccepted.value = true
    // Add more function below
}

onMounted(() => {
    setTimeout(() => {
        // The popup will appear after 5 seconds if it would
        isCookieAccepted.value = checkCookie("isCookieAccepted")
    }, 5000);
})
</script>

<template>
    <Transition name="cookies">
        <div v-if="!isCookieAccepted" class="fixed bottom-[3%] right-4 bg-white rounded-lg shadow-md pt-8 pb-6 px-6 flex flex-col justify-start gap-y-2 ring-1 ring-gray-200">
            <!-- <div @click="" class="absolute top-2 right-2 text-gray-400 w-8 h-8 grid place-items-center rounded-full cursor-pointer hover:text-gray-600">
                <FontAwesomeIcon icon='fal fa-times' class='' aria-hidden='true' />
            </div> -->
            <div class="flex gap-x-1">
                <FontAwesomeIcon icon='fas fa-cookie-bite' class='text-yellow-700' size="xl" aria-hidden='true' />
                <div class="text-yellow-800 text-xl font-bold">Yet another cookie disclaimer!</div>
            </div>
            <div class="text-sm text-gray-600">We use cookies to improve your experience.<br>By using Wowsbar you're agreeing to the collection<br>of data as describer in our Cookie Policy.</div>
            <div class="flex gap-x-4 mt-3">
                <div @click="acceptNecessaryCokies" class="py-1 px-4 ring-1 ring-gray-400 rounded-full cursor-pointer bg-white hover:bg-gray-100 active:bg-gray-200 select-none">Necessary cookies only</div>
                <div @click="acceptAllCokies" class="py-1 px-4 text-gray-100 rounded-full cursor-pointer button-wowsbar">Accept all cookies</div>
            </div>
        </div>
    </Transition>
</template>

<style scoped lang="scss">
.cookies-enter-active,
.cookies-leave-active {
    transition: all 0.2s ease-in-out
}

.cookies-enter-from,
.cookies-leave-to {
    transform: translateY(100%);
}
</style>
