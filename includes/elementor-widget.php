<?php
/**
 * Elementor Widget — FPVSI Accessibility Widget
 * Plugin independiente: configura desde Elementor, se aplica a todo el sitio.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Fpvsi_A11y_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'fpvsi-a11y-widget';
    }

    public function get_title() {
        return 'Accessibility Widget';
    }

    public function get_icon() {
        return 'eicon-accessibility';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'accessibility', 'a11y', 'accesibilidad', 'widget' ];
    }

    public function get_script_depends() {
        return [ 'fpvsi-a11y-el', 'fpvsi-a11y-el-handler' ];
    }

    protected function register_controls() {

        /* ── Apariencia ── */
        $this->start_controls_section( 'section_appearance', [
            'label' => 'Apariencia',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control( 'primary_color', [
            'label'   => 'Color primario',
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '#A10D5E',
        ]);

        $this->add_control( 'accent_color', [
            'label'   => 'Color acento',
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '#F29429',
        ]);

        $this->add_control( 'hover_bg_color', [
            'label'       => 'Fondo filas activas',
            'type'        => \Elementor\Controls_Manager::COLOR,
            'default'     => '',
            'description' => 'Color de fondo sólido para opciones activadas. Ej: un azul claro (#dbeafe) o gris (#f3f4f6). Vacío = tinte suave del primario.',
        ]);

        $this->add_control( 'text_color', [
            'label'       => 'Texto filas activas',
            'type'        => \Elementor\Controls_Manager::COLOR,
            'default'     => '',
            'description' => 'Color del texto e iconos en opciones activadas. Debe ser legible sobre el fondo activo. Vacío = color primario.',
        ]);

        $this->add_control( 'position', [
            'label'   => 'Posición',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'bottom-left',
            'options' => [
                'bottom-left'  => 'Abajo izquierda',
                'bottom-right' => 'Abajo derecha',
                'top-left'     => 'Arriba izquierda',
                'top-right'    => 'Arriba derecha',
            ],
        ]);

        $this->add_control( 'offset_x', [
            'label'   => 'Desplazamiento horizontal (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 500 ] ],
            'default' => [ 'size' => 20, 'unit' => 'px' ],
        ]);

        $this->add_control( 'offset_y', [
            'label'   => 'Desplazamiento vertical (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 500 ] ],
            'default' => [ 'size' => 20, 'unit' => 'px' ],
        ]);

        $this->add_control( 'z_index_val', [
            'label'   => 'z-index',
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 9998,
            'min'     => 1,
            'max'     => 99999,
        ]);

        $this->add_control( 'trigger_icon', [
            'label'       => 'Icono del botón',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'accessibility',
            'placeholder' => 'accessibility',
            'description' => 'Nombre del icono Lucide (ej: <code>accessibility</code>, <code>person-standing</code>, <code>heart</code>). <a href="https://lucide.dev/icons" target="_blank">Buscar iconos</a>',
        ]);

        $this->end_controls_section();

        /* ── Funcionalidades ── */
        $this->start_controls_section( 'section_features', [
            'label' => 'Funcionalidades',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $features = [
            'feat_fontSize'       => 'Tamaño de texto',
            'feat_contrast'       => 'Alto contraste',
            'feat_bigCursor'      => 'Cursor grande',
            'feat_textSpacing'    => 'Espaciado de texto',
            'feat_dyslexiaFont'   => 'Fuente legible',
            'feat_highlightLinks' => 'Resaltar enlaces',
            'feat_tts'            => 'Lector de voz (TTS)',
            'feat_languages'      => 'Selector de idiomas',
        ];

        foreach ( $features as $id => $label ) {
            $this->add_control( $id, [
                'label'        => $label,
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => 'Sí',
                'label_off'    => 'No',
                'return_value' => 'yes',
            ]);
        }

        $this->add_control( 'tts_lang', [
            'label'       => 'Idioma TTS',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'es-ES',
            'description' => 'Código BCP 47 (ej: es-ES, en-US, gl, ca, eu)',
        ]);

        $this->end_controls_section();

        /* ── Etiquetas ── */
        $this->start_controls_section( 'section_labels', [
            'label' => 'Etiquetas / Textos',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $label_fields = [
            'label_title'          => [ 'Título del widget',      'Accesibilidad' ],
            'label_fontSize'       => [ 'Etiqueta tamaño texto',  'Texto' ],
            'label_contrast'       => [ 'Etiqueta contraste',     'Alto contraste' ],
            'label_bigCursor'      => [ 'Etiqueta cursor',        'Cursor grande' ],
            'label_textSpacing'    => [ 'Etiqueta espaciado',     'Espaciado' ],
            'label_dyslexiaFont'   => [ 'Etiqueta fuente',        'Fuente legible' ],
            'label_highlightLinks' => [ 'Etiqueta enlaces',       'Resaltar enlaces' ],
            'label_ttsSection'     => [ 'Título sección TTS',     'Lector de voz' ],
            'label_langSection'    => [ 'Título sección idiomas', 'Idioma' ],
            'label_footer'         => [ 'Texto del pie',          'Preferencias guardadas en tu navegador' ],
            'label_trigger'        => [ 'Aria-label del botón',   'Opciones de accesibilidad' ],
        ];

        foreach ( $label_fields as $id => $info ) {
            $this->add_control( $id, [
                'label'       => $info[0],
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => $info[1],
            ]);
        }

        $this->end_controls_section();

        /* ── Idiomas ── */
        $this->start_controls_section( 'section_languages', [
            'label' => 'Selector de idiomas',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'lang_code', [
            'label'   => 'Código',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'es',
        ]);

        $repeater->add_control( 'lang_label', [
            'label'   => 'Etiqueta',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Español',
        ]);

        $repeater->add_control( 'lang_flag', [
            'label'       => 'Archivo bandera',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'es.svg',
            'description' => 'Nombre del SVG en /assets/flags/ (ej: es.svg, gb.svg)',
        ]);

        $this->add_control( 'languages_list', [
            'label'       => 'Idiomas',
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'lang_code' => 'es', 'lang_label' => 'Español',  'lang_flag' => 'es.svg' ],
                [ 'lang_code' => 'en', 'lang_label' => 'English',  'lang_flag' => 'gb.svg' ],
                [ 'lang_code' => 'gl', 'lang_label' => 'Galego',   'lang_flag' => 'es-ga.svg' ],
                [ 'lang_code' => 'ca', 'lang_label' => 'Català',   'lang_flag' => 'es-ct.svg' ],
                [ 'lang_code' => 'eu', 'lang_label' => 'Euskara',  'lang_flag' => 'es-pv.svg' ],
            ],
            'title_field' => '{{{ lang_label }}} ({{{ lang_code }}})',
        ]);

        $this->end_controls_section();
    }

    /**
     * Construir config JS desde controles de Elementor
     */
    private function build_config() {
        $s = $this->get_settings_for_display();

        $colors = [
            'primary' => ! empty( $s['primary_color'] ) ? $s['primary_color'] : '#A10D5E',
            'accent'  => ! empty( $s['accent_color'] )  ? $s['accent_color']  : '#F29429',
        ];
        if ( ! empty( $s['text_color'] ) ) {
            $colors['text'] = $s['text_color'];
        }
        if ( ! empty( $s['hover_bg_color'] ) ) {
            $colors['hoverBg'] = $s['hover_bg_color'];
        }
        $config = [
            'colors'     => $colors,
            'position'   => ! empty( $s['position'] ) ? $s['position'] : 'bottom-left',
            'offsetX'    => ! empty( $s['offset_x']['size'] ) ? (int) $s['offset_x']['size'] : 20,
            'offsetY'    => ! empty( $s['offset_y']['size'] ) ? (int) $s['offset_y']['size'] : 20,
            'zIndex'     => ! empty( $s['z_index_val'] ) ? (int) $s['z_index_val'] : 9998,
            'ttsLang'    => ! empty( $s['tts_lang'] ) ? $s['tts_lang'] : 'es-ES',
            'flagsUrl'   => FPVSI_A11Y_EL_URL . 'assets/flags/',
            'storageKey' => 'a11y-prefs',
        ];

        if ( ! empty( $s['trigger_icon'] ) ) {
            $config['triggerIcon'] = $s['trigger_icon'];
        }

        // Features
        $feature_map = [
            'feat_fontSize'       => 'fontSize',
            'feat_contrast'       => 'contrast',
            'feat_bigCursor'      => 'bigCursor',
            'feat_textSpacing'    => 'textSpacing',
            'feat_dyslexiaFont'   => 'dyslexiaFont',
            'feat_highlightLinks' => 'highlightLinks',
            'feat_tts'            => 'tts',
            'feat_languages'      => 'languages',
        ];
        $features = [];
        foreach ( $feature_map as $control_id => $feature_key ) {
            if ( 'yes' === $s[ $control_id ] ) {
                $features[] = $feature_key;
            }
        }
        $config['features'] = $features;

        // Labels
        $label_map = [
            'label_title'          => 'title',
            'label_fontSize'       => 'fontSize',
            'label_contrast'       => 'contrast',
            'label_bigCursor'      => 'bigCursor',
            'label_textSpacing'    => 'textSpacing',
            'label_dyslexiaFont'   => 'dyslexiaFont',
            'label_highlightLinks' => 'highlightLinks',
            'label_ttsSection'     => 'ttsSection',
            'label_langSection'    => 'langSection',
            'label_footer'         => 'footer',
            'label_trigger'        => 'trigger',
        ];
        $labels = [];
        foreach ( $label_map as $control_id => $label_key ) {
            if ( ! empty( $s[ $control_id ] ) ) {
                $labels[ $label_key ] = $s[ $control_id ];
            }
        }
        if ( ! empty( $labels ) ) {
            $config['labels'] = $labels;
        }

        // Languages
        if ( ! empty( $s['languages_list'] ) && is_array( $s['languages_list'] ) ) {
            $langs = [];
            foreach ( $s['languages_list'] as $l ) {
                if ( ! empty( $l['lang_code'] ) && ! empty( $l['lang_label'] ) && ! empty( $l['lang_flag'] ) ) {
                    $langs[] = [
                        'code'  => $l['lang_code'],
                        'label' => $l['lang_label'],
                        'flag'  => $l['lang_flag'],
                    ];
                }
            }
            if ( ! empty( $langs ) ) {
                $config['languages'] = $langs;
            }
        }

        return $config;
    }

    protected function render() {
        $config = $this->build_config();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // Guardar config solo en frontend (no en cada preview del editor)
        if ( ! $is_editor ) {
            update_option( 'fpvsi_a11y_elementor_config', $config, false );
        }

        $json = wp_json_encode( $config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        if ( $is_editor ) {
            // Editor: indicador visual
            echo '<div style="padding:12px 16px;background:#f9f5ff;border:2px solid ' . esc_attr( $config['colors']['primary'] ) . ';border-radius:8px;font-family:sans-serif;display:flex;align-items:center;gap:8px;">';
            echo '<span style="font-size:20px;">&#9855;</span>';
            echo '<div>';
            echo '<p style="margin:0;font-weight:600;color:' . esc_attr( $config['colors']['primary'] ) . ';font-size:13px;">Accessibility Widget</p>';
            echo '<p style="margin:0;color:#888;font-size:11px;">Se aplica a todo el sitio. Config se guarda al publicar.</p>';
            echo '</div>';
            echo '</div>';
            // Config como data-attribute — editor-handler.js se encarga de init/destroy
            echo '<div data-fpvsi-config="' . esc_attr( $json ) . '" style="display:none;"></div>';
        }

        // Frontend: no renderizar nada — wp_footer se encarga
    }

    /**
     * Guardar config en wp_options cuando Elementor guarda la página (save/publish)
     */
    public function on_save( $data ) {
        $config = $this->build_config();
        update_option( 'fpvsi_a11y_elementor_config', $config, false );
        return $data;
    }
}
