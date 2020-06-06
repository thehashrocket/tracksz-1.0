Dropzone.autoDiscover = false;
$(document).ready(function () {
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
});