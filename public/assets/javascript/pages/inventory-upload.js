$(document).ready(function() {
    console.log('document ready test..! drop');

    Dropzone.options.dropzone_request = {
        autoProcessQueue: false,
        acceptedFiles: ".xlxs,.csv,.txt,.png,.jpg,.gif",
        init: function(){
            var submit = document.querySelector('#submit-all');
            myDropzone = this;
            submit.addEventListener("click",function(){
                myDropzone.processQueue();
            });
            this.on("complete",function(){
                if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0){
                    var _this = this;
                    _this.removeAllFiles();
                }
            });
        }
    }

});
