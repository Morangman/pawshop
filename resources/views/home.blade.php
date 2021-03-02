@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <!--main-content-->
    <div class="main-content">
        <!-- ========= order-section ============ -->
        <section class="order-section">
            <div class="container">
                <sell-device
                    :category="{{ json_encode($category) }}"
                    :steps="{{ json_encode($steps) }}"
                    :categories="{{ json_encode($relatedCategories) }}"
                    :faqs="{{ json_encode($faqs) }}"
                ></sell-device>
            </div>
        </section>
    </div>
    <!--//main-content-->
    @yield('footer', View::make('footer', ['categories' => $categories]))
@endsection
