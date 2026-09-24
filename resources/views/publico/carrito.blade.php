<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Parrilla &ndash; Carrito</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
</head>
<body>

<nav>
    <a class="nav-brand" href="{{ route('home') }}">
        <svg width="18" height="24" viewBox="0 0 32 42" fill="none"><path d="M16 0C16 0 28 10 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 16 8 12 8 12C8 12 8 18 12 20C12 20 10 14 16 8C16 8 14 16 18 20C20 18 22 14 20 8C24 12 28 18 24 26C24 26 27 23 26 19C27.5 21 28 23 28 22C28 29.732 22.627 36 16 36C9.373 36 4 29.732 4 22C4 10 16 0 16 0Z" fill="#f07000"/></svg>
        <div>
            <div class="nav-brand-name">La Parrilla</div>
            <div class="nav-brand-sub">Asadero &amp; Restaurante</div>
        </div>
    </a>
    <div style="display:flex;align-items:center;gap:12px">
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--muted)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span class="cart-count" id="navCount">0</span>
        </div>
        <a href="{{ route('home') }}" class="nav-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Volver al men&uacute;
        </a>
    </div>
</nav>

<div class="page">

    <div>
        <div class="cart-panel" id="cartPanel">
            <div class="cart-header">
                <h2>Tu Pedido</h2>
                <button type="button" class="btn-vaciar" onclick="vaciarCarrito()">Vaciar carrito</button>
            </div>
            <div class="cart-items" id="cartItems"></div>
            <div class="cart-totals" id="cartTotals" style="display:none">
                <div class="total-row"><span>Subtotal</span><span id="subtotalVal">$0</span></div>
                <div class="total-row final"><span>Total</span><span id="totalVal">$0</span></div>
            </div>
        </div>
    </div>

    <div class="checkout-panel" id="checkoutPanel" style="display:none">
        <div class="checkout-card">
            <h3>Detalles del Pedido</h3>

            <div class="field">
                <label>Tipo de pedido</label>
                <div class="tipo-grid">
                    <button type="button" class="tipo-btn active" id="btnMesa" onclick="setTipo('mesa')">🍽️ En Mesa</button>
                    <button type="button" class="tipo-btn" id="btnLlevar" onclick="setTipo('llevar')">🥡 Para Llevar</button>
                </div>
            </div>

            <div class="field" id="campoMesa">
                <label>N&uacute;mero de mesa</label>
                <select id="numeroMesa">
                    <option value="">Selecciona tu mesa...</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">Mesa {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="field" id="campoDireccion" style="display:none">
                <label>Direcci&oacute;n de entrega</label>
                <input type="text" id="direccionEntrega" placeholder="Ej: Calle 123 #45-67, Barrio Centro">
            </div>

            <div class="field">
                <label>Nombre completo</label>
                <input type="text" id="nombreCliente" placeholder="Tu nombre" value="{{ $nombreCliente ?? '' }}">
            </div>

            <div class="field">
                <label>Nota especial <span style="color:var(--muted)">(opcional)</span></label>
                <textarea id="notaEspecial" placeholder="Sin cebolla, extra salsa, término de cocción..." rows="2"></textarea>
            </div>

            <div class="field">
                <label>M&eacute;todo de pago</label>
                <div class="metodo-list">
                    <div class="metodo-item active" onclick="setMetodo(this,'Efectivo')">
                        <span class="metodo-icon">💵</span>
                        <input type="radio" name="metodo" value="Efectivo" checked>
                        <label>Efectivo</label>
                    </div>
                    <div class="metodo-item" onclick="setMetodo(this,'Tarjeta debito')">
                        <span class="metodo-icon">💳</span>
                        <input type="radio" name="metodo" value="Tarjeta debito">
                        <label>Tarjeta d&eacute;bito</label>
                    </div>
                    <div class="metodo-item" onclick="setMetodo(this,'Tarjeta credito')">
                        <span class="metodo-icon">💳</span>
                        <input type="radio" name="metodo" value="Tarjeta credito">
                        <label>Tarjeta cr&eacute;dito</label>
                    </div>
                    <div class="metodo-item" onclick="setMetodo(this,'Billetera digital')">
                        <span class="metodo-icon">📱</span>
                        <input type="radio" name="metodo" value="Billetera digital">
                        <label>Billetera digital (Nequi / Daviplata)</label>
                    </div>
                </div>
            </div>

            <div class="resumen-mini" id="resumenMini"></div>

            <button type="button" class="btn-finalizar" id="btnFinalizar" onclick="finalizarPedido()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Finalizar Pedido
            </button>
        </div>
    </div>

