$(document).ready(function () {  
    console.log('marketplace loads')
    $('#marketplace_table').DataTable( {
        responsive: true
    });
        
    $(document).on("click","#SuspendExport, #SendDeletes",function() {
        var attr = $(this).attr('checked');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).prop("checked",false);
            $(this).removeAttr("checked");
            $(this).val(0);
        }else{
            $(this).prop("checked",true);
            $(this).attr("checked",'checked');
            $(this).val(1);
        }        
    });

});
