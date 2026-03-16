<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
    .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .header { background: #0D0D0D; padding: 30px; text-align: center; }
    .header h1 { color: #FFBE0B; font-size: 1.5rem; margin: 0; }
    .header p { color: rgba(255,255,255,0.6); font-size: 0.85rem; margin: 5px 0 0; }
    .body { padding: 40px 30px; }
    .body h2 { color: #0D0D0D; font-size: 1.2rem; }
    .body p { color: #555; line-height: 1.7; }
    .codigo-box { background: #0D0D0D; color: #FFBE0B; font-size: 2.5rem; font-weight: 900; text-align: center; letter-spacing: 0.5rem; border-radius: 12px; padding: 20px; margin: 30px 0; }
    .warning { background: #fff8e1; border-left: 4px solid #FFBE0B; padding: 15px; border-radius: 6px; font-size: 0.85rem; color: #555; }
    .footer { background: #f4f4f4; text-align: center; padding: 20px; font-size: 0.75rem; color: #aaa; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>MAC · Arte</h1>
      <p>Museo de Arte Contemporáneo</p>
    </div>
    <div class="body">
      <h2>¡Bienvenido, {{ $nombre }}!</h2>
      <p>Tu cuenta ha sido creada exitosamente. Este es tu <strong>código de seguridad</strong> personal para realizar compras en nuestra plataforma:</p>
      <div class="codigo-box">{{ $codigo }}</div>
      <div class="warning">
        ⚠️ <strong>Importante:</strong> Guarda este código en un lugar seguro. Lo necesitarás cada vez que quieras adquirir una obra. No lo compartas con nadie.
      </div>
      <p style="margin-top:20px;">Si no creaste esta cuenta, ignora este mensaje.</p>
    </div>
    <div class="footer">
      © {{ date('Y') }} Museo de Arte Contemporáneo · Todos los derechos reservados
    </div>
  </div>
</body>
</html>