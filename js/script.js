jQuery(document).ready(function() {

    /**
     * jQuery File Upload
     * upload file
     * add hidden field with uploaded filename
     * add uploaded file thumb to form
     */
    jQuery('#fileupload').fileupload({
        dataType: 'json',
        sequentialUploads: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        start:function () {
            jQuery('#fileupload-btn').css('display','none');
        },
        progressall:function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery('#fileupload-btn').css('display','none');
            jQuery('#upload-progress').html('Loading ' + progress + '%');
        },
        stop: function(){
            jQuery('#fileupload-btn').css('display','block');
            jQuery('#upload-progress').html('');
        },
        done: function (e, data) {

                jQuery.each(data.result.files, function (index, file) {
                    if(!file.error){
                        jQuery('#uploaded').append('<input type="hidden" name="uploaded[]" value="' + file.name + '"/>');
                        jQuery('#uploaded').append('<div class="azzap-up-img"><img alt="' + file.name + '" src="' + file.thumbnailUrl + '"/></div>');
                    }else{
                        alert('Error uploading file, ' + file.error);
                    }
                });
        }
    });
});