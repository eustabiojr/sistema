function Ageunet(){}
function Template(){}

function __ageunet_def_idioma(idioma)
{
    Ageunet.idioma = idioma;
}

function __ageunet_def_depuracao(depuracao)
{
    Ageunet.depuracao = depuracao;
}

function __ageunet_run_after_loads(url, dados)
{
    if (typeof Ageunet.aoAposCarregar == "function") {
        Ageunet.aoAposCarregar(url, dados);
    }
    
    if (typeof Template.aoAposCarregar == "function") {
        Template.aoAposCarregar(url, dados);
    }
}

function __ageunet_rodar_apos_posts(url, dados)
{
    if (typeof Ageunet.aoAposPost == "function") {
        Ageunet.aoAposPost(url, dados);
    }
    
    if (typeof Template.aoAposPost == "function") {
        Template.aoAposPost(url, dados);
    }
}

function __ageunet_roda_antes_carregamentos(url)
{
    if (typeof Ageunet.aoAntesCarregar == "function") {
        Ageunet.aoAntesCarregar(url);
    }
    
    if (typeof Template.aoAntesCarregar == "function") {
        Template.aoAntesCarregar(url);
    }
}

function __ageunet_rodar_antes_posts(url)
{
    if (typeof Ageunet.aoAntesPost == "function") {
        Ageunet.aoAntesPost(url);
    }
    
    if (typeof Template.aoAntesPost == "function") {
        Template.aoAntesPost(url);
    }
}

function __ageunet_mensagem_falha()
{
    if (Ageunet.depuracao == 1) {
        if (Ageunet.idioma == 'pt') {
            return 'Requisição falhou. Verifique a conexão com internet e os logs do servidor de aplicação';
        }
        return 'Request failed. Check the internet connection and the application server logs';
    }
    else
    {
        if (Ageunet.idioma == 'pt') {
            return 'Requisição falhou';
        }
        return 'Request failed';
    }
}

/**
 * Goto a given pagina
 */
function __ageunet_vaipara_pagina(pagina)
{
    window.location = pagina;
}

/**
 * Returns the URL Base
 */
function __ageunet_base_url()
{
   return window.location.protocol +'//'+ window.location.host + window.location.pathname.split( '/' ).slice(0,-1).join('/');
}

/**
 * Returns the query string
 */
function __ageunet_string_consulta()
{
    var string_consulta = {};
    var consulta = window.location.search.substring(1);
    var vars = consulta.split("&");
    for (var i=0; i<vars.length; i++)
    {
        var pair = vars[i].split("=");
        if (typeof string_consulta[pair[0]] === "undefined")
        {
            string_consulta[pair[0]] = pair[1];
            // If second entry with this name
        }
        else if (typeof string_consulta[pair[0]] === "string")
        {
            var arr = [ string_consulta[pair[0]], pair[1] ];
            string_consulta[pair[0]] = arr;
        }
        else
        {
            string_consulta[pair[0]].push(pair[1]);
        }
    } 
    return string_consulta;
}

/**
 * Converts query string into json object
 */
function __ageunet_consulta_para_json(consulta)
{
    var partes = consulta.split('&');
    var params = Object();
    var decode = function (s) {
        if (typeof s !== "undefined"){
            return urldecode(s.replace(/\+/g, " "));
        }
        return s;
    };
    
    for (var i=0; i < partes.length ; i++) {
        var part = partes[i].split('=');
        if(part[0].search("\\[\\]") !== -1) {
            part[0]=part[0].replace(/\[\]$/,'');
            if( typeof params[part[0]] === 'undefined' ) {
                params[part[0]] = [decode(part[1])];

            } else {
                params[part[0]].push(decode(part[1]));
            }


        } else {
            params[part[0]] = decode(part[1]);
        }
    }

    return params;
}

/**
 * Loads an HTML content
 */
