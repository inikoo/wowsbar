/**
 *  author: Vika Aqordi
 *  created on: 09-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

export interface RGBA {
    r: number
    g: number
    b: number
    a: number
}

export interface HSV {
    h: number
    s: number
    v: number
}

export interface Colors {
    rgba: RGBA
    hsv: HSV
    hex: string
}