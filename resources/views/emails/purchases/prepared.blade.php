<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>Intento de compra</title>
</head>
<body>
<div>
    <p>
        <strong>Nombre del lead:</strong> {{ $name }}
        <br/>
        <br/>
        <strong>Correo:</strong> {{ $email }}
        <br/>
        <strong>Medio de transporte:</strong> <span style="text-transform: capitalize;">{{ $shippingMethod }}</span>
        <br/>
        <strong>Monto total:</strong> ${{ $purchaseAmount }} USD
        <br/>
        <strong>Medio de pago:</strong> <span style="text-transform: uppercase;">{{ $paymentMethod }}</span>
    </p>
</div>
</body>
</html>
