let wrapper = document.getElementById("signature-pad");
let clearButton = wrapper.querySelector("[data-action=clear]");
let savePNGButton = wrapper.querySelector("[data-action=save-png]");
let canvas = wrapper.querySelector("canvas");
let signaturePad = new SignaturePad(canvas, {
    // It's Necessary to use an opaque color when saving image as JPEG;
    // this option can be omitted if only saving as PNG or SVG
    backgroundColor: 'rgb(255, 255, 255, 0)',
    minWidth: 2,
    maxWidth: 4,
    onEnd: function(){
        document.querySelector('.signature-save').disabled = false;
    }
});

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    let ratio = Math.max(window.devicePixelRatio || 1, 1);

    // This part causes the canvas to be cleared
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    // This library does not listen for canvas changes, so after the canvas is automatically
    // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
    // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
    // that the state of this library is consistent with visual state of the canvas, you
    // have to clear it manually.
    signaturePad.clear();
}

// On mobile devices it might make more sense to listen to orientation change,
// rather than window resize events.
window.onresize = resizeCanvas;
resizeCanvas();

function download(dataURL, filename) {
    let blob = dataURLToBlob(dataURL);
    let url = window.URL.createObjectURL(blob);
    let a = document.createElement("a");
    a.style = "display: none";
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
}

// One could simply use Canvas#toBlob method instead, but it's just to show
// that it can be done using result of SignaturePad#toDataURL.
function dataURLToBlob(dataURL) {
    // Code taken from https://github.com/ebidel/filer.js
    let parts = dataURL.split(';base64,');
    let contentType = parts[0].split(":")[1];
    let raw = window.atob(parts[1]);
    let rawLength = raw.length;
    let uInt8Array = new Uint8Array(rawLength);
    for (let i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }
    return new Blob([uInt8Array], { type: contentType });
}

function getFinalSignature(maxWidth, maxHeight) {
    return processSignature(canvas.width, canvas.height, maxWidth, maxHeight);
}
function processSignature(imgWidth, imgHeight, maxWidth, maxHeight) {
    let ctx = canvas.getContext('2d');
    let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    let data = imageData.data;

    // Mencari batas trim
    let top = null, bottom = null, left = null, right = null;

    // mencari left
    for (let x = 0; x < imgWidth && left == null; x++) {
        for (let y = 0; y < imgHeight; y++) {
            let alpha = data[(y * imgWidth + x) * 4 + 3];
            if (alpha !== 0) {
                left = x;
                break;
            }
        }
    }
    // mencari right
    for (let x = imgWidth - 1; x >= 0 && right == null; x--) {
        for (let y = 0; y < imgHeight; y++) {
            let alpha = data[(y * imgWidth + x) * 4 + 3];
            if (alpha !== 0) {
                right = x;
                break;
            }
        }
    }
    // mencari top
    for (let y = 0; y < imgHeight && top == null; y++) {
        for (let x = 0; x < imgWidth; x++) {
            let alpha = data[(y * imgWidth + x) * 4 + 3];
            if (alpha !== 0) {
                top = y;
                break;
            }
        }
    }
    // mencari bottom
    for (let y = imgHeight - 1; y >= 0 && bottom == null; y--) {
        for (let x = 0; x < imgWidth; x++) {
            let alpha = data[(y * imgWidth + x) * 4 + 3];
            if (alpha !== 0) {
                bottom = y;
                break;
            }
        }
    }
    let canvas2 = document.createElement('canvas');
    canvas2.width = imgWidth;
    canvas2.height = imgHeight;
    let ctx2 = canvas.getContext('2d');
    ctx2.putImageData(imageData, 0, 0);
    // Membuat canvas baru untuk hasil trim
    let trimCanvas = document.createElement('canvas');

    let trimCanvasWidth = right - left + 1;
    let trimCanvasHeight = bottom - top + 1;

    trimCanvas.width = trimCanvasWidth;
    trimCanvas.height = trimCanvasHeight;
    let trimCtx = trimCanvas.getContext('2d');
    // Mengambil data gambar dari area trim
    let trimImageData = ctx2.getImageData(left, top, trimCanvas.width, trimCanvas.height);
    // Menggambar data gambar ke canvas trim
    trimCtx.putImageData(trimImageData, 0, 0);

    // Hitung rasio untuk mempertahankan rasio aspek
    let ratio = 1;

    if (trimCanvasWidth > maxWidth) {
        // perkecil
        ratio = maxWidth / trimCanvasWidth;
    }
    if (trimCanvasHeight > maxHeight && ratio > maxHeight / trimCanvasHeight) {
        // perkecil lagi
        ratio = maxHeight / trimCanvasHeight;
    }
    let newWidth = parseInt(trimCanvasWidth * ratio);
    let newHeight = parseInt(trimCanvasHeight * ratio);


    let resizedImageData = resizeImageData(trimImageData, newWidth, newHeight, 'bilinear-interpolation');

    let resizedCanvas = document.createElement('canvas');
    let resizedCtx = resizedCanvas.getContext('2d');
    resizedCanvas.width = maxWidth;
    resizedCanvas.height = maxHeight;

    let mx = Math.round((maxWidth - newWidth) / 2);
    let my = Math.round((maxHeight - newHeight) / 2);

    resizedCtx.putImageData(resizedImageData, mx, my);
    return { canvas: resizedCanvas, left: left, right: right, top: top, bottom: bottom, width: resizedCanvas.width, height: resizedCanvas.height };

}

clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
    document.querySelector('.signature-save').disabled = true;
});

savePNGButton.addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        alert("Please provide a signature first.");
    } else {
        let result = getFinalSignature(240, 240);
        let dataUrl = result.canvas.toDataURL();
        
        // Konversi data URL ke Blob
        fetch(dataUrl)
            .then(res => res.blob())
            .then(blob => {
                // Siapkan FormData
                const formData = new FormData();
                formData.append('file', blob, 'file.png');

                // Kirim ke server
                fetch(saveSignatureUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    
                })
                .catch(error => {
                    console.error('Error uploading signature:', error);
                });
            });
    }
});
