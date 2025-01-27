jQuery(function (e) {
    initArea('area.cuaca-control');
    initMenu('.menu-cuaca-item a');
    renderCuaca('area.cuaca-control', dataCuaca);
    applyAttributes($('#jenis_pekerjaan_id'));

    $(document).on('change', 'select.resource-bill-of-quantity', function (e2) {
        let select = $(this);
        setMinMaxVolume(select);
    })

    $(document).on('change', '#jenis_pekerjaan_id', function (e) {
        let s_jenis_pekerjaan_id = $(this).val();
        applyAttributes($('#jenis_pekerjaan_id'));
        $.ajax({
            'url': 'lib.mobile-tools/ajax-load-peralatan-pekerjaan.php',
            'type': 'GET',
            'dataType': 'json',
            'data': { id: s_jenis_pekerjaan_id },
            success: function (data) {
                let i;
                let selector = $('select.resource-peralatan');
                selector.empty().append('<option value=""></option>');
                for (i in data) {
                    selector.append('<option value="' + data[i].v + '">' + data[i].l + '</option>');
                }
            }
        });
        $.ajax({
            'url': 'lib.mobile-tools/ajax-load-material-pekerjaan.php',
            'type': 'GET',
            'dataType': 'json',
            'data': { id: s_jenis_pekerjaan_id },
            success: function (data) {
                let i;
                let selector = $('select.resource-material');
                selector.empty().append('<option value=""></option>');
                for (i in data) {
                    selector.append('<option value="' + data[i].v + '">' + data[i].l + '</option>');
                }
            }
        });
    });

    $(document).on('change', '#bill_of_quantity_id', function (e) {

        let s_bill_of_quantity_id = $(this).val();
        let proyek_id = $(this).find('option:selected').attr('data-proyek-id');
        $.ajax({
            'url': 'lib.mobile-tools/ajax-load-bill-of-quantity.php',
            'type': 'GET',
            'dataType': 'html',
            'data': { proyek_id: proyek_id, parent_id: s_bill_of_quantity_id },
            success: function (data) {
                let select = $('.resource-bill-of-quantity');
                select.empty();
                select.append(data);
            }
        });
    });

    $(document).on('error', '#static-map-image', function (e) {
        $(this).replaceWith('<div data-role="error">Tidak bisa menampilkan peta.</div>');
    });

    $(document).on('change', '#lokasi_proyek_id', function (e) {
        detectLocation();
    });

    $(document).on('click', '.remover', function (e) {
        let tr = $(this).closest('tr');
        if (confirm('Apakah Anda akan menghapus item ini?')) {
            tr.remove();
        }
    });

    $(document).on('click', '#tambah-peralatan', function (e) {
        let res = $('.resource-peralatan')[0].outerHTML;
        let html = '<tr>\r\n' +
            '<td>' + res + '</td>\r\n' +
            '<td>\r\n' +
            '<input type="number" step="any" min="0" value="1" class="form-control" required="required">\r\n' +
            '</td>\r\n' +
            '<td>\r\n' +
            '<button type="button" class="btn btn-danger remove-peralatan">&#215;</button>\r\n' +
            '</td>\r\n' +
            '</tr>\r\n';
        let dom = $(html);
        dom.find('.resource-peralatan').attr({ 'required': 'required' });
        $('#tabel-peralatan tbody').append(dom);
        fixPeralatan();
    });

    $(document).on('click', '.remove-peralatan', function (e) {
        $(this).closest('tr').remove();
        fixPeralatan();
    });

    $(document).on('click', '#tambah-material', function (e) {
        let res = $('.resource-material')[0].outerHTML;
        let html = '<tr>\r\n' +
            '<td>' + res + '</td>\r\n' +
            '<td>\r\n' +
            '<input type="number" step="any" min="0" value="1" class="form-control" required="required">\r\n' +
            '</td>\r\n' +
            '<td>\r\n' +
            '<button type="button" class="btn btn-danger remove-material">&#215;</button>\r\n' +
            '</td>\r\n' +
            '</tr>\r\n';
        let dom = $(html);
        dom.find('.resource-material').attr({ 'required': 'required' });
        $('#tabel-material tbody').append(dom);
        fixMaterial();
    });

    $(document).on('click', '.remove-material', function (e) {
        $(this).closest('tr').remove();
        fixMaterial();
    });

    $(document).on('click', '#tambah-boq', function (e) {
        let res = $('.resource-bill-of-quantity')[0].outerHTML;
        let html = '<tr>\r\n' +
            '<td>' + res + '</td>\r\n' +
            '<td>\r\n' +
            '<input type="number" step="any" min="0" value="" class="form-control volume" required="required">\r\n' +
            '</td>\r\n' +
            '<td>\r\n' +
            '<button type="button" class="btn btn-danger remove-boq">&#215;</button>\r\n' +
            '</td>\r\n' +
            '</tr>\r\n';
        let dom = $(html);
        dom.find('.resource-bill-of-quantity').attr({ 'required': 'required' });
        $('#tabel-boq tbody').append(dom);
        setMinMaxVolume(dom.find('select'));
        fixBoq();
    });

    $(document).on('click', '.remove-boq', function (e) {
        $(this).closest('tr').remove();
        fixBoq();
    });

    $(document).on('click', '#tambah-man-power', function (e) {
        let res = $('.resource-man-power')[0].outerHTML;
        let html = '<tr>\r\n' +
            '<td>' + res + '</td>\r\n' +
            '<td>\r\n' +
            '<input type="number" step="any" min="1" value="1" class="form-control" required="required">\r\n' +
            '</td>\r\n' +
            '<td>\r\n' +
            '<button type="button" class="btn btn-danger remove-man-power">&#215;</button>\r\n' +
            '</td>\r\n' +
            '</tr>\r\n';
        let dom = $(html);
        dom.find('.resource-man-power').attr({ 'required': 'required' });
        $('#tabel-man-power tbody').append(dom);
        fixManPower();
    });

    $(document).on('click', '.remove-man-power', function (e) {
        $(this).closest('tr').remove();
        fixManPower();
    });

    $(document).on('change', '.resource-man-power', function (e) {
        let opt = $(this);
        let tr = opt.closest('tr');
        let te = tr.find('[type="number"]');
        let jumlahPekerja = opt.find('option:selected').attr('data-jumlah-pekerja');
        if (jumlahPekerja !== undefined && jumlahPekerja !== null) {
            te.val(jumlahPekerja);
        } else {
            te.val('');
        }
        setTotal();
    });

    $(document).on('change', '#tabel-man-power tbody [type="number"]', function (e) {
        setTotal();
    });

    $(document).on('click', '.remove-issue', function (e) {
        let table = $(this).closest('table');
        if ($(this).closest('tbody').find('tr').length > 1) {
            $(this).closest('tr').remove();
        }
        fixRekomendasiPermasalahan(table);
    });

    $(document).on('click', '.add-issue', function (e) {
        let table = $(this).closest('table');
        let last = $(this).closest('table').find('tbody tr:last-child').clone();
        $(this).closest('table').find('tbody tr:last-child').after(last);
        fixRekomendasiPermasalahan(table);
    });

    $(document).on('change', '.table-permasalahan [data-col-name="ditutup"] input[type="checkbox"]', function () {
        if ($(this).prop('checked')) {
            closeIssue($(this).closest('tr').attr('data-primary-key'));
        }
        else {
            openIssue($(this).closest('tr').attr('data-primary-key'));
        }
    });

    $(document).on('change', '.table-rekomendasi [data-col-name="ditutup"] input[type="checkbox"]', function () {
        if ($(this).prop('checked')) {
            closeRecomendation($(this).closest('tr').attr('data-primary-key'));
        }
        else {
            openRecomendation($(this).closest('tr').attr('data-primary-key'));
        }
    });

    $('#permasalahan-modal').on('shown.bs.modal', function () {
        let modalElem = $('#permasalahan-modal');
        modalElem.find('.add-issue, .save-issue, .cancel-issue').prop('disabled', true);
        let proyekId = $(this).attr('data-proyek-id');
        $.ajax({
            url: 'lib.mobile-tools/ajax-load-permasalahan.php',
            method: 'GET',
            data: { proyek_id: proyekId },
            success: function (response) {
                modalElem.find('.modal-body').html(response);
                modalElem.find('.add-issue, .save-issue, .cancel-issue').prop('disabled', false);
            },
            error: function () {
                modalElem.find('.modal-body').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
            }
        });
    });

    $(document).on('click', '.add-issue', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.attr('data-mode', 'edit');
        modalElem.find('[name="permasalahan_id"]').val('');
    });

    $(document).on('click', '.edit-issue', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.attr('data-mode', 'edit');
        modalElem.find('[name="permasalahan_id"]').val($(this).closest('tr').attr('data-permasalahan-id'));
        modalElem.find('[name="permasalahan"]').val($(this).closest('tr').attr('data-permasalahan'));
        modalElem.find('[name="rekomendasi"]').val($(this).closest('tr').attr('data-rekomendasi'));
        modalElem.find('[name="tindak_lanjut"]').val($(this).closest('tr').attr('data-tindak-lanjut'));
    });

    $(document).on('click', '.save-issue', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.find('.add-issue, .save-issue, .cancel-issue').prop('disabled', true);
        let proyek_id = modalElem.attr('data-proyek-id');
        let permasalahan_id = modalElem.find('[name="permasalahan_id"]').val();
        let permasalahan = modalElem.find('[name="permasalahan"]').val();
        let rekomendasi = modalElem.find('[name="rekomendasi"]').val();
        let tindak_lanjut = modalElem.find('[name="tindak_lanjut"]').val();
        let url = 'lib.mobile-tools/ajax-load-permasalahan.php?proyek_id=' + proyek_id;

        $.ajax({
            url: url,
            method: 'POST',
            data: { 
                proyek_id: proyek_id, 
                permasalahan: permasalahan, 
                rekomendasi: rekomendasi, 
                tindak_lanjut: tindak_lanjut, 
                permasalahan_id: permasalahan_id 
            },
            success: function (response) {
                modalElem.find('.modal-body').html(response);
                modalElem.find('.add-issue, .save-issue, .cancel-issue').prop('disabled', false);
                modalElem.attr('data-mode', 'list');

                while ($('#permasalahan_id option').length > 1) {
                    $('#permasalahan_id option:last').remove();
                }

                modalElem.find('table tbody tr').each(function (e2) {
                    let newValue = $(this).attr('data-permasalahan-id');  
                    let newLabel = $(this).attr('data-permasalahan') + " : " + $(this).attr('data-rekomendasi') + " : " + $(this).attr('data-tindak-lanjut');  
                    let newOption = $('<option>', {
                        value: newValue,  
                        text: newLabel    
                    });

                    $('#permasalahan_id').append(newOption);
                });
            },
            error: function () {
                $('.modal-body').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
            }
        });
    });

    $(document).on('click', '.cancel-issue', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.attr('data-mode', 'list');
        modalElem.find('[name="permasalahan_id"]').val('');
    });

    $('#rekomendasi-modal').on('shown.bs.modal', function () {
        let modalElem = $('#rekomendasi-modal');
        modalElem.find('.add-recommendation, .save-recommendation, .cancel-recommendation').prop('disabled', true);
        let proyekId = $(this).attr('data-proyek-id');
        $.ajax({
            url: 'lib.mobile-tools/ajax-load-rekomendasi.php',
            method: 'GET',
            data: { proyek_id: proyekId },
            success: function (response) {
                modalElem.find('.modal-body').html(response);
                modalElem.find('.add-recommendation, .save-recommendation, .cancel-recommendation').prop('disabled', false);
            },
            error: function () {
                modalElem.find('.modal-body').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
            }
        });
    });

    $(document).on('click', '.add-recommendation', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.attr('data-mode', 'edit');
        modalElem.find('[name="rekomendasi_id"]').val('');
    });

    $(document).on('click', '.edit-recommendation', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.attr('data-mode', 'edit');
        modalElem.find('[name="rekomendasi_id"]').val($(this).closest('tr').attr('data-rekomendasi-id'));
        modalElem.find('[name="rekomendasi"]').val($(this).closest('tr').attr('data-rekomendasi'));
        console.log($(this).closest('tr').attr('data-rekomendasi'))
    });

    $(document).on('click', '.save-recommendation', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.find('.add-recommendation, .save-recommendation, .cancel-recommendation').prop('disabled', true);
        let proyek_id = modalElem.attr('data-proyek-id');
        let rekomendasi_id = modalElem.find('[name="rekomendasi_id"]').val();
        let rekomendasi = modalElem.find('[name="rekomendasi"]').val();
        let url = 'lib.mobile-tools/ajax-load-rekomendasi.php?proyek_id=' + proyek_id;

        $.ajax({
            url: url,
            method: 'POST',
            data: { proyek_id: proyek_id, rekomendasi: rekomendasi, rekomendasi_id: rekomendasi_id },
            success: function (response) {
                modalElem.find('.modal-body').html(response);
                modalElem.find('.add-recommendation, .save-recommendation, .cancel-recommendation').prop('disabled', false);
                modalElem.attr('data-mode', 'list');

                while ($('#rekomendasi_id option').length > 1) {
                    $('#rekomendasi_id option:last').remove();
                }

                modalElem.find('table tbody tr').each(function (e2) {
                    let newValue = $(this).attr('data-rekomendasi-id');  
                    let newLabel = $(this).attr('data-rekomendasi');  
                    let newOption = $('<option>', {
                        value: newValue,  
                        text: newLabel
                    });

                    $('#rekomendasi_id').append(newOption);
                });

            },
            error: function () {
                $('.modal-body').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
            }
        });
    });

    $(document).on('click', '.cancel-recommendation', function (e) {
        let modalElem = $(this).closest('.modal');
        modalElem.attr('data-mode', 'list');
        modalElem.find('[name="rekomendasi_id"]').val('');
    });

    $.ajax({
        'url': 'lib.mobile-tools/ajax-load-man-power.php',
        'type': 'GET',
        'dataType': 'html',
        'data': { proyek_id: $('[name="proyek_id"]').val() },
        success: function (data) {
            let select = $('.resource-man-power');
            select.empty();
            select.append(data);
        }
    });

    $('.resource-peralatan').load('lib.mobile-tools/ajax-load-peralatan.php');
    $('.resource-material').load('lib.mobile-tools/ajax-load-material.php');
    loadAcuanPengawasan();

    document.querySelector('#tabel-boq').addEventListener('change', function (event) {
        if (event.target.closest('.resource-bill-of-quantity')) {
            loadAcuanPengawasan();
        }
    });

    document.querySelector('[name="proyek_id"]').addEventListener('change', function (event) {
        loadAcuanPengawasan();
    });

});

