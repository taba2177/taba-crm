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
                    <h2>{!! __('pricing_plan') !!}</h2>
                    <ul class="thm-breadcrumb">
                        <li><a href="{{ url("/") }}">{!! __('home') !!}</a></li>
                        <li><span>/</span></li>
                        <li>{!! __('pricing') !!}</li>
                    </ul>
                </div>
            </div>
        </section>
        <!--End Page Header-->


        <!--Start Pricing One -->
        <section class="pricing-one">
            <div class="container">
                <div class="title-box">
                    <h2>{!! __('tech_pricing') !!} <br>
                        {!! __('packages') !!}</h2>
                </div>
                <div class="row">
                    <!--Start Pricing One Single-->
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay=".3s">
                        <div class="pricing-one__single text-center">
                            <div class="table-header">
                                <div class="shape1"><img src="{{ asset("/assets/images/shapes/pricing-v1-shape1.png") }}" alt="#"></div>
                                <div class="shape2"><img src="{{ asset("/assets/images/shapes/pricing-v1-shape2.png") }}" alt="#"></div>
                                <h2>{!! __('standard') !!}</h2>
                                <p>{!! __('simple_plan') !!}</p>
                            </div>

                            <div class="table-content">
                                <div class="table-content__top">
                                    <div class="shape3"></div>
                                    <h2>{!! __('10') !!} <span>{!! __('month') !!}</span></h2>
                                </div>

                                <ul>
                                    <li>
                                        <p>{!! __('installation') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('repair_replacement') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('monitoring') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('panel_maintenance') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('247_support') !!}</p>
                                    </li>

                                </ul>
                            </div>

                            <div class="table-footer">
                                <div class="btn-box">
                                    <a class="thm-btn" href="{{ url("#") }}">{!! __('started') !!}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Pricing One Single-->

                    <!--Start Pricing One Single-->
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay=".3s">
                        <div class="pricing-one__single text-center">
                            <div class="table-header">
                                <div class="shape1"><img src="{{ asset("/assets/images/shapes/pricing-v1-shape1.png") }}" alt="#"></div>
                                <div class="shape2"><img src="{{ asset("/assets/images/shapes/pricing-v1-shape2.png") }}" alt="#"></div>
                                <h2>{!! __('professional') !!}</h2>
                                <p>{!! __('small_team') !!}</p>
                            </div>

                            <div class="table-content">
                                <div class="table-content__top">
                                    <div class="shape3"></div>
                                    <h2>{!! __('19') !!} <span>{!! __('month') !!}</span></h2>
                                </div>

                                <ul>
                                    <li>
                                        <p>{!! __('installation') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('repair_replacement') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('monitoring') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('panel_maintenance') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('247_support') !!}</p>
                                    </li>

                                </ul>
                            </div>

                            <div class="table-footer">
                                <div class="btn-box">
                                    <a class="thm-btn" href="{{ url("#") }}">{!! __('started') !!}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Pricing One Single-->

                    <!--Start Pricing One Single-->
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay=".3s">
                        <div class="pricing-one__single text-center">
                            <div class="table-header">
                                <div class="shape1"><img src="{{ asset("/assets/images/shapes/pricing-v1-shape1.png") }}" alt="#"></div>
                                <div class="shape2"><img src="{{ asset("/assets/images/shapes/pricing-v1-shape2.png") }}" alt="#"></div>
                                <h2>{!! __('ultimate') !!}</h2>
                                <p>{!! __('large_plan') !!}</p>
                            </div>

                            <div class="table-content">
                                <div class="table-content__top">
                                    <div class="shape3"></div>
                                    <h2>{!! __('49') !!} <span>{!! __('yearly') !!}</span></h2>
                                </div>

                                <ul>
                                    <li>
                                        <p>{!! __('installation') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('repair_replacement') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('monitoring') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('panel_maintenance') !!}</p>
                                    </li>
                                    <li>
                                        <p>{!! __('247_support') !!}</p>
                                    </li>

                                </ul>
                            </div>

                            <div class="table-footer">
                                <div class="btn-box">
                                    <a class="thm-btn" href="{{ url("#") }}">{!! __('started') !!}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Pricing One Single-->

                </div>
            </div>
        </section>
        <!--End Pricing One -->


        @endsection
