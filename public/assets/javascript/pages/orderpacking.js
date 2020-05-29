$(document).ready(function () {
  console.log("document ready pick..!");

  // Handle click on "btn_mailing_download" control Starts
  $(document).on("click", ".btn_packing_download, .btn_packing_view", function () {
    
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/pdf_packing",
      type: "POST",
      data: { status: $("#OrderSortBy :selected").val() },
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {
        if (data.status) {
          location.reload();
        } else {
          location.reload();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });
});
