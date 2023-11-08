import { noop } from 'lodash';
import type { Plugin, CustomRTE } from 'grapesjs';
import type CKE from 'ckeditor4';

function getMergeTagData() {
    return axios.get(route('org.models.mailshot.custom.text'))
        .then(response => response.data)
        .catch(error => {
            console.error(error);
            return [];
        });
}


export type PluginOptions = {
  options?: CKE.config;
  ckeditor?: CKE.CKEditorStatic | string;
  position?: 'left' | 'center' | 'right';
  customRte?: Partial<CustomRTE>;
  onToolbar?: (toolbar: HTMLElement) => void;
  RTE?: noop;
};

const isString = (value: any): value is string => typeof value === 'string';

const loadFromCDN = (url: string) => {
  const scr = document.createElement('script');
  scr.src = url;
  document.head.appendChild(scr);
  return scr;
}

const forEach = <T extends HTMLElement = HTMLElement>(items: Iterable<T>, clb: (item: T) => void) => {
  [].forEach.call(items, clb);
}

const stopPropagation = (ev: Event) => ev.stopPropagation();

const plugin: Plugin<PluginOptions> = async(editor, options = {}) => {
  const opts: Required<PluginOptions> = {
    options: {},
    customRte: {},
    position: 'left',
    ckeditor: 'https://cdn.ckeditor.com/4.21.0/standard-all/ckeditor.js',
    onToolbar: () => {},
    ...options,
  };

  const mergeTagsOption = await getMergeTagData()
  let ck: CKE.CKEditorStatic | undefined;
  const { ckeditor } = opts;
  const hasWindow = typeof window !== 'undefined';
  let dynamicLoad = false;

  // Check and load CKEDITOR constructor
  if (ckeditor) {
    if (isString(ckeditor)) {
      if (hasWindow) {
        dynamicLoad = true;
        const scriptEl = loadFromCDN(ckeditor);
        scriptEl.onload = () => {
          ck = window.CKEDITOR;
        }
      }
    } else if (ckeditor.inline!) {
      ck = ckeditor;
    }
  } else if (hasWindow) {
    ck = window.CKEDITOR;
  }

  const updateEditorToolbars = () => setTimeout(() => editor.refresh(), 0);
  const logCkError = () => {
    editor.log('CKEDITOR instance not found', { level: 'error' })
  };

  if (!ck && !dynamicLoad) {
    return logCkError();
  }

  const focus = (el: HTMLElement, rte?: CKE.editor) => {
    if (rte?.focusManager?.hasFocus) return;
    el.contentEditable = 'true';
    rte?.focus();
    updateEditorToolbars();
  };


  editor.setCustomRte({
    getContent(el, rte: CKE.editor) {
      return rte.getData();
    },

    enable(el, rte?: CKE.editor) {
      // If already exists I'll just focus on it
      if(rte && rte.status != 'destroyed') {
        focus(el, rte);
        return rte;
      }

      if (!ck) {
        logCkError();
        return;
      }

      // Seems like 'sharedspace' plugin doesn't work exactly as expected
      // so will help hiding other toolbars already created
      const rteToolbar = editor.RichTextEditor.getToolbarEl();
      forEach(rteToolbar.children as Iterable<HTMLElement>, (child) => {
        child.style.display = 'none';
      });

      // Check for the mandatory options
      const ckOptions = { ...opts.options };
      const plgName = 'sharedspace';

      if (ckOptions.extraPlugins) {
        if (typeof ckOptions.extraPlugins === 'string') {
          ckOptions.extraPlugins += `,${plgName}`;
        } else if (Array.isArray(ckOptions.extraPlugins)) {
          (ckOptions.extraPlugins as string[]).push(plgName);
        }
      } else {
        ckOptions.extraPlugins = plgName;
      }

      if(!ckOptions.sharedSpaces) {
        ckOptions.sharedSpaces = { top: rteToolbar };
      }

      // Init CKEDITOR
      rte = ck!.inline(el, ckOptions);

      console.log(rte)

      // Make click event propogate
      rte.on('contentDom', () => {
        const editable = rte!.editable();
        editable.attachListener(editable, 'click', () => el.click());
      });

      // The toolbar is not immediatly loaded so will be wrong positioned.
      // With this trick we trigger an event which updates the toolbar position
      rte.on('instanceReady', () => {
        const toolbar = rteToolbar.querySelector<HTMLElement>(`#cke_${rte!.name}`);
        if (toolbar) {
          toolbar.style.display = 'block';
          opts.onToolbar(toolbar);
        }
        // Update toolbar position
        editor.refresh();
        // Update the position again as the toolbar dimension might have a new changed
        updateEditorToolbars();
      });

      // Prevent blur when some of CKEditor's element is clicked
      rte.on('dialogShow', () => {
        const els = document.querySelectorAll<HTMLElement>('.cke_dialog_background_cover, .cke_dialog_container');
        forEach(els, (child) => {
          child.removeEventListener('mousedown', stopPropagation);
          child.addEventListener('mousedown', stopPropagation);
        });
      });

      // On ENTER CKEditor doesn't trigger `input` event
      rte.on('key', (ev: any) => {
        ev.data.keyCode === 13 && updateEditorToolbars();
      });

      rte.ui.addButton('customTag', {
        label: 'Custom Tag',
        command: 'customTag',
        toolbar: 'insert',
        className: 'custom-tag-button',
        icon : false
      });

      CKEDITOR.dialog.add('customTagDialog', function (editor) {
        return {
            title: 'Custom Tag Options',
            minWidth: 200,
            minHeight: 100,
            buttons: [],
            contents: [{
                id: 'tab1',
                label: 'Tab 1',
                title: 'Tab 1',
                expand: true,
                padding: 0,
                elements: [{
                    type: 'html',
                    html: '<ul id="customTagList"></ul>',
                    onLoad: function () {
                        var listContainer = document.getElementById('customTagList');
                        mergeTagsOption.forEach(function (item) {
                            var listItem = document.createElement('li');
                            var button = document.createElement('button');
                            button.textContent = item.label;
                            button.addEventListener('click', function () {
                                editor.insertHtml(`<p id="${item.value}">[${item.label}]</p>`);
                                editor.getDialog().hide();
                            });
                            listItem.appendChild(button);
                            listContainer.appendChild(listItem);
                        });
                    }
                }]
            }]
        };
    });
    

      rte.addCommand('customTag', {
        exec: function (editor) {
          editor.openDialog('customTagDialog');
        }
      });
    
    rte.execCommand('toolbarCollapse');
    rte.execCommand('toolbarExpand');

      focus(el, rte);

      return rte;
    },

    disable(el, rte?: CKE.editor) {
      el.contentEditable = 'false';
      rte?.focusManager?.blur(true);
    },

    ...opts.customRte,
  });

  // Update RTE toolbar position
  editor.on('rteToolbarPosUpdate', (pos: any) => {
    const { elRect } = pos;

    switch (opts.position) {
      case 'center':
        pos.left = (elRect.width / 2) - (pos.targetWidth / 2);
        break;
      case 'right':
        pos.left = ''
        pos.right = 0;
        break;
    }
  });
};

export default plugin;


