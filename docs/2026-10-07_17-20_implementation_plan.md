# Plan de Implementación: Plataforma de Tamizaje Visual Corporativo (SpotVision)
**Fecha y Hora:** 2026-10-07 17:20 (CST)  
**Basado en:** `docs/Flujo_de_SpotVision_Visual_Corporativo.png`, `docs/Izamar Rodriguez Cabello.pdf`, `docs/AGENTS.md` y `docs/AuditoriaDeSeguridad.md`.

---

## 1. Visión General del Proyecto
Desarrollar una aplicación robusta en **Laravel 11/12 + FilamentPHP v3** que automatice todo el embudo corporativo de tamizaje visual con el autorrefractómetro portátil Welch Allyn Spot Vision Screener, abarcando desde la atracción inicial y captura de datos (Fase 1) hasta el retargeting automatizado (Fase 5), con importación de archivos PDF desde USB/almacenamiento, deduplicación inteligente de clientes, extracción híbrida de datos clínicos (IA y programática), generación de análisis rápido de resultados y envío estructurado vía WhatsApp.

---

## 2. Alineación con las 5 Fases del Bosquejo (`Flujo_de_SpotVision_Visual_Corporativo.png`)

### Fase 1: Atracción y Captura de Datos (El Intercambio)
- **Formulario Público de Registro Móvil / Tablet** (`/registro` o QR):
  - Captura ágil: Nombre completo, WhatsApp / Teléfono, Correo electrónico, Empresa / Sucursal, Fecha de nacimiento / Edad, Sexo.
  - Consentimiento legal garantizado: Checkbox obligatorio de términos, condiciones y aviso de privacidad de datos médicos con modal explicativo.
  - Generación de código único de sujeto / código QR para vinculación rápida durante el examen.

### Fase 2: Tamizaje Visual con Spot Vision (La Experiencia)
- **Importador de PDFs Spot Vision (Carga Individual, Carga Masiva y Escaneo de Carpeta USB)**:
  - Extracción y almacenamiento del archivo PDF original en almacenamiento seguro.
  - Extractor Híbrido de Información (IA + Programación):
    - *Extracción Programática*: Descompresión de streams raster Haru PDF con GD, lectura de metadatos (nombre, fecha de nacimiento, código de sujeto ENG, fecha/hora, OD/OS Esfera, Cilindro, Eje, DP, Desviación).
    - *Extracción con IA Multimodal*: Servicio configurable (OpenAI / Gemini Vision) para analizar la imagen del reporte y extraer mediciones exactas en JSON estructurado.
    - *Revisión Clínica Rápida*: Pantalla de validación en Filament para verificar/ajustar mediciones antes de guardar.
  - **Lógica de Deduplicación y Cruce de Pacientes**:
    - Búsqueda por `subject_code` (ID Sujeto, ej. `ENG7`), o código de cliente.
    - Búsqueda por Nombre completo + Fecha de nacimiento / Edad (para discriminar homónimos exactos).
    - Si no existe: Se crea un nuevo registro de cliente automáticamente vinculado al examen.
    - Si existe: Se vincula el examen al cliente registrado en la Fase 1.

### Fase 3: Entrega Digital y Asesoría Breve (El Gancho)
- **Motor de Análisis Rápido Clínico**:
  - Evaluación automática de reglas optométricas:
    - Estado General: **PASA** (Todo normal) o **REMITIR** (Fuera de rango).
    - Detección de afecciones: Miopía (SE < -0.50), Astigmatismo (DC <= -0.75), Hipermetropía (SE > +1.00), Anisometropía (diferencia OD/OS >= 1.00D), Anisocoria (diferencia pupilar >= 1mm), Estrabismo/Desviación de mirada (>= 5°).
    - Generación de resumen amigable en lenguaje humano para el paciente/empleado.
- **Visualizador Digital para el Paciente** (`/reporte/{uuid}`):
  - Página responsiva segura con tarjeta visual estética, resultados OD/OS, semáforo de estado, recomendaciones y botón de descarga del PDF original.
- **Centro de Envíos WhatsApp / Correo**:
  - Generación de plantilla de mensaje personalizado con link al reporte digital.
  - Cola y lista de envíos de WhatsApp en Filament con enlace directo a WhatsApp Web / API (`wa.me`).

