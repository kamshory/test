var options = {
enableHighAccuracy: true,
timeout: 5000,
maximumAge: 0
};
function detectLocation()
{
    navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
}
function onSuccess(position)
{
    $('[name="latitude"]').val(position.coords.latitude);
    $('[name="longitude"]').val(position.coords.longitude);
    $('[name="altitude"]').val(position.coords.altitude);
}
function onError(error)
{
    var errorMessage = "";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            errorMessage = "Pengguna menolak permintaan geolokasi."
            break;
        case error.POSITION_UNAVAILABLE:
            errorMessage = "Geolokasi tidak tersedia."
            break;
        case error.TIMEOUT:
            errorMessage = "Tenggang waktu permintaan geolokasi telah habis."
            break;
        case error.UNKNOWN_ERROR:
            errorMessage = "Terjadi kesalahan yang tidak diketahui."
            break;
    }
    alert(errorMessage);
}