

{{-- <div class="custom-cursor__cursor">{{ __('') }}</div> --}}
<div class="custom-cursor__cursor-two">{{ __('') }}</div>


<div class="preloader">
    <div class="preloader__image"></div>
    <img src=" {{ asset("/assets/images/loader.png") }}" alt="" srcset="" hidden>
</div>
<!-- /.preloader -->

<div class="page-wrapper">

    <!--Start Main Header One-->
    <header class="main-header main-header-one clearfix">
        <div class="main-header-one__top">
            <div class="container">
                <div class="main-header-one__top-inner">
                    <div class="main-header-one__top-left">
                        <div class="main-header-one__top-left-btn">
                            <a href="{{ url("http://192.168.8.117:9000/admin") }}">{{ __('toasl_maana') }}</a>
                        </div>
                    </div>

                    <div class="main-header-one__top-right">
                        <div class="main-header__contact-info">
                            <ul>
                                <li>
                                    <div class="inner">
                                        <div class="icon-box">
                                            <span class="icon-call">{{ __('') }}</span>
                                        </div>
                                        <div class="text-box">
                                            <p>{{ __('llastfsar') }}</p>
                                            <h4><a href="{{ url("tel:8857002451") }}">{{ __('088_57_00_24_51') }}</a></h4>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="inner">
                                        <div class="icon-box">
                                            <span class="icon-email">{{ __('') }}</span>
                                        </div>
                                        <div class="text-box">
                                            <p>{{ __('arsal_amyl') }}</p>
                                            <h4><a href="{{ url("mailto:yourmail@email.com") }}">{{ __('helpus_at_battourcom') }}</a></h4>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="inner">
                                        <div class="icon-box">
                                            <span class="icon-clock2">{{ __('') }}</span>
                                        </div>
                                        <div class="text-box">
                                            <p>{{ __('algmaa_mghlk') }}</p>
                                            <h4>{{ __('alsbt_alkhmys600_800') }}</h4>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="main-header-one__bottom">
            <nav class="main-menu clearfix">
                <div class="main-menu__wrapper clearfix">
                    <div class="container">
                        <div class="main-header-one__bottom-inner">

                            <div class="main-header-one__bottom-left">
                                <div class="logo-one">
                                    <a href="{{ url("/") }}"><img src="{{ asset("/assets/images/resources/logo-1.png") }}" alt="#"></a>
                                </div>

                                {{-- <li class="dropdown">
                                    <a href="{{ url("#") }}">{{ __('alkhdmat') }}</a>
                                    <ul>
                                        <li><a href="{{ url("services") }}">{{ __('services') }}</a></li>
                                        <li><a href="{{ url("services") }}">{{ __('digital_marketing') }}</a></li>
                                        <li><a href="{{ url("services") }}">{{ __('graphic_designing') }}</a>
                                        </li>
                                        <li><a href="{{ url("services") }}">{{ __('data_structuring') }}</a></li>
                                        <li><a href="{{ url("services") }}">{{ __('web_development') }}</a>
                                        </li>
                                        <li><a href="{{ url("services") }}">{{ __('seo_marketing') }}</a></li>
                                    </ul>
                                </li> --}}


                                <div class="main-menu__main-menu-box">
                                    <a href="{{ url("#") }}" class="mobile-nav__toggler"><i class="fa fa-bars">{{ __('') }}</i></a>
                                    <ul class="main-menu__list">


                                        <li>
                                            <a href="{{ url("/") }}">{{ __('alryysy') }}</a>
                                        </li>

                                        <li>
                                            <a href="{{ url("about") }}">{{ __('mn_nhn') }}</a>
                                        </li>


                                        <li class="dropdown">
                                            <a href="{{ url("#") }}">{{ __('alkhdmat') }}</a>
                                            <ul>
                                                <li><a href="{{ url("services") }}">{{ __('digital_marketing') }}</a></li>
                                                <li><a href="{{ url("services") }}">{{ __('graphic_designing') }}</a>
                                                </li>
                                                <li><a href="{{ url("services") }}">{{ __('data_structuring') }}</a></li>
                                                <li><a href="{{ url("services") }}">{{ __('web_development') }}</a>
                                                </li>
                                                <li><a href="{{ url("services") }}">{{ __('seo_marketing') }}</a></li>
                                            </ul>
                                        </li>

                                        <li>
                                            <a href="{{ url("pricing") }}">{{ __('alasaaar_oalaarod') }}</a>
                                        </li>
{{--
                                        <li>
                                            <a href="{{ url("projects") }}">{{ __('msharyaana') }}</a>
                                        </li> --}}

                                        <li>
                                            <a href="{{ url("contact") }}">{{ __('atsl_bna') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="main-header-one__bottom-right">
                                <div class="main-header__search">
                                    {{-- <a href="{{ url("#") }}" class="main-menu__search search-toggler icon-search">{{ __('') }}</a> --}}
                                    <div>

                                        <select class="changeLanguage">
                                            <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : ''}}>English</option>
                                            <option value="ar" {{ session()->get('locale') == 'ar' ? 'selected' : ''}}>عربي</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!--End Main Header One-->

    <div class="stricky-header stricky-header__one stricked-menu main-menu">
        <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
    </div><!-- /.stricky-header -->



