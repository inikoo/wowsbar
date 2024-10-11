/**
 *  author: Vika Aqordi
 *  created on: 09-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

export const propertiesToHTMLStyle = (properties: any, xxx?: string[]) => {
    const htmlStyle = {
        position: properties.position?.type || 'static',
        left: properties.position?.x || '0px', 
        top: properties.position?.y || '0px',

        height: properties.height || 'auto',
        width: properties.width || 'auto',

        paddingTop: (properties.padding?.top?.value || 0) + (properties.padding?.unit || 'px'), 
        paddingBottom: (properties.padding?.bottom?.value || 0) + (properties.padding?.unit || 'px'), 
        paddingRight: (properties.padding?.right?.value || 0) + (properties.padding?.unit || 'px'), 
        paddingLeft: (properties.padding?.left?.value || 0) + (properties.padding?.unit || 'px'),

        marginTop: (properties.margin?.top?.value || 0) + (properties.margin?.unit || 'px'), 
        marginBottom: (properties.margin?.bottom?.value || 0) + (properties.margin?.unit || 'px'), 
        marginRight: (properties.margin?.right?.value || 0) + (properties.margin?.unit || 'px'), 
        marginLeft: (properties.margin?.left?.value || 0) + (properties.margin?.unit || 'px'),
        
        background: properties.background?.type === 'color' ? properties.background?.color : properties.background?.image,
        
        borderTop: `${properties.border?.top?.value}${properties.border?.unit} ${properties.border?.style || 'solid'} ${properties.border?.color}`,
        borderBottom: `${properties.border?.bottom?.value}${properties.border?.unit} ${properties.border?.style || 'solid'} ${properties.border?.color}`,
        borderRight: `${properties.border?.right?.value}${properties.border?.unit} ${properties.border?.style || 'solid'} ${properties.border?.color}`,
        borderLeft: `${properties.border?.left?.value}${properties.border?.unit} ${properties.border?.style || 'solid'} ${properties.border?.color}`,

        borderTopRightRadius: `${properties.border?.rounded.topright?.value}${properties.border?.rounded.unit}`,
        borderBottomRightRadius: `${properties.border?.rounded.bottomright?.value}${properties.border?.rounded.unit}`,
        borderBottomLeftRadius: `${properties.border?.rounded.bottomleft?.value}${properties.border?.rounded.unit}`,
        borderTopLeftRadius: `${properties.border?.rounded.topleft?.value}${properties.border?.rounded.unit}`,
    }

    if(xxx?.toRemove) {
        xxx.toRemove.forEach((item) => {
            delete htmlStyle[item]
        });
    }

    console.log('htmlstyle', htmlStyle)
    return htmlStyle;
}
