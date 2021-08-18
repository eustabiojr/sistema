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

function __ageunet_roda_apos_carregamentos(url, dados)
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
function __ageunet_url_base()
{
   return window.location.protocol +'//'+ window.location.host + window.location.pathname.split( '/' ).slice(0,-1).join('/');
}

/**
 * Returns the query string (Atualizado em 20/06/2021)
 */
function __ageunet_string_consulta()
{
    // string_consulta é uma objeto vazio inicialmente.
    let string_consulta = {};
    // Pega as strings de consulta que fica após o '?'
    let consulta = window.location.search.substring(1);
    let vars = consulta.split("&");

    for (let i=0; i < vars.length; i++)
    {
        let dupla = vars[i].split("=");
        if (typeof string_consulta[dupla[0]] === "undefined")
        {
            string_consulta[dupla[0]] = dupla[1];
            // If second entry with this name
        }
        else if (typeof string_consulta[dupla[0]] === "string")
        {
            let arr = [ string_consulta[dupla[0]], dupla[1] ];
            string_consulta[dupla[0]] = arr;
        }
        else
        {
            string_consulta[dupla[0]].push(dupla[1]);
        }
    } 
    return string_consulta;
}

/**
 * Converts query string into json object (Atualizado para JS Puro. Jun21. Apenas revisado.)
 */
function __ageunet_consulta_para_json(consulta)
{
    let partes = consulta.split('&');
    let params = Object();

    // função anônima atribuída a uma variável
    let decode = function (s) {
        if (typeof s !== "undefined"){
            // este replace substitui globalmente o sinal de mais (+) por espaço em branco
            return urldecode(s.replace(/\+/g, " "));
        }
        return s;
    };
    
    for (let i=0; i < partes.length ; i++) {
        let parte = partes[i].split('=');
        // caso localize o alvo, realiza a substituição do alvo por nada (ou seja, remove)
        // Veja que no search o parâmetro está sendo passado entre aspas. Sendo assim,
        // neste caso será necessário acrescentar uma barra invertida para escapar o caractere.
        //
        // If it locates the target, performs replacement of the target with nothing (ie removes)
        // See that in search the parameter is being passed in quotes. Therefore,
        // in this case it will be necessary to add a backslash to escape the character. 
        //
        // Portanto, esse regex busca por: [] (Ou seja, um parte de parenteses)
        // if(parte[0].search("\\[\\]") !== -1) {
        if(parte[0].search(/\[\]/) !== -1) {
            // a regex seguinte especifica que o alvo (no caso []) fica no final da string.
            parte[0]=parte[0].replace(/\[\]$/,'');
            
            /**
             * Caso não esteja defindo, definimos. Caso esteja definido, acrescentamos 
             * mais um item.
             */
            if( typeof params[parte[0]] === 'undefined' ) {
                params[parte[0]] = [decode(parte[1])];

            } else {
                params[parte[0]].push(decode(parte[1]));
            }
        } else {
            params[parte[0]] = decode(parte[1]);
        }
    }
    return params;
}

/**
 * Carrega um conteudo HTML (AQUI)
 */
