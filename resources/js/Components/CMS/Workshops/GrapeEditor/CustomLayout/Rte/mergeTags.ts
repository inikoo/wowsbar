export default class MergeTag {
    constructor(sel, tags, options) {
        var self = this;
        options = options ? options : {};
        tags = tags ? tags : [
           'email','first Name','last Name','arya Test'
        ];
        options.open = self.getElm(options.open);
        options.openEvent = options.openEvent || "click";
        options.style = Object(options.style);
        options.style.display = options.style.display || "block";
        options.closeOnBlur = options.closeOnBlur || false;
        options.template = options.template || "<div data-col=\"{color}\" style='width:100%;>{color}</div>";
        self.elm = self.getElm(sel);
        self.cbs = [];
        self.tags = '';
        self.isOpen = true;
        self.tags = tags;
        self.options = options;
        self.render();
        if (options.open) {
            options.open.addEventListener(options.openEvent, ev => {
                self.isOpen ? self.close() : self.open();
            });
        }

        // Click on tags
        self.elm.addEventListener("click", ev => {
            var col = ev.target.getAttribute("data-col");
            if (!col) {
                return;
            }
            self.tags = col;
            self.set(col);
            self.close();
        });

        if (options.closeOnBlur) {
            window.addEventListener("click", ev => {
                if (ev.target != options.open && ev.target != self.elm && self.isOpen) {
                    self.close();
                }
            });
        }

        if (options.autoclose !== false) {
            self.close();
        }
    };

    getElm(el) {
        if (typeof el === "string") {
            return document.querySelector(el);
        }
        return el;
    };
    render() {
        var self = this,
            html = "";

        self.tags.forEach(c => {
            console.log(c)
            html += self.options.template.replace(/\{color\}/g, c);
        });

        self.elm.innerHTML = html;
    };

    close() {
        this.elm.style.display = "none";
        this.isOpen = false;
    };
    open() {
        this.elm.style.display = this.options.style.display;
        this.isOpen = true;
    };

    colorChosen(cb) {
        console.log(cb)
        this.cbs.push(cb);
    };

    set(c, p) {
        var self = this;
        self.color = c;
        if (p === false) {
            return;
        }
        self.cbs.forEach(cb => {
            cb && cb(c);
        });
        self.cbs = [];
    };
};