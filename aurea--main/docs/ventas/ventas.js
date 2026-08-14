// ================= DATOS DE AUDÍFONOS =================
const audifonosData = {
    'ric-negro': { nombre: 'RIC Negro', precio: '$1,899' },
    'ric-plomo': { nombre: 'RIC Plomo', precio: '$1,899' },
    'bte-beige': { nombre: 'BTE Beige', precio: '$2,299' },
    'bte-marron': { nombre: 'BTE Marrón', precio: '$2,299' },
    'd10-granito': { nombre: 'D10 Granito', precio: '$1,699' },
    'd10-marron': { nombre: 'D10 Marrón', precio: '$1,699' },
    'quixg4': { nombre: 'QUIX G4 Pro', precio: '$2,599' },
    'ileag5': { nombre: 'ILEA G5 Plus', precio: '$2,999' },
    'vega': { nombre: 'VEGA Power', precio: '$2,199' }
};

// ================= VER DETALLE =================
function verDetalle(tipo) {
    const data = audifonosData[tipo];
    if (!data) return;
    
    const mensaje = `📢 ${data.nombre}\n💰 Precio: ${data.precio}\n\n¿Deseas más información?`;
    
    if (confirm(mensaje)) {
        window.open(`https://wa.me/59177534284?text=Hola,%20quiero%20información%20sobre%20${data.nombre}`, '_blank');
    }
}

// ================= SCROLL TOP =================
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btntop');
    if (!btn) return;
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
        }
    });
    
    btn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});