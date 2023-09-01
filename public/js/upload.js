let isAdvancedUpload = function() {
    let div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
  }();
  
  let draggableFileArea = document.querySelector(".drag-file-area");
  let browseFileText = document.querySelector(".browse-files");
  let dragDropText = document.querySelector(".dynamic-message");
  let fileInput = document.querySelector(".image-input");
  let cannotUploadMessage = document.querySelector(".cannot-upload-message");
  let cancelAlertButton = document.querySelector(".cancel-alert-button");
  let uploadedFile = document.querySelector(".file-block");
  let fileName = document.querySelector(".file-name");
  let fileSize = document.querySelector(".file-size");
  let progressBar = document.querySelector(".progress-bar");
  let removeFileButton = document.querySelector(".remove-file-icon");
  let fileFlag = 0;
  
  fileInput.addEventListener("click", () => {
      fileInput.value = '';
      console.log(fileInput.value);
  });
  
  fileInput.addEventListener("change", e => {
      console.log(" > " + fileInput.value)
      dragDropText.innerHTML = 'File Dropped Successfully!';
      document.querySelector(".label").innerHTML = `Title *`;
    //   uploadButton.innerHTML = `Upload`;
      fileName.innerHTML = fileInput.files[0].name;
      fileSize.innerHTML = (fileInput.files[0].size/1024).toFixed(1) + " KB";
      uploadedFile.style.cssText = "display: flex;";
      progressBar.style.width = 0;
      fileFlag = 0;
  });

  
  cancelAlertButton.addEventListener("click", () => {
      cannotUploadMessage.style.cssText = "display: none;";
  });
  
  if(isAdvancedUpload) {
      ["drag", "dragstart", "dragend", "dragover", "dragenter", "dragleave", "drop"].forEach( evt => 
          draggableFileArea.addEventListener(evt, e => {
              e.preventDefault();
              e.stopPropagation();
          })
      );
  
      ["dragover", "dragenter"].forEach( evt => {
          draggableFileArea.addEventListener(evt, e => {
              e.preventDefault();
              e.stopPropagation();
              dragDropText.innerHTML = 'Drop your file here!';
          });
      });
  
      draggableFileArea.addEventListener("drop", e => {
          dragDropText.innerHTML = 'File Dropped Successfully!';
          document.querySelector(".label").innerHTML = `drag & drop or <span class="browse-files"> <input type="file" class="default-file-input" style=""/> <span class="browse-files-text" style="top: -23px; left: -20px;"> browse file</span> </span>`;
        //   uploadButton.innerHTML = `Upload`;
          
          let files = e.dataTransfer.files;
          fileInput.files = files;
          console.log(files[0].name + " " + files[0].size);
          console.log(document.querySelector(".default-file-input").value);
          fileName.innerHTML = files[0].name;
          fileSize.innerHTML = (files[0].size/1024).toFixed(1) + " KB";
          uploadedFile.style.cssText = "display: flex;";
          progressBar.style.width = 0;
          fileFlag = 0;
      });
  }
  
  removeFileButton.addEventListener("click", () => {
      uploadedFile.style.cssText = "display: none;";
      fileInput.value = '';
  });