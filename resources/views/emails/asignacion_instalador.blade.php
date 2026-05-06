<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Asignación de Proyecto</title>
</head>
<body style="margin:0; padding:0; background:#f2f2f2; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f2f2f2; padding: 30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 3px 12px rgba(0,0,0,0.12);">

                {{-- Header --}}
                <tr>
                    <td style="background: linear-gradient(135deg, #1e3a5f, #2563eb); padding: 28px 30px; text-align:center;">
                        <img src="{{ $message->embed(public_path('logo/logo_oh.png')) }}"
                             height="60" style="max-height:60px; display:block; margin:0 auto;" alt="Ohffice">
                    </td>
                </tr>

                {{-- Banner informativo --}}
                <tr>
                    @if($esCambioDireccion)
                    <td style="background:#fff7ed; border-left:5px solid #ea580c; padding:14px 30px;">
                        <span style="font-size:14px; color:#9a3412; font-weight:bold;">&#9888;&#65039; Actualización de Dirección de Despacho</span>
                    </td>
                    @else
                    <td style="background:#eff6ff; border-left:5px solid #2563eb; padding:14px 30px;">
                        <span style="font-size:14px; color:#1e40af; font-weight:bold;">&#128196; Nueva Asignación de Proyecto</span>
                    </td>
                    @endif
                </tr>

                {{-- Cuerpo --}}
                <tr>
                    <td style="padding: 30px;">

                        <p style="font-size:15px; color:#333; margin:0 0 8px;">
                            Estimado/a <strong>{{ $instalador->nombre }}</strong>,
                        </p>

                        @if($esCambioDireccion)
                        <p style="font-size:14px; color:#555; line-height:1.7; margin:0 0 24px;">
                            La <strong>dirección de despacho</strong> de tu asignación ha sido actualizada.
                            Te informamos para que tengas la información correcta antes de dirigirte al lugar de trabajo.
                        </p>
                        @else
                        <p style="font-size:14px; color:#555; line-height:1.7; margin:0 0 24px;">
                            Se te ha asignado el siguiente proyecto. Ingresa a la aplicación para ver el detalle completo,
                            aceptar la asignación y registrar el avance del trabajo.
                        </p>
                        @endif

                        {{-- Tabla detalle del proyecto --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-radius:7px; overflow:hidden; margin-bottom:24px;">
                            <tr style="background:#1e3a5f;">
                                <td colspan="2" style="padding:10px 18px;">
                                    <span style="color:#fff; font-size:12px; font-weight:bold; letter-spacing:0.5px; text-transform:uppercase;">Detalle del Proyecto</span>
                                </td>
                            </tr>
                            <tr style="background:#fafafa;">
                                <td style="padding:12px 18px; font-size:13px; color:#777; width:40%; border-bottom:1px solid #eee;">Nota de Venta</td>
                                <td style="padding:12px 18px; font-size:14px; color:#1e3a5f; font-weight:bold; border-bottom:1px solid #eee;">
                                    NV-{{ str_pad($asignacion->nota_venta, 6, '0', STR_PAD_LEFT) }}
                                </td>
                            </tr>
                            <tr style="background:#ffffff;">
                                <td style="padding:12px 18px; font-size:13px; color:#777; border-bottom:1px solid #eee;">Cliente</td>
                                <td style="padding:12px 18px; font-size:14px; color:#222; font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $notaVenta?->nv_cliente ?? '—' }}
                                </td>
                            </tr>
                            <tr style="background:#fafafa;">
                                <td style="padding:12px 18px; font-size:13px; color:#777; border-bottom:1px solid #eee;">Descripci&#243;n</td>
                                <td style="padding:12px 18px; font-size:14px; color:#222; border-bottom:1px solid #eee;">
                                    {{ $notaVenta?->nv_descripcion ?? '—' }}
                                </td>
                            </tr>
                            <tr style="background:#fafafa;">
                                <td style="padding:12px 18px; font-size:13px; color:#777; border-bottom:1px solid #eee;">Direcci&#243;n de Despacho</td>
                                <td style="padding:12px 18px; font-size:14px; color:#222; font-weight:bold; border-bottom:1px solid #eee;">
                                    {{ $asignacion->lugar_despacho_nom ?? '—' }}
                                </td>
                            </tr>
                            <tr style="background:#ffffff;">
                                <td style="padding:12px 18px; font-size:13px; color:#777;">Fecha de Asignaci&#243;n</td>
                                <td style="padding:12px 18px; font-size:14px; color:#222; font-weight:bold;">
                                    {{ $asignacion->fecha_asigna?->format('d-m-Y') ?? '—' }}
                                </td>
                            </tr>
                        </table>

                        {{-- Botón de acceso --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ url('/mis-asignaciones') }}"
                                       style="display:inline-block; background:#2563eb; color:#ffffff; text-decoration:none;
                                              font-size:14px; font-weight:bold; padding:12px 32px; border-radius:6px;
                                              letter-spacing:0.5px;">
                                        Ver mis asignaciones
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="font-size:14px; color:#555; margin:0 0 6px;">
                            Ante cualquier duda, consulta con tu supervisor.
                        </p>
                        <p style="font-size:14px; color:#555; margin:0;">Saludos cordiales,</p>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8f8f8; border-top:3px solid #1e3a5f; padding:22px 30px; text-align:center;">
                        <img src="{{ $message->embed(public_path('logo/logo_oh.png')) }}"
                             height="38" style="max-height:38px; display:block; margin:0 auto 10px;" alt="Ohffice">
                        <p style="font-size:11px; color:#aaa; margin:0;">Este es un mensaje autom&#225;tico, por favor no responda a este correo.</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
