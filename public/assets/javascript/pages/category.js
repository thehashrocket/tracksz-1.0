$(document).ready(function() {
   console.log('document ready test..!');
   $('#category_table').DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": "lists"
} );
});