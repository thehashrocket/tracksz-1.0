$(document).ready(function () {
  console.log("document ready test..!");

  $(document).on("change", "#NoAdditionalOrder", function () {
    var selectObj = $(this);
    var selectedOption = selectObj.find(":selected");
    var selectedValue = selectedOption.val();
    var targetDiv = $("#addfolderinput");
    targetDiv.html("");
    for (var i = 0; i < selectedValue; i++) {
      var f = i + 1;
      console.log("test asd ::");
      var work_val = "";
      work_val = $("#additional_order" + f + "_hidden").val();
      targetDiv.append(
        $(
          "<label>Work Folder #" +
            f +
            " (work" +
            f +
            ")</label><br><input type='text' class='form-control' id='NoAdditionalOrder" +
            f +
            "' name='NoAdditionalOrder" +
            f +
            "' value='" +
            work_val +
            "'><br>"
        )
      );
    }
  });
  $("#NoAdditionalOrder").trigger("change");
});
