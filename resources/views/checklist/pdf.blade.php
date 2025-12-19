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
        
        .item-label,
        .item-value,
        .item-obs {
            display: table-cell;
            padding: 6px 8px;
            vertical-align: top;
        }
        
        .item-label {
            font-weight: 500;
            color: #374151;
            width: 40%;
        }
        
        .item-value {
            text-align: center;
            font-weight: bold;
            width: 15%;
        }

        .item-obs {
            width: 45%;
            font-size: 9px;
            color: #4b5563;
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

        .obs-text {
            white-space: pre-wrap;
        }

        .obs-empty {
            color: #d1d5db;
            font-style: italic;
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
            font-size: 10px;
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
            margin-bottom: 10px;
        }

        .sucursal-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
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
                <td><strong>Teléfono:</strong></td>
                <td>{{ $checklist->telefono ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Sucursal:</strong></td>
                <td colspan="3">
                    @if($checklist->sucursal)
                        {{ $checklist->sucursal->nombre }} - {{ $checklist->sucursal->comuna }}
                    @else
                        Sin sucursal asignada
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Autorizado por:</strong></td>
                <td colspan="3">{{ $checklist->mod_autorizadas_por ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    {{-- Badge de Sucursal --}}
    @if($checklist->sucursal)
        <div class="sucursal-badge">
            📍 Instalación en: {{ $checklist->sucursal->nombre }}
        </div>
    @endif

    {{-- Observaciones de cabecera --}}
    @if($checklist->mod_autorizadas_por_obs || $checklist->telefono_obs)
        <div class="observaciones">
            <div class="observaciones-title">Observaciones generales:</div>
            <div class="observaciones-text">
                @if($checklist->mod_autorizadas_por_obs)
                    <strong>Autorizado por:</strong> {{ $checklist->mod_autorizadas_por_obs }}@if($checklist->telefono_obs)<br>@endif
                @endif
                @if($checklist->telefono_obs)
                    <strong>Teléfono:</strong> {{ $checklist->telefono_obs }}
                @endif
            </div>
        </div>
    @endif
    
    @php
        $renderValue = function($value) {
            if ($value === 'SI') {
                return '<span class="value-si">SI</span>';
            } elseif ($value === 'NO') {
                return '<span class="value-no">NO</span>';
            } else {
                return '<span class="value-empty">-</span>';
            }
        };

        $renderObs = function($text) {
            if (!$text) {
                return '<span class="obs-empty">-</span>';
            }
            return '<span class="obs-text">'.nl2br(e($text)).'</span>';
        };
    @endphp
    
    <!-- SECCIÓN 1: NÚMERO PROYECTO/PEDIDO -->
    <div class="section">
        <div class="section-title">NÚMERO PROYECTO/PEDIDO</div>
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Rectificación Medidas</div>
                <div class="item-value">{!! $renderValue($checklist->rectificacion_medidas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->rectificacion_medidas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Planos Actualizados</div>
                <div class="item-value">{!! $renderValue($checklist->planos_actualizados) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->planos_actualizados_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Planos Muebles Especiales</div>
                <div class="item-value">{!! $renderValue($checklist->planos_muebles_especiales) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->planos_muebles_especiales_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Modificaciones Realizadas</div>
                <div class="item-value">{!! $renderValue($checklist->modificaciones_realizadas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->modificaciones_realizadas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Despacho Integral</div>
                <div class="item-value">{!! $renderValue($checklist->despacho_integral) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->despacho_integral_obs) !!}</div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 2: ERRORES PROYECTO -->
    <div class="section">
        <div class="section-title">ERRORES PROYECTO</div>
        @if($checklist->hasAnyErrors())
            <div class="error-badge">⚠️ Se encontraron {{ $checklist->countErrors() }} error(es)</div>
        @endif
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Errores en Ventas</div>
                <div class="item-value">{!! $renderValue($checklist->errores_ventas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_ventas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Diseño</div>
                <div class="item-value">{!! $renderValue($checklist->errores_diseno) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_diseno_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Rectificación</div>
                <div class="item-value">{!! $renderValue($checklist->errores_rectificacion) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_rectificacion_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Producción</div>
                <div class="item-value">{!! $renderValue($checklist->errores_produccion) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_produccion_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Proveedor</div>
                <div class="item-value">{!! $renderValue($checklist->errores_proveedor) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_proveedor_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Despacho</div>
                <div class="item-value">{!! $renderValue($checklist->errores_despacho) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_despacho_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Errores en Instalación</div>
                <div class="item-value">{!! $renderValue($checklist->errores_instalacion) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_instalacion_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Otros Errores</div>
                <div class="item-value">{!! $renderValue($checklist->errores_otro) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->errores_otro_obs) !!}</div>
            </div>
        </div>
        
        @if($checklist->observaciones)
        <div class="observaciones">
            <div class="observaciones-title">Observaciones generales de errores:</div>
            <div class="observaciones-text">{{ $checklist->observaciones }}</div>
        </div>
        @endif
    </div>
    
    <div class="page-break"></div>
    
    <!-- SECCIÓN 3: ESTADO OBRA -->
    <div class="section">
        <div class="section-title">ESTADO OBRA AL MOMENTO DE LA INSTALACIÓN</div>
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Instalación de Cielo</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_cielo) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->instalacion_cielo_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Instalación de Piso</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_piso) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->instalacion_piso_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Remate Muros</div>
                <div class="item-value">{!! $renderValue($checklist->remate_muros) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->remate_muros_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Nivelación Piso</div>
                <div class="item-value">{!! $renderValue($checklist->nivelacion_piso) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->nivelacion_piso_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Muros a Plomo</div>
                <div class="item-value">{!! $renderValue($checklist->muros_plomo) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->muros_plomo_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Instalación Eléctrica</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_electrica) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->instalacion_electrica_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Instalación Voz y Dato</div>
                <div class="item-value">{!! $renderValue($checklist->instalacion_voz_dato) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->instalacion_voz_dato_obs) !!}</div>
            </div>
        </div>
    </div>
    
    <!-- SECCIÓN 4: INSPECCIÓN FINAL -->
    <div class="section">
        <div class="section-title">INSPECCIÓN FINAL</div>
        <div class="items-grid">
            <div class="item-row">
                <div class="item-label">Paneles Alineados</div>
                <div class="item-value">{!! $renderValue($checklist->paneles_alineados) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->paneles_alineados_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Nivelación Cubiertas</div>
                <div class="item-value">{!! $renderValue($checklist->nivelacion_cubiertas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->nivelacion_cubiertas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Pasacables Instalados</div>
                <div class="item-value">{!! $renderValue($checklist->pasacables_instalados) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->pasacables_instalados_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Limpieza Cubiertas</div>
                <div class="item-value">{!! $renderValue($checklist->limpieza_cubiertas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->limpieza_cubiertas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Limpieza Cajones</div>
                <div class="item-value">{!! $renderValue($checklist->limpieza_cajones) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->limpieza_cajones_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Limpieza Piso</div>
                <div class="item-value">{!! $renderValue($checklist->limpieza_piso) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->limpieza_piso_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Llaves Instaladas</div>
                <div class="item-value">{!! $renderValue($checklist->llaves_instaladas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->llaves_instaladas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Funcionamiento Mueble</div>
                <div class="item-value">{!! $renderValue($checklist->funcionamiento_mueble) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->funcionamiento_mueble_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Puntos Eléctricos</div>
                <div class="item-value">{!! $renderValue($checklist->puntos_electricos) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->puntos_electricos_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Sillas Ubicadas</div>
                <div class="item-value">{!! $renderValue($checklist->sillas_ubicadas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->sillas_ubicadas_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Accesorios</div>
                <div class="item-value">{!! $renderValue($checklist->accesorios) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->accesorios_obs) !!}</div>
            </div>
            <div class="item-row">
                <div class="item-label">Check Herramientas</div>
                <div class="item-value">{!! $renderValue($checklist->check_herramientas) !!}</div>
                <div class="item-obs">{!! $renderObs($checklist->check_herramientas_obs) !!}</div>
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
        <p>Sistema Herrajes - Ohffice &copy; {{ date('Y') }}</p>
    </div>
    
</body>
</html>