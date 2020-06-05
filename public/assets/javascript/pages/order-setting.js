$(document).ready(function () {
  console.log("document ready test..!");

  $("#NoAdditionalOrder").change(function () {
    var selectObj = $(this);
    var selectedOption = selectObj.find(":selected");
    var selectedValue = selectedOption.val();
    var targetDiv = $("#addfolderinput");
    targetDiv.html("");
    for (var i = 0; i < selectedValue; i++) {
      var f = i + 1;
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
            "'><br>"
        )
      );
    }
  });
});