</div>

<div class="modal-overlay" id="modalCustom">
    <div class="modal-box">
        <div class="modal-icon" id="modalIcon">⚠️</div>
        <div class="modal-title" id="modalTitle">Aviso</div>
        <div class="modal-text" id="modalText"></div>
        <div class="modal-btns" id="modalBtns">
            <button type="button" class="modal-btn-ok" onclick="cerrarModal()">Aceptar</button>
        </div>
    </div>
</div>

<script>
let _confirmCallback = null;

function mostrarAlerta(icon, titulo, texto) {
    document.getElementById('modalIcon').textContent  = icon;
    document.getElementById('modalTitle').textContent = titulo;
    document.getElementById('modalText').textContent  = texto;
    document.getElementById('modalBtns').innerHTML =
        '<button type="button" class="modal-btn-ok" onclick="cerrarModal()">Aceptar</button>';
    _confirmCallback = null;
    document.getElementById('modalCustom').classList.add('open');
}

function mostrarConfirm(titulo, texto, callback) {
    document.getElementById('modalIcon').textContent  = '🗑️';
    document.getElementById('modalTitle').textContent = titulo;
    document.getElementById('modalText').textContent  = texto;
    document.getElementById('modalBtns').innerHTML =
        '<button type="button" class="modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>' +
        '<button type="button" class="modal-btn-ok" onclick="confirmarAccion()">Confirmar</button>';
    _confirmCallback = callback;
    document.getElementById('modalCustom').classList.add('open');
}

function confirmarAccion() {
    cerrarModal();
    if (_confirmCallback) _confirmCallback();
}

function cerrarModal() {
    document.getElementById('modalCustom').classList.remove('open');
}

document.getElementById('modalCustom').addEventListener('click', function (e) {
    if (e.target === this) cerrarModal();
});
</script>

<script>
const CART_KEY = 'laparrilla_cart';
let tipoSeleccionado = 'mesa';
let metodoSeleccionado = 'Efectivo';

