$(document).ready(function() {
    console.log('document ready test..! drop');
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

    Dropzone.autoDiscover = false;

    // var myDropzone = new Dropzone("#dropzoneFrom", { 
    //     url: "/inventory/importupload",
    //     success : function(file, response){
    //         console.log(file);
    //         console.log(response);
    //     }
    
    // });

});
