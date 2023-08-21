<script setup>
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudUpload, faImagePolaroid } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCloudUpload, faImagePolaroid)

const tabs = [
    {
        name: 'General',
        images: [
            {
                id: 1,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 2,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 3,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 4,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 1,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 2,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 3,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 4,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 1,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 2,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 3,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 4,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
        ]
    },
    {
        name: 'Wood',
        images: [
            {
                id: 4,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 5,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 6,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
        ]
    },
    {
        name: 'Beach',
        images: [
            {
                id: 7,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 8,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 9,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            }
        ]
    },
    {
        name: 'Sky',
        images: [
            {
                id: 10,
                name: "Basic Tee",
                href: "#",
                imageSrc:
                    "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
                imageAlt: "Front of men's Basic Tee in black.",
                price: "$35",
                color: "Black",
            },
            {
                id: 11,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
            {
                id: 12,
                name: 'Leather Long Wallet',
                color: 'Natural',
                price: '$75',
                href: '#',
                imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg',
                imageAlt: 'Hand stitched, orange leather long wallet.',
            },
        ]
    }
]

const activeTab = ref(0)
const activeSidebar = ref(0)

const changeTab = (index) => {
    activeTab.value = index
}

</script>

<template>
    <div class="flex gap-x-2">
        <!-- Sidebar -->
        <section class="bg-gray-50 w-64">
            <div class="py-2 px-2 cursor-pointer hover:bg-gray-200 text-sm flex gap-x-1 items-center text-gray-600 hover:text-gray-700"
                :class="[activeSidebar === 0 ? 'tabNavigationActive' : 'tabNavigation']"
                @click="activeSidebar = 0"
            >
                <FontAwesomeIcon icon='fal fa-cloud-upload' class='w-4 h-4 text-gray-400' aria-hidden='true' />
                <span>Uploaded Images</span>
            </div>
            <div class="py-2 px-2 cursor-pointer hover:bg-gray-200 text-sm flex gap-x-1 items-center text-gray-600 hover:text-gray-700"
                :class="[activeSidebar === 1 ? 'tabNavigationActive' : 'tabNavigation']"
                @click="activeSidebar = 1"
            >
                <FontAwesomeIcon icon='fal fa-image-polaroid' class='w-4 h-4 text-gray-400' aria-hidden='true' />
                <span>Stock Images</span>
            </div>
        </section>

        <!-- Main content -->
        <section class="bg-gray-50 w-full rounded-r-md">
            <!-- Tab -->
            <div class="md:flex md:items-center md:justify-between">
                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px">
                        <li v-for="(tab, index) in tabs" :key="tab" class="mr-2">
                            <a href="#" :class="{
                                'inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': index !== activeTab,
                                'inline-block py-2 px-4 text-orange-500 border-b-2 border-orange-500 rounded-t-lg dark:text-orange-500 dark:border-orange-500': index === activeTab
                            }" @click="changeTab(index)">
                                {{ tab.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Images list -->
            <div class="pt-6 pl-4 grid grid-cols-4 gap-x-3 gap-y-4">
                <div v-for="image in tabs[activeTab].images" :key="image.id" class="opacity-75 hover:opacity-100 cursor-pointer relative flex flex-col gap-y-1">
                    <div class="flex-none aspect-[4/1] bg-white overflow-hidden rounded">
                        <img :src="image.imageSrc" :alt="image.imageAlt" class="h-full w-full object-cover object-center" />
                    </div>
                    <h3 class="text-sm text-gray-700 flex justify-center items-center">
                        {{ image.name }}
                    </h3>
                </div>
            </div>
        </section>
    </div>
</template>
