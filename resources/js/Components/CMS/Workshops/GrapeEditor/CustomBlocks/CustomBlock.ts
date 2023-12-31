import { CtaBlock1 } from './CTA/CTA';
import { headerBlock1, headerBlock2,headerBlock3} from './Header/Header'
import { footerBlock1, footerBlock2, footerBlock3 } from './Footer/Footer'
import { heroBlock1 , heroBlock2, heroBlock3, heroBlock4} from './Hero/Hero'
import { Appointment } from './Appointment/Appointment'
import { BlogBlock1, BlogBlock2, BlogBlock4 } from './Blog/Blog'
import { StatisticsBlock1, StatisticsBlock2, StatisticsBlock3 } from './Statistics/Statistics'
import { PricingBlock1, PricingBlock2, PricingBlock3, PricingBlock4 } from './Pricing/Pricing'
import { CtaBlock1, CtaBlock2, CtaBlock3 } from './CTA/CTA'
/* import loadStyles from '@/Components/CMS/Workshops/GrapeEditor/CustomStyle/styles'; */
import  EmailBlocks from '@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/EmailBlocks/index'

export const CustomBlock = (editor : Any) => {
    IconBlock(editor)
    Gradient(editor)
    CodeEditor(editor)
    IFrameBlocks(editor)
    setTimeout(()=>{
      let categories = editor.BlockManager.getCategories();
      categories.each((category)=>category.set("open",false))
    },500)
}

export const CodeEditor = (editor : Any )=>{
    const panelViews = editor.Panels.addPanel({
        id: 'views'
      });
      panelViews.get('buttons').add([{
        attributes: {
           title: 'Open Code'
        },
        className: 'fa fa-file-code-o',
        command: 'open-code',
        togglable: false, //do not close when button is clicked again
        id: 'open-code'
      }]);
}

export const IconBlock = (editor : Any) => {
    editor.BlockManager.get("icon").attributes = {
        ...editor.BlockManager.get("icon").attributes,
        media: '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M116.65 219.35a15.68 15.68 0 0 0 22.65 0l96.75-99.83c28.15-29 26.5-77.1-4.91-103.88C203.75-7.7 163-3.5 137.86 22.44L128 32.58l-9.85-10.14C93.05-3.5 52.25-7.7 24.86 15.64c-31.41 26.78-33 74.85-5 103.88zm143.92 100.49h-48l-7.08-14.24a27.39 27.39 0 0 0-25.66-17.78h-71.71a27.39 27.39 0 0 0-25.66 17.78l-7 14.24h-48A27.45 27.45 0 0 0 0 347.3v137.25A27.44 27.44 0 0 0 27.43 512h233.14A27.45 27.45 0 0 0 288 484.55V347.3a27.45 27.45 0 0 0-27.43-27.46zM144 468a52 52 0 1 1 52-52 52 52 0 0 1-52 52zm355.4-115.9h-60.58l22.36-50.75c2.1-6.65-3.93-13.21-12.18-13.21h-75.59c-6.3 0-11.66 3.9-12.5 9.1l-16.8 106.93c-1 6.3 4.88 11.89 12.5 11.89h62.31l-24.2 83c-1.89 6.65 4.2 12.9 12.23 12.9a13.26 13.26 0 0 0 10.92-5.25l92.4-138.91c4.88-6.91-1.16-15.7-10.87-15.7zM478.08.33L329.51 23.17C314.87 25.42 304 38.92 304 54.83V161.6a83.25 83.25 0 0 0-16-1.7c-35.35 0-64 21.48-64 48s28.65 48 64 48c35.2 0 63.73-21.32 64-47.66V99.66l112-17.22v47.18a83.25 83.25 0 0 0-16-1.7c-35.35 0-64 21.48-64 48s28.65 48 64 48c35.2 0 63.73-21.32 64-47.66V32c0-19.48-16-34.42-33.92-31.67z"/></svg>',
    };
}

export const HeaderPlugins = (editor: any) => {
    const header = [headerBlock1(),headerBlock2(),headerBlock3()]
    header.map((item,index)=>{
        editor.Blocks.add(item.id,item);
    })
}

export const FooterPlugins = (editor: any) => {
  const footer = [footerBlock1(),footerBlock2(),footerBlock3()]
  footer.map((item,index)=>{
    editor.Blocks.add(item.id,item);
})
}

export const HeroPlugins = (editor: any) => {
    const hero = [heroBlock1(),heroBlock2(),heroBlock3(),heroBlock4()]
    hero.map((item,index)=>{
      editor.Blocks.add(item.id,item);
  })
  }

  export const AppointmentPlugins = (editor: any) => {
    const appointment = [Appointment()]
    appointment.map((item,index)=>{
        editor.Blocks.add(item.id,item);
    })
}


export const BlogPlugins = (editor: any) => {
    const Blog = [BlogBlock1(), BlogBlock2(),BlogBlock4()]
    Blog.map((item,index)=>{
        editor.Blocks.add(item.id,item);
    })
}

export const StatisticsPlugins = (editor: any) => {
    const statistics = [StatisticsBlock1(), StatisticsBlock2(), StatisticsBlock3()]
    statistics.map((item,index)=>{
        editor.Blocks.add(item.id,item);
    })
}

export const PricingPlugins = (editor: any) => {
    const pricing = [PricingBlock1(), PricingBlock2(), PricingBlock3(),PricingBlock4()]
    pricing.map((item,index)=>{
        editor.Blocks.add(item.id,item);
    })
}

export const CtaPlugins = (editor: any) => {
    const cta = [CtaBlock1(), CtaBlock2(), CtaBlock3()]
    cta.map((item,index)=>{
        editor.Blocks.add(item.id,item);
    })
}



export const addNewBlocks  = (editor: any, blocks : Array) => {
    const bm = editor.BlockManager;
    blocks.map((item)=>bm.add(item.id,item))
};

export const Gradient  = (editor: any) => {
 editor.StyleManager.addProperty('decorations', {
  extend: 'background-image', 
  name: 'Gradient Background',
})}


export  const GradientTypography  = (editor: any) => {
  const options: PluginOptions = {
    grapickOpts: {},
    selectEdgeStops: true,
    styleType: 'gradient',
    builtInType: 'background',
    ...opts,
  };
/*   loadStyles(editor, options); */
};


export const IFrameBlocks  = (editor) => {
    editor.BlockManager.add('iframe', {
      label: 'I Frame',
      category: "Basic",
      content: '<section data-id="hero-iFrame" data-type="html"class="wowsbar-block"><iframe style="width:100%;" src="<your iframe src here>"></iframe></section>',
    });
  };

  export const Email = (editor : Any, opt : Object) => {
    EmailBlocks(editor,opt)
}

export const customUploadImage = (editor : Any, opt = null) =>{
    editor.Blocks.add('image', {
        select: true,
        label: 'Image',
        activate: true,
        content: `<p style='width: fit-content;  height: fit-content;' data-gj-type="you-custom-component"><img style='width:100px; height:100px' src='https://place-hold.it/100x100'></img></span>`,
        media: `<svg viewBox="0 0 24 24">
                    <path fill="currentColor" d="M21,3H3C2,3 1,4 1,5V19A2,2 0 0,0 3,21H21C22,21 23,20 23,19V5C23,4 22,3 21,3M5,17L8.5,12.5L11,15.5L14.5,11L19,17H5Z" />
                </svg>`,
      
    });
}