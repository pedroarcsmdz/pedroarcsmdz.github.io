<?php
/**
 * FORMULARIO DE CONTACTO SÚPER SIMPLE
 * NO REQUIERE CREDENCIALES SMTP VISIBLES
 * Usa la función mail() del servidor
 */

// ===============================================
// CONFIGURACIÓN (Solo cambia tu email aquí)
// ===============================================
define('MI_EMAIL', 'pedroarcmdz@gmail.com'); // ← CAMBIA POR TU EMAIL
define('NOMBRE_SITIO', 'Pedro-Arcos');

/**
 * FUNCIÓN PRINCIPAL DE ENVÍO
 */
function enviarMensaje($datos) {
    $para = MI_EMAIL;
    $asunto = '📧 Nuevo mensaje: ' . $datos['asunto'];
    $mensaje = crearMensajeHTML($datos);
    $mensajeTexto = crearMensajeTexto($datos);
    
    // Crear boundary para email multipart
    $boundary = md5(uniqid(time()));
    
    // Headers del correo
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        'From: ' . NOMBRE_SITIO . ' <' . $datos['email'] . '>',
        'Reply-To: ' . $datos['nombre'] . ' <' . $datos['email'] . '>',
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 1'
    ];
    
    // Cuerpo del email (texto + HTML)
    $cuerpo = "--$boundary\r\n";
    $cuerpo .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $cuerpo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $cuerpo .= $mensajeTexto . "\r\n";
    
    $cuerpo .= "--$boundary\r\n";
    $cuerpo .= "Content-Type: text/html; charset=UTF-8\r\n";
    $cuerpo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $cuerpo .= $mensaje . "\r\n";
    
    $cuerpo .= "--$boundary--";
    
    // Enviar el correo
    return mail($para, $asunto, $cuerpo, implode("\r\n", $headers));
}

/**
 * CREAR MENSAJE HTML ATRACTIVO
 */
