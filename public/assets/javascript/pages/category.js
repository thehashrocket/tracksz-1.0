$(document).ready(function () {
  $("#category_table").DataTable({
    responsive: true,
    order: [[0, "desc"]],
    columns: [
      {
        width: "5%",
        visible: true, // Hide Which Column Do not need to show in Datatable list
        data: "Name",
        name: "Name",
        title: "Name",
        orderable: true,
        searchable: true,
      },
      {
        width: "10%",
        data: "Description",
        name: "Description",
        title: "Description",
        orderable: true,
        searchable: true,
      },
      {
        width: "10%",
        data: "Image",
        name: "Image",
        title: "Image",
        orderable: true,
        searchable: true,
      },
      {
        width: "5%",
        data: "action",
        name: "action",
        title: "Action",
        orderable: false,
        searchable: false,
      },
    ],
  });

  $(document).on("click", ".btn_delete", function () {
    if (!confirm("Do you want to delete ?")) {
      return false;
    } else {
      $.ajax({
        type: "POST",
        url: BASE_URL + "/category/delete",
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
});
