$(document).ready(function () {
  
  // Handle click on "btn_mailing_download" control Starts
  $(document).on(
    "click",
    ".btn_mailing_download",
    function () {
      // Ajax CSRF Token Setup
      $.ajaxSetup({
        headers: {
          "X-CSRF-Token": $('input[name="__token"]').val(),
        },
      });
      $.ajax({
        url: BASE_URL + "/order/pdf_mailing",
        type: "POST",
        data: { status: $("#OrderSortBy :selected").val() },
       // dataType: "JSON",
        beforeSend: function () {},
        success: function (data, textStatus, jqXHR) {  
                var csvFile;
                var downloadLink;
                downloadLink = document.createElement("a");
                downloadLink.download = 'mailing.pdf';
                downloadLink.href = BASE_URL+"/mailing.pdf";

                downloadLink.style.display = "none";
                document.body.appendChild(downloadLink);
                downloadLink.click();
                


          
        },
        error: function (jqXHR, textStatus, errorThrown) {},
      });
    }
  );
});

/* View pdf file js */

$(document).ready(function () {
  
  // Handle click on "btn_mailing_download" control Starts
  $(document).on(
    "click",
    ".btn_mailing_view",
    function () {
      //
      // Ajax CSRF Token Setup
      $.ajaxSetup({
        headers: {
          "X-CSRF-Token": $('input[name="__token"]').val(),
        },
      });
      $.ajax({
        url: BASE_URL + "/order/pdf_mailing",
        type: "POST",
        data: { status: $("#OrderSortBy :selected").val() },
       // dataType: "JSON",
        beforeSend: function () {},
        success: function (data, textStatus, jqXHR) {  
                var csvFile;
                var downloadLink;
                downloadLink = document.createElement("a");
                downloadLink.download = 'mailing.pdf';
                downloadLink.href = BASE_URL+"/mailing.pdf";
                window.open(downloadLink.href);
          
        },
        error: function (jqXHR, textStatus, errorThrown) {},
      });
    }
  );
});

