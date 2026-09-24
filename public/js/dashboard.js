/* ============================================================
   Utilidades compartidas por los paneles internos (admin/cocina).
   Sustituye al patron original de <a href="...GET..."> + confirm
   por formularios POST reales (@csrf) con dialogo de confirmacion,
   para que las acciones que cambian datos no queden expuestas a
   un simple enlace GET.
   ============================================================ */

/**
 * Intercepta el submit de un boton dentro de un <form>, muestra un
 * dialogo de confirmacion y, si el usuario confirma, envia el formulario.
 * Uso: <button type="button" onclick="confirmarFormulario(event, this, '🗑️', 'Titulo', 'Texto')">
 */
let _mdSubmitForm = null;

function confirmarFormulario(e, btn, icon, title, text) {
    e.preventDefault();
    _mdSubmitForm = btn.closest('form');

    document.getElementById('mdIcon').textContent = icon;
    document.getElementById('mdTitle').textContent = title;
    document.getElementById('mdText').textContent = text;
    document.getElementById('mdOk').onclick = () => {
        mdClose();
        _mdSubmitForm.submit();
    };

    const ov = document.getElementById('mdOverlay');
    ov.style.display = 'flex';
    ov.onclick = (ev) => { if (ev.target === ov) mdClose(); };

    return false;
}

function mdClose() {
    const ov = document.getElementById('mdOverlay');
    if (ov) ov.style.display = 'none';
}

/**
 * Filtro de texto simple para tablas: oculta filas cuyo texto no
 * contenga la busqueda. Uso: <input onkeyup="filtrarTabla('miTabla', 'miInput')">
 */
function filtrarTabla(tablaId, inputId) {
    const q = document.getElementById(inputId).value.toLowerCase();
    document.querySelectorAll('#' + tablaId + ' tbody tr').forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

/** Abre/cierra un modal por id (los de "crear"/"editar"). */
function abrirModal(id) {
    document.getElementById(id).classList.add('open');
}

function cerrarModales() {
    document.querySelectorAll('.modal-overlay').forEach((m) => m.classList.remove('open'));
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.modal-overlay').forEach((overlay) => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) cerrarModales();
        });
    });
});
