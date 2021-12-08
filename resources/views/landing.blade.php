<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>El Farmaceutico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.3/dist/css/bulma-carousel.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet"> 
</head>
<body>
    {{-- NAVIGATION --}}
    <nav class="nav">
        <a href="/" class="p-2 pl-5 ml-5">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </a>
        <div class="ml-auto mr-5 pr-5 logout">
            <span>ESTER TERMENS | </span>
            <img src="{{ asset('images/logout.svg') }}" class="ml-1" alt="Logout Button">
        </div>
    </nav>
    {{-- END NAVIGATION --}}

    {{-- CAROUSEL --}}
    <!-- Slider main container -->
    <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            @foreach ($banners as $key => $banner)
                <div class="swiper-slide">
                    <div class="image">
                        <img src="{{ asset('storage/banners/'.$banner->url) }}" alt="{{$banner->url}}">
                        <p class="text">
                            {!! str_replace([
                                'width','height'
                            ],[
                                '',''
                            ],Illuminate\Support\Facades\Storage::disk('public')->get('banners/'.$banner->title)) !!}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>
    
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    
        <!-- If we need scrollbar -->
        <div class="swiper-scrollbar"></div>
    </div>
  

    {{-- <section class="section carousel p-0">
        <div id="mainSlider" class="carousel">
            @foreach ($banners as $key => $banner)
                <div class="item-1 has-text-centered">
                    <div class="image">
                        <img src="{{ asset('storage/banners/'.$banner->url) }}" alt="{{$banner->url}}">
                        <p class="text">
                            {!! str_replace([
                                'width','height'
                            ],[
                                '',''
                            ],Illuminate\Support\Facades\Storage::disk('public')->get('banners/'.$banner->title)) !!}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section> --}}
    {{-- END ACROUSEL --}}

    {{-- SUB BANNERS --}}
    <div class="section subbanners">
        <div class="columns is-multiline">
            @foreach ($subBanners as $subBanner)
                <div class="column">
                    <div class="image">
                        <img src="{{asset('storage/sub-banners/'.$subBanner->icon)}}" alt="{{$subBanner->icon}}">
                    </div>
                    <p class="text">{{$subBanner->title}}</p>
                    <p class="period-text">Período lectivo:</p>
                    <p class="period">{{$subBanner->period}}</p>
                    <a href="#" class="accessButton">Acceder</a>
                </div>
            @endforeach
        </div>
    </div>
    {{-- END SUB BANNERS --}}

    {{-- TEXTS --}}
    <section class="section">
        <div class="container text-container">
            @foreach ($texts as $text)
                <div class="box">
                    <div class="columns is-multiline">
                        <div class="column has-text-centered">
                            <img src="{{asset('storage/texts_images/'.$text->url)}}" alt="{{$text->url}}">
                        </div>
                        <div class="column">
                            <p class="text-title">{{$text->title}}</p>
                            <p class="doctor">{{$text->doctor}}</p>
                            <p class="location">{{$text->location}}</p>
                            <p class="text-text">{{$text->text}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    {{-- END TEXTS --}}

    {{-- FOOTER --}}
    <section class="section has-text-centered info">
        <p class="mb-1"><img src="{{asset('images/email.svg')}}" alt="Email"></p>
        <p class="mb-1"><img src="{{asset('images/phone.svg')}}" alt="Phone"></p>
        <p><img src="{{asset('images/schedule.svg')}}" alt="Schedule"></p>
    </section>
    <section class="section">
        <p class="has-text-centered">
            <img src="{{asset('images/logoRojo.svg')}}" alt="Logo rojo">
        </p>
        <hr>
        <p class="has-text-centered copy">Copyright 2021 - © EDICIONES MAYO, S.A.</p>
        <p class="has-text-centered">Página web optimizada para navegadores Google Chrome, Mozilla Firefox, Safari, Android Browser & WebView (v5.0+) y Microsoft Edge.</p>
    </section>
    {{-- END FOOTER --}}

    <script src="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.3/dist/js/bulma-carousel.min.js"></script>
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script>
        // bulmaCarousel.attach('#mainSlider', {
        //     slidesToScroll: 1,
        //     slidesToShow: 1,
        //     loop: true,
        //     autoplay: true,
        //     autoplaySpeed: 5000,
        //     pauseOnHover: false,
        // });

        const swiper = new Swiper('.swiper', {
            loop: true,
            autoplay: true,

            pagination: {
                el: '.swiper-pagination',
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // And if we need scrollbar
            // scrollbar: {
            //     el: '.swiper-scrollbar',
            // },
        });

    </script>
</body>
</html>