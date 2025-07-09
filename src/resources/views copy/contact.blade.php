@extends('layouts.app')

@section('title', 'Contact | بطور للتسويق الإلكتروني')
@section('meta_description', 'Get in touch with us through the Contact page of our بطور للتسويق الإلكتروني application.')

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
                    <h2>{!! __('contact') !!}</h2>
                    <ul class="thm-breadcrumb">
                        <li><a href="{{ url("/") }}">{!! __('home') !!}</a></li>
                        <li><span>/</span></li>
                        <li>{!! __('contact') !!}</li>
                    </ul>
                </div>
            </div>
        </section>
        <!--End Page Header-->

        <!--Start Contact Page-->
        <section class="contact-page">
            <div class="container">
                <div class="contact-page__top">
                    <div class="title text-center">
                        <h2>{!! __('please_dont') !!} <br> {!! __('with_inquiries') !!}</h2>
                        <p>{!! __('also_reach') !!}</p>
                    </div>

                    <ul class="list-unstyled">
                        <!--Start Contact Page Top single-->
                        <li class="contact-page__top-single">
                            <div class="icon">
                                <span class="icon-location-filled"></span>
                            </div>
                            <div class="content">
                                <h2>{!! __('address') !!}</h2>
                                <p>{!! __('king_street') !!} <br>
                                    {!! __('hamilton_ontario') !!}</p>
                            </div>
                        </li>
                        <!--End Contact Page Top single-->

                        <!--Start Contact Page Top single-->
                        <li class="contact-page__top-single">
                            <div class="icon">
                                <span class="icon-phone-auricular"></span>
                            </div>
                            <div class="content">
                                <h2>{!! __('touch') !!}</h2>
                                <p>
                                    <a href="{{ url("tel:8801682648101") }}">{!! __('8801682648101') !!}</a>
                                    <a href="{{ url("tel:00881745651") }}">{!! __('0088_17456') !!}</a>
                                </p>
                            </div>
                        </li>
                        <!--End Contact Page Top single-->

                        <!--Start Contact Page Top single-->
                        <li class="contact-page__top-single">
                            <div class="icon">
                                <span class="icon-email2"></span>
                            </div>
                            <div class="content">
                                <h2>{!! __('email_address') !!}</h2>
                                <p>
                                    <a href="{{ url("mailto:info24@gmail.com") }}">{!! __('info24_at_gmailcom') !!}</a>
                                    <a href="{{ url("mailto:support24@gmail.com") }}">{!! __('support24_at_gmailcom') !!}</a>
                                </p>
                            </div>
                        </li>
                        <!--End Contact Page Top single-->
                    </ul>
                </div>

                <div class="contact-page__bottom">
                    <div class="contact-page__bottom-pattern"
                        style="background-image: url(assets/images/pattern/contact-page-pattern.jpg);"> </div>
                    <div class="contact-page__bottom-inner">
                        <form action="https://bracketweb.com/ontech-html/assets/inc/sendemail.php" class="contact-page__form contact-form-validated">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="contact-page__input-box">
                                        <input type="text" placeholder="الاسم" name="name">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="contact-page__input-box">
                                        <input type="email" placeholder="الايميل" name="email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="contact-page__input-box">
                                        <input type="text" placeholder="الجوال" name="phone">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="contact-page__input-box">
                                        <input type="text" placeholder="الموضوع" name="subject">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="contact-page__input-box">
                                        <textarea name="message" placeholder="ملاحظات"></textarea>
                                    </div>
                                    <div class="contact-page__btn">
                                        <button type="submit" class="thm-btn">
                                            {!! __('send_message') !!}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!--End Contact Page-->

        <!--Start Google Map One-->
        <section class="google-map-one">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4562.753041141002!2d-118.80123790098536!3d34.152323469614075!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80e82469c2162619%3A0xba03efb7998eef6d!2sCostco+Wholesale!5e0!3m2!1sbn!2sbd!4v1562518641290!5m2!1sbn!2sbd"
                class="google-map-one__map" allowfullscreen></iframe>
        </section>
        <!--End Google Map One-->


@endsection
