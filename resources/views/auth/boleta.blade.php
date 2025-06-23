<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Boleta 80 mm</title>
  <style>
   @page {
  margin: 1mm; 
  size: 72mm auto;
}
body {
  width: 72mm;
  margin: 0; padding: 0;
  font-size: 10px; line-height: 1.1;
}
h4, p { margin: 0; padding: 0; }

    .text-center { text-align:center; }
    .text-right  { text-align:right; }
    table {
      width:100%; border-collapse: separate; border-spacing:0;
    }
    th, td { padding:1px 0; }
    .border-bottom { border-bottom:1px dashed #000; }
    .border-top    { border-top:1px dashed #000; }
  </style>
</head>
<body>
  @php
    $subtotal = $pagos->sum(fn($p) => $p->metodoPago->Monto ?? 0);
    $total    = $subtotal;
  @endphp

  <div class="text-center">
    <h4 style="margin:2px 0;">NEOPROYECT</h4>
    <p style="margin:1px 0;">RUC: 12345678901</p>
    <p style="margin:1px 0;">Boleta N°: {{ $nroDoc }}</p>
    <p style="margin:1px 0;">{{ $fecha }}</p>
    <p style="margin:1px 0;">
      Cliente: {{ optional($cliente)->NombreCliente }} {{ optional($cliente)->ApellidopCliente }}
    </p>
  </div>

  <table>
    <thead class="border-bottom">
      <tr>
        <th style="width:15%;">CANT</th>
        <th style="width:55%;">DESCRIP</th>
        <th style="width:30%;" class="text-right">IMPORTE</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pagos as $pago)
      <tr>
        <td class="text-center">1</td>
        <td>Pago {{ $pago->PeriodoMes }}</td>
        <td class="text-right">{{ number_format($pago->metodoPago->Monto ?? 0, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table style="margin-top:2px;">
    <tr class="border-top">
      <td><strong>Total</strong></td>
      <td class="text-right"><strong>{{ number_format($total, 2) }}</strong></td>
    </tr>
  </table>

  <div class="text-center" style="margin-top:3px;">
    <p style="margin:1px 0;">¡Gracias por su preferencia!</p>
  </div>
</body>
</html>
