$(document).ready(function() {
    console.log('document ready test..!');

    $('#downloads_table').DataTable({
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
                url: BASE_URL + '/download/delete',
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