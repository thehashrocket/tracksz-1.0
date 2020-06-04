$(document).ready(function () {
  $(document).on("click", "#FlatRatePriority, #GlobalWeight", function () {
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
