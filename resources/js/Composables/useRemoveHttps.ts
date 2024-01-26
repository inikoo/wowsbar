// To remove https:// or http:// from a string
export const useRemoveHttps = (text: string) => {
    if(!text) return ''

    return text.toString().replace(/^https?:\/\//g, '')
}