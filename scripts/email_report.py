import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication
import sys
import os
from datetime import date, timedelta
from export_gastos import export_to_excel

def send_weekly_report():
    # Obtener mes y año actuales
    today = date.today()
    mes_str = str(today.month).zfill(2)
    anio_str = str(today.year)
    
    # 1. Generar el archivo Excel temporalmente llamando a la función de export_gastos
    import io
    from contextlib import redirect_stdout
    
    # Capturamos la salida impresa de export_to_excel para obtener la ruta
    f = io.StringIO()
    with redirect_stdout(f):
        export_to_excel(mes_str, anio_str)
    
    # Limpiamos la cadena para sacar el path
    salida = f.getvalue()
    filepath = ''
    if salida:
        # cogemos la última línea no vacía impresa que deberia ser la ruta
        lineas = [l for l in salida.split('\n') if l.strip()]
        if lineas:
            filepath = lineas[-1].strip()
    
    if not filepath or not os.path.exists(filepath):
        print("Error: No se pudo generar o encontrar el archivo Excel.")
        return

    # 2. Configurar Email
    remitente = "ecobricsoporte@gmail.com" # Asumiendo que esta es desde donde se envía y a donde se recibe
    destinatario = "ecobricsoporte@gmail.com"
    # IMPORTANTE: Para usar Gmail en un script, se necesita una 'App Password' generada desde la cuenta de Google
    # Si no funciona, requerirá que el usuario active "App Passwords" en su cuenta de Gmail.
    password = os.environ.get("GMAIL_APP_PASSWORD", "AQUI_IRIA_LA_CONTRASENA_DE_APP") 
    
    fecha_inicio = today - timedelta(days=7)
    
    msg = MIMEMultipart()
    msg['From'] = remitente
    msg['To'] = destinatario
    msg['Subject'] = f"Ecobric ERP - Reporte Semanal Completo ({fecha_inicio.strftime('%d/%m/%Y')} al {today.strftime('%d/%m/%Y')})"

    cuerpo = f"""
    Hola Admin,
    
    Adjunto encontrarás el reporte completo de Gastos e Ingresos exportado del sistema.
    Este reporte automático es parte del flujo semanal de Ecobric ERP.
    
    Mes analizado: {mes_str}/{anio_str}
    
    Saludos,
    Servicio de Python Automatizado - Ecobric ERP
    """
    msg.attach(MIMEText(cuerpo, 'plain'))

    # Adjuntar Excel
    with open(filepath, "rb") as f_excel:
        part = MIMEApplication(
            f_excel.read(),
            Name=os.path.basename(filepath)
        )
    part['Content-Disposition'] = f'attachment; filename="{os.path.basename(filepath)}"'
    msg.attach(part)

    # 3. Enviar correo usando el servidor SMTP de Gmail
    try:
        # Como es una simulación temporal o puede que no tengamos la clave real en este entorno,
        # meteremos lógica por si falla la autenticación, informar por consola.
        print("Conectando al servidor SMTP...")
        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        
        if password == "AQUI_IRIA_LA_CONTRASENA_DE_APP":
            print("SIMULACIÓN DE ENVÍO EXITOSA.")
            print(f"El correo con asunto '{msg['Subject']}' y el archivo adjunto {os.path.basename(filepath)} se habría enviado correctamente si hubieras provisto una contraseña de aplicación.")
            print("Para uso en producción: Configura la variable de entorno GMAIL_APP_PASSWORD o modifica este script con tu App Password de Gmail.")
        else:
            server.login(remitente, password)
            server.send_message(msg)
            print("Correo enviado satisfactoriamente a", destinatario)
            
        server.quit()
        
    except Exception as e:
        print(f"Ocurrió un error al enviar el correo: {e}")
        print("Nota: Si el error es de autenticación, verifica tu Contraseña de Aplicación de Google.")

if __name__ == "__main__":
    send_weekly_report()
