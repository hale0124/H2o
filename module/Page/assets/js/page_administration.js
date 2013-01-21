$(document).ready(function(){
    $('#display_wysiwyg').change(function(){
        if($(this).is(':checked')){
            $('textarea.editable').addClass('ckeditor');
            CKEDITOR.replaceAll();
        }else{
            if (countProps(CKEDITOR.instances)) { 
                for(name in CKEDITOR.instances)
                {
                    CKEDITOR.instances[name].destroy();
                }
            }
        } 
    });
});