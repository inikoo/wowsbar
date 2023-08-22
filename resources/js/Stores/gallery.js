import { defineStore } from 'pinia'

export const useGalleryStore = defineStore('gallery', {
    state: () => ({
        uploaded_images: [],
        stock_images: []
    }),
})

