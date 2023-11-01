import type { Editor } from 'grapesjs';
import { RequiredPluginOptions } from '..';
import { cmdImport } from './../consts';

export default (editor: Editor, config: RequiredPluginOptions) => {
  const pfx = editor.getConfig('stylePrefix');
  const importLabel = config.modalImportLabel;
  const importCnt = config.modalImportContent;

  editor.Commands.add(cmdImport, {
    codeViewer: null as any,
    container: null as HTMLElement | null,

    run(editor) {
      const codeContent = typeof importCnt == 'function' ? importCnt(editor) : importCnt;
      const codeViewer = this.getCodeViewer();
      editor.Modal.open({
        title: config.modalImportTitle,
        content: this.getContainer(),
      }).onceClose(() => editor.stopCommand(cmdImport));
      codeViewer.setContent(codeContent ?? '');
      codeViewer.refresh();
      setTimeout(()=> codeViewer.focus(), 0);
    },

    stop() {
      editor.Modal.close();
    },

    getContainer() {
      if (!this.container) {
        const codeViewer = this.getCodeViewer();
        const container = document.createElement('div');
        container.className = `${pfx}import-container`;

        // Import Label
        if (importLabel) {
          const labelEl = document.createElement('div');
          labelEl.className = `${pfx}import-label`;
          labelEl.innerHTML = importLabel;
          container.appendChild(labelEl);
        }

        container.appendChild(codeViewer.getElement());

        // Import button
        const btnImp = document.createElement('button');
        btnImp.type = 'button';
        btnImp.innerHTML = config.modalImportButton;
        btnImp.className = `${pfx}btn-prim ${pfx}btn-import`;
        btnImp.onclick = () => {
          editor.Css.clear();
          editor.setComponents(codeViewer.getContent().trim());
          editor.Modal.close();
        };
        container.appendChild(btnImp);

        this.container = container;
      }

      return this.container;
    },

    /**
     * Return the code viewer instance
     * @returns {CodeViewer}
     */
    getCodeViewer() {
      if (!this.codeViewer) {
        this.codeViewer = editor.CodeManager.createViewer({
          codeName: 'htmlmixed',
          theme: 'hopscotch',
          readOnly: false,
          ...config.importViewerOptions,
        });
      }

      return this.codeViewer;
    },
  });
};