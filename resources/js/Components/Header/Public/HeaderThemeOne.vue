<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'
import Image from "@/Components/Image.vue"


const props = defineProps<{
    data?: {
        data: {
            id: string
            name: string
            style: {
                fontSize: number
                left: number
                top: number
                color?: string
            }
            type: string
        }[]
        layout: {
            width: number
            height: number
        }
    }
}>()

// console.log(props.data)
// const logo = usePage().props.art.logo

const widthComponent = props.data?.layout?.width ?? 0
const heightComponent = props.data?.layout?.height ?? 0
const dataLength = props.data?.data?.length ?? 0

const getComponent = (type: string) => {
    const component = {
        'text': 'p',
        'search': Button
    }

    return component[type]
}

const calcPercentage = (total: number, amount: number) => {
    return (amount/total) * 100
}

</script>

<template>
    <!-- <pre>{{ data }}</pre> -->
    <div class="w-16">
        <Image :src="data.logo" />
    </div>
    <div class="mt-24 bg-gray-100 fixed z-10 flex justify-center items-center"
        :class="`w-full`"
    >
        <div :class="`isolate relative`"
            :style="['width: 100%', 'height: ' + heightComponent + 'px']"
        >
            <template v-if="data?.data">
                <div v-for="(component, index) in data.data" class="absolute"
                    :style="[
                        'top: ' + calcPercentage(heightComponent, component.style.top) + '%',
                        'left: ' + calcPercentage(widthComponent, component.style.left) + '%',
                        'z-index: ' + (dataLength - index),
                        'font-size: ' + component.style.fontSize + 'px',
                        'color: ' + (component.style.color ?? '#374151')
                    ]"
                >
                    <component :is="getComponent(component.type)">
                        {{ component.name }}
                    </component>
                </div>
            </template>
        </div>
    </div>
</template>