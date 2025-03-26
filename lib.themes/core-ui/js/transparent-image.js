document.addEventListener('DOMContentLoaded', function () {
    $('.signature-image').each(function(){
        let el = $(this);
        let canvas = document.createElement('canvas');
        let ctx = canvas.getContext('2d');
        let image = new Image();
        let originalUrl = el.attr('src');
        image.src = originalUrl;
        image.onload = function() {
            canvas.width = image.width;
            canvas.height = image.height;
            ctx.drawImage(image, 0, 0);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let pixels = imageData.data;
            for (let i = 0; i < pixels.length; i += 4) {
                if (pixels[i] > 250 && pixels[i + 1] > 250 && pixels[i + 2] > 250) {
                    pixels[i + 3] = 0;
                }
            }
            ctx.putImageData(imageData, 0, 0);
            el.attr('data-original-url', originalUrl);
            el.attr('src', canvas.toDataURL());
        };
    });
});