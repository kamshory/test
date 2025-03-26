let billOfQuantityId;
let proyekId;
let bukuHarianId;
let billOfQuantityProyekId;
let uploadCount = 0; // Counter to keep track of multiple uploads

jQuery(function($) {
    $(document).on('click', '.upload-image-boq', function(e) {
        billOfQuantityId = $(this).closest('tr').attr('data-boq-id');
        proyekId = $(this).closest('tr').attr('data-proyek-id');
        bukuHarianId = $(this).closest('tr').attr('data-buku-harian-id');
        billOfQuantityProyekId = $(this).closest('tr').attr('data-boq-proyek-id');

        // Trigger the hidden file input click event
        if ($('#file-upload').length > 0) {
            $('#file-upload').remove();
        }
        $('body').append('<input type="file" id="file-upload" accept=".jpg,.jpeg," style="display: none;" />');
        $('#file-upload').click();
    });

    // When a file is selected
    $(document).on('change', '#file-upload', function(e) {
        let fileInput = this;
        if (fileInput.files.length > 0) {
            let file = fileInput.files[0];
            let fileName = file.name.toLowerCase();
            let fileExtension = fileName.split('.').pop();

            // Check if the file extension is either jpeg or jpg
            if (fileExtension === 'jpg' || fileExtension === 'jpeg') {
                // Create FormData to send file and additional data
                let formData = new FormData();
                formData.append('image', fileInput.files[0]);
                formData.append('bukuHarianId', bukuHarianId);
                formData.append('billOfQuantityId', billOfQuantityId);
                formData.append('billOfQuantityProyekId', billOfQuantityProyekId);

                // Increment the upload count to generate a unique toast ID
                uploadCount++;

                // Create a unique toast container for each upload
                let toastId = 'upload_' + uploadCount;
                let toastHtml = `
                    <div class="toast" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true" data-delay="0">
                        <div class="toast-header">
                            <span class="mr-auto">Uploading ${file.name}</span>
                            <small class="text-muted">&nbsp;In Progress</small>
                        </div>
                        <div class="toast-body">
                            <div class="progress">
                                <div class="progress-bar" id="progress-bar-${uploadCount}" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                `;
                $('#toast-container').append(toastHtml);
                $('#' + toastId).toast('show');

                // Use FileReader to load the image and then resize it
                let reader = new FileReader();
                reader.onload = function(event) {
                    let img = new Image();
                    img.onload = function() {
                        // Create a canvas to resize the image
                        let canvas = document.createElement('canvas');
                        let ctx = canvas.getContext('2d');
                        let thumbnailSize = 100; // Final size of the thumbnail (100x100)

                        // Calculate the aspect ratio of the original image
                        let aspectRatio = img.width / img.height;

                        // Resize the image to fit within a 100x100 box, maintaining aspect ratio
                        if (aspectRatio < 1) {
                            // Landscape orientation (width < height)
                            canvas.width = thumbnailSize;
                            canvas.height = thumbnailSize / aspectRatio; // Adjust height based on aspect ratio
                        } else {
                            // Portrait orientation (height < width)
                            canvas.height = thumbnailSize;
                            canvas.width = thumbnailSize * aspectRatio; // Adjust width based on aspect ratio
                        }

                        // Ensure the image is resized and drawn to fill the canvas properly
                        ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, canvas.width, canvas.height);

                        // Now, crop the resized image to 100x100 (centered)
                        let offsetX = (canvas.width - thumbnailSize) / 2; // Center horizontally
                        let offsetY = (canvas.height - thumbnailSize) / 2; // Center vertically

                        // Create a new canvas for the final 100x100 thumbnail
                        let finalCanvas = document.createElement('canvas');
                        finalCanvas.width = thumbnailSize;
                        finalCanvas.height = thumbnailSize;

                        let finalCtx = finalCanvas.getContext('2d');

                        // Draw the cropped 100x100 area from the resized image (centered)
                        finalCtx.drawImage(canvas, offsetX, offsetY, thumbnailSize, thumbnailSize, 0, 0, thumbnailSize, thumbnailSize);

                        // Convert the final canvas to a data URL (image in base64 format)
                        let thumbnailDataUrl = finalCanvas.toDataURL('image/jpeg');

                        // Add the original image and thumbnail to FormData
                        formData.append('thumbnail', dataURLtoBlob(thumbnailDataUrl));

                        // Send the AJAX request to upload both the original and thumbnail
                        $.ajax({
                            url: 'lib.mobile-tools/ajax-upload-image.php', // PHP script that handles the file upload
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            xhr: function() {
                                let xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(e) {
                                    if (e.lengthComputable) {
                                        let percentComplete = (e.loaded / e.total) * 100;
                                        // Update the progress bar in the toast
                                        $('#progress-bar-' + uploadCount).css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(response) {
                                // File uploaded successfully, remove the progress bar and show success toast
                                $('#' + toastId).find('.toast-header small').html('&nbsp;Completed');
                                $('#' + toastId).find('.progress').hide();
                                $('#' + toastId).find('.toast-body').append('<div class="alert alert-success">File uploaded successfully!</div>');
                                setTimeout(function() {
                                    $('#' + toastId).toast('hide');
                                }, 2000); // Hide after 2 seconds
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                // File upload failed, show error message and remove the progress bar
                                $('#' + toastId).find('.toast-header small').html('&nbsp;Failed');
                                $('#' + toastId).find('.progress').hide();
                                $('#' + toastId).find('.toast-body').append('<div class="alert alert-danger">An error occurred during the upload.</div>');
                                setTimeout(function() {
                                    $('#' + toastId).toast('hide');
                                }, 2000); // Hide after 2 seconds
                            }
                        });
                    };
                    img.src = event.target.result; // Set the source of the image
                };
                reader.readAsDataURL(file); // Read the image file as a Data URL
            } else {
                alert('Hanya menerima file JPG dan JPEG.');
            }
        }
    });

    $(document).on('click', '.show-image-boq', function() {
        let proyekId = $(this).attr('data-proyek-id');
        let bukuHarianId = $(this).attr('data-buku-harian-id');
        let billOfQuantityId = $(this).attr('data-bill-of-quantity-id');
        $.ajax({
            url: 'lib.mobile-tools/ajax-load-galeri-proyek.php', // PHP script that handles the file upload
            type: 'GET',
            data: { proyekId: proyekId, bukuHarianId: bukuHarianId, billOfQuantityId: billOfQuantityId },
            success: function(response) {
                $('#galeri-proyek-modal .modal-body').html(response);
            },
            error: function(xhr, status, error) {}
        });
    });

});

// Helper function to convert DataURL to Blob
function dataURLtoBlob(dataURL) {
    let byteString = atob(dataURL.split(',')[1]);
    let arrayBuffer = new ArrayBuffer(byteString.length);
    let uintArray = new Uint8Array(arrayBuffer);

    for (let i = 0; i < byteString.length; i++) {
        uintArray[i] = byteString.charCodeAt(i);
    }

    return new Blob([uintArray], { type: 'image/jpeg' });
}