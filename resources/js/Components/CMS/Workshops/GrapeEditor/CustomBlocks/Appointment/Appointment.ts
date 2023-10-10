export const Appointment = () => {
    return {
      id: 'appointment-block-3',
      class: '',
      category: 'Appointment',
      label : 'appointment',
      content :`<section class="wowsbar-appoinment">
      <div class="bg-white">
      <div class="pb-16 pt-6 sm:pb-24">
          <div
              class="mx-auto mt-8 max-w-2xl px-4 sm:px-6 lg:max-w-7xl lg:px-8 border rounded-md"
          >
              <div
                  class="lg:grid lg:auto-rows-min lg:grid-cols-12 lg:gap-x-8"
              >
                  <div class="mt-8 lg:col-span-5 lg:col-start-1 lg:row-span-3 lg:row-start-1 lg:mt-0"
                  >
                      <div class="flex justify-center align-middle p-20">
                      <img src="https://dummyimage.com/" data-gjs-removable="false" class="w-32 h-32  p-2" alt="Description of the image">
                      </div>
                      <hr />
                      <div class="text-lg text-slate-400">
                      HI you can create appointemt
                      </div>
                      <div class="text-4xl font-medium">
                          HI you can create appointemt
                      </div>
                      <div>
                          <div class="flex justify-start my-2 gap-3">
                              <div>
                              HI you can create appointemt
                              </div>
                              <div>{{ Book.meet.duration }}</div>
                          </div>
                          <div class="flex justify-start my-2 gap-3">
                              <div>
                              HI you can create appointemt
                              </div>
                              <div>{{ Book.meetInformation }}</div>
                          </div>
                      </div>

                      <div class="my-3">
                          <h2 class="text-sm font-medium text-gray-900">
                              Description
                          </h2>

                          <div
                              class="mt-1 mb-2 text-gray-500 text-xs"
                             
                          >  HI you can create appointemt</div>
                      </div>
                  </div>
                  <div class="mt-8 lg:col-span-7">
                      <span class="text-lg font-medium text-gray-900"
                          >Select a Date & Time</span
                      >
                  </div>

              </div>
          </div>
      </div>
  </div>
  </section>
  `
    }
  }