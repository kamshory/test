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
        modalElem.attr('data-mode', 'list');
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
            error: function (error) {
                modalElem.find('.modal-body').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
            }
        });
    });
    $('#permasalahan-modal').on('hidden.bs.modal', function (e) {
        emptyOption($('[data-name="permasalahan_id"]'));
        let modalElem = $('#permasalahan-modal');
        modalElem.find('table tbody tr').each(function (e2) {
            let currentValue = $(this).attr('data-value') || '';
            let newValue = $(this).attr('data-permasalahan-id');  
            let newLabel = $(this).attr('data-permasalahan') + " : " + $(this).attr('data-rekomendasi') + " : " + $(this).attr('data-tindak-lanjut');  
            let newOption = $('<option>', {
                value: newValue,  
                text: newLabel,
                selected: currentValue == newValue  
            });
            $('[data-name="permasalahan_id"]').append(newOption);
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
                emptyOption($('[data-name="permasalahan_id"]'));
                modalElem.find('table tbody tr').each(function (e2) {
                    let currentValue = $(this).attr('data-value') || '';
                    let newValue = $(this).attr('data-permasalahan-id');  
                    let newLabel = $(this).attr('data-permasalahan') + " : " + $(this).attr('data-rekomendasi') + " : " + $(this).attr('data-tindak-lanjut');  
                    let newOption = $('<option>', {
                        value: newValue,  
                        text: newLabel,
                        selected: currentValue == newValue    
                    });

                    $('[data-name="permasalahan_id"]').append(newOption);
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

    

    $('.resource-peralatan').load('lib.mobile-tools/ajax-load-peralatan.php', function(){
        $('#tabel-peralatan tbody tr').each(function(){
            let tr = $(this);
            let currentValue = tr.attr('data-value') || '';
            if(currentValue != '')
            {
                tr.find('select').val(currentValue);
            }
        });
    });
    $('.resource-material').load('lib.mobile-tools/ajax-load-material.php?proyekId='+proyekId, function(){
        $('#tabel-material tbody tr').each(function(){
            let tr = $(this);
            let currentValue = tr.attr('data-value') || '';
            if(currentValue != '')
            {
                tr.find('select').val(currentValue);
            }
        });
    });
    $(document).on('change', '.resource-material', function(e){
        let tr = $(this).closest('tr');
        let max = $(this).find('option:selected').attr('data-max');
        tr.find('input[type="number"]').attr('max', max);
    });
    loadAcuanPengawasan();

    document.querySelector('#tabel-boq').addEventListener('change', function (event) {
        if (event.target.closest('.resource-bill-of-quantity')) {
            loadAcuanPengawasan();
        }
    });

    document.querySelector('[name="proyek_id"]').addEventListener('change', function (event) {
        loadAcuanPengawasan();
    });


    $(document).on('click', '.add-man-power', function(e2){
        let modalElem = $(this).closest('.modal');
        modalElem.find('[name="man_power_id"]').val('');
        modalElem.find('[name="proyek_id"]').val(modalElem.attr('data-proyek-id'));
        modalElem.find('[name="nama"]').val('');
        modalElem.find('[name="pekerjaan"]').val('');
        modalElem.find('[name="jumlah_pekerja"]').val('');
        modalElem.attr('data-mode', 'edit');
    });

    $(document).on('click', '.cancel-man-power', function(e2){
        let modal = $(this).closest('.modal');
        modal.attr('data-mode', 'list');
    });

    $(document).on('click', '.edit-man-power', function(e){
        let tr = $(this).closest('tr');
        let nama = tr.attr('data-nama');
        let pekerjaan = tr.attr('data-pekerjaan');
        let jumlahPekerja = tr.attr('data-jumlah-pekerja');
        let manPowerId = tr.attr('data-man-power-id');
        let proyekId = tr.attr('data-proyek-id');

        let modalElem = $(this).closest('.modal');
        
        modalElem.find('[name="proyek_id"]').val(proyekId);
        modalElem.find('[name="man_power_id"]').val(manPowerId);
        modalElem.find('[name="nama"]').val(nama);
        modalElem.find('[name="pekerjaan"]').val(pekerjaan);
        modalElem.find('[name="jumlah_pekerja"]').val(jumlahPekerja);
        modalElem.attr('data-mode', 'edit');
    });
    $(document).on('click', '.save-man-power', function(e2){
        let modalElem = $(this).closest('.modal');
        let formElem = modalElem.find('form.data-form-edit');
        let proyekId = formElem.find('[name="proyek_id"]').val();
        let manPowerId = formElem.find('[name="man_power_id"]').val();
        let nama = formElem.find('[name="nama"]').val();
        let pekerjaan = formElem.find('[name="pekerjaan"]').val();
        let jumlahPekerja = formElem.find('[name="jumlah_pekerja"]').val();
        let data = {
            action: 'edit',
            manPowerId:manPowerId,
            proyekId:proyekId,
            nama:nama,
            pekerjaan:pekerjaan,
            jumlahPekerja:jumlahPekerja
        };
        $.ajax({
            url: 'lib.mobile-tools/ajax-load-man-power.php',
            method: 'POST',
            data: data,
            success: function (response) {
                listManPower(modalElem);
            },
            error: function () {
                modalElem.find('.data-form-list').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
            }
        });
    });
    $('#man-power-modal').on('shown.bs.modal', function () {
        let modalElem = $(this).closest('.modal');
        listManPower(modalElem);
    });
    $('#man-power-modal').on('hidden.bs.modal', function (e1) { 
        let modalElem = $(this).closest('.modal');
        let tBody = modalElem.find('.data-form-list tbody');
        let label = tBody.attr('data-label-option-select-one');
        let arr = [];
        let inputs = $('#tabel-man-power .resource-man-power');
        if(inputs.length > 0)
        {
            inputs.each(function(){
                arr.push($(this).val());
            });
            inputs.empty();
            let opt1 = $('<option />');
            opt1.attr('value', '');
            opt1.text(label);
            inputs.append(opt1);
            tBody.find('tr').each(function(index){
                let tr = $(this);
                let opt2 = $('<option />');
                opt2.attr('value', tr.attr('data-man-power-id'));
                opt2.attr('data-jumlah-pekerja', tr.attr('data-jumlah-pekerja'));
                opt2.text(tr.attr('data-nama-lengkap'));
                inputs.append(opt2)
            });
            inputs.each(function(index){
                let i = parseInt(index);
                $(this).val(arr[i]);
            });
        }
    });
    optionManPower();
});

let geolocationOptions = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

/**
 * Loads and updates manpower options based on the project ID.
 */
function optionManPower()
{
    $.ajax({
        'url': 'lib.mobile-tools/ajax-load-man-power.php',
        'type': 'GET',
        'dataType': 'html',
        'data': { proyek_id: $('#man-power-modal').attr('data-proyek-id') },
        success: function (data) {
            let select = $('.resource-man-power');
            select.empty();
            select.append(data);
            $('#tabel-man-power tbody tr').each(function(){
                let currentValue = $(this).attr('data-value') || '';
                if(currentValue != '')
                {
                    $(this).find('select').val(currentValue);
                }
            });
        }
    });
}

/**
 * Fetches and lists manpower details based on project ID.
 * @param {jQuery} modalElem - The modal element containing the project ID.
 */
function listManPower(modalElem)
{
    modalElem.attr('data-mode', 'list');
    let proyekId = modalElem.attr('data-proyek-id');
    $.ajax({
        url: 'lib.mobile-tools/ajax-load-man-power.php',
        method: 'GET',
        data: { 
            action: 'list', 
            proyek_id: 
            proyekId 
        },
        success: function (response) {
            modalElem.find('.data-form-list').html(response);
        },
        error: function () {
            modalElem.find('.data-form-list').html('<div class="alert alert-warning">Terjadi kesalahan dalam memuat data.</div>');
        }
    });
}

/**
 * Removes all but the first option element from the provided selector.
 * @param {jQuery} selector - The jQuery object containing the select element to be modified.
 */
function emptyOption(selector)
{
    while(selector.find('option').length > 1)
    {
        selector.find('option:last-child').remove();
    }
}

/**
 * Fixes table input names for materials.
 */
function fixMaterial() {
    $('#tabel-material tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `material_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_material[${index}]`);
    });
}

/**
 * Fixes table input names for equipment.
 */
function fixPeralatan() {
    $('#tabel-peralatan tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `peralatan_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_peralatan[${index}]`);
    });
}

/**
 * Fixes table input names for BOQ (Bill of Quantities).
 */
function fixBoq() {
    $('#tabel-boq tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `boq_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_boq[${index}]`);
    });
}

/**
 * Fixes table input names for manpower.
 */
function fixManPower() {
    $('#tabel-man-power tbody').find('tr').each(function (index) {
        let tr = $(this);
        tr.find('select').attr('name', `man_power_id[${index}]`);
        tr.find('input[type="number"]').attr('name', `jumlah_man_power[${index}]`);
    });
}

/**
 * Calculates and updates the total manpower value.
 */
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

/**
 * Sets the minimum and maximum volume attributes for a select input.
 * @param {jQuery} select - The select element whose volume values will be set.
 */
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

/**
 * Detects the user's location using the Geolocation API.
 */
function detectLocation() {
    navigator.geolocation.getCurrentPosition(onSuccess, onError, geolocationOptions);
}

/**
 * Success callback for geolocation.
 * @param {GeolocationPosition} position - The position data returned by the geolocation API.
 */
function onSuccess(position) {
    $('[name="latitude"]').val(position.coords.latitude);
    $('[name="longitude"]').val(position.coords.longitude);
    $('[name="altitude"]').val(position.coords.altitude);
}

/**
 * Error callback for geolocation.
 * @param {GeolocationPositionError} error - The error object returned by the geolocation API.
 */
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

/**
 * Applies attributes based on selected option's data attributes.
 * @param {jQuery} obj - The select element containing the selected option.
 */
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

/**
 * Adds a location to the project by sending the data to the server.
 * @returns {boolean} False to prevent default form submission.
 */
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

/**
 * Fixes the input names for problem and recommendation table rows.
 * @param {jQuery} table - The table element containing the rows to be updated.
 */
function fixRekomendasiPermasalahan(table) {
    table.find('tbody').find('tr').each(function (index) {
        $(this).find(`[data-name="permasalahan_id"]`).attr('name', `permasalahan_id[${index}]`);
        $(this).find(`[data-name="rekomendasi_id"]`).attr('name', `rekomendasi_id[${index}]`);
    });
}

/**
 * Closes the issue with the given ID.
 * @param {string} id - The ID of the issue to close.
 */
function closeIssue(id) {
    issue(id, 1);
}

/**
 * Opens the issue with the given ID.
 * @param {string} id - The ID of the issue to open.
 */
function openIssue(id) {
    issue(id, 0);
}

/**
 * Updates the issue status (open or closed).
 * @param {string} id - The issue ID.
 * @param {number} status - The new status (0 = open, 1 = closed).
 */
function issue(id, status) {
    $.ajax({
        "method": "POST",
        "url": "lib.mobile-tools/ajax-load-permasalahan.php",
        "data": { user_action: 'set-status', permasalahan_id: id, status: status },
        function(data) {

        }
    });
}

/**
 * Closes the recommendation with the given ID.
 * @param {string} id - The ID of the recommendation to close.
 */
function closeRecomendation(id) {
    recomendation(id, 1);
}

/**
 * Opens the recommendation with the given ID.
 * @param {string} id - The ID of the recommendation to open.
 */
function openRecomendation(id) {
    recomendation(id, 0);
}

/**
 * Updates the recommendation status (open or closed).
 * @param {string} id - The recommendation ID.
 * @param {number} status - The new status (0 = open, 1 = closed).
 */
function recomendation(id, status) {
    $.ajax({
        "method": "POST",
        "url": "lib.mobile-tools/ajax-load-rekomendasi.php",
        "data": { user_action: 'set-status', rekomendasi_id: id, status: status },
        function(data) {

        }
    });
}

/**
 * Loads the reference for work supervision based on project and BOQ IDs.
 */
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