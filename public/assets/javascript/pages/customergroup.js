$(document).ready(function () {
  console.log("document ready test..!");

  $("#customergroup_table").DataTable({
    responsive: true,
    order: [[0, "desc"]],
  });

  $(document).on("click", ".btn_delete", function () {
    if (!confirm("Do you want to delete ?")) {
      return false;
    } else {
      $.ajax({
        type: "POST",
        url: BASE_URL + "/customergroup/delete",
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

  // Checkbox checked popup
  $(document).on("click", "#CustomerGroupApproval", function () {
    var attr = $(this).attr("checked");
    if (typeof attr !== typeof undefined && attr !== false) {
      $(this).prop("checked", false);
      $(this).removeAttr("checked");
      $(this).val(0);
    } else {
      $(this).prop("checked", true);
      $(this).attr("checked", "checked");
      $(this).val(1);
    }
  });
 
});
