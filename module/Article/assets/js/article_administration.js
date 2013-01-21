/**
 * CK editor initialization
 */
/*
$(document).ready(function(){
   if (countProps(CKEDITOR.instances)) { 
        for(name in CKEDITOR.instances)
        {
            CKEDITOR.remove(CKEDITOR.instances[name]);
        }
    }
    $('.ckeditor').ckeditor(); 
});

function ckeditorPatternConfig(variables, variablesLabel)
{   
    return {
        uiColor: '#9ddbff',
        toolbar_saToolbar:
        [
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Format','FontSize'],
        ['TextColor','BGColor'],
        ['Outdent','Indent','-','SpecialChar'],
        ['Image','Table'],
        ],
        enterMode : CKEDITOR.ENTER_BR,
        toolbar: 'saToolbar',
        extraPlugins: 'saVariables',
        language: 'cs',
        saVariables: variables,
        saVariablesLabel: variablesLabel,
        entities: false
    };
}

*/
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