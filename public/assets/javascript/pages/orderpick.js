$(document).ready(function () {
  // Handle click on "btn_mailing_download" control Starts
  $(document).on("click", ".btn_pick_download", function () {
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/pdf_pick",
      type: "POST",
      data: {
        status: $("#OrderStatus :selected").val(),
        sortorder: $("#OrderSortBy :selected").val(),
      },
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {        
        if (data.status) {
          var downloadLink;
          downloadLink = document.createElement("a");
          downloadLink.download = "picking.pdf";
          downloadLink.href = BASE_URL + "/assets/order/picking/picking.pdf";
          downloadLink.style.display = "none";
          document.body.appendChild(downloadLink);
          downloadLink.click();
        }else{
          location.reload();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });
});
