<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Hero from '@/Components/Marketing/Hero.vue';
import { Hero as heroType } from "@/types/hero";
import { ref } from 'vue'
import {
  Dialog,
  DialogPanel,
  Popover,
  PopoverButton,
  PopoverGroup,
  PopoverPanel,
  Tab,
  TabGroup,
  TabList,
  TabPanel,
  TabPanels,
} from '@headlessui/vue'
import {
  Bars3Icon,
  MagnifyingGlassIcon,

  ShoppingBagIcon,

} from '@heroicons/vue/24/outline'

import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,


} from '@headlessui/vue'

import { MinusIcon, PlusIcon } from '@heroicons/vue/24/outline'

const product = {
  name: 'Zip Tote Basket',
  price: '$140',
  rating: 4,
  images:
  {
    id: 1,
    name: 'Angled view',
    src: 'https://tailwindui.com/img/ecommerce-images/product-page-03-product-01.jpg',
    alt: 'Angled front view with bag zipped and handles upright.',
  },
  // More images...
  colors: [
    { name: 'Washed Black', bgColor: 'bg-gray-700', selectedColor: 'ring-gray-700' },
    { name: 'White', bgColor: 'bg-white', selectedColor: 'ring-gray-400' },
    { name: 'Washed Gray', bgColor: 'bg-gray-500', selectedColor: 'ring-gray-500' },
  ],
  description: `
    <p>The Zip Tote Basket is the perfect midpoint between shopping tote and comfy backpack. With convertible straps, you can hand carry, should sling, or backpack this convenient and spacious bag. The zip top and durable canvas construction keeps your goods protected for all-day use.</p>
  `,
  details: [
    {
      name: 'SEO',
      items: [
        'As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks',
      ],
    },
    {
      name: 'PPC',
      items: [
        'As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks',
      ],
    },
    {
      name: 'SSM',
      items: [
        'As PPC specialists, our proven PPC system allows you to put your products and services in front of your potential customer at the time they are making a buying decision. We work with you to create high performing results-driven campaigns focused on delivering conversions, not just clicks',
      ],
    },
  ],
}

const navigation = {
  categories: [
    {
      name: 'Women',
      featured: [
        {
          name: 'New Arrivals',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-category-01.jpg',
          imageAlt: 'Models sitting back to back, wearing Basic Tee in black and bone.',
        },
        {
          name: 'Basic Tees',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-category-02.jpg',
          imageAlt: 'Close up of Basic Tee fall bundle with off-white, ochre, olive, and black tees.',
        },
        {
          name: 'Accessories',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-category-03.jpg',
          imageAlt: 'Model wearing minimalist watch with black wristband and white watch face.',
        },
        {
          name: 'Carry',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-category-04.jpg',
          imageAlt: 'Model opening tan leather long wallet with credit card pockets and cash pouch.',
        },
      ],
    },
    {
      name: 'Men',
      featured: [
        {
          name: 'New Arrivals',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-01-men-category-01.jpg',
          imageAlt: 'Hats and sweaters on wood shelves next to various colors of t-shirts on hangers.',
        },
        {
          name: 'Basic Tees',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-01-men-category-02.jpg',
          imageAlt: 'Model wearing light heather gray t-shirt.',
        },
        {
          name: 'Accessories',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-01-men-category-03.jpg',
          imageAlt:
            'Grey 6-panel baseball hat with black brim, black mountain graphic on front, and light heather gray body.',
        },
        {
          name: 'Carry',
          href: '#',
          imageSrc: 'https://tailwindui.com/img/ecommerce-images/mega-menu-01-men-category-04.jpg',
          imageAlt: 'Model putting folded cash into slim card holder olive leather wallet with hand stitching.',
        },
      ],
    },
  ],
  pages: [
    { name: 'Company', href: '#' },
    { name: 'Stores', href: '#' },
  ],
}

const collections = [
  {
    name: 'Handcrafted Collection',
    href: '#',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-01-collection-01.jpg',
    imageAlt: 'Brown leather key ring with brass metal loops and rivets on wood table.',
    description: 'Keep your phone, keys, and wallet together, so you can lose everything at once.',
  },
  {
    name: 'Organized Desk Collection',
    href: '#',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-01-collection-02.jpg',
    imageAlt: 'Natural leather mouse pad on white desk next to porcelain mug and keyboard.',
    description: 'The rest of the house will still be a mess, but your desk will look great.',
  },
  {
    name: 'Focus Collection',
    href: '#',
    imageSrc: 'https://tailwindui.com/img/ecommerce-images/home-page-01-collection-03.jpg',
    imageAlt: 'Person placing task list card into walnut card holder next to felt carrying case on leather desk pad.',
    description: 'Be more productive than enterprise project managers with a single piece of paper.',
  },
]
const footerNavigation = {
  shop: [
    { name: 'Bags', href: '#' },
    { name: 'Tees', href: '#' },
    { name: 'Objects', href: '#' },
    { name: 'Home Goods', href: '#' },
    { name: 'Accessories', href: '#' },
  ],
  company: [
    { name: 'Who we are', href: '#' },
    { name: 'Sustainability', href: '#' },
    { name: 'Press', href: '#' },
    { name: 'Careers', href: '#' },
    { name: 'Terms & Conditions', href: '#' },
    { name: 'Privacy', href: '#' },
  ],
  account: [
    { name: 'Manage Account', href: '#' },
    { name: 'Returns & Exchanges', href: '#' },
    { name: 'Redeem a Gift Card', href: '#' },
  ],
  connect: [
    { name: 'Contact Us', href: '#' },
    { name: 'Twitter', href: '#' },
    { name: 'Instagram', href: '#' },
    { name: 'Pinterest', href: '#' },
  ],
}



