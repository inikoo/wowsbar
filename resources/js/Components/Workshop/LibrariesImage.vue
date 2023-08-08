<script setup>
import { ref } from 'vue'
const products = [
    {
        id: 1,
        name: "Leather Long Wallet",
        color: "Natural",
        price: "$75",
        href: "#",
        imageSrc:
            "https://tailwindui.com/img/ecommerce-images/home-page-04-trending-product-02.jpg",
        imageAlt: "Hand stitched, orange leather long wallet.",
    },
    {
        id: 2,
        name: "Basic Tee",
        href: "#",
        imageSrc:
            "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
        imageAlt: "Front of men's Basic Tee in black.",
        price: "$35",
        color: "Black",
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
        name: "Basic Tee",
        href: "#",
        imageSrc:
            "https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg",
        imageAlt: "Front of men's Basic Tee in black.",
        price: "$35",
        color: "Black",
    },
];

const tabs = ['Wood', 'Sky', 'Mountain', 'Animal']
const activeTab = ref(tabs[0]) // Tab aktif saat ini

const changeTab = (tab) => {
    activeTab.value = tab

}

</script>

<template>
     <div class="md:flex md:items-center md:justify-between">
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
      <ul class="flex flex-wrap -mb-px">
        <li v-for="(tab, index) in tabs" :key="tab" class="mr-2">
          <a
            href="#"
            :class="{
              'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': tab !== activeTab,
              'inline-block p-4 text-orange-500 border-b-2 border-orange-500 rounded-t-lg dark:text-orange-500 dark:border-orange-500': tab === activeTab
            }"
            @click="changeTab(tab)"
          >
            {{ tab }}
          </a>
        </li>
      </ul>
    </div>
  </div>

    <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 md:grid-cols-4 md:gap-y-0 lg:gap-x-8">
        <div v-for="product in products" :key="product.id" class="group relative">
            <div class="h-28 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75">
                <img :src="product.imageSrc" :alt="product.imageAlt" class="h-full w-full object-cover object-center" />
            </div>
            <h3 class="mt-4 text-sm text-gray-700 flex justify-center items-center">
                <span>
                    <span class="absolute inset-0" />
                    {{ product.name }}
                </span>
            </h3>
        </div>
    </div>

   
</template>
