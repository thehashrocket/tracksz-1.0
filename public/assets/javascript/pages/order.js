$(document).ready(function() {
    console.log('document ready test..!');

    $('#order_table').DataTable( {
        responsive: true,
        "order": [[ 0, "desc" ]],     
    });


    
    $(document).on("click",".btn_delete",function() {
        if (!confirm("Do you want to delete ?")){            
            return false;
        } else{
            $.ajax({
                type: 'POST',
                url: BASE_URL+'/order/delete',
                data: {'Id':$(this).attr('delete_id')},
                dataType: "json",
                success: function(resultData) {
                    if(resultData.status){
                        location.reload();
                    }else{
                        location.reload();
                    }               
                 }
          });
        }
    });


   /*-----mukesh js-------------*/
     $(document).ready(function() {

                $("#from_date").hide();
                $("#to_date").hide();
                $(".date_range").hide();
                $('#orderStatus').hide();
              $("#exportType").change(function () {
            if ($(this).val() == "range")
             {
                $("#from_date").show();
                $("#to_date").show();
                $(".date_range").show();
                $("#current_time").hide();
                $('.orderStatus').hide();
             }
             else if($(this).val() == "current")
             {
                 $("#current_time").show();
                 $("#from_date").hide();
                 $("#to_date").hide();
                 $(".date_range").hide();
                 $('.orderStatus').hide();
             }
             else if($(this).val() == "status")
             {
                 $("#orderStatus").show();
                 $("#from_date").hide();
                 $("#to_date").hide();
                 $(".date_range").hide();
                 $('#current_time').hide();


             }
            else
             {
                $("#from_date").hide();
                $("#to_date").hide();
                $("#orderStatus").hide();
                $(".date_range").hide();
                $('#current_time').hide();
             }
        });
               
       });

});
