/*-----------------------------------------------------------------------------------

Theme Name: Gerold - Personal Portfolio Tailwind CSS Template
Theme URI: https://themejunction.net/
Author: Theme-Junction
Author URI: https://themeforest.net/user/theme-junction
Description: Gerold - Personal Portfolio Tailwind CSS Template

-----------------------------------------------------------------------------------

/***************************************************
==================== JS INDEX ======================
****************************************************
WoW Js
mobile toggle button
Sticky Header Js
Fun Fact Js
Services Hover Js
Portfolio Filter Js
Testimonial Carousel Js
Post Carousel Js
Portfolio Carousel Js
Nice Select Js
All Popup Js
GSAP
Lenis Scroll Js
Preloader
Portfolio Filter Js
indexing and active link

****************************************************/

(function ($) {
    "use strict";

    var windowSize = $(window).width();

    $(window).on("load", function () {
        // Contact Form Js
        if ($("#contact-form").length > 0) {
            $("#contact-form").validate({
                rules: {
                    conName: "required",
                    conEmail: {
                        required: true,
                        email: true,
                    },
                },
                messages: {
                    conName: "Enter your name.",
                    conEmail: "Enter a valid email.",
                },
                submitHandler: function (form) {
                    // start ajax request
                    $.ajax({
                        type: "POST",
                        url: "assets/mail/contact-form.php",
                        data: $("#contact-form").serialize(),
                        cache: false,
                        success: function (data) {
                            if (data == "Y") {
                                window.alert("Message sent successfully!");
                                $("#contact-form").trigger("reset");
                            } else {
                                window.alert("Message hasn't sent!");
                            }
                        },
                    });
                },
            });
        }
        /*
	============================== Preloader =====================================
	*/
        const svg = document.getElementById("preloaderSvg");

        const preTl = gsap.timeline({
            onComplete: startAnimationAfterPreloader,
        });

        const curve = "M0 502S175 272 500 272s500 230 500 230V0H0Z";
        const flat = "M0 2S175 1 500 1s500 1 500 1V0H0Z";

        preTl.to(".preloader-heading .load-text , .preloader-heading .cont", {
            delay: 0.15,
            y: -100,
            opacity: 0,
        });
        preTl
            .to(svg, {
                duration: 0.5,
                attr: { d: curve },
                ease: "power2.easeIn",
            })
            .to(svg, {
                duration: 0.5,
                attr: { d: flat },
                ease: "power2.easeOut",
            });
        preTl.to(".preloader", {
            y: -1500,
        });
        preTl.to(".preloader", {
            zIndex: -1,
            display: "none",
        });

        let svgText = document.querySelector(
            ".hero-section .intro_text svg text"
        );
        let heroAnimation = document.querySelector(".heroAnimation");

        function startAnimationAfterPreloader() {
            if (svgText) {
                // Add a class or directly apply styles to trigger the stroke animation
                svgText.classList.add("animate-stroke");
            }

            if (heroAnimation) {
                // Add a class or directly apply styles to trigger the stroke animation
                heroAnimation.classList.add("activeAnimation");
                heroAreaAnimation();
            }
        }
        // WoW Js
        var wow = new WOW({
            boxClass: "wow", // default
            animateClass: "animated", // default
            offset: 100, // default
            mobile: true, // default
            live: true, // default
        });
        wow.init();
        // mobile toggle button
        const mobileMenus = document.querySelectorAll(".mobile-menu");
        if (mobileMenus?.length) {
            const mobileMenuToggleButtons =
                document.querySelectorAll(".menu-bar button");
            mobileMenuToggleButtons?.forEach((mobileMenuToggleButton, idx) => {
                mobileMenuToggleButton.addEventListener("click", function () {
                    mobileMenuToggleButton.classList.toggle("active");
                    mobileMenus.forEach((mobileMenu, idx2) => {
                        if (idx === idx2) {
                            mobileMenu.classList.toggle("active");
                        }
                    });
                });
            });
        }

        // Sticky Header Js

        var lastScrollTop = 0;
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();

            if (scroll > 300) {
                $(".header-area.header-sticky").addClass("sticky");
                $(".header-area.header-sticky").removeClass("sticky-out");
            } else if (scroll < lastScrollTop) {
                if (scroll < 500) {
                    $(".header-area.header-sticky").addClass("sticky-out");
                    $(".header-area.header-sticky").removeClass("sticky");
                }
            } else {
                $(".header-area.header-sticky").removeClass("sticky");
            }

            lastScrollTop = scroll;
        });

        // Fun Fact Js
        if ($(".funfact-item .odometer").length > 0) {
            $(".funfact-item .odometer").appear(function () {
                var odo = $(".funfact-item .odometer");
                odo.each(function () {
                    var countNumber = $(this).attr("data-count");

                    $(this).html(countNumber);
                });
            });
        }
        // Fun Fact Js
        if ($(".funfact-item-2 .odometer").length > 0) {
            $(".funfact-item-2 .odometer").appear(function () {
                var odo = $(".funfact-item-2 .odometer");
                odo.each(function () {
                    var countNumber = $(this).attr("data-count");

                    $(this).html(countNumber);
                });
            });
        }
        //  Hover Bg Animation Js
        function bg_animation(parent, item) {
            const itemParent = $(parent);

            if (itemParent.length) {
                var active_bg = $(`${parent} .active-bg`);
                var element = $(`${parent} .current`);
                $(`${parent} ${item}`).on("mouseenter", function () {
                    var e = $(this);
                    activebg(active_bg, e, parent, item);
                    $(`${parent} ${item}`).removeClass("current");
                    $(this).addClass("current");
                });
                $(`${parent}`).on("mouseleave", function () {
                    element = $(`${parent} .current`);
                    activebg(active_bg, element, parent, item);
                    element.closest(`${item}`).siblings().removeClass("mleave");
                });
                activebg(active_bg, element, parent, item);
            }
        }
        bg_animation(".services-widget", ".service-item");
        bg_animation(".blog-widget", ".blog-item");

        function activebg(active_bg, e, parent, item) {
            if (!e.length) {
                return false;
            }
            var topOff = e.offset().top;
            var height = e.outerHeight();
            var menuTop = $(`${parent}`).offset().top;
            e.closest(`${item}`).removeClass("mleave");
            e.closest(`${item}`).siblings().addClass("mleave");
            active_bg.css({
                top: topOff - menuTop + "px",
                height: height + "px",
            });
        }

        // Portfolio Filter Js
        $(".portfolio-box").imagesLoaded(function () {
            var $grid = $(".portfolio-box").isotope({
                // options
                masonry: {
                    columnWidth: ".portfolio-box .portfolio-sizer",
                    gutter: ".portfolio-box .gutter-sizer",
                },
                itemSelector: ".portfolio-box .portfolio-item",
                percentPosition: true,
            });

            // filter items on button click
            $(".filter-button-group").on("click", "button", function () {
                $(".filter-button-group button").removeClass("active");
                $(this).addClass("active");

                var filterValue = $(this).attr("data-filter");
                $grid.isotope({ filter: filterValue });
            });
        });

        // Testimonial Carousel Js
        $(".testimonial-carousel.owl-carousel").owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: true,
            autoplay: false,
            active: true,
            rtl: true,
            smartSpeed: 1000,
            autoplayTimeout: 7000,
            responsive: {
                0: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 2,
                },
            },
        });

        // Testimonial Slider Js
        if ($(".tj-testimonial-slider").length > 0) {
            var brand = new Swiper(".tj-testimonial-slider", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 6000,
                },
                speed: 3000,
                pagination: {
                    el: ".testimonial-pagination",
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                },
            });
        }

        if ($(".testimonial-carousel-h5").length > 0) {
            $(".testimonial-carousel-h5").owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: true,
                autoplay: false,
                active: true,
                smartSpeed: 1000,
                autoplayTimeout: 7000,
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: 1,
                    },
                    768: {
                        items: 1,
                    },
                    992: {
                        items: 2,
                    },
                    1000: {
                        items: 2,
                    },
                },
            });
        }
        // testimonial Slider Js
        if ($(".testimonial-slider-6").length > 0) {
            var testimonial = new Swiper(".testimonial-slider-6", {
                slidesPerView: 4,
                spaceBetween: 30,
                centeredSlides: true,
                loop: true,
                speed: 2000,
                autoplay: {
                    delay: 2000,
                },
                navigation: {
                    prevEl: ".testimonial-prev",
                    nextEl: ".testimonial-next",
                },
                pagination: {
                    el: ".testimonial-pagination",
                    clickable: true,
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    576: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 1.5,
                    },
                    992: {
                        slidesPerView: 1.5,
                    },
                    1200: {
                        slidesPerView: 2.5,
                    },
                    1440: {
                        slidesPerView: 3.5,
                    },
                },
            });
        }
        // Home 7 testimonial Slider Js
        if ($(".tj-testimonial-7-active")) {
            var testimonialSwiper = new Swiper(".tj-testimonial-7-active", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                centeredSlides: true,
                autoplay: {
                    delay: 2000,
                },
                speed: 3000,
                pagination: {
                    el: ".testimonial-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".slider-next",
                    prevEl: ".slider-prev",
                },
            });
        }
        // Testimonial Slider Js
        if ($(".testimonial-slider-8").length > 0) {
            var brand = new Swiper(".testimonial-slider-8", {
                slidesPerView: 3,
                spaceBetween: 30,
                active: true,
                loop: true,
                autoplay: {
                    delay: 6000,
                },
                speed: 3000,
                pagination: {
                    el: ".testimonial-pagination",
                    clickable: true,
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    576: {
                        slidesPerView: 1.5,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        }

        // Home 9 testimonial Slider  Js
        if ($(".testimonial-slider-9").length > 0) {
            var slider = new Swiper(".testimonial-slider-9", {
                direction: "vertical",
                slidesPerView: "auto",
                spaceBetween: 30,
                centeredSlides: true,
                loop: true,
                speed: 1500,
                autoplay: {
                    delay: 3000,
                },
            });
        }

        // Portfolio Slider js
        if ($(".portfolio-slider-5").length > 0) {
            var portfolio = new Swiper(".portfolio-slider-5", {
                spaceBetween: 30,
                autoplay: {
                    delay: 8500,
                },
                speed: 3000,
                pagination: {
                    el: ".portfolio-pagination",
                    clickable: true,
                },
                loop: true,
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 1.5,
                    },
                    992: {
                        slidesPerView: 2.5,
                    },
                    1200: {
                        slidesPerView: 2.5,
                    },
                    1400: {
                        slidesPerView: 2.5,
                    },
                },
            });
        }

        // Post Carousel Js
        $(".tj-post__gallery.owl-carousel").owlCarousel({
            items: 1,
            loop: true,
            margin: 30,
            dots: false,
            nav: true,
            navText: [
                '<i class="fal fa-arrow-left"></i>',
                '<i class="fal fa-arrow-right"></i>',
            ],
            autoplay: false,
            smartSpeed: 1000,
            autoplayTimeout: 3000,
        });
        // Portfolio Carousel Js
        $(".portfolio_gallery.owl-carousel").owlCarousel({
            items: 2,
            loop: true,
            lazyLoad: true,
            center: true,
            // autoWidth: true,
            autoplayHoverPause: true,
            autoplay: false,
            autoplayTimeout: 5000,
            smartSpeed: 800,
            margin: 30,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1,
                    margin: 0,
                },
                768: {
                    items: 2,
                    margin: 20,
                },
                992: {
                    items: 2,
                    margin: 30,
                },
            },
        });
        // Project Slider Js
        if ($(".portfolio-slider-6").length > 0) {
            var slider = new Swiper(".portfolio-slider-6", {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: true,
                speed: 2000,
                autoplay: true,
                centeredSlides: true,
                navigation: {
                    prevEl: ".portfolio-slider-6-prev",
                    nextEl: ".portfolio-slider-6-next",
                },
                pagination: {
                    el: ".portfolio-pagination",
                    clickable: true,
                },
                breakpoints: {
                    1200: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 1.5,
                    },
                    576: {
                        slidesPerView: 1,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }

        // Brand Slider Js
        if ($(".brand-slider").length > 0) {
            var brand = new Swiper(".brand-slider", {
                slidesPerView: 6,
                spaceBetween: 30,
                loop: false,
                breakpoints: {
                    320: {
                        slidesPerView: 2,
                    },
                    576: {
                        slidesPerView: 3,
                    },
                    640: {
                        slidesPerView: 3,
                    },
                    768: {
                        slidesPerView: 4,
                    },
                    992: {
                        slidesPerView: 5,
                    },
                    1024: {
                        slidesPerView: 6,
                    },
                },
            });
        }

        if ($(".tj-marquee--1")?.length) {
            //rold marquee
            let SwiperTop = new Swiper(".tj-marquee--1", {
                loop: true,
                slidesPerView: "auto",
                allowTouchMove: false,
                spaceBetween: 0,
                centeredSlides: true,
                loop: true,
                speed: 4000,
                freemode: true,
                autoplay: {
                    delay: 0,
                },
            });
        }
        // Marquee slider Js
        if ($(".tj-marquee--2").length > 0) {
            var swiper = new Swiper(".tj-marquee--2", {
                slidesPerView: "auto",
                spaceBetween: 80,
                loop: true,
                speed: 4000,
                freemode: true,
                centeredSlides: true,
                allowTouchMove: false,
                breakpoints: {
                    320: {
                        spaceBetween: 40,
                    },
                    768: {
                        spaceBetween: 40,
                    },
                    992: {
                        spaceBetween: 40,
                    },
                    1024: {
                        spaceBetween: 80,
                    },
                },

                autoplay: {
                    delay: 0,
                },
            });
        }

        // Marquee slider Js
        if ($(".tj-marquee--3").length > 0) {
            var swiper = new Swiper(".tj-marquee--3", {
                slidesPerView: "auto",
                spaceBetween: 20,
                centeredSlides: true,
                freemode: true,
                loop: true,
                speed: 4000,
                allowTouchMove: false,
                autoplay: {
                    delay: 0,
                },
            });
        }
        // Services Slider Js
        if ($(".service-slider").length > 0) {
            var service = new Swiper(".service-slider", {
                slidesPerView: 3,
                spaceBetween: 30,
                loop: true,
                centeredSlides: true,
                speed: 2000,
                autoplay: {
                    delay: 2000,
                },
                navigation: {
                    prevEl: ".service-prev",
                    nextEl: ".service-next",
                },
                pagination: {
                    el: ".service-pagination",
                    clickable: true,
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                    },
                    430: {
                        slidesPerView: 1.2,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 2,
                    },
                    1200: {
                        slidesPerView: 3,
                    },
                    1400: {
                        slidesPerView: 3,
                    },
                },
            });
        }

        // Nice Select Js
        $("select").niceSelect();

        // All Popup Js
        if ($(".popup_video").length > 0) {
            $(`.popup_video`).lightcase({
                transition: "elastic",
                showSequenceInfo: false,
                slideshow: false,
                swipe: true,
                showTitle: false,
                showCaption: false,
                controls: true,
            });
        }
    });
    /*****************************************************************
================================= GSAP ====================================
********************************************************************/
    gsap.registerPlugin(ScrollTrigger, TweenMax, ScrollToPlugin);

    gsap.config({
        nullTargetWarn: false,
    });

    // Lenis Scroll Js

    /*
============================== Lenis Scroll Js =====================================
*/
    const lenis = new Lenis();
    lenis.on("scroll", ScrollTrigger.update);
    gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
    });
    gsap.ticker.lagSmoothing(0);

    const smoothScroll = () => {
        var links = document.querySelectorAll('a[href^="#"]');
        if (!links.length) {
            return;
        }
        links.forEach(function (link) {
            link.addEventListener("click", function (e) {
                e.preventDefault();

                var targetId = this.getAttribute("href").substring(1);

                var targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: "smooth" });
                } else {
                    window.scroll({ top: 0, left: 0, behavior: "smooth" });
                }
            });
        });
    };

    smoothScroll();

    // Portfolio Filter Js
    $(".portfolio-box").imagesLoaded(function () {
        var $grid = $(".portfolio-box").isotope({
            // options
            masonry: {
                columnWidth: ".portfolio-box .portfolio-sizer",
                gutter: ".portfolio-box .gutter-sizer",
            },
            itemSelector: ".portfolio-box .portfolio-item",
            percentPosition: true,
        });

        // filter items on button click
        $(".filter-button-group").on("click", "button", function () {
            $(".filter-button-group button").removeClass("active");
            $(this).addClass("active");

            var filterValue = $(this).attr("data-filter");
            $grid.isotope({ filter: filterValue });
        });
    });

    const buttonGroup = document.querySelector(
        ".portfolio-filter .button-group"
    );
    function buttonBgAnimation() {
        const activeBg = document.querySelector(
            ".portfolio-filter .button-group .active-bg"
        );
        const buttons = document.querySelectorAll(
            ".portfolio-filter .button-group button"
        );

        const activeFilterBtn = (activeBg, element) => {
            if (activeBg && element) {
                const rect = element.getBoundingClientRect();
                const parentRect = buttonGroup.getBoundingClientRect();

                activeBg.style.width = `${rect.width}px`;
                activeBg.style.height = `${rect.height}px`;
                activeBg.style.left = `${rect.left - parentRect.left}px`;
                activeBg.style.top = `${rect.top - parentRect.top}px`;
            }
        };

        // Event listener for button clicks
        buttons.forEach((button) => {
            button.addEventListener("click", () => {
                // Remove the 'active' class from all buttons
                buttons.forEach((btn) => btn.classList.remove("active"));

                // Add the 'active' class to the clicked button
                button.classList.add("active");

                // Update the position of the active background
                activeFilterBtn(activeBg, button);
            });
        });

        // Initialize with the currently active button
        const activeElement = document.querySelector(
            ".portfolio-filter .button-group .active"
        );
        activeFilterBtn(activeBg, activeElement);
    }

    if (buttonGroup) {
        buttonBgAnimation();
    }

    // indexing and active link
    const sections = document.querySelectorAll("section"); // All sections
    const navLinks = document.querySelectorAll("nav>ul li:has(a) > a"); // All nav links

    window.addEventListener("scroll", () => {
        if (navLinks?.length) {
            let currentSection = "";

            // Loop through sections to find the current one
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                if (window.scrollY >= sectionTop - sectionHeight / 3) {
                    currentSection = section.getAttribute("id");
                }
            });

            // Remove 'active' class from all links and add it to the current one
            navLinks.forEach((link) => {
                link.classList.remove("active");

                if (link.getAttribute("href")?.includes(currentSection)) {
                    link.classList.add("active");
                }
            });
        }
    });
    const themeControllerButton = document.querySelector(".theme-controller");

    // theme controller
    if (themeControllerButton) {
        themeController();
    }

    // accordions

    const buttons = document.querySelectorAll(".accordion-btn");
    if (buttons?.length) {
        buttons.forEach((button, index) => {
            button.addEventListener("click", function () {
                const content = button.nextElementSibling;

                const isOpen = content.classList.contains("open");
                buttons.forEach((button2, index2) => {
                    button2.classList.remove("open");
                });
                document
                    .querySelectorAll(".accordion-content")
                    .forEach((item, i) => {
                        item.style.maxHeight = "0";
                        item.classList.remove("open");
                    });

                if (!isOpen) {
                    content.style.maxHeight = content.scrollHeight + "px";
                    content.classList.add("open");
                    this.classList.add("open");
                }
            });
        });

        document.querySelectorAll(".accordion-content").forEach((item, i) => {
            const isOpen = item.classList.contains("open");
            if (isOpen) {
                item.style.maxHeight = item.scrollHeight + "px";
            }
        });
    }

    // Anim Js
    const target = document.getElementById("anim");

    if (target) {
        function splitTextToSpans(targetElement) {
            if (targetElement) {
                const text = targetElement.textContent;
                targetElement.innerHTML = "";
                for (let character of text) {
                    const span = document.createElement("span");
                    if (character === " ") {
                        span.innerHTML = "&nbsp;";
                    } else {
                        span.textContent = character;
                    }
                    targetElement.appendChild(span);
                }
            }
        }
        splitTextToSpans(target);
    }

    // Rating Js
    $(document).ready(function () {
        if ($(".fill-ratings span").length > 0) {
            var star_rating_width = $(".fill-ratings span").width();

            $(".star-ratings").width(star_rating_width);
        }
    });

    // // Portfolio filter js
    function getGutterSize() {
        const width = window.innerWidth;
        if (width >= 992 && width <= 1199) {
            return 60;
        } else if (width >= 768 && width <= 991) {
            return 50;
        } else {
            return 125; // default or for other widths
        }
    }

    function initIsotope() {
        var $grid = $(".tj-project-4-wrappper").isotope({
            itemSelector: ".tj-project-4-item",
            percentPosition: true,
            masonry: {
                columnWidth: ".tj-project-4-item",
                gutter: getGutterSize(),
            },
        });
        return $grid;
    }

    $(".tj-project-4-wrappper").imagesLoaded(function () {
        var $grid = initIsotope();

        // Recalculate on window resize
        $(window).on("resize", function () {
            var newGutter = getGutterSize();
            $grid.isotope("option", {
                masonry: {
                    columnWidth: ".tj-project-4-item",
                    gutter: newGutter,
                },
            });
            $grid.isotope("layout");
        });
    });

    // Skillbar Js
    if ($(".progress_bar").length > 0) {
        $(document).ready(function () {
            progress_bar();
        });
        function progress_bar() {
            var speed = 30;
            var items = $(".progress_bar").find(".progress-item");
            items.appear(function () {
                var item = $(this).find(".progress");
                var itemValue = item.data("progress");
                var i = 0;
                var value = $(this);
                var count = setInterval(function () {
                    if (i <= itemValue) {
                        var iStr = i.toString();
                        item.css({
                            width: iStr + "%",
                        });
                        value.find(".item_value").html(iStr + "%");
                    } else {
                        clearInterval(count);
                    }
                    i++;
                }, speed);
            });
        }
    }

    /*
	============================== Hero 04 & 07 Animation =====================================
	*/
    function heroAreaAnimation() {
        let heroArea = $(".heroAnimation.activeAnimation");

        let hero4SubTitle = $(
            ".activeAnimation .tj-hero-4-subtitle, .activeAnimation .tj-hero-7-thumb"
        );
        let hero4Title = $(
            ".activeAnimation .tj-hero-4-title, .activeAnimation .tj-hero-7-content"
        );
        let hero4Thumb = $(
            ".activeAnimation .tj-hero-4-bottom-thumb .img, .activeAnimation .tj-hero-7-button"
        );
        let hero4Shape1 = $(
            ".activeAnimation .tj-hero-4-bottom-thumb-shape-1, .activeAnimation .tj-feature-7-thumb"
        );
        let hero4Shape2 = $(
            ".activeAnimation .tj-hero-4-bottom-thumb-shape-2, .activeAnimation .tj-feature-7-wrapper"
        );
        let hero4Reviews = $(".activeAnimation .tj-hero-4-bottom-reviews");
        let hero4Counter = $(".activeAnimation .tj-hero-4-bottom-counter");

        if (heroArea.length > 0) {
            const heroTl = gsap.timeline();

            if (hero4SubTitle.length > 0) {
                heroTl.from(hero4SubTitle, {
                    y: 50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
            if (hero4Title.length > 0) {
                heroTl.from(hero4Title, {
                    y: 50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
            if (hero4Thumb.length > 0) {
                heroTl.from(hero4Thumb, {
                    y: 50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
            if (hero4Shape1.length > 0) {
                heroTl.from(hero4Shape1, {
                    x: 50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
            if (hero4Shape2.length > 0) {
                heroTl.from(hero4Shape2, {
                    x: -50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
            if (hero4Reviews.length > 0) {
                heroTl.from(hero4Reviews, {
                    x: -50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
            if (hero4Counter.length > 0) {
                heroTl.from(hero4Counter, {
                    x: 50,
                    opacity: 0,
                    duration: 0.5,
                });
            }
        }
    }

    /*
	============================== Title Animation =====================================
	*/
    // splitText
    if ($(".tj-char-animation").length > 0) {
        let char_come = gsap.utils.toArray(".tj-char-animation");
        char_come.forEach((splitTextLine) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: splitTextLine,
                    start: "top 90%",
                    end: "bottom 60%",
                    scrub: false,
                    markers: false,
                    toggleActions: "play none none none",
                },
            });

            const itemSplitted = new SplitText(splitTextLine, {
                type: "chars, words",
            });
            gsap.set(splitTextLine, { perspective: 300 });
            itemSplitted.split({ type: "chars, words" });
            tl.from(itemSplitted.chars, {
                duration: 1,
                delay: 0.5,
                x: 100,
                autoAlpha: 0,
                stagger: 0.05,
            });
        });
    }
    // Text Invert
    if ($(".tj-text-invert").length > 0) {
        const split = new SplitText(".tj-text-invert", { type: "lines" });
        split.lines.forEach((target) => {
            gsap.to(target, {
                backgroundPositionX: 0,
                ease: "none",
                scrollTrigger: {
                    trigger: target,
                    scrub: 1,
                    start: "top 85%",
                    end: "bottom center",
                },
            });
        });
    }

    // Side Bar Sticky Js
    if ($(".tj-sticky").length > 0) {
        var sticky = new Sticky(".tj-sticky");
    }

    if ($(".tj_title_anim")) {
        // line 3d
        let tj_title_anim = gsap.utils.toArray(".tj_title_anim");
        tj_title_anim.forEach((splitTextLine) => {
            var delay_value = 0.5;
            if (splitTextLine.getAttribute("data-delay")) {
                delay_value = splitTextLine.getAttribute("data-delay");
            }
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: splitTextLine,
                    start: "top 90%",
                    duration: 1.5,
                    scrub: false,
                    markers: false,
                    toggleActions: "play none none none",
                },
            });

            const itemSplitted = new SplitText(splitTextLine, {
                type: "lines",
            });
            gsap.set(splitTextLine, {
                perspective: 400,
            });
            itemSplitted.split({
                type: "lines",
            });
            tl.from(itemSplitted.lines, {
                duration: 1,
                delay: delay_value,
                opacity: 0,
                rotationX: -80,
                force3D: true,
                transformOrigin: "top center -50",
                stagger: 0.1,
            });
        });
    }
    // services accordion
    // accordion Js
    $(".accordion-list > li:nth-child(1)")
        .addClass("active")
        .find(".tj-service-5-accordion-wrap")
        .show();
    $(
        ".accordion-list > li:not(:nth-child(1)) .tj-service-5-accordion-wrap"
    ).hide();
    $(".accordion-list > li").click(function () {
        if ($(this).hasClass("active")) {
            $(this)
                .removeClass("active")
                .find(".tj-service-5-accordion-wrap")
                .slideUp();
        } else {
            $(
                ".accordion-list > li.active .tj-service-5-accordion-wrap"
            ).slideUp();
            $(".accordion-list > li.active").removeClass("active");
            $(this)
                .addClass("active")
                .find(".tj-service-5-accordion-wrap")
                .slideDown();
        }
    });

    // Define variables
    var tabLabels = document.querySelectorAll("#tabs li");
    var tabPanes = document.querySelectorAll(".tab-contents");
    var tabContainer = document.querySelector("#tab-contents");

    function setMinTabHeight() {
        let maxHeight = 0;

        if (tabPanes.length) {
            tabPanes.forEach((pane) => {
                const wasHidden =
                    pane.style.display === "none" ||
                    pane.style.visibility === "hidden";

                if (wasHidden) {
                    // Temporarily make hidden pane visible to get its height
                    pane.style.visibility = "hidden";
                    pane.style.display = "block"; // Ensure it's displayed for height measurement
                }

                const height = pane.offsetHeight;
                if (height > maxHeight) {
                    maxHeight = height;
                }

                // Revert back to original state if it was hidden
                if (wasHidden) {
                    pane.style.visibility = "";
                    pane.style.display = "none"; // Hide it again after measuring
                }
            });

            // Set the container's minHeight to the height of the tallest pane
            tabContainer.style.minHeight = maxHeight + "px";
        }
    }

    if (tabLabels?.length) {
        function activateTab(e) {
            e.preventDefault();

            // Deactivate all tabs
            tabLabels.forEach((label) => label.classList.remove("active"));
            tabPanes.forEach((pane) => {
                pane.classList.remove("show");
                pane.classList.add("fade");
            });

            // Activate the clicked tab
            const parentLi = e.target.closest("li");
            const targetId = e.target.getAttribute("href");
            if (parentLi && targetId) {
                parentLi.classList.add("active");
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    setTimeout(() => {
                        targetPane.classList.remove("fade");
                        targetPane.classList.add("show", "fade");

                        // Update the min-height after fade starts
                        setMinTabHeight();
                    }, 10); // Small delay to trigger fade effect
                }
            }
        }

        // Add click listeners to tab links
        tabLabels.forEach((label) => {
            const link = label.querySelector("a");
            if (link) {
                link.addEventListener("click", activateTab);
            }
        });

        // Set initial height on load
        setMinTabHeight();
    }

    // hover image animation
    const hoverItem = document.querySelectorAll(".anim-reveal-item");
    if (hoverItem?.length) {
        function moveImage(e, hoverItem, index) {
            const item = hoverItem.getBoundingClientRect();
            const x = e.clientX - item.x;
            const y = e.clientY - item.y;
            if (hoverItem.children[index]) {
                hoverItem.children[
                    index
                ].style.transform = `translate(${x}px, ${y}px)`;
            }
        }
        hoverItem.forEach((item, i) => {
            item.addEventListener("mousemove", (e) => {
                setInterval(moveImage(e, item, 1), 50);
            });
        });
    }

    //  button hover animation
    $(".tj-btn-rounded").on("mouseenter", function (e) {
        var x = e.pageX - $(this).offset().left;
        var y = e.pageY - $(this).offset().top;
        $(this).find(".tj-btn-circle-dot").css({
            top: y,
            left: x,
        });
    });
    $(".tj-btn-rounded").on("mouseout", function (e) {
        var x = e.pageX - $(this).offset().left;
        var y = e.pageY - $(this).offset().top;
        $(this).find(".tj-btn-circle-dot").css({
            top: y,
            left: x,
        });
    });
})(jQuery);
