/**
 *  author: Vika Aqordi
 *  created on: 09-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

import { Image } from "@/types/Image"


export const propertiesToHTMLStyle = (properties?: BlockProperties, options?: { toRemove?: string[], onlySelect?: string[] }) => {
    if (!properties) {
        return {};
    }
    
    let htmlStyle = {
        position: properties?.position?.type || 'static',
        left: properties?.isCenterHorizontal && properties?.position.type === 'fixed' ? '50%' : properties?.position?.x || '0px', 
        top: properties?.position?.y || '0px',
        transform: properties?.isCenterHorizontal && properties?.position.type === 'fixed' ? 'translateX(-50%)' : undefined,

        height: (properties?.dimension?.height?.value || 0) + properties?.dimension?.height?.unit || 'px',
        width: (properties?.dimension?.width?.value || 0) + properties?.dimension?.width?.unit || 'px',
        color: properties?.text?.color,
        fontFamily: properties?.text?.fontFamily,

        paddingTop: (properties?.padding?.top?.value || 0) + properties?.padding?.unit,
        paddingBottom: (properties?.padding?.bottom?.value || 0) + properties?.padding?.unit,
        paddingRight: (properties?.padding?.right?.value || 0) + properties?.padding?.unit,
        paddingLeft: (properties?.padding?.left?.value || 0) + properties?.padding?.unit,

        marginTop: (properties?.margin?.top?.value || 0) + properties?.margin?.unit,
        marginBottom: (properties?.margin?.bottom?.value || 0) + properties?.margin?.unit,
        marginRight: properties?.isCenterHorizontal ? 'auto' : (properties?.margin?.right?.value || 0) + properties?.margin?.unit,
        marginLeft: properties?.isCenterHorizontal ? 'auto' : (properties?.margin?.left?.value || 0) + properties?.margin?.unit,

        background: properties?.background?.type === 'color' ? properties?.background?.color : properties?.background?.image,

        borderTop: `${properties?.border?.top?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,
        borderBottom: `${properties?.border?.bottom?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,
        borderRight: `${properties?.border?.right?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,
        borderLeft: `${properties?.border?.left?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,

        borderTopRightRadius: `${properties?.border?.rounded?.topright?.value}${properties?.border?.rounded?.unit}`,
        borderBottomRightRadius: `${properties?.border?.rounded?.bottomright?.value}${properties?.border?.rounded?.unit}`,
        borderBottomLeftRadius: `${properties?.border?.rounded?.bottomleft?.value}${properties?.border?.rounded?.unit}`,
        borderTopLeftRadius: `${properties?.border?.rounded?.topleft?.value}${properties?.border?.rounded?.unit}`,
        boxSizing: 'border-box',
        ...properties?.additional_style
    }

    if (options?.onlySelect) {
        // Select only specified keys in onlySelect
        htmlStyle = Object.keys(htmlStyle)
            .filter(key => options.onlySelect!.includes(key))
            .reduce((obj, key) => {
                obj[key] = htmlStyle[key];
                return obj;
            }, {} as typeof htmlStyle);
    } else if (options?.toRemove) {
        // Remove specified keys in toRemove
        options.toRemove.forEach((item: string) => {
            delete htmlStyle[item];
        });
    }

    // console.log('htmlstyle', htmlStyle)
    return htmlStyle;
}

export const onDrag = (e, block_properties, _parentComponent) => {
    const parentWidth = _parentComponent.clientWidth
    const parentHeight = _parentComponent.clientHeight

    const percentageLeft = e.left / parentWidth * 100
    const percentageTop = e.top / parentHeight * 100

    // Update position based on the dragging
    block_properties.position.x = `${percentageLeft}%`
    block_properties.position.y = `${percentageTop}%`
}


export const camelToKebab = (str: string) => {
    return str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
}

export const styleToString = (styleObject: {}) => {
    if (!styleObject) return ''

    return Object.entries(styleObject)
    .map(([key, value]) => `${camelToKebab(key)}:${value}`)
    .join(';')
}

export interface BlockProperties {
    position: {
        type: string
        x: string
        y: string
    }
    dimension: {
        height: {
            value: number
            unit: string
        }
        width: {
            value: number
            unit: string
        }
    }
    text: {
        color: string
        fontFamily: string
    }
    padding: {
        unit: string
        top: {
            value: number
        }
        bottom: {
            value: number
        }
        right: {
            value: number
        }
        left: {
            value: number
        }
    }
    margin: {
        unit: string
        top: {
            value: number
        }
        bottom: {
            value: number
        }
        right: {
            value: number
        }
        left: {
            value: number
        }
    }
    border: {
        unit: string
        color: string
        top: {
            value: number
        }
        bottom: {
            value: number
        }
        right: {
            value: number
        }
        left: {
            value: number
        }
        rounded: {
            unit: string
            topright: {
                value: number
            }
            bottomright: {
                value: number
            }
            bottomleft: {
                value: number
            }
            topleft: {
                value: number
            }
        }
    }
    isCenterHorizontal: boolean
    background: {
        type: string
        color: string
        image: Image
    }
    additional_style: {} // "display: flex; align-items: center; justify-content: space-between"
}