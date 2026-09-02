{{-- Modal de confirmacion generico usado por dashboard.js (confirmarFormulario). --}}
<div id="mdOverlay" style="display:none;position:fixed;inset:0;z-index:400;background:rgba(0,0,0,.75);backdrop-filter:blur(4px);align-items:center;justify-content:center">
    <div style="background:#1c1a18;border:1px solid #2e2b27;border-radius:16px;padding:32px 28px;width:100%;max-width:360px;text-align:center">
        <div id="mdIcon" style="font-size:38px;margin-bottom:12px"></div>
        <div id="mdTitle" style="font-family:'Bebas Neue',sans-serif;font-size:22px;margin-bottom:8px;color:#f0ece6"></div>
        <div id="mdText" style="font-size:14px;color:#8a8078;margin-bottom:24px;line-height:1.6"></div>
        <div style="display:flex;gap:10px;justify-content:center">
            <button type="button" onclick="mdClose()" style="background:none;border:1px solid #2e2b27;border-radius:8px;padding:10px 24px;font-size:14px;font-family:'Barlow',sans-serif;color:#8a8078;cursor:pointer">Cancelar</button>
            <button type="button" id="mdOk" style="background:#f07000;border:none;border-radius:8px;padding:10px 24px;font-size:14px;font-family:'Barlow',sans-serif;font-weight:700;color:#fff;cursor:pointer">Confirmar</button>
        </div>
    </div>
</div>