### Fase 4: Cierre en Sitio o Etiquetado en CRM (La Clasificación)
- **Clasificación del Prospecto / Cliente**:
  - `Compra en Sitio` (Cliente Activo: compra inmediata de armazón, micas, lentes de protección o graduados; registro de orden / monto).
  - `No Comprador - Requiere Lentes` (Prospecto prioritario para retargeting).
  - `Pasa - Sin Requerimiento` (Prospecto preventivo).
- Acciones rápidas de cambio de estado y registro de venta en la tabla de Filament.

### Fase 5: Retargeting y Automatización (El Seguimiento)
- **Matriz de Seguimiento Automatizado**:
  - **Día 3**: Concientización de salud visual y catálogo digital / Lentes de seguridad.
  - **Día 15**: Cupón de descuento por tiempo limitado e incentivo vinculado al pre-diagnóstico.
  - **Día 90**: Recordatorio de examen visual completo clínico y refracción.
- Vista de campañas de retargeting en Filament con filtros por fase de días transcurridos y disparadores de WhatsApp en un clic.

---

## 3. Endurecimiento de Seguridad (`docs/AuditoriaDeSeguridad.md`)
1. **CSP Estricta (Content Security Policy)**:
   - Implementación de middleware con cabeceras `script-src 'self'`, `object-src 'none'`, `base-uri 'self'`, `img-src 'self' data: blob:`.
2. **Mitigación XSS**:
   - Sanitización y escape contextual de todas las variables mostradas en vistas Blade y Filament.
3. **Validación de Archivos y Mitigación DoS**:
   - Límite de tamaño de subida a 15 MB.
   - Verificación estricta de extensiones (`.pdf`) y tipos MIME (`application/pdf`).
   - Almacenamiento fuera del webroot público con nombres generados por UUID aleatorio.
4. **Sanitización de Teléfonos y URLs**:
   - Normalización de números telefónicos a formato internacional E.164 (solo dígitos y prefijo internacional) para evitar inyecciones en enlaces `https://wa.me/`.
5. **Cabeceras de Servidor Web**:
   - `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy`.
6. **Protección contra Prototype Pollution & Deserialización**:
   - Sanitización profunda en payloads JSON entrantes.

---

## 4. Plan de Ejecución Paso a Paso
1. **Paso 1: Inicialización del Proyecto**:
   - Instalar Laravel 11/12 y configurar base de datos SQLite preconfigurada.
   - Instalar `filament/filament:^3.2` y publicar recursos.
2. **Paso 2: Base de Datos y Modelos**:
   - Crear migraciones para `companies`, `clients`, `screenings` (estudios SpotVision), `orders` (ventas en sitio) y `retargeting_logs`.
3. **Paso 3: Lógica de Extracción de PDF SpotVision**:
   - Servicio extractor programático (`SpotVisionPdfExtractor`) que descomprime la imagen de Haru PDF, parsea código de barras, metadatos y mediciones.
   - Servicio de soporte de IA (`SpotVisionAiExtractor`) configurable con API Key para visión multimodal.
   - Servicio de análisis clínico rápido (`SpotVisionClinicalAnalyzer`).
4. **Paso 4: Recursos y Páginas Filament**:
   - Recurso `ClientResource` con filtros corporativos, historial de tamizajes y estatus CRM.
   - Recurso `ScreeningResource` con importador individual, masivo y escáner de carpeta USB.
   - Página Filament `WhatsAppQueuePage` para gestión visual de envíos.
   - Página Filament `RetargetingDashboard` para las campañas de Día 3, Día 15 y Día 90.
5. **Paso 5: Frontend Público & Experiencia de Usuario**:
   - Landing de registro de eventos corporativos (`/registro/{empresa?}`).
   - Portal de reporte digital del paciente (`/reporte/{uuid}`).
6. **Paso 6: Pruebas y Validación**:
   - Probar la importación del PDF de muestra `docs/Izamar Rodriguez Cabello.pdf`.
   - Validar la deduplicación, el análisis rápido y la generación de enlaces de WhatsApp.
   - Ejecutar la auditoría de seguridad del checklist.
7. **Paso 7: Documentación de Entrega**:
   - Generar el reporte de entrega `docs/YYYY-MM-DD_HH-mm_walkthrough.md` según `docs/AGENTS.md`.
