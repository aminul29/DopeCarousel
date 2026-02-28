(function () {
  function toNumber(value, fallback) {
    var parsed = Number(value);
    return Number.isFinite(parsed) ? parsed : fallback;
  }

  function safeDeviceValue(obj, key, fallback) {
    if (!obj || typeof obj !== 'object') {
      return fallback;
    }
    return toNumber(obj[key], fallback);
  }

  function parseConfig(root) {
    var raw = root.getAttribute('data-dc-config');
    if (!raw) {
      return null;
    }

    try {
      return JSON.parse(raw);
    } catch (error) {
      return null;
    }
  }

  function buildOptions(root, config) {
    var layout = config.layout || 'slider';
    var style = config.style || 'slide';
    var isTicker = style === 'ticker';

    if (style === 'fade' && layout !== 'slider') {
      style = 'slide';
    }

    var rows = layout === 'double_row' ? 2 : 1;

    var slidesPerViewMobile = safeDeviceValue(config.slidesPerView, 'mobile', 1);
    var slidesPerViewTablet = safeDeviceValue(config.slidesPerView, 'tablet', 2);
    var slidesPerViewDesktop = safeDeviceValue(config.slidesPerView, 'desktop', 3);

    var spaceMobile = safeDeviceValue(config.spaceBetween, 'mobile', 12);
    var spaceTablet = safeDeviceValue(config.spaceBetween, 'tablet', 18);
    var spaceDesktop = safeDeviceValue(config.spaceBetween, 'desktop', 24);

    var speed = toNumber(config.speed, 700);
    var tickerSpeed = toNumber(config.tickerSpeed, 4500);

    var options = {
      slidesPerView: slidesPerViewMobile,
      spaceBetween: spaceMobile,
      speed: isTicker ? tickerSpeed : speed,
      loop: isTicker ? true : Boolean(config.loop),
      allowTouchMove: config.drag !== false,
      grabCursor: config.drag !== false,
      watchOverflow: true,
      effect: style === 'fade' ? 'fade' : 'slide',
      breakpoints: {
        768: {
          slidesPerView: slidesPerViewTablet,
          spaceBetween: spaceTablet,
          grid: {
            rows: rows,
            fill: 'row',
          },
        },
        1025: {
          slidesPerView: slidesPerViewDesktop,
          spaceBetween: spaceDesktop,
          grid: {
            rows: rows,
            fill: 'row',
          },
        },
      },
      grid: {
        rows: rows,
        fill: 'row',
      },
    };

    if (style === 'fade') {
      options.fadeEffect = {
        crossFade: true,
      };
    }

    if (isTicker) {
      options.autoplay = {
        delay: 0,
        disableOnInteraction: false,
        reverseDirection: config.tickerDirection === 'reverse',
        pauseOnMouseEnter: false,
      };
      options.freeMode = {
        enabled: true,
        momentum: false,
      };
    } else if (config.autoplay) {
      options.autoplay = {
        delay: toNumber(config.autoplayDelay, 3000),
        disableOnInteraction: false,
        pauseOnMouseEnter: Boolean(config.pauseOnHover),
      };
    }

    if (config.arrows && config.selectors && config.selectors.next && config.selectors.prev) {
      options.navigation = {
        nextEl: config.selectors.next,
        prevEl: config.selectors.prev,
      };
    }

    if (config.dots && config.selectors && config.selectors.pagination) {
      options.pagination = {
        el: config.selectors.pagination,
        clickable: true,
      };
    }

    if (root.classList.contains('dc-carousel--style-ticker')) {
      root.classList.add('dc-carousel--ticker-ready');
    }

    return options;
  }

  function buildTickerRowOptions(config, reverseDirection) {
    var slidesPerViewMobile = safeDeviceValue(config.slidesPerView, 'mobile', 1);
    var slidesPerViewTablet = safeDeviceValue(config.slidesPerView, 'tablet', 2);
    var slidesPerViewDesktop = safeDeviceValue(config.slidesPerView, 'desktop', 3);

    var spaceMobile = safeDeviceValue(config.spaceBetween, 'mobile', 12);
    var spaceTablet = safeDeviceValue(config.spaceBetween, 'tablet', 18);
    var spaceDesktop = safeDeviceValue(config.spaceBetween, 'desktop', 24);

    var tickerSpeed = toNumber(config.tickerSpeed, 4500);

    return {
      slidesPerView: slidesPerViewMobile,
      spaceBetween: spaceMobile,
      speed: tickerSpeed,
      loop: true,
      allowTouchMove: config.drag !== false,
      grabCursor: config.drag !== false,
      watchOverflow: true,
      autoplay: {
        delay: 0,
        disableOnInteraction: false,
        reverseDirection: reverseDirection,
        pauseOnMouseEnter: false,
      },
      freeMode: {
        enabled: true,
        momentum: false,
      },
      breakpoints: {
        768: {
          slidesPerView: slidesPerViewTablet,
          spaceBetween: spaceTablet,
        },
        1025: {
          slidesPerView: slidesPerViewDesktop,
          spaceBetween: spaceDesktop,
        },
      },
    };
  }

  function initAlternateTicker(root, config) {
    var topSwiperElement = root.querySelector('.dc-carousel__swiper--top');
    var bottomSwiperElement = root.querySelector('.dc-carousel__swiper--bottom');

    if (!topSwiperElement || !bottomSwiperElement) {
      return false;
    }

    var topReversed = config.tickerDirection === 'reverse';
    var bottomReversed = !topReversed;

    root.dcSwiperTop = new window.Swiper(topSwiperElement, buildTickerRowOptions(config, topReversed));
    root.dcSwiperBottom = new window.Swiper(bottomSwiperElement, buildTickerRowOptions(config, bottomReversed));
    root.classList.add('dc-carousel--ticker-ready');

    return true;
  }

  function destroyCarousel(root) {
    if (!root) {
      return;
    }

    ['dcSwiper', 'dcSwiperTop', 'dcSwiperBottom'].forEach(function (key) {
      if (root[key] && typeof root[key].destroy === 'function') {
        root[key].destroy(true, true);
      }
      root[key] = null;
    });

    root.dataset.ready = '0';
    root.classList.remove('dc-carousel--ticker-ready');
  }

  function initCarousel(root, forceReinit) {
    if (!root) {
      return;
    }

    if (forceReinit) {
      destroyCarousel(root);
    }

    if (root.dataset.ready === '1') {
      return;
    }

    if (typeof window.Swiper !== 'function') {
      return;
    }

    var config = parseConfig(root);
    if (!config) {
      return;
    }

    if (config.alternateTickerRows) {
      if (initAlternateTicker(root, config)) {
        root.dataset.ready = '1';
        return;
      }
    }

    var swiperElement = root.querySelector('.dc-carousel__swiper');
    if (!swiperElement) {
      return;
    }

    var options = buildOptions(root, config);
    root.dataset.ready = '1';
    root.dcSwiper = new window.Swiper(swiperElement, options);
  }

  function initScope(scope) {
    var root = scope || document;
    root.querySelectorAll('.dc-carousel').forEach(function (carouselRoot) {
      initCarousel(carouselRoot, false);
    });
  }

  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/dope_carousel.default', function ($scope) {
      if (!$scope || !$scope[0]) {
        return;
      }

      $scope[0].querySelectorAll('.dc-carousel').forEach(function (carouselRoot) {
        initCarousel(carouselRoot, true);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initScope(document);
  });
})();
