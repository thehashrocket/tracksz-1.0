$(document).ready(function() {
   console.log('document ready test..!');
   $('#category_table').DataTable( {
    "processing": true,
    "serverSide": true,    

    // "ajax":'lists',
    "ajax":{
        "url": 'lists',
        "type": "GET",        
        "dataType": "json",
        beforeSend: function() {              
             console.log('before send')
        },dataSrc: function ( json ) {
             console.log('json')
             console.log(json)
               for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {                               
                   json.data[i]['Image'] = '<img src="'+json.dir_path+json.data[i]['Image']+'" height=50 width=50>';                 
               }
           return json.data;
        },complete: function() {
             console.log('complete send')
        }
    },
    "columns": [
        { "data": "Name" },
        { "data": "Description" },
        { "data": "Image"},
        { "data": "Id"},
        // { "data": "parent_category" },
        // { "data": "status" }
    ]  
} );


});