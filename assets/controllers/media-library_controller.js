import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        console.log("media library charged")
        const fileInput = document.querySelector('input[type="file"]');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');

        if (fileInput) {
            fileInput.addEventListener('change', (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewContainer.classList.remove('hidden');
                        previewImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        }
    }

    disconnect() {

    }
}
