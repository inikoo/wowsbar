/**
 * author Vika Aqordi
 * created on 08-11-2024-14h-02m
 * github: https://github.com/aqordeno
 * copyright 2024
*/

export const iframeToParent = (data: any) => {
    if (window) {
        window.parent.postMessage(data, '*')
    }
}