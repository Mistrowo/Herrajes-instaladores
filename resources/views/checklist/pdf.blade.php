<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist - NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #6b7280;
        }
        
        .info-box {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2563eb;
        }
        
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-box td {
            padding: 5px;
            font-size: 10px;
        }
        
        .info-box td:first-child {
            font-weight: bold;
            color: #4b5563;
            width: 150px;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #2563eb;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 12px;
        }
        
        .items-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .item-row {
            display: table-row;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .item-label {
            display: table-cell;
            padding: 8px;
            font-weight: 500;
            color: #374151;
            width: 60%;
        }
        
        .item-value {
            display: table-cell;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            width: 20%;
        }
        
        .value-si {
            color: #059669;
            background: #d1fae5;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .value-no {
            color: #dc2626;
            background: #fee2e2;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .value-empty {
            color: #9ca3af;
        }
        
        .observaciones {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            padding: 12px;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .observaciones-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
        }
        
        .observaciones-text {
            color: #78350f;
            white-space: pre-wrap;
        }
        
        .error-badge {
            background: #fecaca;
            color: #991b1b;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
        
        .signatures {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 10px;
        }
        
        .signature-line {
            border-top: 2px solid #374151;
            margin-top: 40px;
            padding-top: 8px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    
    <!-- HEADER -->
    <div class="header">
        <h1>CHECKLIST DE INSTALACION</h1>
        <p>Sistema Herrajes - Ohffice</p>
    </div>
    
    <!-- INFORMACIÓN GENERAL -->
    <div class="info-box">
        <table>
            <tr>
                <td><strong>Nota de Venta:</strong></td>
                <td>NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}</td>
                <td><strong>Fecha Completado:</strong></td>
                <td>
                    @if($checklist->fecha_completado)
                        {{ \Carbon\Carbon::parse($checklist->fecha_completado)->format('d/m/Y H:i') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Instalador:</strong></td>
                <td>{{ $checklist->instalador->nombre ?? 'N/A' }}</td>
                <td><strong>Telefono:</strong></td>
                <td>{{ $checklist->telefono ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Autorizado por:</strong></td>
                <td colspan="3">{{ $checklist->mod_autorizadas_por ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
    
    @php
        // Helper function para mostrar SI/NO
        $renderValue = function($value) {
            if ($value === 'SI') {
                return '<span class="value-si">SI</span>';
            } elseif ($value === 'NO') {
                return '<span class="value-no">NO</span>';
            } else {
                return '<span class="value-empty">-</span>';
            }
        };
    @endphp
    
    <!-- SECCIÓN 1: NÚMERO PROYECTO/PEDIDO -->
    <div class="section">
        <div class="section-title">NUMERO PROYECTO/PEDIDO</div>
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Rectificacion Medidas</div>
                <div class="item-value">{!! $renderValue($checklist->rectificacion_medidas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Planos Actualizados</div>
                <div class="item-value">{!! $renderValue($checklist->planos_actualizados) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Planos Muebles Especiales</div>
                <div class="item-value">{!! $renderValue($checklist->planos_muebles_especiales) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Modificaciones Realizadas</div>
                <div class="item-value">{!! $renderValue($checklist->modificaciones_realizadas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Despacho Integral</div>
                <div class="item-value">{!! $renderValue($checklist->despacho_integral) !!}</div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 2: ERRORES PROYECTO -->
    <div class="section">
        <div class="section-title">ERRORES PROYECTO</div>
        @if($checklist->hasAnyErrors())
            <div class="error-badge">Se encontraron {{ $checklist->countErrors() }} error(es)</div>
        @endif
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Errores en Ventas</div>
                <div class="item-value">{!! $renderValue($checklist->errores_ventas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Diseno</div>
                <div class="item-value">{!! $renderValue($checklist->errores_diseno) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Rectificacion</div>
                <div class="item-value">{!! $renderValue($checklist->errores_rectificacion) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Produccion</div>
                <div class="item-value">{!! $renderValue($checklist->errores_produccion) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Proveedor</div>
                <div class="item-value">{!! $renderValue($checklist->errores_proveedor) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Despacho</div>
                <div class="item-value">{!! $renderValue($checklist->errores_despacho) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Instalacion</div>
                <div class="item-value">{!! $renderValue($checklist->errores_instalacion) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Otros Errores</div>
                <div class="item-value">{!! $renderValue($checklist->errores_otro) !!}</div>
            </div>
        </div>
        
        @if($checklist->observaciones)
        <div class="observaciones">
            <div class="observaciones-title">Observaciones:</div>
            <div class="observaciones-text">{{ $checklist->observaciones }}</div>
        </div>
        @endif
    </div>
    
    <div class="page-break"></div>
    
    <!-- SECCIÓN 3: ESTADO OBRA -->
    <div class="section">
        <div class="section-title">ESTADO OBRA AL MOMENTO DE LA INSTALACION</div>
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Instalacion de Cielo</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_cielo) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Instalacion de Piso</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_piso) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Remate Muros</div>
                <div class="item-value">{!! $renderValue($checklist->remate_muros) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Nivelacion Piso</div>
                <div class="item-value">{!! $renderValue($checklist->nivelacion_piso) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Muros a Plomo</div>
                <div class="item-value">{!! $renderValue($checklist->muros_plomo) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Instalacion Electrica</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_electrica) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Instalacion Voz y Dato</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_voz_dato) !!}</div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 4: INSPECCIÓN FINAL -->
    <div class="section">
        <div class="section-title">INSPECCION FINAL</div>
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Paneles Alineados</div>
                <div class="item-value">{!! $renderValue($checklist->paneles_alineados) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Nivelacion Cubiertas</div>
                <div class="item-value">{!! $renderValue($checklist->nivelacion_cubiertas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Pasacables Instalados</div>
                <div class="item-value">{!! $renderValue($checklist->pasacables_instalados) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Limpieza Cubiertas</div>
                <div class="item-value">{!! $renderValue($checklist->limpieza_cubiertas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Limpieza Cajones</div>
                <div class="item-value">{!! $renderValue($checklist->limpieza_cajones) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Limpieza Piso</div>
                <div class="item-value">{!! $renderValue($checklist->limpieza_piso) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Llaves Instaladas</div>
                <div class="item-value">{!! $renderValue($checklist->llaves_instaladas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Funcionamiento Mueble</div>
                <div class="item-value">{!! $renderValue($checklist->funcionamiento_mueble) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Puntos Electricos</div>
                <div class="item-value">{!! $renderValue($checklist->puntos_electricos) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Sillas Ubicadas</div>
                <div class="item-value">{!! $renderValue($checklist->sillas_ubicadas) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Accesorios</div>
                <div class="item-value">{!! $renderValue($checklist->accesorios) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Check Herramientas</div>
                <div class="item-value">{!! $renderValue($checklist->check_herramientas) !!}</div>
            </div>
        </div>
    </div>
    
    <!-- FIRMAS -->
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">Instalador</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Supervisor</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Cliente</div>
        </div>
    </div>
    
    <!-- FOOTER -->
    <div class="footer">
        <p>Documento generado el {{ date('d/m/Y H:i') }}</p>
        <p>Sistema Herrajes - Ilesa &copy; {{ date('Y') }}</p>
    </div>
    
</body>
</html>