function __ageunet_carrega_html(content, afterCallback, url)
{
    var url_container   = url.match('target_container=([0-z-]*)');
    var match_container = content.match('adianti_target_container\\s?=\\s?"([0-z-]*)"');
    
    if (url_container !== null)
    {
        var target_container = url_container[1];
        $('#'+target_container).empty();
        $('#'+target_container).html(content);
    }
    else if ( match_container !== null)
    {
        var target_container = match_container[1];
        $('#'+target_container).empty();
        $('#'+target_container).html(content);
    }
    else if ($('[widget="TWindow"]').length > 0 && (content.indexOf('widget="TWindow"') > 0))
    {
        $('[widget="TWindow"]').attr('remove', 'yes');
        $('#adianti_online_content').empty();
        content = content.replace(new RegExp('__ageunet_append_pagina', 'g'), '__ageunet_append_pagina2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
        $('#adianti_online_content').html(content);
        $('[widget="TWindow"][remove="yes"]').remove();
    }
    else
    {
        if (content.indexOf('widget="TWindow"') > 0)
        {
            content = content.replace(new RegExp('__ageunet_append_pagina', 'g'), '__ageunet_append_pagina2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
            $('#adianti_online_content').html(content);
        }
        else
        {
            if (typeof Ageunet.onClearDOM == "function")
            {
                Ageunet.onClearDOM();
            }
            
            $('[widget="TWindow"]').remove();
            $('#adianti_div_content').html(content);
        }
    }
    
    if (typeof afterCallback == "function")
    {
        afterCallback(url, content);
    }
}

/**
 * Loads an HTML content. This function is called if there is an window opened.
 */
function __ageunet_carrega_html2(content)
{
   if ($('[widget="TWindow2"]').length > 0)
   {
       $('[widget="TWindow2"]').attr('remove', 'yes');
       $('#adianti_online_content2').hide();
       content = content.replace(new RegExp('__ageunet_carrega_html', 'g'), '__ageunet_carrega_html2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
       content = content.replace(new RegExp('__ageunet_load_pagina', 'g'), '__ageunet_load_pagina2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
       content = content.replace(new RegExp('__ageunet_post_dados', 'g'), '__ageunet_post_dados2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
       content = content.replace(new RegExp('TWindow','g'), 'TWindow2'); // quando submeto botão de busca, é destruído tudo que tem TWindow2 e recarregado
       content = content.replace(new RegExp('generator="adianti"', 'g'), 'generator="adianti2"'); // links também são alterados
       $('#adianti_online_content2').html(content);
       $('[widget="TWindow2"][remove="yes"]').remove();
       $('#adianti_online_content2').show();
   }
   else
   {
       if (content.indexOf('widget="TWindow2"') > 0)
       {
           $('#adianti_online_content2').html(content);
       }
       else if (content.indexOf('widget="TWindow"') > 0)
       {
           $('#adianti_online_content').html(content);
       }
       else
       {
           $('#adianti_div_content').html(content);
       }
   }
}

function __ageunet_load_pagina_no_register(pagina)
{
    $.get(pagina)
    .done(function(dados) {
        __ageunet_carrega_html(dados, null, pagina);
    }).fail(function(jqxhr, textStatus, exception) {
       __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
    });
}

function __ageunet_load_pagina_no_register2(pagina)
{
    $.get(pagina)
    .done(function(dados) {
        __ageunet_carrega_html2(dados);
    }).fail(function(jqxhr, textStatus, exception) {
       __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Called by Seekbutton. Add the pagina content. 
 */
function __ageunet_append_pagina(pagina, callback)
{
    pagina = pagina.replace('engine.php?','');
    params_json = __ageunet_consulta_para_json(pagina);

    uri = 'engine.php?' 
        + 'class=' + params_json.class
        + '&method=' + params_json.method
        + '&static=' + (params_json.static == '1' ? '1' : '0');

    $.post(uri, params_json)
    .done(function(dados){
        dados = dados.replace(new RegExp('__ageunet_append_pagina', 'g'), '__ageunet_append_pagina2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
        $('#adianti_online_content').after('<div></div>').html(dados);
        
        if (typeof callback == "function")
        {
            callback();
        }
    }).fail(function(jqxhr, textStatus, exception) {
       __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Called by Seekbutton from opened windows. 
 */
function __ageunet_append_pagina2(pagina)
{
    pagina = pagina.replace('engine.php?','');
    params_json = __ageunet_consulta_para_json(pagina);

    uri = 'engine.php?' 
        + 'class=' + params_json.class
        + '&method=' + params_json.method
        + '&static=' + (params_json.static == '1' ? '1' : '0');

    $.post(uri, params_json)
    .done(function(dados) {
        dados = dados.replace(new RegExp('__ageunet_carrega_html', 'g'), '__ageunet_carrega_html2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('__ageunet_load_pagina', 'g'), '__ageunet_load_pagina2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('__ageunet_post_dados', 'g'), '__ageunet_post_dados2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('TWindow', 'g'),             'TWindow2'); // quando submeto botão de busca, é destruído tudo que tem TWindow2 e recarregado
        dados = dados.replace(new RegExp('generator="adianti"', 'g'), 'generator="adianti2"'); // links também são alterados
        $('#adianti_online_content2').after('<div></div>').html(dados);
    }).fail(function(jqxhr, textStatus, exception) {
       __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Open a pagina using ajax
 */
function __ageunet_load_pagina(pagina, callback)
{
    if (typeof pagina !== 'undefined')
    {
        $( '.modal-backdrop' ).remove();
        var url = pagina;
        url = url.replace('index.php', 'engine.php');
        
        if(url.indexOf('engine.php') == -1) {
            url = 'xhr-'+url;
        }
        
        __ageunet_roda_antes_carregamentos(url);
        
        if (url.indexOf('&static=1') > 0)
        {
            $.get(url)
            .done(function(dados) {
                __ageunet_parse_html(dados);
                
                Ageunet.requestURL  = url;
                Ageunet.requestData = null;
                
                if (typeof callback == "function")
                {
                    callback();
                }
                
                __ageunet_run_after_loads(url, dados);
                
            }).fail(function(jqxhr, textStatus, exception) {
               __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
               loading = false;
            });
        }
        else
        {
            $.get(url)
            .done(function(dados) {
                Ageunet.requestURL  = url;
                Ageunet.requestData = null;
                
                __ageunet_carrega_html(dados, __ageunet_run_after_loads, url);
                
                if (typeof callback == "function")
                {
                    callback();
                }
                
                if ( url.indexOf('register_state=false') < 0 && history.pushState && (dados.indexOf('widget="TWindow"') < 0) )
                {
                    __ageunet_register_state(url, 'adianti');
                    Ageunet.currentURL = url;
                }
            }).fail(function(jqxhr, textStatus, exception) {
               __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
               loading = false;
            });
        }
    }
}

/**
 * Used by all links inside a window (generator=adianti)
 */
function __ageunet_load_pagina2(pagina)
{
    url = pagina;
    url = url.replace('index.php', 'engine.php');
    __ageunet_load_pagina_no_register2(url);
    
    Ageunet.requestURL  = url;
    Ageunet.requestData = null;
}

/**
 * Start blockUI dialog
 */
function __ageunet_block_ui(wait_message)
{
    if (typeof $.blockUI == 'function')
    {
        if (typeof Ageunet.blockUIConter == 'undefined')
        {
            Ageunet.blockUIConter = 0;
        }
        Ageunet.blockUIConter = Ageunet.blockUIConter + 1;
        if (typeof wait_message == 'undefined')
        {
            wait_message = Ageunet.waitMessage;
        }
        
        $.blockUI({ 
           message: '<h1><i class="fa fa-spinner fa-pulse"></i> '+wait_message+'</h1>',
           fadeIn: 0,
           fadeOut: 0,
           css: { 
               border: 'none', 
               top: '100px',
               left: 0,
               maxWidth: '300px',
               width: 'inherit',
               padding: '15px', 
               backgroundColor: '#000', 
               'border-radius': '5px 5px 5px 5px',
               opacity: .5, 
               color: '#fff' 
           }
        });
        
        $('.blockUI.blockMsg').mycenter();
    }
}

/**
 * Open a window
 */
function __ageunet_window(title, width, height, content)
{
    $('<div />').html(content).dialog({
        modal: true,
        title: title,
        width : width,
        height : height,
        resizable: true,
        closeOnEscape:true,
        close: function(ev, ui) { $(this).remove(); },
        focus:true
    });
}

function __ageunet_window_pagina(title, width, height, pagina)
{
    if (width<2)
    {
        width = $(window).width() * width;
    }
    if (height<2)
    {
        height = $(window).height() * height;
    }
    
    $('<div />').append($("<iframe style='width:100%;height:97%' />").attr("src", pagina)).dialog({
        modal: true,
        title: title,
        width : width,
        height : height,
        resizable: false,
        closeOnEscape:true,
        close: function(ev, ui) { $(this).remove(); },
        focus:true
    });
}

/**
 * Show standard dialog
 */
function __ageunet_dialog( options )
{
    if (options.type == 'info') {
        var icon = (options.icon ? options.icon : 'fa fa-info-circle fa-4x blue');
    }
    else if (options.type == 'warning') {
        var icon = (options.icon ? options.icon : 'fa fa-exclamation-triangle fa-4x orange');
    }
    else if (options.type == 'error') {
        var icon = (options.icon ? options.icon : 'fa fa-exclamation-circle fa-4x red');
    }
    
    if (typeof bootbox == 'object')
    {
        bootbox.dialog({
          title: options.title,
          animate: false,
          backdrop: true,
          onEscape: function() {
            if (typeof options.callback != 'undefined')
            { 
                options.callback();
            }
          },
          message: '<div>'+
                    '<span class="'+icon+'" style="float:left"></span>'+
                    '<span style="margin-left:70px;display:block;max-height:500px">'+options.message+'</span>'+
                    '</div>',
          buttons: {
            success: {
              label: "OK",
              className: "btn-default",
              callback: function() {
                if (typeof options.callback != 'undefined')
                { 
                    options.callback();
                }
              }
            }
          }
        });
    }
    else {
        // fallback mode
        alert(options.message);
        if (typeof options.callback != 'undefined') {
            options.callback();
        }
    }
}

/**
 * Show message error dialog
 */
function __ageunet_error(title, message, callback)
{
    __ageunet_dialog( { type: 'error', title: title, message: message, callback: callback} );
}

/**
 * Show message info dialog
 */
function __ageunet_message(title, message, callback)
{
    __ageunet_dialog( { type: 'info', title: title, message: message, callback: callback} );
}

/**
 * Show message warning dialog
 */
function __ageunet_warning(title, message, callback)
{
    __ageunet_dialog( { type: 'warning', title: title, message: message, callback: callback} );
}

/**
 * Show question dialog
 */
function __ageunet_question(title, message, callback_yes, callback_no, label_yes, label_no)
{
    if (typeof bootbox == 'object')
    {
        bootbox.dialog({
          title: title,
          animate: false,
          message: '<div>'+
                    '<span class="fa fa-question-circle fa-4x blue" style="float:left"></span>'+
                    '<span style="margin-left:70px;display:block;max-height:500px">'+message+'</span>'+
                    '</div>',
          buttons: {
            yes: {
              label: label_yes,
              className: "btn-default",
              callback: function() {
                if (typeof callback_yes != 'undefined') {
                    callback_yes();
                }
              }
            },
            no: {
              label: label_no,
              className: "btn-default",
              callback: function() {
                if (typeof callback_no != 'undefined') {
                    callback_no();
                }
              }
            },
          }
        });
    }
    else
    {
        // fallback mode
        var r = confirm(message);
        if (r == true) {
            if (typeof callback_yes != 'undefined') {
                callback_yes();
            }
        } else {
            if (typeof callback_no != 'undefined') {
                callback_no();
            }
        }
    }
}

/**
 * Show input dialog
 */
function __ageunet_input(question, callback)
{
    if (typeof bootbox == 'object')
    {
        bootbox.prompt(question, function(result) {
          if (result !== null) {
            callback(result);
          }
        });
    }
    else
    {
        var result = prompt(question, '');
        callback(result);
    }
}

function __ageunet_show_toast64(type, message64, place, icon)
{
    __ageunet_show_toast(type, atob(message64), place, icon)
}

function __ageunet_show_toast(type, message, place, icon)
{
    var place = place.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
            if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
            return index == 0 ? match.toLowerCase() : match.toUpperCase();
          });
    
    var options = {
        message: message,
        position: place
    };
    
    if (type == 'show') {
        options['progressBarColor'] = 'rgb(0, 255, 184)';
        options['theme'] = 'dark';
    }
    
    if (typeof icon !== 'undefined') {
        options['icon'] = 'fa ' + icon.replace(':', '-');
    }
    
    iziToast[type]( options );
}

/**
 * Closes blockUI dialog
 */
function __ageunet_unblock_ui()
{
    if (typeof $.blockUI == 'function')
    {
        Ageunet.blockUIConter = Ageunet.blockUIConter -1;
        if (Ageunet.blockUIConter <= 0)
        {
            $.unblockUI( { fadeIn: 0, fadeOut: 0 } );
            Ageunet.blockUIConter = 0;
        }
    }
}

/**
 * Post form dados
 */
function __ageunet_post_dados(form, action)
{
    if (action.substring(0,4) == 'xhr-')
    {
        url = action;
    }
    else
    {
        url = 'index.php?'+action;
        url = url.replace('index.php', 'engine.php');
    }
    
    if (document.querySelector('#'+form) instanceof Node)
    {
        if (!document.querySelector('#'+form).hasAttribute('novalidate') && document.querySelector('#'+form).checkValidity() == false)
        {
            document.querySelector('#'+form).reportValidity();
            return;
        }
    }
    
    __ageunet_block_ui();
    
    dados = $('#'+form).serialize();
    
    __ageunet_rodar_antes_posts(url);
    
    if (url.indexOf('&static=1') > 0 || (action.substring(0,4) == 'xhr-'))
    {
        $.post(url, dados)
        .done(function(result) {
            __ageunet_parse_html(result);
            __ageunet_unblock_ui();
            
            Ageunet.requestURL  = url;
            Ageunet.requestData = dados;
            
            __ageunet_rodar_apos_posts(url, dados);
            
        }).fail(function(jqxhr, textStatus, exception) {
            __ageunet_unblock_ui();
            __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
            loading = false;
        });
    }
    else
    {
        $.post(url, dados)
        .done(function(result) {
            Ageunet.currentURL  = url;
            Ageunet.requestURL  = url;
            Ageunet.requestData = dados;
            
            __ageunet_carrega_html(result, __ageunet_rodar_apos_posts, url);
            __ageunet_unblock_ui();
            
        }).fail(function(jqxhr, textStatus, exception) {
            __ageunet_unblock_ui();
            __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
            loading = false;
        });
    }
}

/**
 * Post form dados over window
 */
function __ageunet_post_dados2(form, url)
{
    url = 'index.php?'+url;
    url = url.replace('index.php', 'engine.php');
    dados = $('#'+form).serialize();
    
    $.post(url, dados)
    .done(function(result)
    {
        __ageunet_carrega_html2(result);
        __ageunet_unblock_ui();
        
        Ageunet.requestURL  = url;
        Ageunet.requestData = dados;
        
    }).fail(function(jqxhr, textStatus, exception) {
        __ageunet_unblock_ui();
        __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Register URL state
 */
function __ageunet_register_state(url, origin)
{
    if (Ageunet.registerState !== false || origin == 'user')
    {
        var stateObj = { url: url };
        if (typeof history.pushState != 'undefined') {
            history.pushState(stateObj, "", url.replace('engine.php', 'index.php').replace('xhr-', ''));
        }
    }
}

/**
 * Ajax lookup
 */
function __ageunet_ajax_lookup(action, field)
{
    var value = field.value;
    __ageunet_ajax_exec(action +'&key='+value+'&ajax_lookup=1', null);
}

/**
 * Execute an Ajax action
 */
function __ageunet_ajax_exec(action, callback, automatic_output)
{
    var uri = 'engine.php?' + action +'&static=1';
    var automatic_output = (typeof automatic_output === "undefined") ? true : automatic_output;
    
    $.ajax({url: uri})
    .done(function( dados ) {
        if (automatic_output) {
            __ageunet_parse_html(dados, callback);
        }
        else {
            callback(dados);
        }
    }).fail(function(jqxhr, textStatus, exception) {
       __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Get remote content
 */
function __ageunet_get_pagina(action, callback, postdados)
{
    var uri = 'engine.php?' + action +'&static=1';
    
    if (typeof postdados !== "undefined") {
        if (typeof postdados.static !== "undefined") {
            var uri = 'engine.php?' + action +'&static='+postdados.static;
        }
    }
    
    $.ajax({
      url: uri,
      dados: postdados
      }).done(function( dados ) {
          return callback(dados);
      }).fail(function(jqxhr, textStatus, exception) {
         __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
      });
}

function __ageunet_post_lookup(form, action, field, callback) {
    if (typeof field == 'string') {
        field_obj = $('#'+field);
    }
    else if (field instanceof HTMLElement) {
        field_obj = $(field);
    }
    
    var formdados = $('#'+form).serializeArray();
    formdados.push({name: '_field_value', value: field_obj.val()});
    
    var uri = 'engine.php?' + action +'&static=1';
    formdados.push({name: '_field_id',   value: field_obj.attr('id')});
    formdados.push({name: '_field_name', value: field_obj.attr('name')});
    formdados.push({name: '_form_name',  value: form});
    formdados.push({name: '_field_dados', value: $.param(field_obj.dados(), true)});
    formdados.push({name: 'key',         value: field_obj.val()}); // for BC
    formdados.push({name: 'ajax_lookup', value: 1});
    
    $.ajax({
      type: 'POST',
      url: uri,
      dados: formdados
      }).done(function( dados ) {
          __ageunet_parse_html(dados, callback);
      }).fail(function(jqxhr, textStatus, exception) {
         __ageunet_error('Error', textStatus + ': ' + __ageunet_mensagem_falha());
      });
}

/**
 * Parse returning HTML
 */
function __ageunet_parse_html(dados, callback)
{
    tmp = dados;
    tmp = new String(tmp.replace(/window\.opener\./g, ''));
    tmp = new String(tmp.replace(/window\.close\(\)\;/g, ''));
    tmp = new String(tmp.replace(/^\s+|\s+$/g,""));
    
    if ($('[widget="TWindow2"]').length > 0)
    {
       // o código dinâmico gerado em ajax lookups (ex: seekbutton)
       // deve ser modificado se estiver dentro de window para pegar window2
       tmp = new String(tmp.replace(/TWindow/g, 'TWindow2'));
    }
    
    try {
        // permite código estático também escolher o target
        var match_container = dados.match('adianti_target_container\\s?=\\s?"([0-z]*)"');
        
        if ( match_container !== null)
        {
            var target_container = match_container[1];
            $('#'+target_container).empty();
            $('#'+target_container).html(tmp);
        }
        else
        {
            // target default
            $('#adianti_online_content').find('script').remove();
            $('#adianti_online_content').append(tmp);
        }
        
        if (callback && typeof(callback) === "function")
        {
            callback(dados);
        }
        
    } catch (e) {
        if (e instanceof Error) {
            $('<div />').html(e.message + ': ' + tmp).dialog({modal: true, title: 'Error', width : '80%', height : 'auto', resizable: true, closeOnEscape:true, focus:true});
        }
    }
}

/**
 * Download a file
 */
function __ageunet_download_file(file)
{
    extension = file.split('.').pop();
    screenWidth  = screen.width;
    screenHeight = screen.height;
    if (extension !== 'html')
    {
        screenWidth /= 3;
        screenHeight /= 3;
    }
    
    window.open('download.php?file='+file, '_blank',
      'width='+screenWidth+
     ',height='+screenHeight+
     ',top=0,left=0,status=yes,scrollbars=yes,toolbar=yes,resizable=yes,maximized=yes,menubar=yes,location=yes');
}

/**
 * Open pagina in new tab
 */
function __ageunet_open_pagina(pagina)
{
    var win = window.open(pagina, '_blank');
    if (win)
    {
        win.focus();
    }
    else
    {
        alert('Please allow popups for this website');
    }
}

/**
 * Process popovers
 */
function __ageunet_process_popover()
{
    var get_placement = function (tip, element) {
        $element = $(element);

        var valid_placements = [
            "auto",
            "top",
            "right",
            "bottom",
            "left",
        ];

        if (typeof $element.attr('popside') === "undefined" || valid_placements.indexOf($element.attr('popside')) === -1) {
            return 'auto';
        }
        else {
            return $(element).attr("popside");
        }
    };
    
    var get_content = function (tip, element) {
        if (typeof $(this).attr('popaction') === "undefined") {
            if (typeof $(this).attr("popcontent64") !== "undefined") {
                return base64_decode($(this).attr("popcontent64"));
            }
            else {
                return $(this).attr("popcontent") || '';
            }
        }
        else {
            var inst = $(this);
            __ageunet_get_pagina($(this).attr('popaction'), function(dados) {
                var popover = inst.attr('dados-content',dados).dados('bs.popover');
                popover.setContent();
                popover.show();
            }, {'static': '0'});
            return '<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>';
        }
    };
    
    var get_title = function () {
        return $(this).attr("poptitle") || '';
    };
    
    var pop_template = '<div class="popover" role="tooltip" style="max-width:800px"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>';

    $('[popover="true"]:not([poptrigger]):not([processed="true"])').popover({
        placement: get_placement,
        trigger: 'hover',
        container: 'body',
        template: pop_template,
        delay: { show: 10, hide: 10 },
        content: get_content,
        html: true,
        title: get_title,
        sanitizeFn : function(d) { return d },
    }).attr('processed', true);
    
    $('[popover="true"][poptrigger="click"]:not([processed="true"])').popover({
        placement: get_placement,
        trigger: 'click',
        container: 'body',
        template: pop_template,
        delay: { show: 10, hide: 10 },
        content: get_content,
        sanitizeFn : function(d) { return d },
        html: true,
        title: get_title
    }).on('shown.bs.popover', function (e) {
        if (typeof $(this).attr('popaction') !== "undefined") {
            var inst = $(this);
            __ageunet_get_pagina($(this).attr('popaction'), function(dados) {
                var popover = inst.attr('dados-content',dados).dados('bs.popover');
                popover.setContent();
                // popover.$tip.addClass( $(e.target).attr('popside') );
            }, {'static': '0'});
        }
    }).attr('processed', true);
    
    $('body').on('click', function (e) {
        //$('.tooltip').hide();
        if (!$(e.target).is('[popover="true"]') && !$(e.target).parents('.popover').length > 0) {
            // avoid closing dropdowns inside popover (colorpicker, datepicker) when they are outside popover DOM
            if (!$(e.target).parents('.dropdown-menu').length > 0) {
                $('.popover').popover('hide');
            }
        }
    });
}

/**
 * Show popover nearby element
 */
function __ageunet_show_popover(element, title, message, placement, custom_options)
{
    var standard_options = {trigger:"manual", title:title, html: true, content:message, placement:placement, sanitizeFn : function(d) { return d }};
    var options = standard_options;
    
    if (typeof custom_options !== undefined)
    {
        var options = Object.assign(standard_options, custom_options);
    }
    if ($(element).length>0 && $(element).css("visibility") == "visible") {
        $(element).popover(options).popover("show");
    }
}

/**
 * Start actions
 */
$(function() {
    Ageunet.blockUIConter = 0;
    
    if (typeof $().tooltip == 'function')
    {
        $(document.body).tooltip({
            selector: "[title]",
            placement: function (tip, element) {
                    $element = $(element);
                    
                    var valid_placements = [
                        "auto",
                        "top",
                        "right",
                        "bottom",
                        "left",
                    ];
            
                    if (typeof $element.attr('titside') === "undefined" || valid_placements.indexOf($element.attr('titside')) === -1) {
                        return 'auto';
                    }
                    else {
                        return $(element).attr("titside");
                    }
                },
            trigger: 'hover',
            cssClass: 'tooltip',
            container: 'body',
            content: function () {
                return $(this).attr("title");
            },
            html: true
        });
    }
    
    if (typeof $().popover == 'function')
    {
        $( document ).on( "dialogopen", function(){
            __ageunet_process_popover();
        });
    }
    
    if (typeof jQuery.ui !== 'undefined')
    {
        $.ui.dialog.prototype._focusTabbable = $.noop;
    }
});

/**
 * On Ajax complete actions
 */
$(document).ajaxComplete(function ()
{
    if (typeof $().popover == 'function')
    {
        __ageunet_process_popover();
    }
    
    if (typeof $().DataTable == 'function')
    {
        $('table[dadostable="true"]:not(.dadosTable)').DataTable( {
            responsive: true,
            paging: false,
            searching: false,
            ordering:  false,
            info: false
        });
    }
});

/**
 * Override the default pagina loader
 */
$( document ).on( 'click', '[generator="adianti"]', function()
{
   __ageunet_load_pagina($(this).attr('href'));
   return false;
});

/**
 * Override the default pagina loader for new windows
 */
$( document ).on( 'click', '[generator="adianti2"]', function()
{
   __ageunet_load_pagina2($(this).attr('href'));
   return false;
});

/**
 * Register pagina navigation
 */
window.onpopstate = function(stackstate)
{
    if (stackstate.state)
    {
        __ageunet_load_pagina_no_register(stackstate.state.url);
    }
};

$.fn.mycenter = function () {
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.outerHeight() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.outerWidth() ) / 2+$(window).scrollLeft() + "px");
    return this;
}