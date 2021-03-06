$(document).ready(function () {
  
  $("#order_table").DataTable({
    responsive: true,
    order: [[0, "desc"]],
  });

  // Handle click on "btn_delete" control Starts
  $(document).on("click", ".btn_delete", function () {
    if (!confirm("Do you want to delete ?")) {
      return false;
    } else {
      $.ajax({
        type: "POST",
        url: BASE_URL + "/order/delete",
        data: { Id: $(this).attr("delete_id") },
        dataType: "json",
        success: function (resultData) {
          if (resultData.status) {
            location.reload();
          } else {
            location.reload();
          }
        },
      });
    }
  });

  // Handle click on "btn_clear" control Starts
  $(document).on("click", ".btn_clear", function () {
    $("#clear_filter").val(true);
  });

  // Handle click on "Select all" control Starts
  $(document).on("click", "#select_all_chkbox", function () {
    var attr = $(this).attr("checked");
    if (typeof attr !== typeof undefined && attr !== false) {
      $(this).prop("checked", false);
      $(this).removeAttr("checked");
      $(this).val(0);
      // Datatables Child Checkbox
      $(".child_chkbox").removeAttr("checked");
      $(".child_chkbox").prop("checked", false);
    } else {
      $(this).prop("checked", true);
      $(this).attr("checked", "checked");
      $(this).val(1);
      // Datatables Child Checkbox
      $(".child_chkbox").prop("checked", true);
      $(".child_chkbox").attr("checked", "checked");
    }
  });
  // Handle click on "Select all" control Ends

  // Handle click on "btn_status_update" control Starts
  $(document).on("click", ".btn_status_update,.btn_shipping", function () {
    var data_array = [];
    $.each($("input[name='child_chkbox[]']:checked"), function (key, value) {
      data_array.push($(this).val());
    });
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/update_status",
      type: "POST",
      data: { ids: data_array, status: $(this).attr("status") },
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

  // Handle click on "OrderStatus" control Starts
  $(document).on("change", "#OrderStatus", function () {
    $("#order_change").submit();
  });

  $(document).on("change", "#selected_export_order", function () {
    var export_type = $("#selected_export_order").val();
    var data_array = [];
    $.each($("input[name='child_chkbox[]']:checked"), function (key, value) {
      data_array.push($(this).val());
    });
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/export_order_list",
      type: "POST",
      data: { ids: data_array, export_formate: export_type },
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {
        if(data.status){
          var origin = window.location.origin;
          var link = document.createElement("a");
          link.setAttribute("download", data.filename);
          document.body.appendChild(link);
          link.href = origin + data.filename;
          link.click();
          location.reload();
        }else{
          location.reload();
        }
     
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });
});