function fixMaterial() {
    $('#tabel-material tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `material_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_material[${index}]`);
    });
}
function fixPeralatan() {
    $('#tabel-peralatan tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `peralatan_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_peralatan[${index}]`);
    });
}
function fixBoq() {
    $('#tabel-boq tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `boq_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_boq[${index}]`);
    });
}

function fixManPower() {
    $('#tabel-man-power tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `man_power_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_man_power[${index}]`);
    });
}

function setTotal() {
    let total = 0;
    $('#tabel-man-power tbody').find('tr').each(function (e) {
        let val = parseInt($(this).find('[type="number"]').val());
        if (isNaN(val)) {
            val = 0;
        }
        total += val;
    });
    $('#total-man-power').text(`Total ${total} orang`);
}

let geolocationOptions = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

function setMinMaxVolume(select) {
    let maxStr = select.find('option:selected').attr('data-volume');
    let minStr = select.find('option:selected').attr('data-volume-proyek');
    if (maxStr == '') {
        maxStr = '0';
    }
    if (minStr == '') {
        minStr = '0';
    }
    let maxVal = parseFloat(maxStr);
    let minVal = parseFloat(minStr);
    let volume = select.closest('tr').find('input.volume');
    volume.attr('min', minVal);
    volume.attr('max', maxVal);
}

