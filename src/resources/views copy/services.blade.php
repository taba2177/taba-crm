@extends('layouts.app')

@section('title', 'Home | بطور للتسويق الإلكتروني')
@section('meta_description', 'خدمات بطور للتسويق الإلكتروني')

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
                    <h2>{!! __('services') !!}</h2>
                    <ul class="thm-breadcrumb">
                        <li><a href="{{ url("/") }}">{!! __('home') !!}</a></li>
                        <li><span>/</span></li>
                        <li>{!! __('services') !!}</li>
                    </ul>
                </div>
            </div>
        </section>
        <!--End Page Header-->

        <!--Start Services One-->
        <section class="services-one services-one--two services">
            <div class="container">
                <div class="row">
                    <!--Start Services One Single-->
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInLeft" data-wow-delay="0ms"
                        data-wow-duration="1000ms">
                        <div class="services-one__single text-center">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/services-v1-shape12.png") }}" alt="#"></div>
                            <div class="services-one__single-icon">
                                <div class="shape1"></div>
                                <span class="icon-server-data"></span>
                            </div>

                            <div class="services-one__single-content">
                                <h2><a href="{{ url("service") }}">{!! __('tech_analysis') !!}</a></h2>
                                <p>{!! __('there_many') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Services One Single-->

                    <!--Start Services One Single-->
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInLeft" data-wow-delay="100ms"
                        data-wow-duration="1000ms">
                        <div class="services-one__single text-center">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/services-v1-shape12.png") }}" alt="#"></div>
                            <div class="services-one__single-icon">
                                <div class="shape1"></div>
                                <span class="icon-laptop"></span>
                            </div>

                            <div class="services-one__single-content">
                                <h2><a href="{{ url("service") }}">{!! __('consultancy') !!}</a></h2>
                                <p>{!! __('there_many') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Services One Single-->

                    <!--Start Services One Single-->
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInRight" data-wow-delay="0ms"
                        data-wow-duration="1000ms">
                        <div class="services-one__single text-center">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/services-v1-shape12.png") }}" alt="#"></div>
                            <div class="services-one__single-icon">
                                <div class="shape1"></div>
                                <span class="icon-hard-drive"></span>
                            </div>

                            <div class="services-one__single-content">
                                <h2><a href="{{ url("service") }}">{!! __('data_structuring') !!}</a></h2>
                                <p>{!! __('there_many') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Services One Single-->

                    <!--Start Services One Single-->
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInLeft" data-wow-delay="0ms"
                        data-wow-duration="1000ms">
                        <div class="services-one__single text-center">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/services-v1-shape12.png") }}" alt="#"></div>
                            <div class="services-one__single-icon">
                                <div class="shape1"></div>
                                <span class="icon-networking-seo"></span>
                            </div>

                            <div class="services-one__single-content">
                                <h2><a href="{{ url("service") }}">{!! __('digital_marketing') !!}</a></h2>
                                <p>{!! __('there_many') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Services One Single-->

                    <!--Start Services One Single-->
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInLeft" data-wow-delay="100ms"
                        data-wow-duration="1000ms">
                        <div class="services-one__single text-center">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/services-v1-shape12.png") }}" alt="#"></div>
                            <div class="services-one__single-icon">
                                <div class="shape1"></div>
                                <span class="icon-web-design"></span>
                            </div>

                            <div class="services-one__single-content">
                                <h2><a href="{{ url("service") }}">{!! __('graphic_designing') !!}</a></h2>
                                <p>{!! __('there_many') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Services One Single-->

                    <!--Start Services One Single-->
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInRight" data-wow-delay="100ms"
                        data-wow-duration="1000ms">
                        <div class="services-one__single text-center">
                            <div class="shape2"><img src="{{ asset("/assets/images/shapes/services-v1-shape12.png") }}" alt="#"></div>
                            <div class="services-one__single-icon">
                                <div class="shape1"></div>
                                <span class="icon-marketing-analysis"></span>
                            </div>

                            <div class="services-one__single-content">
                                <h2><a href="{{ url("service") }}">{!! __('market_analysis') !!}</a></h2>
                                <p>{!! __('there_many') !!} </p>
                            </div>
                        </div>
                    </div>
                    <!--End Services One Single-->
                </div>
            </div>
        </section>
        <!--End Services One-->

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

@endsection
