=== FPVSI Accessibility Widget for Elementor ===
Contributors: sigdevgba
Tags: accessibility, elementor, a11y, tts, widget
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.3.0
License: MIT

Widget de accesibilidad para Elementor. Arrastra, configura y se aplica a todo el sitio.

== Description ==

Plugin independiente que añade un widget de Elementor para configurar visualmente el widget de accesibilidad FPVSI.

* Lector de voz (TTS)
* Tamaño de texto, alto contraste, cursor grande
* Espaciado, fuente legible, resaltar enlaces
* Selector de idiomas (GTranslate)
* Icono del botón personalizable (Lucide icons)

Al publicar la página, la configuración se aplica a TODO el sitio automáticamente.

== Installation ==

1. Sube el ZIP desde Plugins > Añadir nuevo > Subir plugin
2. Activa el plugin (requiere Elementor)
3. Abre cualquier página en Elementor
4. Arrastra "Accessibility Widget" desde el panel
5. Configura y publica

== Changelog ==

= 1.3.0 =
* Fix: live preview real en Elementor via editor-handler.js (onInit/onDestroy)
* Fix: al borrar widget del editor se elimina el FAB flotante
* Fix: color fondo activo ahora es sólido (no 6% opacity invisible)
* Fix: color texto solo aplica a filas activas (no a todo el panel)
* Nuevas CSS vars: --a11y-active-bg, --a11y-active-bg-rgb, --a11y-text

= 1.2.0 =
* Controles "Fondo filas activas" y "Texto filas activas" para legibilidad

= 1.1.0 =
* Live preview en el editor de Elementor (destroy + re-init al cambiar parámetros)
* Config se persiste en wp_options al guardar/publicar via elementor/document/after_save
* Método destroy() añadido a widget.js

= 1.0.0 =
* Versión inicial
