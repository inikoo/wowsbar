import type { Component } from 'vue'

//Components
import Footer1 from '@/Components/Workshop/Footer/Template/Footer1.vue'


export const getComponent = (componentName: string) => {
    const components: Component = {
        'FooterTheme1': Footer1,
        'FooterTheme2': Footer1,
        'FooterTheme3': Footer1,
    }

    return components[componentName] ?? null
}

