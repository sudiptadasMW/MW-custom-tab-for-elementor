/**
 * MW Custom Tab for Elementor — fabrication-widget.js
 * Version: 1.0.0
 * Handles: Style One tabs, Style Two top + sub tabs, Swiper carousel sync, keyboard navigation.
 */

(function ($) {
    'use strict';

    /* ------------------------------------------------------------------
       Swiper instance registry — keyed by "widgetId-catIndex"
       ------------------------------------------------------------------ */
    var MWSwipers = {};

    /* ==================================================================
       MAIN INIT
       ================================================================== */
    function initWidget($widget) {
        var layout  = $widget.data('layout');
        var display = $widget.data('display') || 'static';

        if (layout === 'style_one') {
            initStyleOne($widget);
        } else if (layout === 'style_two') {
            initStyleTwo($widget, display);
        }
    }

    /* ==================================================================
       STYLE ONE — Vertical Tabs
       ================================================================== */
    function initStyleOne($widget) {
        var $navItems = $widget.find('.mw-tab-nav-item');
        var $panels   = $widget.find('.mw-tab-panel');

        if (!$navItems.length) return;

        /* Click */
        $navItems.on('click', function () {
            activateStyleOneTab($(this), $navItems, $panels);
        });

        /* Keyboard navigation (arrow keys) */
        $navItems.on('keydown', function (e) {
            var $items  = $navItems;
            var $cur    = $(this);
            var curIdx  = $items.index($cur);
            var target  = -1;

            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                e.preventDefault();
                target = (curIdx + 1) % $items.length;
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                e.preventDefault();
                target = (curIdx - 1 + $items.length) % $items.length;
            } else if (e.key === 'Home') {
                e.preventDefault();
                target = 0;
            } else if (e.key === 'End') {
                e.preventDefault();
                target = $items.length - 1;
            }

            if (target >= 0) {
                $items.eq(target).trigger('click').focus();
            }
        });
    }

    function activateStyleOneTab($clicked, $navItems, $panels) {
        var index = $clicked.data('index');

        $navItems
            .removeClass('active')
            .attr('aria-selected', 'false')
            .attr('tabindex', '-1');

        $panels.removeClass('active');

        $clicked
            .addClass('active')
            .attr('aria-selected', 'true')
            .attr('tabindex', '0');

        $panels.filter('[data-index="' + index + '"]').addClass('active');
    }

    /* ==================================================================
       STYLE TWO — Category Tabs + Sub Tabs
       ================================================================== */
    function initStyleTwo($widget, display) {
        var $topBtns   = $widget.find('.mw-top-tab-btn');
        var $catPanels = $widget.find('.mw-cat-panel');

        if (!$topBtns.length) return;

        /* Init the first active panel immediately */
        var $firstPanel = $catPanels.filter('.active').first();
        if ($firstPanel.length) {
            initSubTabs($firstPanel, display, $widget);
        }

        /* Top tab click */
        $topBtns.on('click', function () {
            var $this    = $(this);
            var catIndex = $this.data('cat-index');

            $topBtns
                .removeClass('active')
                .attr('aria-selected', 'false');

            $catPanels.removeClass('active');

            $this
                .addClass('active')
                .attr('aria-selected', 'true');

            var $activePanel = $catPanels.filter('[data-cat-index="' + catIndex + '"]');
            $activePanel.addClass('active');

            /* Init sub-tabs for this panel (lazy) */
            initSubTabs($activePanel, display, $widget);
        });

        /* Horizontal keyboard navigation for top tabs */
        $topBtns.on('keydown', function (e) {
            var $items = $topBtns;
            var curIdx = $items.index($(this));
            var target = -1;

            if (e.key === 'ArrowRight') { e.preventDefault(); target = (curIdx + 1) % $items.length; }
            else if (e.key === 'ArrowLeft') { e.preventDefault(); target = (curIdx - 1 + $items.length) % $items.length; }
            else if (e.key === 'Home') { e.preventDefault(); target = 0; }
            else if (e.key === 'End') { e.preventDefault(); target = $items.length - 1; }

            if (target >= 0) {
                $items.eq(target).trigger('click').focus();
            }
        });
    }

    /* ------------------------------------------------------------------
       Sub Tabs Init (per panel)
       ------------------------------------------------------------------ */
    function initSubTabs($panel, display, $widget) {
        /* Guard: only init once */
        if ($panel.data('mw-sub-init')) return;
        $panel.data('mw-sub-init', true);

        var catIndex  = $panel.data('cat-index');
        var $subItems = $panel.find('.mw-sub-tab-item');
        var $rights   = $panel.find('.mw-tab-right');
        var $images   = $panel.find('.mw-static-image');

        if (!$subItems.length) return;

        /* Carousel init */
        if (display === 'carousel') {
            initCarousel($panel, catIndex, $subItems, $rights, $widget);
        }

        /* Sub tab click */
        $subItems.on('click', function () {
            var $this     = $(this);
            var itemIndex = $this.data('item-index');

            $subItems
                .removeClass('active')
                .attr('aria-selected', 'false')
                .attr('tabindex', '-1');

            $this
                .addClass('active')
                .attr('aria-selected', 'true')
                .attr('tabindex', '0');

            if (display === 'carousel') {
                var key    = $widget.attr('id') + '-cat-' + catIndex;
                var swiper = MWSwipers[key];
                if (swiper) {
                    swiper.slideTo(parseInt(itemIndex, 10));
                }
            } else {
                $images.removeClass('active');
                $images.filter('[data-item-index="' + itemIndex + '"]').addClass('active');
            }

            $rights.removeClass('active');
            $rights.filter('[data-item-index="' + itemIndex + '"]').addClass('active');
        });

        /* Keyboard navigation — vertical */
        $subItems.on('keydown', function (e) {
            var $items = $subItems;
            var curIdx = $items.index($(this));
            var target = -1;

            if (e.key === 'ArrowDown') { e.preventDefault(); target = (curIdx + 1) % $items.length; }
            else if (e.key === 'ArrowUp') { e.preventDefault(); target = (curIdx - 1 + $items.length) % $items.length; }
            else if (e.key === 'Home') { e.preventDefault(); target = 0; }
            else if (e.key === 'End') { e.preventDefault(); target = $items.length - 1; }

            if (target >= 0) {
                $items.eq(target).trigger('click').focus();
            }
        });
    }

    /* ------------------------------------------------------------------
       Carousel Init (Swiper.js)
       ------------------------------------------------------------------ */
    function initCarousel($panel, catIndex, $subItems, $rights, $widget) {
        var $carouselEl = $panel.find('.mw-carousel-wrapper');
        if (!$carouselEl.length) return;

        /* Require Swiper */
        if (typeof Swiper === 'undefined') {
            console.warn('MW Custom Tab: Swiper.js is not available. Falling back to static display.');
            return;
        }

        var widgetId = $widget.attr('id') || ('mw-widget-' + catIndex);
        var key      = widgetId + '-cat-' + catIndex;

        /* Destroy any existing instance */
        if (MWSwipers[key]) {
            MWSwipers[key].destroy(true, true);
            delete MWSwipers[key];
        }

        var $paginationEl = $carouselEl.find('.swiper-pagination');
        var $prevEl       = $carouselEl.find('.swiper-button-prev');
        var $nextEl       = $carouselEl.find('.swiper-button-next');

        MWSwipers[key] = new Swiper($carouselEl[0], {
            slidesPerView: 1,
            loop: false,
            speed: 400,
            a11y: {
                enabled: true,
                prevSlideMessage: 'Previous image',
                nextSlideMessage: 'Next image',
            },
            pagination: {
                el: $paginationEl[0] || null,
                clickable: true,
            },
            navigation: {
                nextEl: $nextEl[0] || null,
                prevEl: $prevEl[0] || null,
            },
            on: {
                slideChange: function () {
                    var newIndex = this.activeIndex;

                    /* Sync sub tab */
                    $subItems
                        .removeClass('active')
                        .attr('aria-selected', 'false')
                        .attr('tabindex', '-1');

                    $subItems
                        .filter('[data-item-index="' + newIndex + '"]')
                        .addClass('active')
                        .attr('aria-selected', 'true')
                        .attr('tabindex', '0');

                    /* Sync right content */
                    $rights.removeClass('active');
                    $rights.filter('[data-item-index="' + newIndex + '"]').addClass('active');
                },
            },
        });
    }

    /* ==================================================================
       ELEMENTOR FRONTEND INTEGRATION
       ================================================================== */
    function onElementorFrontendInit() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/mw_fabrication_widget.default',
            function ($widget) {
                initWidget($widget);
            }
        );
    }

    /* ------------------------------------------------------------------
       Bootstrap
       ------------------------------------------------------------------ */
    if (typeof elementorFrontend !== 'undefined') {
        if (elementorFrontend.isEditMode && elementorFrontend.isEditMode()) {
            /* Elementor editor: hook into preview init */
            $(window).on('elementor/frontend/init', onElementorFrontendInit);
        } else {
            $(window).on('elementor/frontend/init', onElementorFrontendInit);
        }
    } else {
        /* Non-Elementor fallback */
        $(document).ready(function () {
            $('.mw-fabrication-widget').each(function () {
                initWidget($(this));
            });
        });
    }

    /* ------------------------------------------------------------------
       Expose for programmatic use if needed
       ------------------------------------------------------------------ */
    window.MWFabricationWidget = {
        init:    initWidget,
        swipers: MWSwipers,
    };

})(jQuery);
