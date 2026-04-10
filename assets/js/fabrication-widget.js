/**
 * MW Custom Tab for Elementor — fabrication-widget.js
 * Version: 1.2.0
 *
 * Style One  : vertical tab list → image + description panel (unchanged)
 * Style Two  : top category tabs → [left: item tabs] [middle: per-item carousel/image] [right: per-slide text]
 *
 * KEY FIXES (v1.2):
 *  1. Swiper is never initialised on a hidden (display:none) element.
 *     Each item's Swiper is created lazily the FIRST TIME that item's panel
 *     becomes visible. This ensures Swiper can always measure dimensions.
 *  2. After tab switch, swiper.update() is called so the newly visible
 *     carousel recalculates its layout and shows navigation/pagination.
 */

(function ($) {
    'use strict';

    /* ------------------------------------------------------------------
       Swiper instance registry
       Key: "widgetId-cat-{catIndex}-item-{itemIndex}"
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
       STYLE ONE — Vertical Tabs (unchanged)
       ================================================================== */
    function initStyleOne($widget) {
        var $navItems = $widget.find('.mw-tab-nav-item');
        var $panels   = $widget.find('.mw-tab-panel');

        if (!$navItems.length) return;

        $navItems.on('click', function () {
            activateStyleOneTab($(this), $navItems, $panels);
        });

        $navItems.on('keydown', function (e) {
            var $items = $navItems;
            var curIdx = $items.index($(this));
            var target = -1;

            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                e.preventDefault(); target = (curIdx + 1) % $items.length;
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                e.preventDefault(); target = (curIdx - 1 + $items.length) % $items.length;
            } else if (e.key === 'Home') {
                e.preventDefault(); target = 0;
            } else if (e.key === 'End') {
                e.preventDefault(); target = $items.length - 1;
            }

            if (target >= 0) { $items.eq(target).trigger('click').focus(); }
        });
    }

    function activateStyleOneTab($clicked, $navItems, $panels) {
        var index = $clicked.data('index');

        $navItems.removeClass('active').attr('aria-selected', 'false').attr('tabindex', '-1');
        $panels.removeClass('active');
        $clicked.addClass('active').attr('aria-selected', 'true').attr('tabindex', '0');
        $panels.filter('[data-index="' + index + '"]').addClass('active');
    }

    /* ==================================================================
       STYLE TWO — Category tabs + per-item sliders
       ================================================================== */
    function initStyleTwo($widget, display) {
        var $topBtns   = $widget.find('.mw-top-tab-btn');
        var $catPanels = $widget.find('.mw-cat-panel');

        if (!$topBtns.length && !$catPanels.length) return;

        /* ---- Desktop: top tab click (unchanged) ---- */
        $topBtns.on('click', function () {
            var $this    = $(this);
            var catIndex = $this.data('cat-index');

            $topBtns.removeClass('active').attr('aria-selected', 'false');
            $catPanels.removeClass('active');
            $this.addClass('active').attr('aria-selected', 'true');

            var $activePanel = $catPanels.filter('[data-cat-index="' + catIndex + '"]');
            $activePanel.addClass('active');
            initPanel($activePanel, display, $widget);
        });

        $topBtns.on('keydown', function (e) {
            var $items = $topBtns;
            var curIdx = $items.index($(this));
            var target = -1;
            if (e.key === 'ArrowRight') { e.preventDefault(); target = (curIdx + 1) % $items.length; }
            else if (e.key === 'ArrowLeft') { e.preventDefault(); target = (curIdx - 1 + $items.length) % $items.length; }
            else if (e.key === 'Home') { e.preventDefault(); target = 0; }
            else if (e.key === 'End') { e.preventDefault(); target = $items.length - 1; }
            if (target >= 0) { $items.eq(target).trigger('click').focus(); }
        });

        /* ---- Mobile accordion: build headers + handle clicks ---- */
        function isMobile() {
            return window.innerWidth <= 576;
        }

        function buildAccordions() {
            /* Only run once */
            if ($widget.data('mw-accordion-built')) return;
            $widget.data('mw-accordion-built', true);

            $catPanels.each(function () {
                var $panel    = $(this);
                var catIndex  = $panel.data('cat-index');

                /* Find the matching category name from the top tab button */
                var catName = $widget
                    .find('.mw-top-tab-btn[data-cat-index="' + catIndex + '"]')
                    .text().trim();

                /* Wrap the existing panel content in .mw-accordion-body */
                $panel.wrapInner('<div class="mw-accordion-body"></div>');

                /* Prepend the accordion header */
                var $header = $(
                    '<div class="mw-accordion-header" role="button" tabindex="0" aria-expanded="false">' +
                    '<span class="mw-accordion-title">' + catName + '</span>' +
                    '<svg class="mw-accordion-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                    '<polyline points="6 9 12 15 18 9"></polyline>' +
                    '</svg>' +
                    '</div>'
                );
                $panel.prepend($header);
            });

            /* Click handler for accordion headers */
            $widget.find('.mw-accordion-header').on('click keydown', function (e) {
                if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
                e.preventDefault();

                var $header   = $(this);
                var $panel    = $header.closest('.mw-cat-panel');
                var $body     = $panel.find('.mw-accordion-body').first();
                var isOpen    = $body.hasClass('open');

                /* Close all */
                $widget.find('.mw-accordion-header').removeClass('active').attr('aria-expanded', 'false');
                $widget.find('.mw-accordion-body').removeClass('open');

                /* Open this one if it was closed */
                if (!isOpen) {
                    $header.addClass('active').attr('aria-expanded', 'true');
                    $body.addClass('open');

                    /* Init sub-tabs + Swiper for this panel now that it's visible */
                    initPanel($panel, display, $widget);

                    /* Also refresh any already-created Swipers inside */
                    $panel.find('.mw-carousel-wrapper').each(function () {
                        var $c        = $(this);
                        var itemIndex = parseInt($c.closest('.mw-item-image-panel').data('item-index'), 10);
                        var catIndex  = $panel.data('cat-index');
                        var widgetId  = $widget.attr('id') || 'mw-widget';
                        var key       = widgetId + '-cat-' + catIndex + '-item-' + itemIndex;
                        if (MWSwipers[key]) { MWSwipers[key].update(); }
                    });
                }
            });

            /* Open first accordion by default */
            $widget.find('.mw-accordion-header').first().trigger('click');
        }

        /* ---- Responsive switch ---- */
        function applyResponsiveMode() {
            if (isMobile()) {
                buildAccordions();
            } else {
                /* Desktop: init first active panel normally */
                var $firstPanel = $catPanels.filter('.active').first();
                if ($firstPanel.length) {
                    initPanel($firstPanel, display, $widget);
                }
            }
        }

        applyResponsiveMode();

        /* Re-evaluate on resize (debounced) */
        var resizeTimer;
        $(window).on('resize.mwFab', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                applyResponsiveMode();
            }, 150);
        });
    }

    /* ------------------------------------------------------------------
       Init a category panel (lazy, once per panel)
       ------------------------------------------------------------------ */
    function initPanel($panel, display, $widget) {
        if ($panel.data('mw-panel-init')) return;
        $panel.data('mw-panel-init', true);

        var catIndex  = $panel.data('cat-index');
        var $subItems = $panel.find('.mw-sub-tab-item');

        if (!$subItems.length) return;

        /* Init the Swiper for the FIRST (already-visible) item only.
           All other items get their Swiper created lazily on first click.
           This guarantees Swiper always measures a visible element.        */
        if (display === 'carousel') {
            var $firstImagePanel = $panel.find('.mw-item-image-panel.active').first();
            if ($firstImagePanel.length) {
                initItemSwiper($firstImagePanel, catIndex, $panel, $widget);
            }
        }

        /* -----------------------------------------------------------
           Sub-tab (item) click handler
           - Only middle (.mw-item-image-panel) and right
             (.mw-item-right-panel) columns change.
           - Left column is NEVER touched.
           ----------------------------------------------------------- */
        $subItems.on('click', function () {
            var $this     = $(this);
            var itemIndex = $this.data('item-index');

            /* Update tab ARIA states */
            $subItems.removeClass('active').attr('aria-selected', 'false').attr('tabindex', '-1');
            $this.addClass('active').attr('aria-selected', 'true').attr('tabindex', '0');

            /* Switch middle column */
            $panel.find('.mw-item-image-panel').removeClass('active');
            var $newImagePanel = $panel.find('.mw-item-image-panel[data-item-index="' + itemIndex + '"]');
            $newImagePanel.addClass('active');

            /* Switch right column */
            $panel.find('.mw-item-right-panel').removeClass('active');
            $panel.find('.mw-item-right-panel[data-item-index="' + itemIndex + '"]').addClass('active');

            /* Carousel mode: lazy-init this item's Swiper if not yet done,
               then update so it recalculates layout now that it is visible. */
            if (display === 'carousel') {
                initItemSwiper($newImagePanel, catIndex, $panel, $widget);

                /* Force Swiper to recalculate now the container is visible */
                var widgetId = $widget.attr('id') || 'mw-widget';
                var key      = widgetId + '-cat-' + catIndex + '-item-' + itemIndex;
                if (MWSwipers[key]) {
                    MWSwipers[key].update();
                }
            }
        });

        /* Vertical keyboard nav — sub-tabs */
        $subItems.on('keydown', function (e) {
            var $items = $subItems;
            var curIdx = $items.index($(this));
            var target = -1;

            if (e.key === 'ArrowDown') { e.preventDefault(); target = (curIdx + 1) % $items.length; }
            else if (e.key === 'ArrowUp') { e.preventDefault(); target = (curIdx - 1 + $items.length) % $items.length; }
            else if (e.key === 'Home') { e.preventDefault(); target = 0; }
            else if (e.key === 'End') { e.preventDefault(); target = $items.length - 1; }

            if (target >= 0) { $items.eq(target).trigger('click').focus(); }
        });
    }

    /* ------------------------------------------------------------------
       Lazily init a single item's Swiper.
       Called only when the item's image panel is visible.
       ------------------------------------------------------------------ */
    function initItemSwiper($imagePanel, catIndex, $panel, $widget) {
        /* Skip if already initialised for this panel instance */
        if ($imagePanel.data('mw-swiper-init')) return;

        if (typeof Swiper === 'undefined') {
            console.warn('MW Custom Tab: Swiper.js is not available.');
            return;
        }

        var $carousel = $imagePanel.find('.mw-carousel-wrapper');
        if (!$carousel.length) return;

        $imagePanel.data('mw-swiper-init', true);

        var itemIndex = parseInt($imagePanel.data('item-index'), 10);
        var widgetId  = $widget.attr('id') || 'mw-widget';
        var key       = widgetId + '-cat-' + catIndex + '-item-' + itemIndex;

        /* Destroy any stale instance (e.g. after Elementor widget re-render) */
        if (MWSwipers[key]) {
            MWSwipers[key].destroy(true, true);
            delete MWSwipers[key];
        }

        /* The right-column panel that belongs to this item */
        var $rightPanel = $panel.find(
            '.mw-item-right-panel[data-item-index="' + itemIndex + '"]'
        );

        MWSwipers[key] = new Swiper($carousel[0], {
            slidesPerView : 1,
            loop          : false,
            speed         : 400,
            a11y: {
                enabled         : true,
                prevSlideMessage: 'Previous slide',
                nextSlideMessage: 'Next slide',
            },
            pagination: {
                el       : $carousel.find('.swiper-pagination')[0] || null,
                clickable: true,
            },
            navigation: {
                nextEl: $carousel.find('.swiper-button-next')[0] || null,
                prevEl: $carousel.find('.swiper-button-prev')[0] || null,
            },
            on: {
                /* Only slide text in THIS item's right panel changes.
                   Left column (item tabs) is never touched.           */
                slideChange: function () {
                    var slideIdx = this.activeIndex;
                    $rightPanel.find('.mw-slide-text').removeClass('active');
                    $rightPanel
                        .find('.mw-slide-text[data-slide-index="' + slideIdx + '"]')
                        .addClass('active');
                },
            },
        });
    }

    /* ==================================================================
       ELEMENTOR FRONTEND INTEGRATION
       ================================================================== */
    function onElementorFrontendInit() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/widget',
            function ($widget) {
                $widget.find('.mw-fabrication-widget').each(function () {
                    initWidget($(this));
                });
            }
        );
    }

    /* Bootstrap */
    if (typeof elementorFrontend !== 'undefined') {
        $(window).on('elementor/frontend/init', onElementorFrontendInit);
    } else {
        $(document).ready(function () {
            $('.mw-fabrication-widget').each(function () {
                initWidget($(this));
            });
        });
    }

    window.MWFabricationWidget = {
        init   : initWidget,
        swipers: MWSwipers,
    };

})(jQuery);
