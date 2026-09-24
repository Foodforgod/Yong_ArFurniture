document.addEventListener('DOMContentLoaded', () => {
    const modelViewer = document.querySelector('model-viewer');
    if (modelViewer) {
        modelViewer.addEventListener('error', (event) => {
            console.error('Model Viewer Error:', event);
        });
    }
});