function getCart() { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
function saveCart(c) { localStorage.setItem(CART_KEY, JSON.stringify(c)); renderCart(); }

function parsePrecio(str) {
    if (typeof str === 'number') return str;
    return parseInt(str.replace(/[^0-9]/g, '')) || 0;
}

function formatPrecio(n) {
    return '$ ' + n.toLocaleString('es-CO');
}

function renderCart() {
    const cart = getCart();
    const itemsEl = document.getElementById('cartItems');
    const totalsEl = document.getElementById('cartTotals');
    const checkoutEl = document.getElementById('checkoutPanel');
    const navCount = document.getElementById('navCount');

    const totalQty = cart.reduce((s, i) => s + i.qty, 0);
    navCount.textContent = totalQty;

    if (cart.length === 0) {
        itemsEl.innerHTML = `
            <div class="cart-empty">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <p>Tu carrito está vacío</p>
                <a href="{{ route('home') }}#menu" class="btn-ir-menu">Ver Menú →</a>
            </div>`;
        totalsEl.style.display = 'none';
        checkoutEl.style.display = 'none';
        return;
    }

    let subtotal = 0;
    let html = '';
    cart.forEach((item, idx) => {
        const precio = parsePrecio(item.precio);
        const sub = precio * item.qty;
        subtotal += sub;
        html += `
        <div class="cart-item">
            <img class="item-img" src="${item.img}" alt="${item.nombre}" onerror="this.src='https://via.placeholder.com/72x72/1c1a18/f07000?text=🍗'">
            <div class="item-info">
                <div class="item-name">${item.nombre}</div>
                <div class="item-price">${formatPrecio(precio)}</div>
            </div>
            <div class="item-controls">
                <button type="button" class="qty-btn" onclick="cambiarQty(${idx},-1)">−</button>
                <div class="qty-num">${item.qty}</div>
                <button type="button" class="qty-btn" onclick="cambiarQty(${idx},1)">+</button>
                <button type="button" class="btn-remove" onclick="eliminarItem(${idx})" title="Eliminar">✕</button>
            </div>
        </div>`;
    });

    itemsEl.innerHTML = html;
    totalsEl.style.display = 'block';
    checkoutEl.style.display = 'flex';

    document.getElementById('subtotalVal').textContent = formatPrecio(subtotal);
    document.getElementById('totalVal').textContent = formatPrecio(subtotal);

    let miniHtml = '';
    cart.forEach(item => {
        const precio = parsePrecio(item.precio);
        miniHtml += `
        <div class="resumen-mini-item">
            <img class="resumen-mini-img" src="${item.img}" alt="${item.nombre}" onerror="this.src='https://via.placeholder.com/44x44/1c1a18/f07000?text=🍗'">
            <div class="resumen-mini-name">${item.nombre}</div>
            <div class="resumen-mini-qty">x${item.qty}</div>
            <div class="resumen-mini-price">${formatPrecio(precio * item.qty)}</div>
        </div>`;
    });
    miniHtml += `<hr class="resumen-divider">
        <div class="resumen-total-row"><span>Subtotal</span><span>${formatPrecio(subtotal)}</span></div>
        <div class="resumen-total-row bold"><span>Total</span><span>${formatPrecio(subtotal)}</span></div>`;
    document.getElementById('resumenMini').innerHTML = miniHtml;
}

function cambiarQty(idx, delta) {
    const cart = getCart();
    cart[idx].qty += delta;
    if (cart[idx].qty <= 0) cart.splice(idx, 1);
    saveCart(cart);
}

function eliminarItem(idx) {
    const cart = getCart();
    cart.splice(idx, 1);
    saveCart(cart);
}

function vaciarCarrito() {
    mostrarConfirm('¿Vaciar el carrito?', 'Se eliminarán todos los productos agregados.', () => {
        localStorage.removeItem(CART_KEY); renderCart();
    });
}

function setTipo(tipo) {
    tipoSeleccionado = tipo;
    document.getElementById('btnMesa').classList.toggle('active', tipo === 'mesa');
    document.getElementById('btnLlevar').classList.toggle('active', tipo === 'llevar');
    document.getElementById('campoMesa').style.display = tipo === 'mesa' ? 'block' : 'none';
    document.getElementById('campoDireccion').style.display = tipo === 'llevar' ? 'block' : 'none';
}

function setMetodo(el, metodo) {
    metodoSeleccionado = metodo;
    document.querySelectorAll('.metodo-item').forEach(m => m.classList.remove('active'));
    el.classList.add('active');
    el.querySelector('input[type=radio]').checked = true;
}

function finalizarPedido() {
    const cart = getCart();
    const nombre = document.getElementById('nombreCliente').value.trim();
    const nota = document.getElementById('notaEspecial').value.trim();
    const mesa = document.getElementById('numeroMesa')?.value;
    const direccion = document.getElementById('direccionEntrega')?.value.trim();

    if (!nombre) { mostrarAlerta('⚠️', 'Campo requerido', 'Por favor ingresa tu nombre completo.'); return; }
    if (tipoSeleccionado === 'mesa' && !mesa) { mostrarAlerta('🪑', 'Selecciona tu mesa', 'Por favor selecciona el número de mesa donde estás sentado.'); return; }
    if (tipoSeleccionado === 'llevar' && !direccion) { mostrarAlerta('📍', 'Dirección requerida', 'Por favor ingresa la dirección de entrega.'); return; }
    if (cart.length === 0) { mostrarAlerta('🛒', 'Carrito vacío', 'Agrega al menos un producto antes de finalizar el pedido.'); return; }

    const btn = document.getElementById('btnFinalizar');
    btn.disabled = true;
    btn.textContent = 'Procesando...';

    const datos = {
        cart,
        tipo: tipoSeleccionado === 'mesa' ? 'En mesa' : 'Para llevar',
        mesa_numero: mesa || null,
        direccion_entrega: direccion || null,
        nombre_cliente: nombre,
        nota_especial: nota,
        metodo_pago: metodoSeleccionado
    };

    fetch('{{ route('carrito.procesar') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            localStorage.removeItem(CART_KEY);
            window.location.href = '{{ route('confirmacion') }}?orden=' + res.numero_orden;
        } else {
            mostrarAlerta('❌', 'Error al procesar', res.message || 'No se pudo procesar el pedido. Intenta de nuevo.');
            btn.disabled = false;
            btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Finalizar Pedido';
        }
    })
    .catch(() => {
        mostrarAlerta('📡', 'Error de conexión', 'No se pudo conectar con el servidor. Verifica tu conexión e intenta de nuevo.');
        btn.disabled = false;
        btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Finalizar Pedido';
    });
}

renderCart();
</script>
</body>
</html>
