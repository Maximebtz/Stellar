document.addEventListener('DOMContentLoaded', function () {
    let fileInput = document.querySelector('#advert_images');
    let dropArea = document.querySelector('#drop-area');
    let fileList = document.querySelector('.file-list');

    function addFileToList(file) {
        let listItem = document.createElement('div');
        let imagePreview = document.createElement('img');
        let imgFigure = document.createElement('figure');
        let fileName = document.createElement('span');
        let removeButton = document.createElement('button');

        listItem.classList.add('file-list-item');
        imagePreview.classList.add('file-preview');
        imgFigure.classList.add('file-figure');
        fileName.classList.add('file-name');
        removeButton.classList.add('remove-file-button');

        imagePreview.src = URL.createObjectURL(file);
        fileName.textContent = file.name;
        removeButton.textContent = 'x';

        listItem.appendChild(imgFigure)
        listItem.appendChild(fileName);
        listItem.appendChild(removeButton);
        imgFigure.appendChild(imagePreview);
        fileList.appendChild(listItem);

        removeButton.addEventListener('click', function () {
            listItem.remove();
            updateFileInput();
        });
    }

    function updateFileInput() {
        let newFileList = new DataTransfer();
        let fileItems = document.querySelectorAll('.file-list-item');
        fileItems.forEach(function (item) {
            let fileName = item.querySelector('.file-name').textContent;
            fileInput.files.forEach(function (file) {
                if (file.name !== fileName) {
                    newFileList.items.add(file);
                }
            });
        });
        fileInput.files = newFileList.files;
    }

    // Gérer le glisser-déposer
    dropArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });
    dropArea.addEventListener('dragleave', function () {
        dropArea.classList.remove('dragover');
    });
    dropArea.addEventListener('drop', function (e) {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        let droppedFiles = e.dataTransfer.files;
        Array.from(droppedFiles).forEach(function (file) {
            addFileToList(file);
        });
        updateFileInput();
    });

    // Gérer le changement de fichier via l'input
    fileInput.addEventListener('change', function () {
        let selectedFiles = fileInput.files;
        Array.from(selectedFiles).forEach(function (file) {
            addFileToList(file);
        });
    });
});
