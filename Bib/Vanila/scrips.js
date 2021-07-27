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
