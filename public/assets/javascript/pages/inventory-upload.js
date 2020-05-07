

Dropzone.autoDiscover = false;
$(document).ready(function() {
     var myDropzone = new Dropzone("#dropzone_request1", { 
         url: "/inventory/importupload",
         success : function(file, response){
            res= JSON.parse(response);console.log(res);
             $("#ajaxMsg").empty();
             $("#ajaxMsg").append('<div class="alert alert-success text-center">'+res.message+'</div>');
         }
    
     });

                               /* $.ajax({
                                    type: "POST",
                                    url: "upload.php",
                                    data: 'alertdata=' + alertd,
                                    success: function (data) {
                                        $("#alertdata").html(data);
                                    }
                                });*/
    //console.log('document ready test..! drop');

    // Dropzone.autoDiscover = false;
    // Dropzone.options.dropzoneFrom = {
    //     autoProcessQueue: false,
    //     acceptedFiles: ".xlxs,.csv,.txt,.png,.jpg,.gif",
    //     init: function(){
    //         console.log('init')
    //         var submit = document.querySelector('#submit-all');
    //         myDropzone = this;
    //         submit.addEventListener("click",function(){
    //             myDropzone.processQueue();
    //         });
    //         this.on("success", function(file, responseText) {
    //             console.log(responseText);
    //         });
    //         this.on("complete",function(){

    //             console.log('complete')
    //             if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0){
    //                 var _this = this;
    //                 _this.removeAllFiles();
    //             }
    //         });
    //     }
    // }

   //Dropzone.autoDiscover = false;

    

});
