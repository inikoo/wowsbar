export const SocketFooter = () => {
	let eventData = null

	return {
		eventData,
		actions: {
			unsubscribe() {
				window.Echo.leave(`footer.preview`)
			},
			subscribe: (callback: any) => {
				const channel = window.Echo.private(`footer.preview`).listen(
					".WebpagePreview",
					(event) => {
						if (event) {
							eventData = { ...event }
							if (callback) {
								callback(eventData)
							}
						}
					}
				)
			},
			send: (send = "") => {
				const channelName = `footer.preview`
				const chanel = window.Echo.join(channelName).whisper("otherIsNavigating", { data: send })
			},
		},
	}
}