function crearMensajeHTML($datos) {
    $fecha = date('d/m/Y H:i:s');
    
    return "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body { margin: 0; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
            .header p { margin: 10px 0 0 0; opacity: 0.9; font-size: 14px; }
            .content { padding: 30px; line-height: 1.6; }
            .info-section { background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin: 20px 0; border-radius: 5px; }
            .info-item { margin-bottom: 12px; }
            .info-label { font-weight: 600; color: #495057; display: inline-block; width: 100px; }
            .info-value { color: #212529; }
            .message-section { background: white; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin: 20px 0; }
            .message-title { font-weight: 600; color: #495057; margin-bottom: 15px; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef; }
            .footer p { margin: 0; color: #6c757d; font-size: 12px; line-height: 1.5; }
            .reply-btn { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 12px 25px; border-radius: 25px; font-weight: 600; margin: 15px 0; }
            .reply-btn:hover { text-decoration: none; color: white; }
            
            @media screen and (max-width: 600px) {
                body { padding: 10px; }
                .container { border-radius: 10px; }
                .header, .content, .footer { padding: 20px; }
                .info-label { display: block; margin-bottom: 5px; }
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <!-- Encabezado -->
            <div class='header'>
                <h1>📧 Nuevo Mensaje de Contacto</h1>
                <p>Recibido desde tu sitio web</p>
            </div>
            
            <!-- Contenido -->
            <div class='content'>
                <p><strong>¡Hola Pedro!</strong></p>
                <p>Has recibido un nuevo mensaje de contacto desde tu sitio web. Aquí están los detalles:</p>
                
                <!-- Información del contacto -->
                <div class='info-section'>
                    <div class='info-item'>
                        <span class='info-label'>👤 Nombre:</span>
                        <span class='info-value'>" . htmlspecialchars($datos['nombre'], ENT_QUOTES, 'UTF-8') . "</span>
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>📧 Email:</span>
                        <span class='info-value'>" . htmlspecialchars($datos['email'], ENT_QUOTES, 'UTF-8') . "</span>
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>📱 Teléfono:</span>
                        <span class='info-value'>" . htmlspecialchars($datos['telefono'], ENT_QUOTES, 'UTF-8') . "</span>
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>🏷️ Asunto:</span>
                        <span class='info-value'>" . htmlspecialchars($datos['asunto'], ENT_QUOTES, 'UTF-8') . "</span>
                    </div>
                    <div class='info-item'>
                        <span class='info-label'>📅 Fecha:</span>
                        <span class='info-value'>$fecha</span>
                    </div>
                </div>
                
                <!-- Mensaje -->
                <div class='message-section'>
                    <div class='message-title'>💬 Mensaje:</div>
                    <div style='line-height: 1.7; color: #495057;'>
                        " . nl2br(htmlspecialchars($datos['mensaje'], ENT_QUOTES, 'UTF-8')) . "
                    </div>
                </div>
                
                <!-- Botón de respuesta -->
                <div style='text-align: center;'>
                    <a href='mailto:" . htmlspecialchars($datos['email'], ENT_QUOTES, 'UTF-8') . "?subject=Re: " . urlencode($datos['asunto']) . "' class='reply-btn'>
                        📨 Responder a " . htmlspecialchars($datos['nombre'], ENT_QUOTES, 'UTF-8') . "
                    </a>
                </div>
            </div>
            
            <!-- Pie de página -->
            <div class='footer'>
                <p><strong>Este mensaje fue enviado desde tu formulario de contacto</strong></p>
                <p style='margin-top: 8px;'>
                    🌐 <strong>" . NOMBRE_SITIO . "</strong> | 
                    📍 Emiliano Zapata, Morelos, México | 
                    📞 (+52) 916 150 3119
                </p>
            </div>
        </div>
    </body>
    </html>";
}

/**
 * CREAR MENSAJE DE TEXTO PLANO
 */
function crearMensajeTexto($datos) {
    $fecha = date('d/m/Y H:i:s');
    
    return "
========================================
NUEVO MENSAJE DE CONTACTO
========================================

¡Hola Pedro!

Has recibido un nuevo mensaje desde tu sitio web:

DATOS DEL CONTACTO:
--------------------
Nombre: {$datos['nombre']}
Email: {$datos['email']}
Teléfono: {$datos['telefono']}
Asunto: {$datos['asunto']}
Fecha: $fecha

MENSAJE:
--------
{$datos['mensaje']}

========================================
Para responder, envía un email a: {$datos['email']}

Este mensaje fue enviado desde " . NOMBRE_SITIO . "
pedroam.com | Emiliano Zapata, Morelos, México
========================================";
}

/**
 * VALIDAR DATOS DEL FORMULARIO
 */
function validarFormulario($datos) {
    $errores = [];
    
    // Validar nombre
    if (empty($datos['nombre']) || strlen(trim($datos['nombre'])) < 2) {
        $errores[] = 'El nombre es obligatorio y debe tener al menos 2 caracteres';
    }
    
    // Validar email
    if (empty($datos['email'])) {
        $errores[] = 'El email es obligatorio';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El formato del email no es válido';
    }
    
    // Validar asunto
    if (empty($datos['asunto']) || strlen(trim($datos['asunto'])) < 3) {
        $errores[] = 'El asunto es obligatorio y debe tener al menos 3 caracteres';
    }
    
    // Validar mensaje
    if (empty($datos['mensaje']) || strlen(trim($datos['mensaje'])) < 10) {
        $errores[] = 'El mensaje es obligatorio y debe tener al menos 10 caracteres';
    }
    
    // Validar longitudes máximas para evitar spam
    if (strlen($datos['nombre']) > 100) $errores[] = 'El nombre es demasiado largo';
    if (strlen($datos['email']) > 100) $errores[] = 'El email es demasiado largo';
    if (strlen($datos['asunto']) > 200) $errores[] = 'El asunto es demasiado largo';
    if (strlen($datos['mensaje']) > 2000) $errores[] = 'El mensaje es demasiado largo';
    
    return $errores;
}

/**
 * LIMPIAR Y SANITIZAR DATOS
 */
function limpiarDatos($datos) {
    return [
        'nombre' => trim(strip_tags($datos['nombre'])),
        'email' => trim(strtolower($datos['email'])),
        'telefono' => trim(strip_tags($datos['telefono'])),
        'asunto' => trim(strip_tags($datos['asunto'])),
        'mensaje' => trim($datos['mensaje']) // No eliminar HTML del mensaje
    ];
}

/**
 * MOSTRAR PÁGINA DE RESULTADO
 */
function mostrarResultado($exito, $mensaje) {
    $color = $exito ? '#56ab2f' : '#ff416c';
    $color2 = $exito ? '#a8e6cf' : '#ff4b2b';
    $icono = $exito ? '✅' : '❌';
    $titulo = $exito ? '¡Mensaje Enviado!' : 'Error al Enviar';
    
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$titulo</title>
        <style>
            body { margin: 0; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
            .container { max-width: 500px; margin: 50px auto; background: linear-gradient(135deg, $color 0%, $color2 100%); color: white; padding: 40px; border-radius: 15px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
            h2 { margin: 0 0 20px 0; font-size: 28px; }
            p { margin: 15px 0; font-size: 16px; line-height: 1.5; }
            .btn { display: inline-block; background: rgba(255,255,255,0.2); color: white; text-decoration: none; padding: 12px 25px; border-radius: 25px; margin-top: 20px; font-weight: 600; backdrop-filter: blur(10px); }
            .btn:hover { background: rgba(255,255,255,0.3); text-decoration: none; color: white; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>$icono $titulo</h2>
            <p>$mensaje</p>
            <a href='javascript:history.back()' class='btn'>← Volver al formulario</a>
        </div>
    </body>
    </html>";
}

// =====================================================
// PROCESAMIENTO PRINCIPAL
// =====================================================

// Verificar que sea método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    mostrarResultado(false, 'Método no permitido. Use el formulario para enviar mensajes.');
    exit;
}

// Obtener datos del formulario (coinciden con los nombres en tu HTML)
$datosFormulario = [
    'nombre' => $_POST['name'] ?? '',
    'email' => $_POST['_replyto'] ?? '',
    'telefono' => $_POST['phone'] ?? '',
    'asunto' => $_POST['Subject'] ?? '',
    'mensaje' => $_POST['message'] ?? ''
];

// Limpiar datos
$datos = limpiarDatos($datosFormulario);

// Validar datos
$errores = validarFormulario($datos);

if (!empty($errores)) {
    mostrarResultado(false, 'Hay errores en el formulario:<br>• ' . implode('<br>• ', $errores));
    exit;
}

// Protección básica anti-spam
$palabrasSpam = ['viagra', 'casino', 'loan', 'bitcoin', 'crypto', 'investment'];
$mensajeLimpio = strtolower($datos['mensaje']);

foreach ($palabrasSpam as $palabra) {
    if (strpos($mensajeLimpio, $palabra) !== false) {
        mostrarResultado(false, 'Mensaje rechazado por el filtro anti-spam.');
        exit;
    }
}

// Intentar enviar el mensaje
try {
    if (enviarMensaje($datos)) {
        mostrarResultado(true, 'Tu mensaje ha sido enviado correctamente. Te responderé lo más pronto posible.');
    } else {
        mostrarResultado(false, 'Hubo un problema al enviar tu mensaje. Por favor intenta de nuevo o contáctame directamente a ' . MI_EMAIL);
    }
} catch (Exception $e) {
    // Log del error sin mostrarlo al usuario
    error_log('Error en contactform.php: ' . $e->getMessage());
    mostrarResultado(false, 'Error del servidor. Por favor intenta de nuevo más tarde.');
}
?>