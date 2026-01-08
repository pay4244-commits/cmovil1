# Guía para Generar el APK de CMovil

Como el proyecto se ha creado manualmente, debes utilizar **Android Studio** para compilar la aplicación.

## Paso 1: Configurar la Dirección IP
⚠️ **IMPORTANTE**: Antes de generar el APK para un teléfono físico.

1. Abre el archivo: `app/src/main/java/com/example/cmovil/DataRepository.kt`
2. Busca la línea:
   ```kotlin
   private val BASE_URL = "http://10.0.2.2/cmovil/"
   ```
3. Si vas a instalar la app en un **teléfono real**, cambia `10.0.2.2` por la **Dirección IP Local de tu PC** (donde está XAMPP).
   - Ejemplo: `"http://192.168.1.50/cmovil/"`
   - Para ver tu IP, abre una terminal y escribe `ipconfig`.

*Si solo vas a usar el Emulador de Android, deja `10.0.2.2`.*

## Paso 2: Abrir en Android Studio
1. Abre Android Studio.
2. Selecciona **Open**.
3. Navega y selecciona la carpeta: `c:\xampp\htdocs\cmovil\android_app`
4. Espera a que termine la sincronización de Gradle (puede tardar unos minutos la primera vez).

## Paso 3: Generar el APK
1. En el menú superior, ve a **Build** > **Build Bundle(s) / APK(s)** > **Build APK(s)**.
2. Espera a que termine la compilación.
3. Aparecerá una notificación "Build APK(s): APK(s) generated successfully".
4. Haz clic en **locate** en esa notificación para abrir la carpeta con el archivo `.apk`.
   - Normalmente está en: `android_app/app/build/outputs/apk/debug/app-debug.apk`

## Paso 4: Instalación
1. Copia el archivo `app-debug.apk` a tu teléfono.
2. Ábrelo e instálalo (debes permitir instalar aplicaciones de fuentes desconocidas).
3. Abre la app **CMovil**.
4. Otorga los permisos solicitados (Ubicación y Teléfono).
5. Verifica en el Dashboard Web (`http://localhost/cmovil/`) que los datos están llegando.