function detectLocation() {
    navigator.geolocation.getCurrentPosition(onSuccess, onError, geolocationOptions);
}

function onSuccess(position) {
    $('[name="latitude"]').val(position.coords.latitude);
    $('[name="longitude"]').val(position.coords.longitude);
    $('[name="altitude"]').val(position.coords.altitude);
}

function onError(error) {
    let errorMessage = "";
    switch (error.code) {
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

function applyAttributes(obj) {
    let s_tipe_pondasi_id = obj.find('option:selected').attr('data-tipe-pondasi-id');
    let s_kelas_tower_id = obj.find('option:selected').attr('data-kelas-tower-id');
    let s_lokasi_proyek_id = obj.find('option:selected').attr('data-lokasi-proyek-id');
    let s_kegiatan = obj.find('option:selected').attr('data-kegiatan');

    if (s_tipe_pondasi_id == '0') {
        $('#tipe_pondasi_id').val('').removeAttr('required');
        $('#tipe_pondasi_id').closest('tr').css('display', 'none');
    }
    else {
        $('#tipe_pondasi_id').attr('required', 'required');
        $('#tipe_pondasi_id').closest('tr').css('display', 'table-row');
    }
    if (s_kelas_tower_id == '0') {
        $('#kelas_tower_id').val('').removeAttr('required');
        $('#kelas_tower_id').closest('tr').css('display', 'none');
    }
    else {
        $('#kelas_tower_id').attr('required', 'required');
        $('#kelas_tower_id').closest('tr').css('display', 'table-row');
    }
    if (s_lokasi_proyek_id == '0') {
        $('#lokasi_proyek_id').val('').removeAttr('required');
        $('#lokasi_proyek_id').closest('tr').css('display', 'none');
        $('#tambah-lokasi').closest('tr').css('display', 'none');
    }
    else {
        $('#lokasi_proyek_id').attr('required', 'required');
        $('#lokasi_proyek_id').closest('tr').css('display', 'table-row');
        $('#tambah-lokasi').closest('tr').css('display', 'table-row');
    }
    if (s_kegiatan == '0') {
        $('#kegiatan').val('').removeAttr('required');
        $('#kegiatan').closest('tr').css('display', 'none');
    }
    else {
        $('#kegiatan').attr('required', 'required');
        $('#kegiatan').closest('tr').css('display', 'table-row');
    }
}

function tambahLokasi() {
    let nama = $('form[name="formlokasi_proyek"] [name="nama"]').val().trim();
    let kode_lokasi = $('form[name="formlokasi_proyek"] [name="kode_lokasi"]').val().trim();
    let proyek_id = $('form[name="formlokasi_proyek"] [name="proyek_id"]').val().trim();
    let latitude = $('form[name="formlokasi_proyek"] [name="latitude"]').val().trim();
    let longitude = $('form[name="formlokasi_proyek"] [name="longitude"]').val().trim();
    let atitude = $('form[name="formlokasi_proyek"] [name="atitude"]').val().trim();

    if (nama != '' && proyek_id != '') {
        $.ajax({
            url: 'lib.mobile-tools/ajax-add-lokasi-proyek.php',
            type: 'POST',
            dataType: "json",
            data: { 
                action: 'add', 
                nama: nama, 
                kode_lokasi: kode_lokasi, 
                proyek_id: proyek_id, 
                latitude: latitude, 
                longitude: longitude, 
                atitude: atitude 
            },
            success: function (data) {
                let obj = $('[name="lokasi_proyek_id"]');
                obj.empty();
                let i;
                for (i in data) {
                    obj.append('<option value="' + data[i].v + '">' + data[i].l + '</option>');
                }
                $('#addLocation').removeAttr('disabled');
                $('#add-location-modal').modal('hide')
            }
        });
    }
    return false;
}

function fixRekomendasiPermasalahan(table) {
    table.find('tbody').find('tr').each(function (index) {
        $(this).find(`[data-name="permasalahan_id"]`).attr('name', `permasalahan_id[${index}]`);
        $(this).find(`[data-name="rekomendasi_id"]`).attr('name', `rekomendasi_id[${index}]`);
    });
}

function closeIssue(id) {
    issue(id, 1);
}

function openIssue(id) {
    issue(id, 0);
}

function issue(id, status) {
    $.ajax({
        "method": "POST",
        "url": "lib.mobile-tools/ajax-load-permasalahan.php",
        "data": { user_action: 'set-status', permasalahan_id: id, status: status },
        function(data) {

        }
    });
}

function closeRecomendation(id) {
    recomendation(id, 1);
}

function openRecomendation(id) {
    recomendation(id, 0);
}

function recomendation(id, status) {
    $.ajax({
        "method": "POST",
        "url": "lib.mobile-tools/ajax-load-rekomendasi.php",
        "data": { user_action: 'set-status', rekomendasi_id: id, status: status },
        function(data) {

        }
    });
}
function loadAcuanPengawasan() {
    let proyekId = document.querySelector('[name="proyek_id"]').value;
    let billOfQuantity = [];
    let boqs = document.querySelector('#tabel-boq').querySelectorAll('.resource-bill-of-quantity');
    if (boqs) {
        boqs.forEach((elem, index) => {
            if (elem.value != '') {
                billOfQuantity.push(elem.value);
            }
        });
    }
    $.ajax({
        'method': 'GET',
        'url': 'lib.mobile-tools/ajax-load-acuan-pengawasan-pekerjaan.php',
        'data': { proyekId: proyekId, billOfQuantityId: billOfQuantity },
        'success': function (data) {
            document.querySelector('.acuan-pengawasan-container').innerHTML = data;
        }
    });
}