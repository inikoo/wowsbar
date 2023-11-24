import InlineEditor from  "@ckeditor/ckeditor5-build-decoupled-document";

function getMergeTagData(grapesjsEditor) {
  grapesjsEditor.setCustomRte({
    enable: async function (el, rte) {
      console.log('✔️ RTE enabled');
      console.log('fd',rte)
      if (rte) {
        console.log(rte)
        el.contentEditable = true;
        let rteToolbar = grapesjsEditor.RichTextEditor.getToolbarEl();
        [].forEach.call(rteToolbar.children, (child) => {
          child.style.display = 'none';
        });
  
        rte = await rte;
        rte.ui.view.toolbar.element.style.display = 'block';
        return rte;
      }
  
      // Seems like 'sharedspace' plugin doesn't work exactly as expected
      // so will help hiding other toolbars already created
      let rteToolbar = grapesjsEditor.RichTextEditor.getToolbarEl();
      [].forEach.call(rteToolbar.children, (child) => {
        child.style.display = 'none';
      });
  
      // Init CkEditors
      rte = await InlineEditor.create(el, {
        language: 'en-au',
  
        fontSize: {
          options: [
            9,
            11,
            13,
            'default',
            17,
            19,
            21,
            24,
            26,
            28,
            30,
            32,
            36,
            42,
            48,
            72,
            90,
          ],
        },
        fontFamily: {
          options: [
            'Arial',
            'Arial Black',
            'Georgia',
            'Helvetica',
            'Impact',
            'Malgun Gothic',
            'Microsoft JhengHei',
            'Microsoft YaHei',
            'Times New Roman',
            'Yu Gothic',
          ],
        },
  
        toolbar: [
          'bold',
          'italic',
          'underline',
          'link',
          'bulletedList',
          'numberedList',
          'fontcolor',
          'fontSize',
          'alignment',
          'fontfamily',
        ],
      }).catch(console.error);
  
      if (rte) {
        // // Prevent blur when some of CKEditor's element is clicked
        rte.on('mousedown', (e) => {
          const editorEls = grapesjs.$('.gjs-rte-toolbar');
          ['off', 'on'].forEach((m) =>
            editorEls[m]('mousedown', stopPropagation)
          );
        });
  
        grapesjsEditor.RichTextEditor.getToolbarEl().appendChild(
          rte.ui.view.toolbar.element
        );
        el.contentEditable = true;
      } else {
        console.error('Editor async was not initialized');
      }

      console.log('ijijiji',rte)


      rte.editing.view.document.on('enter', (event, data) => {
        event.preventDefault();
        rte.model.change(writer => {
          const newParagraph = writer.createElement('paragraph');
          writer.append(newParagraph, rte.model.document.getRoot());
          writer.setSelection(newParagraph, 'on');
        });
      });


  
      return rte;
    },
  
    disable(el, rte) {
      console.log('❌ RTE disabled');
      el.contentEditable = false;
    },
  });
  
}
  

export default getMergeTagData;


