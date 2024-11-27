



function iframeStyle(iframeElement, ulidAnnouncement) {
    iframeElement.id = `iframe-wowsbar-${ulidAnnouncement}`;
    iframeElement.setAttribute('allowTransparency', 'true');
    iframeElement.style.height = '0px'
    iframeElement.style.width = '100%';
    iframeElement.style.display = 'block';
    iframeElement.style.backgroundColor = 'transparent';
    iframeElement.style.border = 'none';
    iframeElement.style.isolation = 'isolate';
    iframeElement.style.position = 'relative';
    iframeElement.style.zIndex = 9999;
    iframeElement.style.transition = 'all 0.2s ease-in-out';
    iframeElement.frameBorder = "0"
}

// To render HTML that contain <script></script>
function setInnerHTML(elm, htmlValue) {
    elm.innerHTML = htmlValue;
    
    // Replace <script> from innerHTML to become <script> from javascript (so it will executed)
    Array.from(elm.querySelectorAll("script"))
        .forEach( oldScriptEl => {
            const newScriptEl = document.createElement("script");
            
            Array.from(oldScriptEl.attributes).forEach( attr => {
                newScriptEl.setAttribute(attr.name, attr.value) 
            });
            
            const scriptText = document.createTextNode(oldScriptEl.innerHTML);
            newScriptEl.appendChild(scriptText);
            
            oldScriptEl.parentNode.replaceChild(newScriptEl, oldScriptEl);
        });
}


async function fetchAnnouncementData() {
    const scriptUrl = new URL(document.currentScript.src);

    // Extract the `ulid` from the query parameters
    const ulid = scriptUrl.searchParams.get('ulid');
    const deliveryUrl = scriptUrl.searchParams.get('delivery');
    console.log('ulid:', ulid, 'deliveryUrl:', deliveryUrl)
    
    const domain = window.location.hostname
    const path = window.location.pathname
    console.log('domain:', domain, 'path:', path)

    if (ulid) {
        try {
            // Fetch: Announcement JSON
            const announcementData = await fetch(`ar_web_wowsbar_announcement.php?url_KHj321Tu=${deliveryUrl}/announcement/${ulid}`, {
                headers: {
                    'Accept':'application/json',
                    "Content-Type": "application/json",
                }
            })
                .then(async response => {
                    const xxx = await response.text();
                    console.log('ar_web_wowsbar:', xxx);

                    if (!response.ok) {
                        throw new Error('Network response was not ok ---- ' + response.statusText);
                    }

                    // document.querySelector('#wowsbar_announcement').innerHTML = xxx;

                    return xxx;
                });

            console.log('======== new Announctment data', announcementData);
            // document.querySelector('#wowsbar_announcement').innerHTML = announcementData;

            const wowsbar_announcement = document.querySelector('#wowsbar_announcement')
            setInnerHTML(wowsbar_announcement, announcementData)

            
            

            // createElementAfterBody();

            const iframe = document.createElement('iframe');
            iframe.src = `https://delivery-staging.wowsbar.com/announcement/${ulid}`;
            iframeStyle(iframe, ulid)

            // console.log('zzzz', iframe?.contentWindow)
            // function adjustIframeHeight() {
            //     iframe.style.height = iframe.contentWindow.document.body.clientHeight + 'px';
            //     console.log('ffff', iframe.style.height)
            // }
            
            let iframeHeight
            let iframeTop


            if (announcementData?.container_properties) {
                const containerStyle = propertiesToHTMLStyle(announcementData.container_properties)
                console.log('Container style:', containerStyle);

                // Set style for iframe
                for (const [key, value] of Object.entries(containerStyle)) {
                    iframe.style[key] = value;
                }

                // Listening event from component (inside iframe)
                window.addEventListener('message', function(event) {
                    console.log('received message', event.data)

                    // Emit from each component (AnnouncementInformation1)
                    if(event.data === 'close_button_click') {
                        console.log('Close button clicked')
                        iframe.style.top = `-${parseInt(containerStyle.height, 10) + parseInt(containerStyle.height, 10)}px`
                    }
                });
            } else {
                console.error('No container properties found');
            }

            
            // Insert iframe to first child of <body>
            const body = document.body;
            body.insertBefore(iframe, body.firstChild);

        } catch (error) {
            console.error('Someting went wrong:', error);
        }
    } else {
        console.log("ulid is not exist");
    }
}

// Call the async function
fetchAnnouncementData();



// How to run
// const script = document.createElement('script')
//     script.src = 'http://delivery.wowsbar.test/announcement.js?v=1&ulid=01J9T4KWNQJM9BMMPHKGKY0AVK'
//     script.async = true
//     document.body.appendChild(script)


function propertiesToHTMLStyle(properties, options) {
    const htmlStyle = {
        position: properties.position?.type || 'static',
        left: properties.isCenterHorizontal && properties.position?.type === 'fixed' ? '50%' : properties.position?.x || '0px', 
        top: properties.position?.y || '0px',
        transform: properties.isCenterHorizontal && properties.position?.type === 'fixed' ? 'translateX(-50%)' : '',

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
        marginRight: properties.isCenterHorizontal ? 'auto' : (properties?.margin?.right?.value || 0) + properties?.margin?.unit,
        marginLeft: properties.isCenterHorizontal ? 'auto' : (properties?.margin?.left?.value || 0) + properties?.margin?.unit,

        background: properties?.background?.type === 'color' ? properties?.background?.color : properties?.background?.image,

        borderTop: `${properties?.border?.top?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,
        borderBottom: `${properties?.border?.bottom?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,
        borderRight: `${properties?.border?.right?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,
        borderLeft: `${properties?.border?.left?.value || 0}${properties?.border?.unit || 'px'} solid ${properties?.border?.color}`,

        borderTopRightRadius: `${properties?.border?.rounded?.topright?.value}${properties?.border?.rounded?.unit}`,
        borderBottomRightRadius: `${properties?.border?.rounded?.bottomright?.value}${properties?.border?.rounded?.unit}`,
        borderBottomLeftRadius: `${properties?.border?.rounded?.bottomleft?.value}${properties?.border?.rounded?.unit}`,
        borderTopLeftRadius: `${properties?.border?.rounded?.topleft?.value}${properties?.border?.rounded?.unit}`,
    }

    if(options?.toRemove) {
        options.toRemove.forEach((item) => {
            delete htmlStyle[item]
        });
    }

    // console.log('htmlstyle', htmlStyle)
    return htmlStyle;
}