const props = defineProps<{
  hero: heroType
}>()
</script>

<template layout="Public">
  <!-- <Head title="Homepage"/> -->
  <!-- <Hero :hero="hero"></Hero> -->

  <div class="bg-white">

    <!-- Hero section -->
    <div class="relative bg-gray-900">
      <!-- Decorative image and overlay -->
      <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
        <img src="https://tailwindui.com/img/ecommerce-images/home-page-01-hero-full-width.jpg" alt=""
          class="h-full w-full object-cover object-center" />
      </div>
      <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50" />

      <div class="relative mx-auto flex max-w-3xl flex-col items-center px-6 py-32 text-center sm:py-64 lg:px-0">
        <h1 class="text-4xl font-bold tracking-tight text-white lg:text-6xl">WOWSBAR</h1>
        <p class="mt-4 text-xl text-white">The new arrivals have, well, newly arrived. Check out the latest options from
          our summer small-batch release while they're still in stock.</p>
        <a href="#"
          class="mt-8 inline-block rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100">Shop
          New Arrivals</a>
      </div>
    </div>

    <main>
      <!-- Category section -->
      <section aria-labelledby="category-heading" class="pt-5">
        <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
          <div class="min-h-300 max-h-[32rem] overflow-hidden">
            <img :src="product.images.src" :alt="product.images.alt"
              class="h-full w-full object-cover object-center sm:rounded-lg" />
          </div>

          <div class="mt-10 px-4 sm:mt-16 lg:mt-0 p-5">
            <section aria-labelledby="details-heading" class="mt-10">
              <div class="pb-5">
                <h2 class="text-2xl font-medium" id="details-heading">Ours Services</h2>
                <p class="text-xs text-gray-700">Get the AW advantage of over twenty years of experience working with
                  Pay-Per-Click, email marketing and SEO.</p>
              </div>

              <div class="divide-y divide-gray-200 border-t p-2.5">
                <Disclosure as="div" v-for="detail in product.details" :key="detail.name" v-slot="{ open }">
                  <h3>
                    <DisclosureButton class="group relative flex w-full items-center justify-between py-6 text-left">
                      <span :class="[open ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-medium']">{{ detail.name
                      }}</span>
                      <span class="ml-6 flex items-center">
                        <PlusIcon v-if="!open" class="block h-6 w-6 text-gray-400 group-hover:text-gray-500"
                          aria-hidden="true" />
                        <MinusIcon v-else class="block h-6 w-6 text-indigo-400 group-hover:text-indigo-500"
                          aria-hidden="true" />
                      </span>
                    </DisclosureButton>
                  </h3>
                  <DisclosurePanel as="div" class="prose prose-sm pb-6">
                    <ul role="list">
                      <li v-for="item in detail.items" :key="item">{{ item }}</li>
                    </ul>
                  </DisclosurePanel>
                </Disclosure>
              </div>
            </section>
          </div>
        </div>
      </section>

      <!-- Featured section -->
      <section aria-labelledby="social-impact-heading" class="pt-10 sm:px-6  lg:px-8">
        <div class="relative overflow-hidden rounded-lg">
          <div class="absolute inset-0">
            <img src="https://tailwindui.com/img/ecommerce-images/home-page-01-feature-section-01.jpg" alt=""
              class="h-full w-full object-cover object-center" />
          </div>
          <div class="relative bg-gray-900 bg-opacity-75 px-6 py-32 sm:px-12 sm:py-40 lg:px-16">
            <div class="relative mx-auto flex max-w-3xl flex-col items-center text-center">
              <h2 id="social-impact-heading" class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                <span class="block sm:inline">Level up</span>
                <span class="block sm:inline">your desk</span>
              </h2>
              <p class="mt-3 text-xl text-white">Make your desk beautiful and organized. Post a picture to social media
                and watch it get more likes than life-changing announcements. Reflect on the shallow nature of existence.
                At least you have a really nice desk setup.</p>
              <a href="#"
                class="mt-8 block w-full rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100 sm:w-auto">Shop
                Workspace</a>
            </div>
          </div>
        </div>
      </section>

      <!-- Collection section -->
      <section aria-labelledby="collection-heading"
        class="mx-auto max-w-xl px-4 pt-24 sm:px-6 sm:pt-32 lg:max-w-7xl lg:px-8 py-24">
        <h2 id="collection-heading" class="text-2xl font-bold tracking-tight text-gray-900">Shop by Collection</h2>
        <p class="mt-4 text-base text-gray-500">Each season, we collaborate with world-class designers to create a
          collection inspired by the natural world.</p>

        <div class="mt-10 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:space-y-0">
          <a v-for="collection in collections" :key="collection.name" :href="collection.href" class="group block">
            <div aria-hidden="true"
              class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg lg:aspect-h-6 lg:aspect-w-5 group-hover:opacity-75">
              <img :src="collection.imageSrc" :alt="collection.imageAlt"
                class="h-full w-full object-cover object-center" />
            </div>
            <h3 class="mt-4 text-base font-semibold text-gray-900">{{ collection.name }}</h3>
            <p class="mt-2 text-sm text-gray-500">{{ collection.description }}</p>
          </a>
        </div>
      </section>


      <section aria-labelledby="category-heading" class="pt-5">
        <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
          <div class="min-h-300 max-h-[32rem] overflow-hidden">
            <img :src="product.images.src" :alt="product.images.alt"
              class="h-full w-full object-cover object-center sm:rounded-lg" />
          </div>

          <div class="mt-10 px-4 sm:mt-16 lg:mt-0 p-5">
            <section aria-labelledby="details-heading" class="mt-10">
              <div class="sm:max-w-lg">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Summer styles are finally here
                </h1>
                <p class="mt-4 text-xl text-gray-500">This year, our new summer collection will shelter you from the harsh
                  elements of a world that doesn't care if you live or die.</p>
              </div>
            </section>
          </div>
        </div>
      </section>
    </main>

    <footer aria-labelledby="footer-heading" class="bg-gray-900">
      <h2 id="footer-heading" class="sr-only">Footer</h2>
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="py-20 xl:grid xl:grid-cols-3 xl:gap-8">
          <div class="grid grid-cols-2 gap-8 xl:col-span-2">
            <div class="space-y-12 md:grid md:grid-cols-2 md:gap-8 md:space-y-0">
              <div>
                <h3 class="text-sm font-medium text-white">Shop</h3>
                <ul role="list" class="mt-6 space-y-6">
                  <li v-for="item in footerNavigation.shop" :key="item.name" class="text-sm">
                    <a :href="item.href" class="text-gray-300 hover:text-white">{{ item.name }}</a>
                  </li>
                </ul>
              </div>
              <div>
                <h3 class="text-sm font-medium text-white">Company</h3>
                <ul role="list" class="mt-6 space-y-6">
                  <li v-for="item in footerNavigation.company" :key="item.name" class="text-sm">
                    <a :href="item.href" class="text-gray-300 hover:text-white">{{ item.name }}</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="space-y-12 md:grid md:grid-cols-2 md:gap-8 md:space-y-0">
              <div>
                <h3 class="text-sm font-medium text-white">Account</h3>
                <ul role="list" class="mt-6 space-y-6">
                  <li v-for="item in footerNavigation.account" :key="item.name" class="text-sm">
                    <a :href="item.href" class="text-gray-300 hover:text-white">{{ item.name }}</a>
                  </li>
                </ul>
              </div>
              <div>
                <h3 class="text-sm font-medium text-white">Connect</h3>
                <ul role="list" class="mt-6 space-y-6">
                  <li v-for="item in footerNavigation.connect" :key="item.name" class="text-sm">
                    <a :href="item.href" class="text-gray-300 hover:text-white">{{ item.name }}</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="mt-12 md:mt-16 xl:mt-0">
            <h3 class="text-sm font-medium text-white">Sign up for our newsletter</h3>
            <p class="mt-6 text-sm text-gray-300">The latest deals and savings, sent to your inbox weekly.</p>
            <form class="mt-2 flex sm:max-w-md">
              <label for="email-address" class="sr-only">Email address</label>
              <input id="email-address" type="text" autocomplete="email" required=""
                class="w-full min-w-0 appearance-none rounded-md border border-white bg-white px-4 py-2 text-base text-gray-900 placeholder-gray-500 shadow-sm focus:border-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-900" />
              <div class="ml-4 flex-shrink-0">
                <button type="submit"
                  class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-900">Sign
                  up</button>
              </div>
            </form>
          </div>
        </div>

        <div class="border-t border-gray-800 py-10">
          <p class="text-sm text-gray-400">Copyright &copy; 2021 Your Company, Inc.</p>
        </div>
      </div>
    </footer>
  </div>
</template>



