$(document).ready(function () {
  // Handle click on "btn_mailing_download" control Starts
  $(document).on("click", ".btn_mailing_download", function () {
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
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {
        if (data.status) {
          var downloadLink;
          downloadLink = document.createElement("a");
          downloadLink.download = "mailing.pdf";
          downloadLink.href = BASE_URL + "/assets/order/mailing/mailing.pdf";
          downloadLink.style.display = "none";
          document.body.appendChild(downloadLink);
          downloadLink.click();
        } else {
          location.reload(true);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });

  // Handle click on "btn_mailing_download" control Starts
  $(document).on("click", ".btn_mailing_view", function () {
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
        var downloadLink;
        downloadLink = document.createElement("a");
        downloadLink.download = "mailing.pdf";
        downloadLink.href = BASE_URL + "/assets/order/mailing/mailing.pdf";
        window.open(downloadLink.href);
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });
});
