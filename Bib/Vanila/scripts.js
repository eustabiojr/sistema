/**
 * Meus Scripts
 * 
 * Aqui se localiza alguns JavaScripts úteis, que pretendem substituir alguns recursos
 * da biblioteca jQuery por JavaScript puro, que não existam similares de forma totalmente
 * nativa.
 * 
 * Data: 27/07/2021
 */

/**
 * Método esvazia
 * @param {*} elemento 
 */
function esvazia(elemento) {
    let noh = document.querySelectorAll(elemento);
    for(let i=0; i < noh.length; i++) {
        noh[i].removeChild(noh[i].firstChild);
    }
}

/**
* Método obtScript (substituo do método getScript() do jQuery)
*/
function obtScript(origem, chamadevolta) {
    let script = document.createElement('script');
    script.async = 1;
    const existente = document.getElementsByTagName('script')[0];

    script.onload = function( _, ehAborta) {
        if (ehAborta || !script.readyState || /loaded|complete/.test(script.readyState)) {
            script.onload = null;
            script = undefined;

            if (!ehAborta && chamadevolta) {
                setTimeout(chamadevolta, 0);
            }
        }
    };

    script.type = "text/javascript";
    script.src  = origem;
    existente.parentNode.insertBefore(script, existente);
}

/** Aqui estamos inserindo ao objeto global a função abaixo para que ela
 * se torne disponível a todos os objetos.
 */
Object.prototype.insereApos = function(novoElemento, elementoReferencia) {
    //alert("Insere após");

    // pegamos o elemento pai do elemento de referencia. Em seguida inserimos o novo elemento
    // após o elemento de referencia por usar 'nextSibling'. Eu sei, esse 'insertBefore' para inserir
    // após é estranho. Mas é isso mesmo. Por outro lado, note o 'parentNode' e 'nextSibling'. Ele 
    // pega o nó pai do elemento de refeência como referência para 'insertBefore' e o 'nextSibling'
    // pega o irmão seguinte para inserir o novo elemento.
    return elementoReferencia.parentNode.insertBefore(novoElemento, elementoReferencia.nextSibling);
}
