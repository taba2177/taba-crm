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
                    <h2>{!! __('projects_details') !!}</h2>
                    <ul class="thm-breadcrumb">
                        <li><a href="{{ url("/") }}">{!! __('home') !!}</a></li>
                        <li><span>/</span></li>
                        <li>{!! __('services') !!}</li>
                    </ul>
                </div>
            </div>
        </section>
        <!--End Page Header-->

        <!--Start Projects Details-->
        <section class="projects-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="projects-details__inner">
                            <div class="projects-details-img1">
                                <div class="inner">
                                    <img src="{{ asset("/assets/images/project/projects-details-img1.jpg") }}" alt="#">
                                </div>

                                <div class="content-box">
                                    <ul>
                                        <li>
                                            <div class="icon-box">
                                                <span class="icon-users2"></span>
                                            </div>

                                            <div class="text-box">
                                                <p>{!! __('clients') !!}</p>
                                                <h4>{!! __('road') !!}</h4>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="icon-box">
                                                <span class="icon-copy"></span>
                                            </div>

                                            <div class="text-box">
                                                <p>{!! __('category') !!}</p>
                                                <h4>{!! __('design') !!}</h4>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="icon-box">
                                                <span class="icon-clock2"></span>
                                            </div>

                                            <div class="text-box">
                                                <p>{!! __('date') !!}</p>
                                                <h4>{!! __('march_2023') !!}</h4>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="projects-details-text1">
                                <div class="title-box">
                                    <h2>{!! __('visual_designer') !!}</h2>
                                </div>
                                <h3>{!! __('project_overview') !!}</h3>
                                <p class="text1">{!! __('lorem_ipsum') !!} </p>
                                <p class="text2">{!! __('there_many') !!} </p>
                            </div>

                            <div class="projects-details-text2">
                                <h2>{!! __('what_designer') !!}</h2>
                                <p>{!! __('brand_identity') !!} </p>
                            </div>

                            <div class="projects-details-text3">
                                <div class="row">
                                    <div class="col-xl-5">
                                        <div class="projects-details-text3-img">
                                            <img src="{{ asset("/assets/images/project/projects-details-img2.jpg") }}" alt="#">
                                        </div>
                                    </div>

                                    <div class="col-xl-7">
                                        <div class="projects-details-text3-content">
                                            <div class="text-box1">
                                                <h2>{!! __('goal') !!}</h2>
                                                <p>{!! __('learn_latest') !!} </p>
                                            </div>

                                            <div class="text-box2">
                                                <h3>{!! __('idea_corporate') !!}</h3>
                                                <ul>
                                                    <li>
                                                        <div class="icon-box">
                                                            <span class="icon-check-circle"></span>
                                                        </div>
                                                        <div class="text-box">
                                                            <p>{!! __('master_modern') !!}
                                                            </p>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="icon-box clr2">
                                                            <span class="icon-check-circle"></span>
                                                        </div>
                                                        <div class="text-box">
                                                            <p>{!! __('corporate_graphics') !!}
                                                            </p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="text-box3">
                                                <h2>{!! __('user_interface') !!}</h2>
                                                <p>{!! __('learn_latest') !!} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="projects-details-text4">
                                <div class="projects-details-text4-bg"
                                    style="background-image: url(assets/images/backgrounds/projects-details-text4-bg.jpg);">
                                </div>
                                <div class="text-box">
                                    <h2>{!! __('free_visual') !!}</h2>
                                </div>
                                <div class="btn-box">
                                    <a class="thm-btn" href="{{ url("#") }}">{!! __('tells_talk') !!}</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Projects Details-->
@endsection
