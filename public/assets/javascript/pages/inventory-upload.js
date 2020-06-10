Dropzone.autoDiscover = false;
$(document).ready(function () {
  // DropZone Code Starts for Auto upload multiples files
  if (document.getElementById("dropzone_filebrowse")) {
    var myDropzone = new Dropzone("form#dropzone_filebrowse", {
      url: "/inventory/importupload",
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
$(document).ready(function () {
  var myDropzone = new Dropzone("#dropzone_request", {
    url: "/inventory/importupload",
    success: function (file, response) {
      var parse_obj = JSON.parse(response);
      $("#ajaxMsg").empty();
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

  if (document.getElementById("dropzone_filedelete")) {
    var myDropzone = new Dropzone("form#dropzone_filedelete", {
      url: "/inventory/importdelete",
      success: function (file, response) {
        var parse_obj = JSON.parse(response);

        $("#ajaxMsg").empty();
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
// DropZone Code Ends for Auto upload multiples files
