$(document).ready(function() {
    console.log('document ready test..!');

    $('#customergroup_table').DataTable({
        responsive: true,
        "order": [
            [0, "desc"]
        ]
    });



    $(document).on("click", ".btn_delete", function() {
        if (!confirm("Do you want to delete ?")) {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: BASE_URL + '/customergroup/delete',
                data: { 'Id': $(this).attr('delete_id') },
                dataType: "json",
                success: function(resultData) {
                    if (resultData.status) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });

    // Checkbox checked popup
    $(document).on('click', '#CustomerGroupApproval', function() {
        var attr = $(this).attr('checked');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).prop("checked", false);
            $(this).removeAttr("checked");
            $(this).val(0);
        } else {
            $(this).prop("checked", true);
            $(this).attr("checked", 'checked');
            $(this).val(1);
        }
    });


    // $('#attributes_table').DataTable({
    //     "processing": true,
    //     "serverSide": true,

    //     // "ajax":'lists',
    //     "ajax": {
    //         "url": 'lists',
    //         "type": "GET",
    //         "dataType": "json",
    //         beforeSend: function() {
    //             console.log('before send')
    //         },
    //         dataSrc: function(json) {
    //             console.log('json')
    //             console.log(json)
    //             for (var i = 0, ien = json.data.length; i < ien; i++) {
    //                 json.data[i]['Image'] = '<img src="' + json.dir_path + json.data[i]['Image'] + '" title="Logo for Tracksz.com" alt = "Logo for Tracksz.com" height = 50 width = 50 > ';
    //             }
    //             return json.data;
    //         },
    //         complete: function() {
    //             console.log('complete send')
    //         }
    //     },
    //     "columns": [
    //         { "data": "Name" },
    //         { "data": "Description" },
    //         { "data": "Image" },
    //         { "data": "Id" },
    //         // { "data": "parent_category" },
    //         // { "data": "status" }
    //     ]
    // });


});