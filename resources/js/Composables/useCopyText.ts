// To copy a text to clipboard
export const useCopyText = (textToCopy: string) => {
    const textarea = document.createElement("textarea")
    textarea.value = textToCopy
    document.body.appendChild(textarea)
    textarea.select()
    document.execCommand("copy")
    textarea.remove()
}