<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de {{ ucfirst($tabla) }}</title>
    <style>
    body { font-family: Arial, sans-serif; font-size: 10px; }
    h1 { color: #2c3e50; text-align: center; margin-bottom: 15px; }
    .header-info { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    .header-info p { margin: 2px 0; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background-color: #f8f9fa; text-align: left; padding: 6px; border: 1px solid #dee2e6; }
    td { padding: 6px; border: 1px solid #dee2e6; }
    .total-row { font-weight: bold; background-color: #f8f9fa; }
    .total-row td { border-top: 2px solid #333; }
</style>
    </style>
</head>
<body>
    <h1>Reporte de {{ ucfirst($tabla) }}</h1>
    
    <div class="header-info">
        <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Categoría:</strong> {{ $nombreCategoria }}</p>
        <p><strong>Período:</strong> {{ $fecha_inicio }} - {{ $fecha_fin }}</p>
    </div>

    @include('panel.pdf.'.$tabla)
</body>
</html>