Dropzone.autoDiscover = false;
$(document).ready(function () {

  $("#confirm_table").DataTable({
    responsive: true,
    order: [[0, "desc"]],
  });
  // DropZone Code Starts for Auto upload multiples files
  if (document.getElementById("dropzone_request")) {
    var myDropzone = new Dropzone("form#dropzone_request", {
      url: "/order/confirmation_files",
      addedfiles: function (files) {},
      success: function (file, response) {
        var parse_obj = JSON.parse(response);
        $("#ajaxMsg").html("");
        if (parse_obj.status) {
          $("#ajaxMsg").append(
            '<div class="col-sm-12 alert alert-success text-center">' +
              parse_obj.message +
              "</div>"
          );
        } else {
          $("#ajaxMsg").append(
            '<div class="col-sm-12 alert alert-danger text-center">' +
              parse_obj.message +
              "</div>"
          );
        }
      },
      error: function (file, response) {
        // On ajax error operation
        // console.log(response, errorThrown);
      },
      complete: function () {
        // On ajax complete operation
        // console.log('Complete ajax send');

        setTimeout(function () {
          $("#ajaxMsg").empty();
        }, 5000);
      },
    });
  }

  // Handle click on "btn_status_update" control Starts
  $(document).on("click", ".btn_download", function (e) {
    // e.preventDefault();
    var Id = $(this).attr("id");
    // Ajax CSRF Token Setup
    $.ajaxSetup({
      headers: {
        "X-CSRF-Token": $('input[name="__token"]').val(),
      },
    });
    $.ajax({
      url: BASE_URL + "/order/download/" + Id,
      type: "get",
      dataType: "JSON",
      beforeSend: function () {},
      success: function (data, textStatus, jqXHR) {
        if (data.status) {
         
          var origin = window.location.origin;
          var link = document.createElement("a");
          link.setAttribute("download",data.filename);
          document.body.appendChild(link);
          link.href = origin + data.filename;
          link.click();
          $("#ajaxMsg").append(
            '<div class="col-sm-12 alert alert-success text-center">' +
            data.message +
              "</div>"
          );
        } else {
          $("#ajaxMsg").append(
            '<div class="col-sm-12 alert alert-danger text-center">' +
            data.message +
              "</div>"
          );
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {},complete: function () {
        // On ajax complete operation
        // console.log('Complete ajax send');

        setTimeout(function () {
          $("#ajaxMsg").empty();
        }, 5000);
      },
    });
  });

    // Handle click on "btn_status_update" control Starts
    $(document).on("click", ".btn_view", function (e) {
      // e.preventDefault();
      var Id = $(this).attr("view_id");
      // Ajax CSRF Token Setup
      $.ajaxSetup({
        headers: {
          "X-CSRF-Token": $('input[name="__token"]').val(),
        },
      });
      $.ajax({
        url: BASE_URL + "/order/view/" + Id,
        type: "get",
        dataType: "JSON",
        beforeSend: function () {},
        success: function (data, textStatus, jqXHR) {
          if (data.status) {
           
            var origin = window.location.origin;
            var link = document.createElement("a");
            // link.setAttribute("download",data.filename);
            link.setAttribute("target",'_blank');
            document.body.appendChild(link);
            link.href = origin + data.filename;
            link.click();
            $("#ajaxMsg").append(
              '<div class="col-sm-12 alert alert-success text-center">' +
              data.message +
                "</div>"
            );
          } else {
            $("#ajaxMsg").append(
              '<div class="col-sm-12 alert alert-danger text-center">' +
              data.message +
                "</div>"
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {},complete: function () {
          // On ajax complete operation
          // console.log('Complete ajax send');
  
          setTimeout(function () {
            $("#ajaxMsg").empty();
          }, 5000);
        },
      });
    });
});
