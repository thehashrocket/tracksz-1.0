$(document).ready(function () {
  $("#from_date").hide();
  $("#to_date").hide();
  $(".date_range").hide();
  $("#orderStatus").hide();
  $("#exportType").change(function () {
    if ($(this).val() == "range") {
      $("#from_date").show();
      $("#to_date").show();
      $(".date_range").show();
      $("#current_time").hide();
      $(".orderStatus").hide();
    } else if ($(this).val() == "current") {
      $("#current_time").show();
      $("#from_date").hide();
      $("#to_date").hide();
      $(".date_range").hide();
      $(".orderStatus").hide();
    } else if ($(this).val() == "status") {
      $("#orderStatus").show();
      $("#from_date").hide();
      $("#to_date").hide();
      $(".date_range").hide();
      $("#current_time").hide();
    } else {
      $("#from_date").hide();
      $("#to_date").hide();
      $("#orderStatus").hide();
      $(".date_range").hide();
      $("#current_time").hide();
    }
  });
});


$(function () {


  $("#from_date, #to_date").datepicker({


    changeMonth: true,

    changeYear: true,

    dateFormat: 'yy-mm-dd'

  });
});
