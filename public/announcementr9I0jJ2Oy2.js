



function iframeStyle(iframeElement) {
    // iframeElement.id = `iframe-wowsbar-${ulidAnnouncement}`;
    iframeElement.id = `iframe-wowsbar`;
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
    elm.replaceWith(htmlValue);
    
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
    const loggedIn = scriptUrl.searchParams.get('logged_in');  // true | false
    const jsonUrl = scriptUrl.searchParams.get('json');  // https://delivery-staging.wowsbar.com/announcement
    console.log('ulid:', ulid, 'jsonUrl:', jsonUrl)
    
    let hostname = window.location.hostname
    const pathname = window.location.pathname
    console.log('domain:', hostname, 'path:', pathname)
    
    if (hostname.startsWith('www.')) {
        hostname = hostname.slice(4);
    }

    // if (ulid) {
        try {
            console.log('> On try');
            // Fetch: Announcement JSON
            const inner_url = `${jsonUrl}?domain=${hostname}${pathname}?logged_in=${loggedIn}`  // https://www.aw-indonesia.com/ar_web_wowsbar_announcement.php?url_KHj321Tu=https://delivery-staging.wowsbar.com/announcement?domain=aw-indonesia.com/hello&logged_in=false
            console.log('inner_url:', inner_url)

            const inner_url_encoded = encodeURIComponent(inner_url)
            console.log('inner_url_encoded:', inner_url_encoded)

            const announcementData = await fetch(`ar_web_wowsbar_announcement.php`, {
                headers: {
                    'Accept':'application/json',
                    "Content-Type": "application/json",
                    "X-Wowsbar-Announcement-Url": inner_url
                }
            })
                .then(async response => {
                    console.log('> On response', response);

                    if (!response.ok) {
                        throw new Error('Network response was not ok ---- ' + response.statusText);
                    }

                    const xxx = await response.json();

                    return xxx;
                });

            

            console.log('======== new Announctment data', announcementData);
            // document.querySelector('#wowsbar_announcement').innerHTML = announcementData;


            // wowsbar_announcement.style.height = announcementData.container_properties.dimension.height.value + announcementData.container_properties.dimension.height.unit
            // setInnerHTML(wowsbar_announcement, announcementData.compiled_layout)


            if(announcementData?.compiled_layout) {
                console.log('Compiled layout found', announcementData.ulid)
                const wowsbar_announcement = document.querySelector('#wowsbar_announcement')

                // Create a temporary container to parse the HTML string
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = announcementData.compiled_layout;

                // Extract and execute any <script> tags
                const scripts = tempContainer.querySelectorAll('script');
                scripts.forEach(script => {
                    const newScript = document.createElement('script');
                    newScript.textContent = script.textContent; // Get the content of the script
                    document.head.appendChild(newScript); // Execute it by appending to <head> (or use <body> if preferred)
                })
    
                wowsbar_announcement.replaceWith(...tempContainer.childNodes)

                const dataToLocalStorage = {
                    height: '50px',
                    data: announcementData.compiled_layout
                }
                localStorage.setItem('__wowsbar_announcement', JSON.stringify(dataToLocalStorage))
            } else {
                console.log('No compiled layout found')
            }

            

            // const containerStyle1 = propertiesToHTMLStyle(announcementData.container_properties)
            // console.log('Container style:', containerStyle1);

            // // Set style for container, this will be not needed
            // for (const [key, value] of Object.entries(containerStyle1)) {
            //     wowsbar_announcement.style[key] = value;
            // }

            window.addEventListener('message', function(event) {
                console.log('received message', event.data)

                // Emit from each component (AnnouncementInformation1)
                if(event.data === 'close_button_click') {
                    console.log('Close button clicked')
                    iframe.style.top = `-${parseInt(containerStyle.height, 10) + parseInt(containerStyle.height, 10)}px`
                }
            });
            
            

            // createElementAfterBody();

            // const iframe = document.createElement('iframe');
            // iframe.src = `https://delivery-staging.wowsbar.com/announcement/${ulid}`;
            // iframeStyle(iframe, ulid)
            // iframeStyle(iframe)

            // console.log('zzzz', iframe?.contentWindow)
            // function adjustIframeHeight() {
            //     iframe.style.height = iframe.contentWindow.document.body.clientHeight + 'px';
            //     console.log('ffff', iframe.style.height)
            // }
            
            // let iframeHeight
            // let iframeTop


            // if (announcementData?.container_properties) {
            //     const containerStyle = propertiesToHTMLStyle(announcementData.container_properties)
            //     console.log('Container style:', containerStyle);

            //     // Set style for iframe
            //     for (const [key, value] of Object.entries(containerStyle)) {
            //         iframe.style[key] = value;
            //     }

            //     // Listening event from component (inside iframe)
                
            // } else {
            //     console.error('No container properties found');
            // }

            
            // Insert iframe to first child of <body>
            // const body = document.body;
            // body.insertBefore(iframe, body.firstChild);

        } catch (error) {
            console.error('Announcement went wrong:', error);
        }
    // } else {
    //     console.log("ulid is not exist");
    // }
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
        boxSizing: 'border-box'
    }

    if(options?.toRemove) {
        options.toRemove.forEach((item) => {
            delete htmlStyle[item]
        });
    }

    // console.log('htmlstyle', htmlStyle)
    return htmlStyle;
}