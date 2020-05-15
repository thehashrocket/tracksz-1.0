$(document).ready(function() {
    console.log('document ready test..!');

    $('#category_table').DataTable( {
        responsive: true,
        "order": [[ 0, "desc" ]],
        columns: [ {
            width: "5%",
            visible: true, // Hide Which Column Do not need to show in Datatable list
            data: 'Name',
            name: 'Name',
            title: "Name",
            orderable: true,
            searchable: true
        }, {
            width: "10%",
            data: 'Description',
            name: 'Description',
            title: "Description",
            orderable: true,
            searchable: true
        }, {
            width: "10%",
            data: 'Image',
            name: 'Image',
            title: "Image",
            orderable: true,
            searchable: true
        },{
            width: "5%",
            data: 'action',
            name: 'action',
            title: "Action",
            orderable: false,
            searchable: false
        }]
    });


    
    $(document).on("click",".btn_delete",function() {
        if (!confirm("Do you want to delete ?")){            
            return false;
        } else{
            $.ajax({
                type: 'POST',
                url: BASE_URL+'/category/delete',
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
        

    // $('#category_table').DataTable({
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
