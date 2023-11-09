// export const Appointment = () => {
//     return {
//         id: "appointment-block",
//         class: "",
//         category: "Appointment",
//         label: "appointment",
//         content: `
//       <section data-id="appointment-block-1" data-type="appointment" data-group="contact" class="wowsbar-block flex justify-center mt-12" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false"  data-gjs-removable="false">
//             <div class="bg-white w-fit grid grid-cols-2 max-w-3xl justify-center border-2 border-gray-300 overflow-hidden rounded-md divide-x divide-gray-100" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false"  data-gjs-removable="false">
//                 <div class="overflow-hidden bg-white sm:rounded-lg" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false"  data-gjs-removable="false" >
//                     <div class="px-4 py-5 sm:p-6" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false"  data-gjs-removable="false">
//                         <section data-id="info" class="wowsbar-appointment-block">
//                             <div class="flex justify-center align-middle">
//                                 <img src="https://dummyimage.com/50x50" data-gjs-removable="false" class="h-14 aspect-square"
//                                     alt="Description of the image">
//                             </div>
//                             <div class="text-lg text-slate-400" class="gjs-row" data-gjs-droppable="false" >
//                                 Hi you can create appointment
//                             </div>
//                             <div class="text-xl font-medium" class="gjs-row"  data-gjs-droppable="false" >
//                                 Request a one-to-one appointemt with us
//                             </div>
//                             <div class="flex justify-start my-2 gap-3 items-center text-slate-400">
//                                 <span>
//                                     <svg class="text-slate-400 fill-current" xmlns="http://www.w3.org/2000/svg" height="1.1em"
//                                         viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
//                                         <path
//                                             d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z" />
//                                     </svg>
//                                 </span>
//                                 <div class="leading-none">30 min</div>
//                             </div>
//                             <p class="mt-1 mb-2 text-gray-500 text-xs"> Lorem Ipsum is simply dummy text of the printing and
//                             typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the
//                             1500s, when an unknown printer took a galley of type and scrambled it to make a type
//                             specimen book. It has survived not only five centuries, but also the leap into electronic
//                             typesetting, remaining essentially unchanged. It was popularised in the 1960s with the
//                             release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
//                             publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
//                         </section>
//                     </div>
//                 </div>
//                 <div class="w-96" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false" >
//                     <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white space-y-4" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false" >
//                         <div class="px-4 py-5 sm:px-6" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false" >
//                             <section data-id="title" class="wowsbar-appointment-block">
//                                 Select data & time
//                             </section>
//                         </div>
//                         <div class="px-4 py-5 sm:p-0 text-center" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false" >
//                             <img src="https://dummyimage.com/330x100?text=calendar" data-gjs-removable="false" class="inline" data-gjs-editable="false" data-gjs-droppable="false" />
//                         </div>
//                         <div class="px-4 py-4 sm:p-0 mt-4 text-center">
//                             <img src="https://dummyimage.com/330x100?text=time" data-gjs-removable="false" class="inline"  data-gjs-editable="false" data-gjs-droppable="false"/>
//                         </div>
//                     </div>
//                 </div>
//             </div>
//       </section>
//   `,
//     };
// };

export const Appointment = () => {
    return {
        id: "appointment-block-2",
        class: "",
        category: "Appointment",
        label: "Appointment",
        content: `
          <style>
          .title-text{
            padding: 10px 0px;
            
        }
        .description-text{
            font-size: 12px;
        }
        .image-header{
            display: flex;
            justify-content: center;
        }
</style>
<section data-id="appointment-block-1" data-type="appointment" data-group="contact" class="wowsbar-block flex justify-center mt-12" class="gjs-row" data-gjs-editable="false" data-gjs-droppable="false"  data-gjs-removable="false">
<div style="margin: 20px;" data-gjs-droppable="false"  data-gjs-removable="false">
<div class="container border" style="max-width: 800px;" data-gjs-droppable="false"  data-gjs-removable="false">
<div class="row" data-gjs-droppable="false"  data-gjs-removable="false"> <!-- Baris untuk grid -->
  <div class="col-md-6 p-0 " data-gjs-droppable="false"  data-gjs-removable="false">
      <div class="card  rounded-0 border-0 " data-gjs-droppable="false"  data-gjs-removable="false">
      <div class="card-body" style="border-right : 1px solid #D1D5DB" data-gjs-droppable="false"  data-gjs-removable="false"> 
      <section data-id="info" class="wowsbar-appointment-block" data-gjs-droppable="false"  data-gjs-removable="false">
        <div class="bg-white rounded text-left" data-gjs-droppable="false"  data-gjs-removable="false">
          <div class="image-header">
  <img src="https://dummyimage.com/330x300" alt="" width="100" class="img-fluid mb-3 img-thumbnail shadow-sm">
</div>
          <div class="title-text">
          <span class="small text-uppercase text-muted">Hi you can create appointment</span>
          <h5 class="mb-1"  style="font-size: 22px;">Request a one-to-one appointment with us</h5>
          <div class="d-flex align-items-center">
              <span>
              <svg class="text-slate-400 fill-current" xmlns="http://www.w3.org/2000/svg" height="1.1em"
                  viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                  <path
                      d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z" />
              </svg>
          </span>
              <div class="leading-none px-2 py-1">30 min</div>
          </div>
          </div>   
          <p class="description-text">
            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially
            unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
          </p>
        </div>
        </section>
      </div>
    </div>
  </div>
  <div class="col-md-6 p-0" data-gjs-droppable="false"  data-gjs-removable="false"> 
      <div class="card  rounded-0 border-0" data-gjs-droppable="false"  data-gjs-removable="false">
      <div class="card-body" data-gjs-droppable="false"  data-gjs-removable="false">
        <div class="bg-white rounded text-left" data-gjs-droppable="false"  data-gjs-removable="false"> <!-- Menghapus kelas py-5 dan px-4 -->
        <section data-id="title" class="wowsbar-appointment-block" data-gjs-droppable="false"  data-gjs-removable="false">
          <div class="title-text">
          <span class="small text-uppercase text-muted">Select date & time</span>
      </section>
      <div>
      <img src="https://dummyimage.com/371x349?text=Calendar" data-gjs-removable="false" class="inline"  data-gjs-editable="false" data-gjs-droppable="false"/>
  </div>
          </div>   
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>

      `,
    };
};
