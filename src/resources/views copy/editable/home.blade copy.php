@extends('layouts.app')

@section('title', ' | بطور للتسويق الإلكتروني')
@section('meta_description', 'خدمات بطور للتسويق الإلكتروني ب المملكة العربية السعودية')

@section('content')
               <!--Start Main Slider One-->
               <section class="main-slider main-slider-one" dir="ltr">
                <div class="main-slider-one__inner">
                    <div class="main-slider__carousel owl-carousel owl-theme thm-owl__carousel"
                        data-owl-options='{"loop": true, "items": 1, "navText": ["<span class=\"icon-right-arrow\"></span>{!! __('') !!}<span class=\"icon-right-arrow2\"></span>"], "margin": 0, "dots": false, "nav": true, "animateOut": "slideOutDown", "animateIn": "fadeIn", "active": true, "smartSpeed": 1000, "autoplay": true, "autoplayTimeout": 7000, "autoplayHoverPause": false}'>


                        <!--Start Main Slider One Single-->
                        <div class="main-slider-one__single">
                            <div class="image-layer" style="background-image:url(assets/images/slider/slider-v1-img1.jpg)">
                            </div>
                            <div class="shape1"><img src="assets/images/shapes/slider-v1-shape1.png" alt="#"></div>

                            <div class="container">
                                <div class="main-slider-one__content text-center">
                                    <div class="tagline">
                                        <h6>{!! __('innovative_solution') !!}</h6>
                                    </div>

                                    <div class="title">
                                        <h2>{!! __('digital_solution') !!} <br>
                                            {!! __('services') !!}</h2>
                                    </div>

                                    <div class="text">
                                        <p>{!! __('evolve_into') !!}</p>
                                    </div>

                                    <div class="main-slider-one__content-btn">
                                        <a class="thm-btn" href="contact.html">{!! __('view_services') !!}</a>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!--End Main Slider One Single-->

                        <!--Start Main Slider One Single-->
                        <div class="main-slider-one__single">
                            <div class="image-layer" style="background-image:url(assets/images/slider/slider-v1-img2.jpg)">
                            </div>
                            <div class="shape1"><img src="assets/images/shapes/slider-v1-shape1.png" alt="#"></div>

                            <div class="container">
                                <div class="main-slider-one__content text-center">
                                    <div class="tagline">
                                        <h6>{!! __('innovative_solution') !!}</h6>
                                    </div>

                                    <div class="title">
                                        <h2>{!! __('digital_solution') !!} <br>
                                            {!! __('services') !!}</h2>
                                    </div>

                                    <div class="text">
                                        <p>{!! __('evolve_into') !!}</p>
                                    </div>

                                    <div class="main-slider-one__content-btn">
                                        <a class="thm-btn" href="contact.html">{!! __('view_services') !!}</a>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!--End Main Slider One Single-->


                        <!--Start Main Slider One Single-->
                        <div class="main-slider-one__single">
                            <div class="image-layer" style="background-image:url(assets/images/slider/slider-v1-img3.jpg)">
                            </div>
                            <div class="shape1"><img src="assets/images/shapes/slider-v1-shape1.png" alt="#">
                            </div>

                            <div class="container">
                                <div class="main-slider-one__content text-center">
                                    <div class="tagline">
                                        <h6>{!! __('innovative_solution') !!}</h6>
                                    </div>

                                    <div class="title">
                                        <h2>{!! __('digital_solution') !!} <br>
                                            {!! __('services') !!}</h2>
                                    </div>

                                    <div class="text">
                                        <p>{!! __('evolve_into') !!}</p>
                                    </div>

                                    <div class="main-slider-one__content-btn">
                                        <a class="thm-btn" href="contact.html">{!! __('view_services') !!}</a>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!--End Main Slider One Single-->
                    </div>
                </div>
            </section>
            <!--End Main Slider One-->

            <!--Start Intro One-->
            <section class="intro-one">
                <div class="shape2"><img src="assets/images/shapes/intro-v1-shape2.png" alt=""></div>
                <div class="container">
                    <div class="row">
                        <!--Start Intro One Single-->
                        <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.1s">
                            <div class="intro-one__single">
                                <div class="intro-one__single-img">
                                    <div class="inner">
                                        <img src="assets/images/resources/intro-v1-img1.jpg" alt="#">
                                    </div>
                                    <div class="icon-box">
                                        <span class="icon-privacy"></span>
                                    </div>
                                </div>

                                <div class="intro-one__single-content text-center">
                                    <div class="shape1"><img src="assets/images/shapes/intro-v1-shape1.png" alt="#"></div>
                                    <h2><a href="#">{!! __('digital_security') !!}</a></h2>
                                    <p>{!! __('lorem_ipsum') !!} <br>
                                        {!! __('') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!--End Intro One Single-->

                        <!--Start Intro One Single-->
                        <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.2s">
                            <div class="intro-one__single">
                                <div class="intro-one__single-img">
                                    <div class="inner">
                                        <img src="assets/images/resources/intro-v1-img2.jpg" alt="#">
                                    </div>
                                    <div class="icon-box">
                                        <span class="icon-filter"></span>
                                    </div>
                                </div>

                                <div class="intro-one__single-content text-center">
                                    <div class="shape1"><img src="assets/images/shapes/intro-v1-shape1.png" alt="#"></div>
                                    <h2><a href="#">{!! __('tech_solution') !!}</a></h2>
                                    <p>{!! __('lorem_ipsum') !!} <br>
                                        {!! __('') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!--End Intro One Single-->

                        <!--Start Intro One Single-->
                        <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.3s">
                            <div class="intro-one__single">
                                <div class="intro-one__single-img">
                                    <div class="inner">
                                        <img src="assets/images/resources/intro-v1-img3.jpg" alt="#">
                                    </div>
                                    <div class="icon-box">
                                        <span class="icon-laptop"></span>
                                    </div>
                                </div>

                                <div class="intro-one__single-content text-center">
                                    <div class="shape1"><img src="assets/images/shapes/intro-v1-shape1.png" alt="#"></div>
                                    <h2><a href="#">{!! __('outsourcing') !!}</a></h2>
                                    <p>{!! __('lorem_ipsum') !!} <br>
                                        {!! __('') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!--End Intro One Single-->
                    </div>
                </div>
            </section>
            <!--End Intro One-->

            <!--Start About One-->
            <section class="about-one">
                <div class="shape5 rotate-me"><img src="assets/images/shapes/about-v1-shape4.png" alt="#"></div>
                <div class="shape6"><img src="assets/images/shapes/about-v1-shape5.png" alt="#"></div>
                <div class="container">
                    <div class="row">
                        <!--Start About One Content-->
                        <div class="col-xl-6">
                            <div class="about-one__content">
                                <div class="sec-title">
                                    <div class="sec-title__tagline">
                                        <div class="inner">
                                            <div class="round-box"><img src="assets/images/shapes/sec-title-shape.png"
                                                    alt="#">
                                            </div>
                                            <div class="text">
                                                <p>{!! __('introduce_company') !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="sec-title__title">{!! __('company') !!} <br>
                                        {!! __('specializesin_solutions') !!}</h2>
                                </div>

                                <div class="about-one__content-text1">
                                    <p>{!! __('lorem_ipsum') !!}</p>
                                    <h2>{!! __('ready_transform') !!}</h2>
                                </div>

                                <div class="about-one__content-text2">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div class="about-one__content-text2-single">
                                                <div class="icon-box">
                                                    <div class="shape1"></div>
                                                    <span class="icon-buoy-help"></span>
                                                </div>

                                                <div class="content-box">
                                                    <h3>{!! __('tech_solution') !!}</h3>
                                                    <p>{!! __('there_many') !!} </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div class="about-one__content-text2-single">
                                                <div class="icon-box">
                                                    <div class="shape1"></div>
                                                    <span class="icon-answer-call"></span>
                                                </div>

                                                <div class="content-box">
                                                    <h3>{!! __('quick_support') !!}</h3>
                                                    <p>{!! __('there_many') !!} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="about-one__content-text3">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div class="about-one__content-text3-progress">
                                                <div class="shape2"><img src="assets/images/shapes/about-v1-shape1.png"
                                                        alt="#"></div>
                                                <div class="shape3"><img src="assets/images/shapes/about-v1-shape2.png"
                                                        alt="#"></div>
                                                <div class="progress-box">
                                                    <div class="graph-outer">
                                                        <input type="text" class="dial" data-fgColor="#ffffff"
                                                            data-bgColor="333b50" data-width="80" data-height="80"
                                                            data-linecap="normal" value="95">
                                                        <div class="inner-text count-box"><span class="count-text"
                                                                data-stop="95" data-speed="2000"></span><span
                                                                class="count-Parsent">{!! __('') !!}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="title-box">
                                                    <h3>{!! __('project_solution') !!}</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <div class="about-one__content-text3-content">
                                                <p>{!! __('lorem_ipsum') !!} <br>
                                                    {!! __('consectetur_adipiscing') !!}</p>
                                                <ul>
                                                    <li>
                                                        <p> <span class="icon-check-circle"></span> {!! __('design') !!}</p>
                                                    </li>

                                                    <li>
                                                        <p> <span class="icon-check-circle"></span> {!! __('website_development') !!}</p>
                                                    </li>

                                                    <li>
                                                        <p> <span class="icon-check-circle"></span> {!! __('digital_marketing') !!}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End About One Content-->

                        <!--Start About One Img-->
                        <div class="col-xl-6">
                            <div class="about-one__img">
                                <div class="about-one__img1">
                                    <img src="assets/images/about/about-v1-img1.jpg" alt="#">
                                </div>

                                <div class="about-one__img2">
                                    <div class="shape4 rotate-me"><img src="assets/images/shapes/about-v1-shape3.png"
                                            alt="#"></div>
                                    <ul>
                                        <li>
                                            <div class="img-box1">
                                                <img src="assets/images/about/about-v1-img2.jpg" alt="#">
                                            </div>
                                        </li>

                                        <li>
                                            <div class="img-box2">
                                                <img src="assets/images/about/about-v1-img3.jpg" alt="#">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--End About One Img-->
                    </div>
                </div>
            </section>
            <!--End About One-->

            <!--Start Services One-->
            <section class="services-one">
                <div class="shape3"><img src="assets/images/shapes/services-v1-shape2.png" alt="#"></div>
                <div class="shape4"><img src="assets/images/shapes/services-v1-shape3.png" alt="#"></div>
                <div class="shape5"><img src="assets/images/shapes/services-v1-shape4.png" alt="#"></div>
                <div class="shape6"><img src="assets/images/shapes/services-v1-shape5.png" alt="#"></div>
                <div class="shape7"><img src="assets/images/shapes/services-v1-shape6.png" alt="#"></div>
                <div class="shape8"><img src="assets/images/shapes/services-v1-shape7.png" alt="#"></div>
                <div class="shape9"><img src="assets/images/shapes/services-v1-shape8.png" alt="#"></div>
                <div class="shape10"><img src="assets/images/shapes/services-v1-shape9.png" alt="#"></div>
                <div class="shape11"><img src="assets/images/shapes/services-v1-shape10.png" alt="#"></div>
                <div class="shape12"><img src="assets/images/shapes/services-v1-shape11.png" alt="#"></div>
                <div class="container">
                    <div class="services-one__top">
                        <div class="sec-title">
                            <div class="sec-title__tagline">
                                <div class="inner">
                                    <div class="round-box"><img src="assets/images/shapes/sec-title-shape.png" alt="#">
                                    </div>
                                    <div class="text">
                                        <p>{!! __('services') !!}</p>
                                    </div>
                                </div>
                            </div>
                            <h2 class="sec-title__title">{!! __('providing_complete') !!} <br>
                                {!! __('professional_services') !!}</h2>
                        </div>

                        <div class="services-one__top-text">
                            <p>{!! __('nemo_enim') !!}</p>
                        </div>
                    </div>

                    <div class="row">
                        <!--Start Services One Single-->
                        <div class="col-xl-3 col-lg-6 col-md-6  wow fadeInLeft" data-wow-delay="0ms"
                            data-wow-duration="1000ms">
                            <div class="services-one__single text-center">
                                <div class="shape2"><img src="assets/images/shapes/services-v1-shape1.png" alt="#"></div>
                                <div class="services-one__single-icon">
                                    <div class="shape1"></div>
                                    <span class="icon-server-data"></span>
                                </div>

                                <div class="services-one__single-content">
                                    <h2><a href="service.html">{!! __('tech_analysis') !!}</a></h2>
                                    <p>{!! __('there_many') !!} </p>
                                </div>
                            </div>
                        </div>
                        <!--End Services One Single-->

                        <!--Start Services One Single-->
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInLeft" data-wow-delay="100ms"
                            data-wow-duration="1000ms">
                            <div class="services-one__single text-center">
                                <div class="shape2"><img src="assets/images/shapes/services-v1-shape1.png" alt="#"></div>
                                <div class="services-one__single-icon">
                                    <div class="shape1"></div>
                                    <span class="icon-laptop"></span>
                                </div>

                                <div class="services-one__single-content">
                                    <h2><a href="service.html">{!! __('consultancy') !!}</a></h2>
                                    <p>{!! __('there_many') !!} </p>
                                </div>
                            </div>
                        </div>
                        <!--End Services One Single-->

                        <!--Start Services One Single-->
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInRight" data-wow-delay="0ms"
                            data-wow-duration="1000ms">
                            <div class="services-one__single text-center">
                                <div class="shape2"><img src="assets/images/shapes/services-v1-shape1.png" alt="#"></div>
                                <div class="services-one__single-icon">
                                    <div class="shape1"></div>
                                    <span class="icon-hard-drive"></span>
                                </div>

                                <div class="services-one__single-content">
                                    <h2><a href="service.html">{!! __('data_structuring') !!}</a></h2>
                                    <p>{!! __('there_many') !!} </p>
                                </div>
                            </div>
                        </div>
                        <!--End Services One Single-->

                        <!--Start Services One Single-->
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInRight" data-wow-delay="100ms"
                            data-wow-duration="1000ms">
                            <div class="services-one__single text-center">
                                <div class="shape2"><img src="assets/images/shapes/services-v1-shape1.png" alt="#"></div>
                                <div class="services-one__single-icon">
                                    <div class="shape1"></div>
                                    <span class="icon-marketing-analysis"></span>
                                </div>

                                <div class="services-one__single-content">
                                    <h2><a href="service.html">{!! __('market_analysis') !!}</a></h2>
                                    <p>{!! __('there_many') !!} </p>
                                </div>
                            </div>
                        </div>
                        <!--End Services One Single-->
                    </div>

                    <div class="services-one__bottom text-center">
                        <h2>{!! __('were_ready') !!}</h2>
                        <p>{!! __('nemo_enim') !!} <br>
                            {!! __('') !!}</p>
                        <div class="btn-box">
                            <a class="thm-btn" href="#">{!! __('more_solution') !!}</a>
                        </div>
                    </div>

                </div>
            </section>
            <!--End Services One-->

            <!--Start Video One-->
            <section class="video-one">
                <div class="shape1"><img src="assets/images/shapes/video-v1-shape1.png" alt="#"></div>
                <div class="shape2"><img src="assets/images/shapes/video-v1-shape1.png" alt="#"></div>
                <div class="container">
                    <div class="video-one__inner">
                        <div class="shape3"><img src="assets/images/shapes/video-v1-shape3.png" alt="#"></div>
                        <div class="video-one__bg"
                            style="background-image: url(assets/images/backgrounds/video-v1-bg.jpg);"></div>

                        <div class=" video-one__icon">
                            <a href="https://www.youtube.com/watch?v=pVE92TNDwUk" class="video-one__btn video-popup">
                                <span class="icon-play-button"></span>
                            </a>
                            <span class="border-animation border-1"></span>
                            <span class="border-animation border-2"></span>
                            <span class="border-animation border-3"></span>
                        </div>
                        <div class="title-box text-center">
                            <h2>{!! __('solution_specialists') !!}</h2>
                        </div>
                    </div>
                </div>
            </section>
            <!--End Video One-->

            <!--Start Testimonial One-->
            <section class="testimonial-one">
                <div class="container">

                </div>
            </section>
            <!--End Testimonial One-->


            <!--Start Cta One-->
            <section class="cta-one">
                <div class="container">
                    <div class="cta-one__inner">
                        <div class="cta-one__bg" style="background-image: url(assets/images/backgrounds/cta-v1-bg.jpg);">
                        </div>
                        <div class="cta-one__text">
                            <h2>{!! __('services_customized') !!}</h2>
                        </div>

                        <div class="cta-one__btn">
                            <a href="contact.html">{!! __('view_protfolio') !!}</a>
                        </div>
                    </div>
                </div>
            </section>
            <!--End Cta One-->

            <!--Start Counter One-->
            <section class="counter-one">
                <div class="counter-one__bg" style="background-image: url(assets/images/backgrounds/counter-v1-bg.jpg);">
                </div>
                <div class="container">
                    <div class="row">
                        <!--Start Counter One Single-->
                        <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.1s">
                            <div class="counter-one__single">
                                <div class="counter-one__single-top">
                                    <div class="img-box"><img src="assets/images/shapes/counter-v1-shape1.png" alt="#">
                                    </div>
                                    <div class="content-box">
                                        <h2><span class="odometer" data-count="80">{!! __('00') !!}</span> <span class="plus">{!! __('') !!}</span>
                                        </h2>
                                    </div>
                                </div>

                                <div class="counter-one__single-text">
                                    <h3>{!! __('successful') !!} <br>
                                        {!! __('completed_projects') !!}</h3>
                                </div>
                            </div>
                        </div>
                        <!--End Counter One Single-->

                        <!--Start Counter One Single-->
                        <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.2s">
                            <div class="counter-one__single">
                                <div class="counter-one__single-top">
                                    <div class="img-box"><img src="assets/images/shapes/counter-v1-shape1.png" alt="#">
                                    </div>
                                    <div class="content-box">
                                        <h2><span class="odometer" data-count="50">{!! __('00') !!}</span> <span class="plus">{!! __('') !!}</span>
                                        </h2>
                                    </div>
                                </div>

                                <div class="counter-one__single-text">
                                    <h3>{!! __('agency') !!} <br>
                                        {!! __('specialists') !!}</h3>
                                </div>
                            </div>
                        </div>
                        <!--End Counter One Single-->

                        <!--Start Counter One Single-->
                        <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.3s">
                            <div class="counter-one__single">
                                <div class="counter-one__single-top">
                                    <div class="img-box"><img src="assets/images/shapes/counter-v1-shape1.png" alt="#">
                                    </div>
                                    <div class="content-box">
                                        <h2><span class="odometer" data-count="80">{!! __('00') !!}</span> <span class="plus">{!! __('') !!}</span>
                                        </h2>
                                    </div>
                                </div>

                                <div class="counter-one__single-text">
                                    <h3>{!! __('successful') !!} <br>
                                        {!! __('completed_projects') !!}</h3>
                                </div>
                            </div>
                        </div>
                        <!--End Counter One Single-->
                    </div>

                    <div class="counter-one__bottom text-center">
                        <h2>{!! __('assisting_overcoming') !!} <br>
                            {!! __('technological_obstacles') !!}</h2>
                        <div class="btn-box">
                            <a class="thm-btn" href="contact.html">{!! __('discover_more') !!} </a>
                        </div>
                    </div>
                </div>
            </section>
            <!--End Counter One-->

            <!--Start Google Map One-->
            <section class="google-map-one">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4562.753041141002!2d-118.80123790098536!3d34.152323469614075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80e82469c2162619%3A0xba03efb7998eef6d!2sCostco+Wholesale!5e0!3m2!1sbn!2sbd!4v1562518641290!5m2!1sbn!2sbd"
                    class="google-map-one__map" allowfullscreen></iframe>
            </section>
            <!--End Google Map One-->


@endsection

