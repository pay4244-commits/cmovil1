# Generar APK en la Nube (GitHub Actions)

Este es un método alternativo para generar el APK sin instalar nada en tu computadora, utilizando los servidores de GitHub.

## Pasos

1.  **Crea un repositorio en GitHub**:
    *   Ve a [github.com/new](https://github.com/new) y crea un nuevo repositorio público o privado.

2.  **Sube tu código**:
    *   Si tienes Git instalado:
        ```bash
        git init
        git add .
        git commit -m "Initial commit"
        git branch -M main
        git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
        git push -u origin main
        ```
    *   O simplemente sube los archivos usando la interfaz web de GitHub ("Upload files").

3.  **Espera la compilación**:
    *   Una vez subido el código, ve a la pestaña **"Actions"** en tu repositorio de GitHub.
    *   Verás un flujo de trabajo llamado **"Android Build"** ejecutándose.
    *   Espera a que termine (se pondrá en verde ✅).

4.  **Descarga el APK**:
    *   Haz clic en la ejecución del workflow (ej. "Initial commit").
    *   Baja hasta la sección **"Artifacts"**.
    *   Haz clic en **`app-debug`** para descargar el archivo ZIP.
    *   Descomprime el ZIP y ahí tendrás tu `app-debug.apk` listo para instalar.

## Nota sobre la IP
Recuerda que si compilas en la nube para usar en un dispositivo físico, debes haber cambiado la IP en `DataRepository.kt` **antes** de subir el código, ya que `localhost` o `10.0.2.2` no funcionarán en tu teléfono contra tu servidor XAMPP local.
