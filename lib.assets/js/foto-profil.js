
let ratio = 1 / 1;

let currentLatitude = null;
let currentLongitude = null;

// Get HTML elements
let videoContainer = null;
let video = null;
let sourceCanvas = null;
let croppedCanvas = null;

// Get 2D contexts for canvases
let ctxSource = null;
let ctxCropped = null;

// Default video dimensions
let videoWidth = 320;
let videoHeight = 320;

let width = 320;
let height = 320;

let shutter;

/**
 * Sets dimensions for the canvases based on width.
 * 
 * @param {number} width - The width to set for the elements.
 * @param {number} ratio - The aspect ratio to maintain.
 */
function setDimension(width, ratio) {
    croppedCanvas.width = width;

    croppedCanvas.style.width = width + 'px';
    videoContainer.style.width = width + 'px';

    let height = width / ratio;

    sourceCanvas.height = height;

    let width2 = videoHeight * ratio;
    sourceCanvas.width = width2;

    sourceCanvas.style.width = width2 + 'px';

    sourceCanvas.style.height = height + 'px';
    croppedCanvas.style.height = height + 'px';
    videoContainer.style.height = height + 'px';

}


function detectGeolocation(callbackFunction) {
    // Check if geolocation is available
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            // Get latitude and longitude
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Set hidden inputs
            if (callbackFunction != null) {
                callbackFunction(latitude, longitude);
            }
        }, error => {
            console.error('Error getting location:', error);
        });
    } else {
        console.log('Geolocation is not supported by this browser.');
    }
}

async function setupCamera() {
    let stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;

    video.onloadedmetadata = () => {
        // Calculate scale and adjust dimensions
        let scale = height / video.videoHeight;
        videoWidth = video.videoWidth * scale;
        videoHeight = video.videoHeight * scale;

        video.width = videoWidth;
        video.height = videoHeight;

        let width2 = height * videoWidth / videoHeight;

        sourceCanvas.height = height;
        sourceCanvas.width = width2;
        sourceCanvas.style.height = height + 'px';
        sourceCanvas.style.width = width2 + 'px';


        croppedCanvas.style.height = height + 'px';
        videoContainer.style.height = height + 'px';
        videoContainer.style.width = width + 'px';

        document.getElementById('captureButton').disabled = false;

    };
}


// Wait for the DOM to fully load before setting up event listeners
document.addEventListener("DOMContentLoaded", function() {
    shutter = new Audio('lib.assets/sounds/camera.mp3');
    videoContainer = document.getElementById('videoContainer');
    video = document.getElementById('video');
    sourceCanvas = document.getElementById('sourceCanvas');
    croppedCanvas = document.getElementById('croppedCanvas');

    // Get 2D contexts for canvases
    ctxSource = sourceCanvas.getContext('2d');
    ctxCropped = croppedCanvas.getContext('2d');

    // Default video dimensions
    videoWidth = 320;
    videoHeight = 320;

    document.getElementById('captureButton').addEventListener('click', () => {
        // Capture image from video
        shutter.play();
        ctxSource.drawImage(video, 0, 0, sourceCanvas.width, sourceCanvas.height);

        let sourceWidth = sourceCanvas.width;
        let sourceHeight = sourceCanvas.height;

        let cropWidth = sourceHeight * ratio;
        let cropHeight = sourceHeight;

        croppedCanvas.width = cropWidth;
        croppedCanvas.height = cropHeight;

        let cropX = (sourceWidth - cropWidth) / 2;
        let cropY = (sourceHeight - cropHeight) / 2; // Start from the top

        // Draw cropped image onto the new canvas
        ctxCropped.drawImage(sourceCanvas, cropX, cropY, cropWidth, cropHeight, 0, 0, croppedCanvas.width, croppedCanvas.height);
        document.querySelector('#clearButton').disabled = false;
        document.querySelector('#uploadButton').disabled = false;
    });

    document.getElementById('clearButton').addEventListener('click', () => {
        // Clear canvas
        ctxCropped.clearRect(0, 0, croppedCanvas.width, croppedCanvas.height);
        document.querySelector('#clearButton').disabled = true;
        document.querySelector('#uploadButton').disabled = true;
    });

    

    document.getElementById('uploadButton').addEventListener('click', (e) => {

        // Convert cropped canvas to a blob and upload
        croppedCanvas.toBlob((blob) => {
            const formData = new FormData($('#form-foto-rofil')[0]);
            formData.append('user_action', 'create');
            formData.append('file', blob, 'image.jpeg');
        
            // Show progress bar
            $('#progressContainer').show();
    
            // Create a new XMLHttpRequest
            const xhr = new XMLHttpRequest();
    
            // Show progress
            xhr.upload.addEventListener('progress', (event) => {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    $('#progressBar').val(percentComplete);
                    $('#progressPercent').text(Math.round(percentComplete) + '%');
                }
            });
    
            // Set up the request
            xhr.open('POST', 'foto-profil.php', true);
    
            // Handle response
            xhr.onload = () => {
                if (xhr.status === 200) {
                    window.location = 'foto-profil.php';
                } else {
                    alert('Failed to upload image.');
                }
            };
    
            // Handle error
            xhr.onerror = () => {
                console.error('Error:', xhr.statusText);
                alert('Error uploading image.');
            };
    
            // Send the request
            xhr.send(formData);
        }, 'image/jpeg', 0.75);
    });    

    

    // Initialize camera setup
    setupCamera();

});
