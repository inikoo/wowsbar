// To copy a text to clipboard
export const useRemoveHttps = (text: string) => {
    if(!text) return ''

    return text.toString().replace(/^https?:\/\//g, '')
}