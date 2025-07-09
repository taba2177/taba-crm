@extends('layouts.app')

@section('title', 'About | بطور للتسويق الإلكتروني')
@section('meta_description', 'Learn more about our بطور للتسويق الإلكتروني application on the About page.')

@section('content')
           <!--Start Page Header-->
           <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg)">
            </div>
            <div class="page-header__gradient"
                style="background-image: url(assets/images/backgrounds/page-header-gradient-bg.png);"></div>
            <div class="shape1 float-bob-x"><img src="{{ asset("/assets/images/shapes/page-header-shape1.png") }}" alt="#"></div>

            <div class="container">
                <div class="page-header__inner text-center">
                    <h2>{!! __('about') !!}</h2>
                    <ul class="thm-breadcrumb">
                        <li><a href="{{ url("/") }}">{!! __('home') !!}</a></li>
                        <li><span>/</span></li>
                        <li>{!! __('about') !!}</li>
                    </ul>
                </div>
            </div>
        </section>
        <!--End Page Header-->

        <!--Start About Three-->
        <section class="about-three">
            <div class="shape4"><img src="{{ asset("/assets/images/shapes/about-v3-shape3.png") }}" alt="#"></div>
            <div class="shape5"><img src="{{ asset("/assets/images/shapes/about-v3-shape4.png") }}" alt="#"></div>
            <div class="container">
                <div class="row">
                    <!--Start About One Img-->
                    <div class="col-xl-5">
                        <div class="about-three__img clearfix">
                            <div class="shape2"></div>
                            <div class="shape3"></div>
                            <div class="about-three__img1 wow fadeInLeft" data-wow-delay=".3s">
                                <img src="{{ asset("/assets/images/about/about-v3-img1.jpg") }}" alt="#">
                            </div>

                            <div class="about-three__img2 wow fadeInUp" data-wow-delay=".3s">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/about/about-v3-img2.jpg") }}" alt="#">
                                </div>
                                <div class="shape1 rotate-me"><img src="{{ asset("/assets/images/shapes/about-v3-shape1.png") }}"
                                        alt="#"></div>
                            </div>
                        </div>
                    </div>
                    <!--End About One Img-->

                    <!--Start About One Content-->
                    <div class="col-xl-7">
                        <div class="about-three__content">
                            <div class="sec-title">
                                <div class="sec-title__tagline">
                                    <div class="inner">
                                        <div class="round-box"><img src="{{ asset("/assets/images/shapes/sec-title-shape.png") }}"
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

                            <div class="about-three__content-text1">
                                <p>{!! __('lorem_ipsum') !!}</p>
                            </div>

                            <div class="about-three__content-text2">
                                <div class="row">
                                    <div class="col-xl-7 col-lg-7 col-md-7">
                                        <div class="about-three__content-text2-left">
                                            <ul>
                                                <li>
                                                    <div class="icon-box">
                                                        <span class="icon-buoy-help"></span>
                                                    </div>

                                                    <div class="content-box">
                                                        <h3>{!! __('tech_solution') !!}</h3>
                                                        <p>{!! __('there_many') !!} </p>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="icon-box">
                                                        <span class="icon-answer-call"></span>
                                                    </div>

                                                    <div class="content-box">
                                                        <h3>{!! __('quick_support') !!}</h3>
                                                        <p>{!! __('there_many') !!} </p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-xl-5 col-lg-5 col-md-5">
                                        <div class="about-three__content-text2-progress">
                                            <div class="shape6"><img src="{{ asset("/assets/images/shapes/about-v1-shape1.png") }}"
                                                    alt="#"></div>
                                            <div class="shape7"><img src="{{ asset("/assets/images/shapes/about-v1-shape2.png") }}"
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
                                </div>
                            </div>

                            <div class="about-three__content-text3">
                                <ul>
                                    <li>
                                        <p><span class="icon-check-circle"></span> {!! __('again_there') !!} </p>
                                    </li>

                                    <li>
                                        <p><span class="icon-check-circle"></span> {!! __('libero_tempore') !!} </p>
                                    </li>
                                </ul>
                            </div>

                            <div class="about-three__content-text4">
                                <div class="img-box">
                                    <img src="{{ asset("/assets/images/shapes/about-v3-shape2.png") }}" alt="#">
                                </div>
                                <div class="text-box">
                                    <p>{!! __('your_business') !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End About One Content-->
                </div>
            </div>
        </section>
        <!--End About Three-->

        <!--Start Intro One-->
        <section class="intro-one style3">
            <div class="container">
                <div class="row">
                    <!--Start Intro One Single-->
                    <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.1s">
                        <div class="intro-one__single">
                            <div class="intro-one__single-img">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/resources/intro-v1-img4.jpg") }}" alt="#">
                                </div>
                                <div class="icon-box">
                                    <span class="icon-privacy"></span>
                                </div>
                            </div>

                            <div class="intro-one__single-content text-center">
                                <div class="shape1"><img src="{{ asset("/assets/images/shapes/intro-v1-shape1.png") }}" alt="#"></div>
                                <h2><a href="{{ url("#") }}">{!! __('digital_security') !!}</a></h2>
                                <p>{!! __('dolor_amet') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Intro One Single-->

                    <!--Start Intro One Single-->
                    <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.2s">
                        <div class="intro-one__single">
                            <div class="intro-one__single-img">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/resources/intro-v1-img5.jpg") }}" alt="#">
                                </div>
                                <div class="icon-box">
                                    <span class="icon-certified"></span>
                                </div>
                            </div>

                            <div class="intro-one__single-content text-center">
                                <div class="shape1"><img src="{{ asset("/assets/images/shapes/intro-v1-shape1.png") }}" alt="#"></div>
                                <h2><a href="{{ url("#") }}">{!! __('trusted_agency') !!}</a></h2>
                                <p>{!! __('dolor_amet') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Intro One Single-->

                    <!--Start Intro One Single-->
                    <div class="col-xl-4 col-lg-4 wow animated fadeInUp" data-wow-delay="0.3s">
                        <div class="intro-one__single">
                            <div class="intro-one__single-img">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/resources/intro-v1-img6.jpg") }}" alt="#">
                                </div>
                                <div class="icon-box">
                                    <span class="icon-laptop"></span>
                                </div>
                            </div>

                            <div class="intro-one__single-content text-center">
                                <div class="shape1"><img src="{{ asset("/assets/images/shapes/intro-v1-shape1.png") }}" alt="#"></div>
                                <h2><a href="{{ url("#") }}">{!! __('outsourcing') !!}</a></h2>
                                <p>{!! __('dolor_amet') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Intro One Single-->
                </div>
            </div>
        </section>
        <!--End Intro One-->

        <!--Start Support One-->
        <section class="support-one support-one--two">
            <div class="shape4"><img src="{{ asset("/assets/images/shapes/support-v1-shape4.png") }}" alt="#"></div>
            <div class="container">
                <div class="row">
                    <!--Start Support One Img-->
                    <div class="col-xl-6">
                        <div class="support-one__img">
                            <div class="shape5"></div>
                            <div class="shape6"></div>
                            <div class="shape1 rotate-me"><img src="{{ asset("/assets/images/shapes/support-v1-shape1.png") }}" alt="#">
                            </div>
                            <div class="inner">
                                <img src="{{ asset("/assets/images/resources/support-v2-img1.jpg") }}" alt="#">
                            </div>
                        </div>
                    </div>
                    <!--End Support One Img-->

                    <!--Start Support One Content-->
                    <div class="col-xl-6">
                        <div class="support-one__content">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/support-v1-shape2.png") }}" alt="#"></div>
                            <div class="shape3"><img src="{{ asset("/assets/images/shapes/support-v1-shape3.png") }}" alt="#"></div>
                            <div class="sec-title">
                                <div class="sec-title__tagline">
                                    <div class="inner">
                                        <div class="round-box"><img src="{{ asset("/assets/images/shapes/sec-title-shape.png") }}"
                                                alt="#">
                                        </div>
                                        <div class="text">
                                            <p>{!! __('quality_support') !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="sec-title__title">{!! __('improve_quality') !!} <br>
                                    {!! __('with_technology') !!}</h2>
                            </div>

                            <div class="support-one__content-text1">
                                <p>{!! __('lorem_ipsum') !!}</p>
                            </div>

                            <div class="support-one__content-text2">
                                <div class="support-one__content-text2-single">
                                    <div class="icon-box">
                                        <span class="icon-buoy-help"></span>
                                    </div>
                                    <div class="text-box">
                                        <h3>{!! __('tech_solution') !!}</h3>
                                        <p>{!! __('there_many') !!} <br>
                                            {!! __('passages_lorem') !!}</p>
                                    </div>
                                </div>

                                <div class="support-one__content-text2-single mb0">
                                    <div class="icon-box">
                                        <span class="icon-buoy-help"></span>
                                    </div>
                                    <div class="text-box">
                                        <h3>{!! __('support') !!}</h3>
                                        <p>{!! __('there_many') !!} <br>
                                            {!! __('passages_lorem') !!}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--End Support One Content-->
                </div>
            </div>
        </section>
        <!--End Support One-->

        <!--Start Testimonial One-->
        <section class="testimonial-one">
            <div class="container">
                {{-- <div class="sec-title text-center">
                    <div class="sec-title__tagline">
                        <div class="inner">
                            <div class="round-box"><img src="{{ asset("/assets/images/shapes/sec-title-shape.png") }}" alt="#">
                            </div>
                            <div class="text">
                                <p>{!! __('testimonial') !!}</p>
                            </div>
                        </div>
                    </div>
                    <h2 class="sec-title__title">{!! __('client_feedback') !!} <br>
                        {!! __('theyre_talking') !!}</h2>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="testimonial-one__inner">
                            <div class="owl-carousel owl-theme thm-owl__carousel case-one__carousel" data-owl-options='{
                                "loop": true,
                                "autoplay": true,
                                "margin": 30,
                                "nav": false,
                                "dots": false,
                                "smartSpeed": 500,
                                "autoplayTimeout": 10000,
                                "navText": ["<span class=\"icon-left-arrow\"></span>{!! __('') !!}<span class=\"icon-next\"></span>"],
                                "responsive": {
                                        "0": {
                                            "items": 1
                                        },
                                        "768": {
                                            "items": 1
                                        },
                                        "992": {
                                            "items": 1
                                        },
                                        "1200": {
                                            "items": 1
                                        },
                                        "1500": {
                                            "items": 2
                                        }
                                    }
                                }'>

                                <!--Start Testimonial One Single-->
                                <div class="testimonial-one__single">
                                    <div class="testimonial-one__single-img">
                                        <div class="testimonial-one__single-img-bg"
                                            style="background-image: url(assets/images/shapes/testimonial-v1-shape1.png);">
                                        </div>
                                        <img src="{{ asset("/assets/images/testimonial/testimonial-v1-img1.jpg") }}" alt="#">
                                    </div>

                                    <div class="testimonial-one__single-content">
                                        <div class="testimonial-one__single-content-top">
                                            <div class="icon-box">
                                                <span class="icon-bubble-message"></span>
                                            </div>
                                            <div class="rating-box">
                                                <ul>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star2"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="testimonial-one__single-content-middle">
                                            <h2>{!! __('very_good') !!}</h2>
                                            <p>{!! __('lorem_ipsum') !!}
                                            </p>
                                        </div>

                                        <div class="testimonial-one__single-content-bottom">
                                            <h2>{!! __('tony_adeson') !!}</h2>
                                            <p>{!! __('designer_switzerland') !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--End Testimonial One Single-->


                                <!--Start Testimonial One Single-->
                                <div class="testimonial-one__single">
                                    <div class="testimonial-one__single-img">
                                        <div class="testimonial-one__single-img-bg"
                                            style="background-image: url(assets/images/shapes/testimonial-v1-shape1.png);">
                                        </div>
                                        <img src="{{ asset("/assets/images/testimonial/testimonial-v1-img2.jpg") }}" alt="#">
                                    </div>

                                    <div class="testimonial-one__single-content">
                                        <div class="testimonial-one__single-content-top">
                                            <div class="icon-box">
                                                <span class="icon-bubble-message"></span>
                                            </div>
                                            <div class="rating-box">
                                                <ul>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star"></span>
                                                    </li>
                                                    <li>
                                                        <span class="icon-star2"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="testimonial-one__single-content-middle">
                                            <h2>{!! __('nice_services') !!}</h2>
                                            <p>{!! __('lorem_ipsum') !!}
                                            </p>
                                        </div>

                                        <div class="testimonial-one__single-content-bottom">
                                            <h2>{!! __('adam_wiliam') !!}</h2>
                                            <p>{!! __('designer_switzerland') !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--End Testimonial One Single-->
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </section>
        <!--End Testimonial One-->

        <!--Start Team One-->
        <section class="team-one">
            <div class="container">
                <div class="sec-title text-center">
                    <div class="sec-title__tagline">
                        <div class="inner">
                            <div class="round-box"><img src="{{ asset("/assets/images/shapes/sec-title-shape.png") }}" alt="#">
                            </div>
                            <div class="text">
                                <p>{!! __('team') !!}</p>
                            </div>
                        </div>
                    </div>
                    <h2 class="sec-title__title">{!! __('professional_team') !!}</h2>
                </div>
                <div class="row">
                    <!--Start Team One Single-->
                    <div class="col-xl-4 col-lg-4 wow fadeInLeft" data-wow-delay="100ms" data-wow-duration="1500ms">
                        <div class="team-one__single">
                            <div class="team-one__single-img">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/team/team-v1-img1.jpg") }}" alt="#">
                                    <div class="social-link">
                                        <ul>
                                            <li>
                                                <a class="fb" href="{{ url("#") }}"><span class="icon-facebook"></span></a>
                                            </li>
                                            <li>
                                                <a class="tw" href="{{ url("#") }}"><span class="icon-twitter"></span></a>
                                            </li>
                                            <li>
                                                <a class="pin" href="{{ url("#") }}"><span class="icon-pinterest"></span></a>
                                            </li>
                                            <li>
                                                <a class="in" href="{{ url("#") }}"><span class="icon-instagram"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="team-one__single-content text-center">
                                <h2><a href="{{ url("team-details") }}">{!! __('adam_andeson') !!}</a></h2>
                                <p>{!! __('senior_technician') !!}</p>
                            </div>
                        </div>
                    </div>
                    <!--End Team One Single-->

                    <!--Start Team One Single-->
                    <div class="col-xl-4 col-lg-4 wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1500ms">
                        <div class="team-one__single">
                            <div class="team-one__single-img">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/team/team-v1-img2.jpg") }}" alt="#">
                                    <div class="social-link">
                                        <ul>
                                            <li>
                                                <a class="fb" href="{{ url("#") }}"><span class="icon-facebook"></span></a>
                                            </li>
                                            <li>
                                                <a class="tw" href="{{ url("#") }}"><span class="icon-twitter"></span></a>
                                            </li>
                                            <li>
                                                <a class="pin" href="{{ url("#") }}"><span class="icon-pinterest"></span></a>
                                            </li>
                                            <li>
                                                <a class="in" href="{{ url("#") }}"><span class="icon-instagram"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="team-one__single-content text-center">
                                <h2><a href="{{ url("team-details") }}">{!! __('adam_andeson') !!}</a></h2>
                                <p>{!! __('senior_technician') !!}</p>
                            </div>
                        </div>
                    </div>
                    <!--End Team One Single-->

                    <!--Start Team One Single-->
                    <div class="col-xl-4 col-lg-4 wow fadeInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
                        <div class="team-one__single">
                            <div class="team-one__single-img">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/team/team-v1-img3.jpg") }}" alt="#">
                                    <div class="social-link">
                                        <ul>
                                            <li>
                                                <a class="fb" href="{{ url("#") }}"><span class="icon-facebook"></span></a>
                                            </li>
                                            <li>
                                                <a class="tw" href="{{ url("#") }}"><span class="icon-twitter"></span></a>
                                            </li>
                                            <li>
                                                <a class="pin" href="{{ url("#") }}"><span class="icon-pinterest"></span></a>
                                            </li>
                                            <li>
                                                <a class="in" href="{{ url("#") }}"><span class="icon-instagram"></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="team-one__single-content text-center">
                                <h2><a href="{{ url("team-details") }}">{!! __('adam_andeson') !!}</a></h2>
                                <p>{!! __('senior_technician') !!}</p>
                            </div>
                        </div>
                    </div>
                    <!--End Team One Single-->
                </div>
            </div>
        </section>
        <!--End Team One-->
    @endsection
