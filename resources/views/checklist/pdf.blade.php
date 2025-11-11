<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checklist NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section h3 { background: #f0f0f0; padding: 8px; margin: 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td { border: 1px solid #ddd; padding: 6px; }
        .yes { background: #d4edda; color: #155724; }
        .no { background: #f8d7da; color: #721c24; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHECKLIST DE INSTALACIÓN</h1>
        <h2>NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}</h2>
        <p>Cliente: {{ $asignacion->notaVenta->nv_cliente ?? 'N/A' }} | Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

   

    <div class="footer">
        Generado automáticamente por el sistema de instaladores | {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>