function __ageunet_carrega_html(conteudo, aposChamaDeVolta, url)
{
    const doc = document;
    let recipiente_url     = url.match('recipiente_alvo=([0-z-]*)');
    let combina_recipiente = conteudo.match('ageunet_recipiente_alvo\\s?=\\s?"([0-z-]*)"');

    //alert(`O conteúdo de 'recipiente_url' é: ${recipiente_url}. \r\n Já o retorno de 'combina_recipiente' é: ${conteudo}`);
     
    if (recipiente_url !== null)
    {
        //alert('OK!!! ' + recipiente_url[1]);
        let recipiente_alvo = recipiente_url[1];

        doc.querySelector('#'+recipiente_alvo).innerHTML = '';
        doc.querySelector('#'+recipiente_alvo).innerHTML = conteudo;
    }
    else if ( combina_recipiente !== null)
    {
        let recipiente_alvo = combina_recipiente[1];

        doc.querySelector('#'+recipiente_alvo).innerHTML = '';
        doc.querySelector('#'+recipiente_alvo).innerHTML = conteudo;
    } 
    // else if ($('[widget="TWindow"]').length > 0 && (conteudo.indexOf('widget="TWindow"') > 0)) // Aqui está em jQuery
    else if (doc.querySelectorAll('[widget="GJanela"]').length > 0 && (conteudo.indexOf('widget="GJanela"') > 0))
    {
        //$('[widget="TWindow"]').attr('remove', 'yes'); // aqui é como era em jQuery
        doc.querySelectorAll('[widget="GJanela"]').forEach(x => x.setAttribute('remove', 'yes'));
        //$('#ageunet_conteudo_online').empty();
        doc.querySelector('#ageunet_conteudo_online').innerHTML = '';
        conteudo = conteudo.replace(new RegExp('__ageunet_anexa_pagina', 'g'), '__ageunet_anexa_pagina2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
        //$('#ageunet_conteudo_online').html(conteudo);
        doc.querySelector('#ageunet_conteudo_online').innerHTML = conteudo;
        //$('[widget="TWindow"][remove="yes"]').remove();
        doc.querySelector('[widget="GJanela"][remove="yes"]').remove();
    }
    else
    {
        if (conteudo.indexOf('widget="GJanela"') > 0)
        {
            conteudo = conteudo.replace(new RegExp('__ageunet_anexa_pagina', 'g'), '__ageunet_anexa_pagina2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
            doc.querySelector('#ageunet_conteudo_online').innerHTML = conteudo; // era .html(conteudo) com jQuery
        }
        else
        {
            if (typeof Ageunet.onClearDOM == "function")
            {
                Ageunet.onClearDOM();
            }
             
            doc.querySelector('[widget="GJanela"]').remove(); // Falta testar mais
            doc.querySelector('#ageunet_conteudo_div').innerHTML = conteudo;
        }
    }
     
    if (typeof aposChamaDeVolta == "function")
    {
        aposChamaDeVolta(url, conteudo);
    }
}

/**
 * Loads an HTML conteudo. This function is called if there is an window opened.
 */
function __ageunet_carrega_html2(conteudo)
{
    const doc = document;
    if (doc.querySelectorAll('[widget="GJanela"]').length > 0)
    {
       doc.querySelectorAll('[widget="GJanela"]').forEach(x => x.setAttribute('remove', 'yes'));
       // $('#ageunet_conteudo_online2').hide(); // ###
       doc.querySelector('#ageunet_conteudo_online2').style.display = 'none';
       conteudo = conteudo.replace(new RegExp('__ageunet_carrega_html', 'g'), '__ageunet_carrega_html2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
       conteudo = conteudo.replace(new RegExp('__ageunet_carrega_pagina', 'g'), '__ageunet_carrega_pagina2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
       conteudo = conteudo.replace(new RegExp('__ageunet_dados_post', 'g'), '__ageunet_dados_post2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
       conteudo = conteudo.replace(new RegExp('GJanela','g'), 'GJanela'); // quando submeto botão de busca, é destruído tudo que tem GJanela e recarregado
       conteudo = conteudo.replace(new RegExp('generator="ageunet"', 'g'), 'generator="ageunet2"'); // links também são alterados
       doc.querySelector('#ageunet_conteudo_online2').innerHTML = conteudo;
       doc.querySelector('[widget="GJanela"][remove="yes"]').remove();
       // $('#ageunet_conteudo_online2').show();
       doc.querySelector('#ageunet_conteudo_online2').style.display = 'block';
    }
    else
    {
        if (conteudo.indexOf('widget="GJanela"') > 0)
        {
            doc.querySelector('#ageunet_conteudo_online2').innerHTML = conteudo;
        }
        else if (conteudo.indexOf('widget="GJanela"') > 0)
        {
            doc.querySelector('#ageunet_conteudo_online').innerHTML = conteudo;
        }
        else
        {
            doc.querySelector('#ageunet_conteudo_div').innerHTML = conteudo;
        }
    }
}

function __carrega_pagina_nao_registra(pagina)
{
    /** Versão jQuery 
    $.get(pagina)
    .done(function(dados) {
        __ageunet_carrega_html(dados, null, pagina);
    }).fail(function(jqxhr, textoStatus, exception) {
       __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    });*/

    fetch(pagina, { headers: { 'cache-control' : 'no-cache' } })
    .then(resposta => resposta.text())
    .then(function(dados) {
        __ageunet_carrega_html(dados, null, pagina); 
    })
    .catch(function(textoStatus) {
        __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    })
}

function __carrega_pagina_nao_registra2(pagina)
{
    /** Versão jQuery 
    $.get(pagina)
    .done(function(dados) {
        __ageunet_carrega_html2(dados);
    }).fail(function(jqxhr, textoStatus, exception) {
       __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    }); */

    fetch(pagina, { headers: { 'cache-control' : 'no-cache' } })
    .then(resposta => resposta.text())
    .then(function(dados) {
        __ageunet_carrega_html2(dados, null, pagina); 
    })
    .catch(function(textoStatus) {
        __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    })
}

/**
 * Called by Seekbutton. Add the pagina conteudo. 
 */
function __ageunet_anexa_pagina(pagina, callback)
{
    pagina = pagina.replace('motor.php?','');
    params_json = __ageunet_consulta_para_json(pagina);

    uri = 'motor.php?' 
        + 'classe=' + params_json.classe
        + '&metodo=' + params_json.metodo
        + '&estatico=' + (params_json.estatico == '1' ? '1' : '0');
    /** Versão jQuery 
    $.post(uri, params_json)
    .done(function(dados){
        dados = dados.replace(new RegExp('__ageunet_anexa_pagina', 'g'), '__ageunet_anexa_pagina2'); // chamadas presentes em botões seekbutton em window, abrem em outra janela
        $('#ageunet_conteudo_online').after('<div></div>').html(dados);
        
        if (typeof callback == "function")
        {
            callback();
        }
    }).fail(function(jqxhr, textoStatus, exception) {
       __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    }); */

    let obtemDados = {
        method: 'POST', 
        body: params_json,
        headers: new Headers()
    }
    fetch(uri, { method: 'POST', headers: { 'cache-control' : 'no-cache' }, body: JSON.stringify(params_json) })
    .then(resposta => resposta.json())
    .then(function(dados) {
        dados = dados.replace(new RegExp('__ageunet_anexa_pagina', 'g'), '__ageunet_anexa_pagina2');
        // O método 'insereApos()' substitui o método jQuery 'after()' do jQuery.
        doc.querySelector('#ageunet_conteudo_online').insereApos(document.createElement('div')).innerHTML = dados;
    })
    .catch(function(textoStatus) {
        __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    })
}

/**
 * Called by Seekbutton from opened windows. 
 */
function __ageunet_anexa_pagina2(pagina)
{
    pagina = pagina.replace('motor.php?','');
    params_json = __ageunet_consulta_para_json(pagina);

    uri = 'motor.php?' 
        + 'classe=' + params_json.classe
        + '&metodo=' + params_json.metodo
        + '&estatico=' + (params_json.estatico == '1' ? '1' : '0');

    /*$.post(uri, params_json)
    .done(function(dados) {
        dados = dados.replace(new RegExp('__ageunet_carrega_html', 'g'), '__ageunet_carrega_html2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('__ageunet_carrega_pagina', 'g'), '__ageunet_carrega_pagina2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('__ageunet_dados_post', 'g'), '__ageunet_dados_post2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('TWindow', 'g'),             'GJanela'); // quando submeto botão de busca, é destruído tudo que tem GJanela e recarregado
        dados = dados.replace(new RegExp('generator="ageunet"', 'g'), 'generator="ageunet2"'); // links também são alterados
        $('#ageunet_conteudo_online2').after('<div></div>').html(dados);
    }).fail(function(jqxhr, textoStatus, exception) {
       __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    });*/
    fetch(uri, params_json)
    .then(function(dados) {
        dados = dados.replace(new RegExp('__ageunet_carrega_html', 'g'), '__ageunet_carrega_html2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('__ageunet_carrega_pagina', 'g'), '__ageunet_carrega_pagina2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('__ageunet_dados_post', 'g'), '__ageunet_dados_post2'); // se tem um botão de buscar, ele está conectado a __ageunet_carrega_html
        dados = dados.replace(new RegExp('TWindow', 'g'),             'GJanela'); // quando submeto botão de busca, é destruído tudo que tem GJanela e recarregado
        dados = dados.replace(new RegExp('generator="ageunet"', 'g'), 'generator="ageunet2"'); // links também são alterados
        // Quando era jQuery era assim: after('<div></div>')
        $('#ageunet_conteudo_online2').insereApos(document.createElement('div')).innerHTML = dados;
    }).catch(function(jqxhr, textoStatus, exception) {
       __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Open a pagina using ajax
 */
function __ageunet_carrega_pagina(pagina, callback)
{
    if (typeof pagina !== 'undefined')
    {
        // $( '.modal-backdrop' ).remove(); // Código jQuery, logo abaixo ...
        //
        // JavaScript Puro (ou JS Vanilla)
        Array.from(document.querySelectorAll('.modal-backdrop')).forEach(function(botao) {
            botao.remove();
        });
        let url = pagina;
        url = url.replace('inicio.php', 'motor.php');
        
        if(url.indexOf('motor.php') == -1) {
            url = 'xhr-'+url;
        }
        
        __ageunet_roda_antes_carregamentos(url);
        
        if (url.indexOf('&estatico=1') > 0)
        {
            fetch(url)
            .then(function(dados) {
                __ageunet_analisa_html(dados);
                
                Ageunet.solicitaURL  = url;
                Ageunet.solicitaDados = null;
                
                if (typeof callback == "function")
                {
                    callback();
                }
                
                __ageunet_roda_apos_carregamentos(url, dados);
             
            // .fail(function(jqxhr, textoStatus, exception) {
            }).catch(function(textoStatus) {
               __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
               carregando = false;
            });
        }
        else
        {
            fatch(url)
            .then(function(dados) {
                Ageunet.solicitaURL  = url;
                Ageunet.solicitaDados = null;
                
                __ageunet_carrega_html(dados, __ageunet_roda_apos_carregamentos, url);
                
                if (typeof callback == "function")
                {
                    callback();
                }
                
                if ( url.indexOf('registra_estado=false') < 0 && historico.pushState && (dados.indexOf('widget="TWindow"') < 0) )
                {
                    __ageunet_registra_estado(url, 'ageunet');
                    Ageunet.currentURL = url;
                }
            }).catch(function(textoStatus) {
               __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
               carregando = false;
            });
        }
    }
}

/**
 * Used by all links inside a window (generator=ageunet)
 */
function __ageunet_carrega_pagina2(pagina)
{
    url = pagina;
    url = url.replace('inicio.php', 'motor.php');
    __ageunet_carrega_pagina_no_register2(url);
    
    Ageunet.solicitaURL  = url;
    Ageunet.solicitaDados = null;
}

/**
 * Start blockUI dialog
 */
function __ageunet_bloqueia_ui(mensagem_aguarde)
{
    if (typeof $.blockUI == 'function')
    {
        if (typeof Ageunet.bloqueiaContadorUI == 'undefined')
        {
            Ageunet.bloqueiaContadorUI = 0;
        }
        Ageunet.bloqueiaContadorUI = Ageunet.bloqueiaContadorUI + 1;
        if (typeof mensagem_aguarde == 'undefined')
        {
            mensagem_aguarde = Ageunet.waitMessage;
        }
        
        $.blockUI({ 
           message: '<h1><i class="fa fa-spinner fa-pulse"></i> '+mensagem_aguarde+'</h1>',
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
function __ageunet_janela(titulo, largura, altura, conteudo)
{
    $('<div />').html(conteudo).dialog({
        modal: true,
        title: titulo,
        width : largura,
        height : altura,
        resizable: true,
        closeOnEscape:true,
        close: function(ev, ui) { $(this).remove(); },
        focus:true
    });
}

function __ageunet_janela_pagina(titulo, largura, altura, pagina)
{
    if (width<2)
    {
        largura = $(window).width() * largura;
    }
    if (height<2)
    {
        altura = $(window).height() * altura;
    }
    
    $('<div />').append($("<iframe style='width:100%;height:97%' />").attr("src", pagina)).dialog({
        modal: true,
        title: titulo,
        width : largura,
        height : altura,
        resizable: false,
        closeOnEscape:true,
        close: function(ev, ui) { $(this).remove(); },
        focus:true
    });
}

/**
 * Show standard dialog (###: adaptar para usar bootstrap 5)
 */
function __ageunet_dialogo( opcoes )
{
    if (opcoes.type == 'info') {
        let icone = (opcoes.icone ? opcoes.icone : 'fa fa-info-circle fa-4x blue');
    }
    else if (opcoes.type == 'warning') {
        let icone = (opcoes.icone ? opcoes.icone : 'fa fa-exclamation-triangle fa-4x orange');
    }
    else if (opcoes.type == 'erro') {
        let icone = (opcoes.icone ? opcoes.icone : 'fa fa-exclamation-circle fa-4x red');
    }
    
    if (typeof bootbox == 'object')
    {
        bootbox.dialog({
          title: opcoes.title,
          animate: false,
          backdrop: true,
          onEscape: function() {
            if (typeof opcoes.callback != 'undefined')
            { 
                opcoes.callback();
            }
          },
          message: '<div>'+
                    '<span class="'+icone+'" style="float:left"></span>'+
                    '<span style="margin-left:70px;display:block;max-height:500px">'+opcoes.message+'</span>'+
                    '</div>',
          buttons: {
            success: {
              label: "OK",
              className: "btn-default",
              callback: function() {
                if (typeof opcoes.callback != 'undefined')
                { 
                    opcoes.callback();
                }
              }
            }
          }
        });
    }
    else {
        // fallback mode
        alert(opcoes.message);
        if (typeof opcoes.callback != 'undefined') {
            opcoes.callback();
        }
    }
}

/**
 * Show message error dialog
 */
function __ageunet_erro(titulo, mensagem, callback)
{
    __ageunet_dialogo( { type: 'erro', title: titulo, message: mensagem, callback: callback} );
}

/**
 * Show message info dialog
 */
function __ageunet_mensagem(titulo, mensagem, callback)
{
    __ageunet_dialogo( { type: 'info', title: titulo, message: mensagem, callback: callback} );
}

/**
 * Show message warning dialog
 */
function __ageunet_aviso(titulo, mensagem, callback)
{
    __ageunet_dialogo( { type: 'warning', title: titulo, message: mensagem, callback: callback} );
}

/**
 * Show pergunta dialog
 */
function __ageunet_pergunta(titulo, mensagem, callback_sim, callback_nao, rotulo_sim, rotulo_nao)
{
    if (typeof bootbox == 'object')
    {
        bootbox.dialog({
          title: titulo,
          animate: false,
          message: '<div>'+
                    '<span class="fa fa-pergunta-circle fa-4x blue" style="float:left"></span>'+
                    '<span style="margin-left:70px;display:block;max-height:500px">'+mensagem+'</span>'+
                    '</div>',
          buttons: {
            yes: {
              label: rotulo_sim,
              className: "btn-default",
              callback: function() {
                if (typeof callback_sim != 'undefined') {
                    callback_yes();
                }
              }
            },
            no: {
              label: rotulo_nao,
              className: "btn-default",
              callback: function() {
                if (typeof callback_nao != 'undefined') {
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
        var r = confirm(mensagem);
        if (r == true) {
            if (typeof callback_sim != 'undefined') {
                callback_yes();
            }
        } else {
            if (typeof callback_nao != 'undefined') {
                callback_no();
            }
        }
    }
}

/**
 * Show input dialog
 */
function __ageunet_entrada(pergunta, callback)
{
    if (typeof bootbox == 'object')
    {
        bootbox.prompt(pergunta, function(resultado) {
          if (resultado !== null) {
            callback(resultado);
          }
        });
    }
    else
    {
        var resultado = prompt(pergunta, '');
        callback(resultado);
    }
}

function __ageunet_exibe_toast64(tipo, mensagem64, lugar, icone)
{
    __ageunet_exibe_toast(tipo, atob(mensagem64), lugar, icone)
}

function __ageunet_exibe_toast(tipo, mensagem, lugar, icone)
{
    var lugar = lugar.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
            if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
            return index == 0 ? match.toLowerCase() : match.toUpperCase();
          });
    
    var opcoes = {
        message: mensagem,
        position: lugar
    };
    
    if (type == 'show') {
        opcoes['progressBarColor'] = 'rgb(0, 255, 184)';
        opcoes['theme'] = 'dark';
    }
    
    if (typeof icone !== 'undefined') {
        opcoes['icone'] = 'fa ' + icone.replace(':', '-');
    }
    
    iziToast[type]( opcoes );
}

/**
 * Closes blockUI dialog
 */
function __ageunet_desbloqueia_ui()
{
    if (typeof $.blockUI == 'function')
    {
        Ageunet.bloqueiaContadorUI = Ageunet.bloqueiaContadorUI -1;
        if (Ageunet.bloqueiaContadorUI <= 0)
        {
            $.unblockUI( { fadeIn: 0, fadeOut: 0 } );
            Ageunet.bloqueiaContadorUI = 0;
        }
    }
}

/**
 * Post form dados
 */
function __ageunet_post_dados(form, acao)
{
    if (acao.substring(0,4) == 'xhr-')
    {
        url = acao;
    }
    else
    {
        url = 'inicio.php?'+acao;
        url = url.replace('inicio.php', 'motor.php');
    }
    
    if (document.querySelector('#'+form) instanceof Node)
    {
        if (!document.querySelector('#'+form).hasAttribute('novalidate') && document.querySelector('#'+form).checkValidity() == false)
        {
            document.querySelector('#'+form).reportValidity();
            return;
        }
    }
    
    __ageunet_bloqueia_ui();
    
    dados = $('#'+form).serialize();
    
    __ageunet_rodar_antes_posts(url);
    
    if (url.indexOf('&estatico=1') > 0 || (acao.substring(0,4) == 'xhr-'))
    {
        $.post(url, dados)
        .done(function(resultado) {
            __ageunet_analisa_html(resultado);
            __ageunet_desbloqueia_ui();
            
            Ageunet.solicitaURL  = url;
            Ageunet.solicitaDados = dados;
            
            __ageunet_rodar_apos_posts(url, dados);
            
        }).fail(function(jqxhr, textoStatus, exception) {
            __ageunet_desbloqueia_ui();
            __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
            carregando = false;
        });
    }
    else
    {
        $.post(url, dados)
        .done(function(resultado) {
            Ageunet.currentURL  = url;
            Ageunet.solicitaURL  = url;
            Ageunet.solicitaDados = dados;
            
            __ageunet_carrega_html(resultado, __ageunet_rodar_apos_posts, url);
            __ageunet_desbloqueia_ui();
            
        }).fail(function(jqxhr, textoStatus, exception) {
            __ageunet_desbloqueia_ui();
            __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
            carregando = false;
        });
    }
}

/**
 * Post form dados over window
 */
function __ageunet_dados_post2(form, url)
{
    url = 'inicio.php?'+url;
    url = url.replace('inicio.php', 'motor.php');
    dados = $('#'+form).serialize();
    
    $.post(url, dados)
    .done(function(resultado)
    {
        __ageunet_carrega_html2(resultado);
        __ageunet_desbloqueia_ui();
        
        Ageunet.solicitaURL  = url;
        Ageunet.solicitaDados = dados;
        
    }).fail(function(jqxhr, textoStatus, exception) {
        __ageunet_desbloqueia_ui();
        __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Register URL state
 */
function __ageunet_registra_estado(url, origem)
{
    if (Ageunet.registerState !== false || origem == 'user')
    {
        var estadoObj = { url: url };
        if (typeof historico.pushState != 'undefined') {
            historico.pushState(estadoObj, "", url.replace('motor.php', 'inicio.php').replace('xhr-', ''));
        }
    }
}

/**
 * Ajax lookup
 */
function __ageunet_pesquisa_ajax(acao, campo)
{
    var value = campo.value;
    __ageunet_ajax_exec(acao +'&key='+value+'&pesquisa_ajax=1', null);
}

/**
 * Execute an Ajax acao
 */
function __ageunet_exec_ajax(acao, callback, saida_automatica)
{
    var uri = 'motor.php?' + acao +'&estatico=1';
    var saida_automatica = (typeof saida_automatica === "undefined") ? true : saida_automatica;
    
    $.ajax({url: uri})
    .done(function( dados ) {
        if (saida_automatica) {
            __ageunet_analisa_html(dados, callback);
        }
        else {
            callback(dados);
        }
    }).fail(function(jqxhr, textoStatus, exception) {
       __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
    });
}

/**
 * Get remote conteudo
 */
function __ageunet_obt_pagina(acao, callback, dadospost)
{
    var uri = 'motor.php?' + acao +'&estatico=1';
    
    if (typeof dadospost !== "undefined") {
        if (typeof dadospost.static !== "undefined") {
            var uri = 'motor.php?' + acao +'&estatico='+dadospost.static;
        }
    }
    
    $.ajax({
      url: uri,
      dados: dadospost
      }).done(function( dados ) {
          return callback(dados);
      }).fail(function(jqxhr, textoStatus, exception) {
         __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
      });
}

function __ageunet_pesquisa_post(form, acao, campo, callback) {
    if (typeof campo == 'string') {
        field_obj = $('#'+campo);
    }
    else if (campo instanceof HTMLElement) {
        field_obj = $(campo);
    }
    
    var formdados = $('#'+form).serializeArray();
    formdados.push({name: '_valor_campo', value: field_obj.val()});
    
    var uri = 'motor.php?' + acao +'&estatico=1';
    formdados.push({name: '_id_campo',   value: field_obj.attr('id')});
    formdados.push({name: '_nome_campo', value: field_obj.attr('name')});
    formdados.push({name: '_nome_form',  value: form});
    formdados.push({name: '_campo_dados', value: $.param(field_obj.dados(), true)});
    formdados.push({name: 'key',         value: field_obj.val()}); // for BC
    formdados.push({name: 'pesquisa_ajax', value: 1});
    
    $.ajax({
      tipo: 'POST',
      url: uri,
      dados: formdados
      }).done(function( dados ) {
          __ageunet_analisa_html(dados, callback);
      }).fail(function(jqxhr, textoStatus, exception) {
         __ageunet_erro('Erro', textoStatus + ': ' + __ageunet_mensagem_falha());
      });
}

/**
 * Parse returning HTML
 */
function __ageunet_analisa_html(dados, callback)
{
    tmp = dados;
    tmp = new String(tmp.replace(/window\.opener\./g, ''));
    tmp = new String(tmp.replace(/window\.close\(\)\;/g, ''));
    tmp = new String(tmp.replace(/^\s+|\s+$/g,""));
    
    if ($('[widget="GJanela"]').length > 0)
    {
       // o código dinâmico gerado em ajax lookups (ex: seekbutton)
       // deve ser modificado se estiver dentro de window para pegar window2
       tmp = new String(tmp.replace(/TWindow/g, 'GJanela'));
    }
    
    try {
        // permite código estático também escolher o target
        var combina_recipiente = dados.match('ageunet_recipiente_alvo\\s?=\\s?"([0-z]*)"');
        
        if ( combina_recipiente !== null)
        {
            var recipiente_alvo = combina_recipiente[1];
            $('#'+recipiente_alvo).empty();
            $('#'+recipiente_alvo).html(tmp);
        }
        else
        {
            // target default
            $('#ageunet_conteudo_online').find('script').remove();
            $('#ageunet_conteudo_online').append(tmp);
        }
        
        if (callback && typeof(callback) === "function")
        {
            callback(dados);
        }
        
    } catch (e) {
        if (e instanceof Error) {
            $('<div />').html(e.message + ': ' + tmp).dialog({modal: true, title: 'Erro', width : '80%', height : 'auto', resizable: true, closeOnEscape:true, focus:true});
        }
    }
}

/**
 * Download a arquivo
 */
function __ageunet_baixar_arquivo(arquivo)
{
    extensao = arquivo.split('.').pop();
    larguraTela  = screen.width;
    alturaTela = screen.height;
    if (extensao !== 'html')
    {
        larguraTela /= 3;
        alturaTela /= 3;
    }
    
    window.open('baixar.php?arquivo='+arquivo, '_blank',
      'width='+larguraTela+
     ',height='+alturaTela+
     ',top=0,left=0,status=yes,scrollbars=yes,toolbar=yes,resizable=yes,maximized=yes,menubar=yes,location=yes');
}

/**
 * Open pagina in new tab
 */
function __ageunet_abre_pagina(pagina)
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
function __ageunet_processa_popover()
{
    var obt_localizacao = function (tip, elemento) {
        $elemento = $(elemento);

        var localizacoes_valida = [
            "auto",
            "top",
            "right",
            "bottom",
            "left",
        ];

        if (typeof $elemento.attr('popside') === "undefined" || localizacoes_valida.indexOf($elemento.attr('popside')) === -1) {
            return 'auto';
        } else {
            return $(elemento).attr("popside");
        }
    };
    
    var obt_conteudo = function (tip, $elemento) {
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
            __ageunet_obt_pagina($(this).attr('popaction'), function(dados) {
                var popover = inst.attr('dados-conteudo',dados).dados('bs.popover');
                popover.setContent();
                popover.show();
            }, {'static': '0'});
            return '<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>';
        }
    };
    
    var obt_titulo = function () {
        return $(this).attr("poptitle") || '';
    };
    
    var pop_template = '<div class="popover" role="tooltip" style="max-width:800px"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>';

    $('[popover="true"]:not([poptrigger]):not([processed="true"])').popover({
        placement: obt_localizacao,
        trigger: 'hover',
        container: 'body',
        template: pop_template,
        delay: { show: 10, hide: 10 },
        conteudo: obt_conteudo,
        html: true,
        title: obt_titulo,
        sanitizeFn : function(d) { return d },
    }).attr('processed', true);
    
    $('[popover="true"][poptrigger="click"]:not([processed="true"])').popover({
        placement: obt_localizacao,
        trigger: 'click',
        container: 'body',
        template: pop_template,
        delay: { show: 10, hide: 10 },
        conteudo: obt_conteudo,
        sanitizeFn : function(d) { return d },
        html: true,
        title: obt_titulo
    }).on('shown.bs.popover', function (e) {
        if (typeof $(this).attr('popaction') !== "undefined") {
            var inst = $(this);
            __ageunet_obt_pagina($(this).attr('popaction'), function(dados) {
                var popover = inst.attr('dados-conteudo',dados).dados('bs.popover');
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
 * Show popover nearby elemento
 */
function __ageunet_exibe_popover(elemento, titulo, mensagem, localizacao, opcoes_customizadas)
{
    var opcoes_padrado = {trigger:"manual", title:titulo, html: true, conteudo:mensagem, placement:localizacao, sanitizeFn : function(d) { return d }};
    var opcoes = opcoes_padrado;
    
    if (typeof opcoes_customizadas !== undefined)
    {
        var opcoes = Object.assign(opcoes_padrado, opcoes_customizadas);
    }
    if ($(elemento).length > 0 && $(elemento).css("visibility") == "visible") {
        $(elemento).popover(opcoes).popover("show");
    }
}

/**
 * Start actions
 */
$(function() {
    Ageunet.bloqueiaContadorUI = 0;
    
    if (typeof $().tooltip == 'function')
    {
        $(document.body).tooltip({
            selector: "[title]",
            placement: function (tip, elemento) {
                    $elemento = $(elemento);
                    
                    var localizacoes_valida = [
                        "auto",
                        "top",
                        "right",
                        "bottom",
                        "left",
                    ];
            
                    if (typeof $elemento.attr('titside') === "undefined" || localizacoes_valida.indexOf($elemento.attr('titside')) === -1) {
                        return 'auto';
                    }
                    else {
                        return $(elemento).attr("titside");
                    }
                },
            trigger: 'hover',
            cssClass: 'tooltip',
            container: 'body',
            conteudo: function () {
                return $(this).attr("title");
            },
            html: true
        });
    }
    
    if (typeof $().popover == 'function')
    {
        $( document ).on( "dialogopen", function(){
            __ageunet_processa_popover();
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
$(document).completaAjax(function ()
{
    if (typeof $().popover == 'function')
    {
        __ageunet_processa_popover();
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
 * Sobrescreve o carregador de pagina padrão
 */
$( document ).on( 'click', '[generator="ageunet"]', function()
{
   __ageunet_carrega_pagina($(this).attr('href'));
   return false;
});
/*
const d = document.querySelector(document);
d.addEventListener("click", function() {
    alert("Clicou!");
});*/

/**
 * Override the default pagina loader for new windows
 */
$( document ).on( 'click', '[generator="ageunet2"]', function()
{
   __ageunet_carrega_pagina2($(this).attr('href'));
   return false;
});

/**
 * Register pagina navigation
 */
window.onpopstate = function(stackstate)
{
    if (stackstate.state)
    {
        carrega_pagina_nao_registra(stackstate.state.url);
    }
};

$.fn.mycenter = function () {
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.outerHeight() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.outerWidth() ) / 2+$(window).scrollLeft() + "px");
    return this;
}