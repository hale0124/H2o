(function($) {
$.extend({
    application: {
        init: function() {
                var app = $.application;
                // načtení flashes přímo z html (při neajaxovém požadavku)
                app.flashes.add($.parseJSON($('#flash_messages').attr('data-flash-messages')));
        },
        flashes: {
                delay: 4000,    // čas po který je jedna zpráva zobrazena
                enabled: true,  // zda je povoleno zobrazovat fashes
                messages: [],   // fronta zpráv
                timeout: null,  // objekt s timeoutem při volání funkce hide
                disable: function() {$.application.flashes.enabled = false;}, // zakázání zobrazování fashes
                enable: function() {$.application.flashes.enabled = true;}, // povolení zobrazování fashes
                infoMsg: function(text){
                    var m = {'info': { 0 : text }}
                    $.application.flashes.add(m);
                },
                errorMsg: function(text){
                    var m = {'error': { 0 : text }}
                    $.application.flashes.add(m);
                },
                successMsg: function(text){
                    var m = {'success': { 0 : text }}
                    $.application.flashes.add(m);
                },
                add: function(msg) { // přidání nové zprávy
                    if(msg){
                        $.application.flashes.messages = msg;
                    }
                    $.application.flashes.show();
                },
                show: function() { // zobrazení nové zprávy
                    var flashes = $.application.flashes;
                    if(flashes.enabled) {
                        var messages = $.application.flashes.messages;
                        var messageBox = $('#flash_messages');
                        var html = '<div class="flash-messages-container row">';
                               html += '<div class="span4 offset4">';
                               html += '<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>';
                               html += '</div></div></div>';
                        var flash = $(html);
                        if(countProps(messages) > 0){
                            $.each(messages, function(key, value){
                                var f = flash.clone();
                                $('.alert', f).addClass('alert-'+key);
                                var i = 0;
                                var c = value.length;
                                $.each(value, function(x,message){
                                    var m = '';
                                    if(i < c){
                                        m = message+'<br />';
                                    }else{
                                        m = message;
                                    }
                                    $('.alert',f).append(m);
                                    i++;
                                });
                                messageBox.append(f);
                            });
                            messageBox.removeClass('hidden');
                            flashes.timeout = setTimeout(flashes.hide, flashes.delay);
                        }
                     }
                },
                hide: function() { // skrytí zprávy po určitém timeoutu
                    var messageBox = $('.flash-messages-container');
                    messageBox.fadeOut();
                }
            }
        }
    });
})(jQuery);
