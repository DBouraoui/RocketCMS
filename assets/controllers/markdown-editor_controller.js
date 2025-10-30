import { Controller } from '@hotwired/stimulus';
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

export default class extends Controller {
    connect() {
        this.mde = new EasyMDE({
            element: this.element,
            spellChecker: false,
            placeholder: "Rédige ton contenu ici en Markdown...",
            toolbar: [
                "bold", "italic", "heading-1", "heading-2", "heading-3", "|",
                "quote", "unordered-list", "ordered-list", "|",
                "link", "image", "|",
                "preview", "side-by-side", "fullscreen", "|",
                "guide", "table"
            ],
            autosave: {
                enabled: true,
                uniqueId: "blog_content_autosave",
                delay: 1000,
            },
            status: ["lines", "words"],
            inputStyle: "contenteditable"
        });
    }

    disconnect() {
        if (this.mde) {
            this.mde.toTextArea();
            this.mde = null;
        }
    }
}
