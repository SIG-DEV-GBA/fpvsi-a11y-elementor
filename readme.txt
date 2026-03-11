=== FPVSI Accessibility Widget for Elementor ===
Contributors: sigdevgba
Tags: accessibility, elementor, a11y, tts, widget
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.2.0
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

= 1.2.0 =
* Nuevos controles: "Color texto activo" y "Color fondo hover/activo" para mejor legibilidad
* CSS vars --a11y-text y --a11y-hover-bg parametrizables desde Elementor

= 1.1.0 =
* Live preview en el editor de Elementor (destroy + re-init al cambiar parámetros)
* Config se persiste en wp_options al guardar/publicar via elementor/document/after_save
* Método destroy() añadido a widget.js

= 1.0.0 =
* Versión inicial
