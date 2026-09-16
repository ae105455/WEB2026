<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AureaMed | Reporte detallado de ventas</title>
    <style>
        :root { --blue: #1d4ed8; --green: #059669; --ink: #172033; --line: #dbe3ef; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f5f7fb; color: var(--ink); font-family: Arial, sans-serif; }
        .report-card { width: min(100%, 1080px); padding: 28px; background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 14px 35px rgba(23, 32, 51, .08); }
        .report-brand { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; }
        .report-brand img { width: 82px; height: 58px; object-fit: cover; object-position: center; }
        h1 { margin: 0 0 5px; font-size: 21px; }
        .report-brand p { margin: 0; color: #64748b; font-size: 13px; }
        form { display: grid; gap: 14px; }
        .filter-bar { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr auto; gap: 12px; align-items: end; padding: 16px; border: 1px solid var(--line); border-radius: 12px; background: #fbfcfe; }
        .date-grid { display: contents; }
        label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 700; }
        input[type="date"] { width: 100%; height: 40px; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; }
        select { width: 100%; height: 40px; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font-size: 14px; }
        button { min-height: 40px; border: 0; border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; padding: 0 16px; }
        .filter-button { background: #b04449; }
        .clear-button { min-width: 40px; padding: 0 10px; color: #64748b; background: transparent; }
        .button-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        button[value="pdf"] { background: var(--blue); }
        button[value="xls"] { background: var(--green); }
        button:hover { filter: brightness(.94); }
        @media (max-width: 800px) { .filter-bar { grid-template-columns: 1fr 1fr; } .filter-bar .search-field { grid-column: 1 / -1; } }
        @media (max-width: 500px) { .filter-bar, .button-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="report-card">
        <header class="report-brand">
            <img src="public/assets/img/logo.jpg" alt="Logo AureaMed">
            <div>
                <h1>Reporte detallado de ventas</h1>
                <p>Sistema de control de ventas e inventario</p>
            </div>
        </header>
        <form action="index.php?action=generar" method="post">
            <div class="filter-bar">
                <div class="search-field">
                    <label for="buscar">Buscar</label>
                    <input type="search" id="buscar" name="buscar" placeholder="Producto o N° factura...">
                </div>
                <div>
                    <label for="metodo">Método</label>
                    <select id="metodo" name="metodo">
                        <option value="todos">Todos</option>
                    </select>
                </div>
                <div>
                    <label for="fecha_inicio">Fecha inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-01'); ?>" required>
                </div>
                <div>
                    <label for="fecha_fin">Fecha fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <button class="filter-button" type="submit" name="tipo" value="pdf">Filtrar</button>
                <button class="clear-button" type="reset" title="Limpiar filtros" aria-label="Limpiar filtros">&times;</button>
            </div>
            <div class="button-row">
                <button type="submit" name="tipo" value="pdf">Generar PDF</button>
                <button type="submit" name="tipo" value="xls">Exportar a XLS</button>
            </div>
        </form>
    </main>
</body>
</html>