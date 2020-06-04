$(document).ready(function () {
  $(".myDiv").hide();
  $("#BarcodeType").on("change", function () {
    var demovalue = $(this).val();

    $("div.myDiv").hide();
    $("#" + demovalue).show();
  });

  $(document).on(
    "click",
    "#SkipPDFView, #SplitOrders, #AddBarcode, #IncludeOrderBarcodes, #IncludeItemBarcodes, #CentreHeaderText, #HideEmail, #HidePhone, #IncludeGSTExAus1, #CentreFooter, #ShowItemPrice, #IncludeMarketplaceOrder, #IncludePageNumbers, #HideLabelBoundaries, #IncludeGSTExAus2",
    function () {
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
    }
  );
});
