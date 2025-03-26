document.addEventListener('DOMContentLoaded', function () {
createSortTable();
initDateTimePicker();
initMultipleSelect();
initSortData(window.location.search);

  $("table").each(function () {
    if ($(".check-master").length > 0) {
      $(".check-master").each(function () {
        $(this).on("change", function () {
          let checked = $(this)[0].checked;
          let selector = $(this).attr('data-selector');
          let table = $(this).closest("table");
          table.find(".check-slave").filter(selector).each(function () {
            $(this)[0].checked = checked;
          });
        });
      });
    }
  });

  if ($('[data-ajax-support="true"]').length == 0) {
    $(document).on("click", '[type="submit"][name="user_action"][value="delete"]', function (e2) {
        if(!confirm($(this).attr("data-onclik-message")))
        {
          e2.preventDefault();
          e2.stopPropagation();
        }
      }
    );
  }


    // Kode Anda di sini
    if ($('[data-ajax-support="true"]').length > 0) {
        let ajaxSelector = $('[data-ajax-support="true"]');
        let url;
        $(document).on('click', '[data-ajax-support="true"] .pagination a, [data-ajax-support="true"] .order-controll a', function (e) {
            e.preventDefault();
            e.stopPropagation();
            url = $(this).attr('href');
            ajaxLoad(ajaxSelector, url);

            // Data state yang ingin disimpan
            const state = {};

            // Judul halaman baru (biasanya diabaikan oleh browser)
            const title = document.title;

            // URL baru yang akan ditampilkan di address bar

            // Menambahkan entri baru ke dalam riwayat sesi
            history.pushState(state, title, url);
        });

        window.addEventListener('popstate', function(event) {
            if (event.state) {
                url = location.href;
                ajaxLoad(ajaxSelector, url);
            } 
        });
        $(document).off("click", '[type="submit"][name="user_action"][value="delete"]');
        $(document).on('click', '[data-ajax-support="true"] form.data-form [type="submit"]', function(e){
            e.preventDefault();
            e.stopPropagation();

            if($(this).attr('value') == 'delete' && !confirm($(this).attr("data-onclik-message")))
            {
                return false;
            }

            let frm = $(this).closest('form');
            url = frm.attr('action');
            if(url == '')
            {
                url = location.href;
            }

            
            
            let data = frm.serialize();
            data += '&'+$(this).attr('name')+'='+encodeURIComponent($(this).attr('value'));

            $('.pb-container .progress-infinite').css({'display':'block'});
            $.ajax({
                url: url, // URL tujuan dari atribut action form
                type: frm.attr('method'), // Metode pengiriman dari atribut method form
                data: data, // Serialisasi data form
                success: function(responseTxt) {
                    // Kode yang dijalankan jika permintaan berhasil
                    ajaxSelector.empty().append(responseTxt);
                    prepareDataPage(ajaxSelector);
                    $('.pb-container .progress-infinite').css({'display':'none'});
                },
                error: function(xhr, status, error) {
                    // Kode yang dijalankan jika terjadi kesalahan
                    console.error('Error: ' + error);
                    $('.pb-container .progress-infinite').css({'display':'none'});
                }
            });
            updateOrderUrl(getSearch(url));
        });
    }

    
});

function ajaxLoad(ajaxSelector, url) {
    $('.pb-container .progress-infinite').css({'display':'block'});
    ajaxSelector.load(url, function (responseTxt, statusTxt, xhr) {
        if (statusTxt == "success") {
            ajaxSelector.empty().append(responseTxt);
            prepareDataPage(ajaxSelector);
            $('.pb-container .progress-infinite').css({'display':'none'});
        }
        if (statusTxt == "error") {
            console.error('error');
            $('.pb-container .progress-infinite').css({'display':'none'});
         }
    })
    updateOrderUrl(getSearch(url)); 
}

function prepareDataPage(ajaxSelector)
{
    createSortTable();

    ajaxSelector.find('[data-ajax-support="true"] tbody.data-table-manual-sort').each(function () {
        let dataToSort = $(this)[0];
        Sortable.create(dataToSort, {
            animation: 150,
            scroll: true,
            handle: ".data-sort-handler",
            onEnd: function () {
            // do nothing
            updateNumber($(dataToSort));
            },
        });
    });

    ajaxSelector.find("tbody.data-table-manual-sort").each(function () {
        let dataToSort = $(this)[0];
        Sortable.create(dataToSort, {
          animation: 150,
          scroll: true,
          handle: ".data-sort-handler",
          onEnd: function () {
            // do nothing
            updateNumber($(dataToSort));
          },
        });
    });

    ajaxSelector.find("table").each(function () {
        if ($(".check-master").length > 0) {
          $(".check-master").each(function () {
            $(this).on("change", function () {
              let checked = $(this)[0].checked;
              let table = $(this).closest("table");
              table.find(".check-slave").each(function () {
                $(this)[0].checked = checked;
              });
            });
          });
        }
    });
    
}

function getSearch(url)
{
    let arr = splitWithTail(url, '?', 1);
    return "?" + (arr[1] || ""); 
}