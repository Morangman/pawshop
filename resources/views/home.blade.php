@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <!--main-content-->
    <div class="main-content">
        <!-- ========= order-section ============ -->
        <section class="order-section">
            <div class="container">
                <sell-device
                    :categories="{{ json_encode($relatedCategories) }}"
                    :faqs="{{ json_encode($faqs) }}"
                ></sell-device>

                <div class="brands-content">
                    <h2>Brands</h2>
                    <ul class="brands-list">
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_1.svg') }}" alt=""></a>
                        </li>
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_2.svg') }}" alt=""></a>
                        </li>
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_3.svg') }}" alt=""></a>
                        </li>
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_4.svg') }}" alt=""></a>
                        </li>
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_5.svg') }}" alt=""></a>
                        </li>
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_6.svg') }}" alt=""></a>
                        </li>
                        <li>
                            <a href=""><img src="{{ asset('client/images/brands/brand_7.svg') }}" alt=""></a>
                        </li>
                    </ul>
                </div>

            </div>
        </section>
    </div>
    <!--//main-content-->
    @yield('footer', View::make('footer'))
@endsection
