$(document).ready(function () {
  
  // Handle click on "btn_mailing_download" control Starts
  $(document).on("click", ".btn_pick_download, .btn_pick_view", function () {
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
          location.reload();
        } else {
          location.reload();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });
});
