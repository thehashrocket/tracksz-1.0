$(document).ready(function () {
  // Handle click on "btn_mailing_download" control Starts
  $(document).on("click", ".btn_packing_download", function () {
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/pdf_packing",
      type: "POST",
      data: {
        status: $("#OrderSortBy :selected").val(),
        OrderSort: $("#OrderLayout").val(),
      },
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {
        if (data.status) {
          var downloadLink;
          downloadLink = document.createElement("a");
          downloadLink.download = "packing.pdf";
          downloadLink.href = BASE_URL + "/assets/order/packing/packing.pdf";
          downloadLink.style.display = "none";
          document.body.appendChild(downloadLink);
          downloadLink.click();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {},
      complete: function () {},
    });
  });

  $(document).on("click", ".btn_packing_view", function () {
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/pdf_packing",
      type: "POST",
      data: {
        status: $("#OrderSortBy :selected").val(),
        OrderSort: $("#OrderLayout").val(),
      },
      //dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {        
        var downloadLink;
        downloadLink = document.createElement("a");
        downloadLink.download = "packing.pdf";
        downloadLink.href = BASE_URL + "/assets/order/packing/packing.pdf";
        window.open(downloadLink.href);
      },
      error: function (jqXHR, textStatus, errorThrown) {},
      complete: function () {},
    });
  });
});
