$(document).ready(function() {

    $('#product_table').DataTable({
        order: [
            [1, "asc"] // asc OR desc
        ],
        responsive:false,
        autoWidth: false,
        "scrollX": true
    });    

    $(document).on("click", "#ProductActive, #ProdInterShip, #ProdExpectedShip", function() {
        var attr = $(this).attr('checked');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).prop("checked", false);
            $(this).removeAttr("checked");
            $(this).val(0);
        } else {
            $(this).prop("checked", true);
            $(this).attr("checked", 'checked');
            $(this).val(1);
        }
    });

    $(document).on("click", ".btn_delete", function() {
        if (!confirm("Do you want to delete ?")) {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: BASE_URL + '/product/delete',
                data: { 'Id': $(this).attr('delete_id') },
                dataType: "json",
                success: function(resultData) {
                    if (resultData.status) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
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
  $(document).on("click", "#btn_delete", function () {
    if (!confirm("Do you want to delete ?")) {
            return false;
        }

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
      url: BASE_URL + "/product/delete_product",
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

   $(document).on("change", "#selected_export_product", function () {
    var export_type =  $( "#selected_export_product" ).val();
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
      url: BASE_URL + "/product/export_product",
      type: "POST",
      data: { ids: data_array, export_formate: export_type },
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {
  
      },
      error: function (jqXHR, textStatus, errorThrown) {},
    });
  });




  
