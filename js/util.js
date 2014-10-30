/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function desactivar(elemento) {
    input = document.getElementById(elemento);
    input.disabled = true;
}

function validarNumero(campo, texto) {
    if (isNaN(campo.value)) {
        alert(texto);
        campo.focus();
    };
}

function validarCantidadCaracteres(campo, cantidad) {
    
};

