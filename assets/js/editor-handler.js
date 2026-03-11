/**
 * Elementor Editor Handler — FPVSI Accessibility Widget
 * Manages lifecycle: init on render, destroy on removal, re-init on control change.
 */
(function ($) {
  $(window).on('elementor/frontend/init', function () {
    // Solo en modo editor
    if (!elementorFrontend.isEditMode || !elementorFrontend.isEditMode()) return;

    var Handler = elementorModules.frontend.handlers.Base.extend({
      onInit: function () {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      onDestroy: function () {
        if (typeof FpvsiA11yWidget !== 'undefined') {
          FpvsiA11yWidget.destroy();
        }
      },
      run: function () {
        if (typeof FpvsiA11yWidget === 'undefined') return;
        FpvsiA11yWidget.destroy();
        var el = this.$element.find('[data-fpvsi-config]');
        if (!el.length) return;
        try {
          var config = JSON.parse(el.attr('data-fpvsi-config'));
          FpvsiA11yWidget.init(config);
        } catch (e) {
          console.warn('FPVSI A11y: config parse error', e);
        }
      }
    });

    elementorFrontend.hooks.addAction(
      'frontend/element_ready/fpvsi-a11y-widget.default',
      function ($element) {
        if (elementorFrontend.elementsHandler && elementorFrontend.elementsHandler.addHandler) {
          elementorFrontend.elementsHandler.addHandler(Handler, { $element: $element });
        } else {
          new Handler({ $element: $element });
        }
      }
    );
  });
})(jQuery);
