<div class="footer-container">
    <section class="support-section">
        <div class="container">
            <div class="support-content">
                <img class="support-image" src="{{ asset('client/images/footer_girl.png') }}" alt="" />
                <div class="support-block">
                    <h1>Still have questions?</h1>
                    <div class="text">Use this form to get answers.</div>
                    <a href="#contact-popup" class="btn red-btn popup-open" data-effect="mfp-zoom-in">Contact us <img src="{{ asset('client/images/white_arrow.png') }}" alt="" /></a>
                </div>
            </div>
        </div>
    </section>

    <div id="contact-popup" class="popup-modal mfp-hide mfp-with-anim">
        <div class="popup-content">
            <form method="POST" action="{{ URL::route('callback') }}" name="contactform" class="simple-form popup-form" autocomplete="on">
                @csrf
                <h1>Contact us</h1>
                <input name="name" type="text" placeholder="Name" required />
                <input name="phone" type="tel" placeholder="Phone number" />
                <input name="email" type="email" placeholder="E-mail" required />
                <textarea name="text" placeholder="Your question"></textarea>
                <button type="submit" class="btn red-btn ">Send <img src="{{ asset('client/images/white_arrow.png') }}" alt="" /></button>
            </form>
            <button class="mfp-close" type="button" title="Close (Esc)"><img src="{{ asset('client/images/close.png') }}" alt="" /><img class="sm-only" src="{{ asset('client/images/close_popup.png') }}" alt="" /></button>
        </div>
    </div>
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-copyright">
                <a class="main-logo" href="">
                    <img src="{{ asset('client/images/footer_logo.png') }}" alt="" />
                    <span>Rapid iPhone <i>Repair</i></span>
                </a>
                <div class="copyright">&copy; 2021-2022. All rights reserved.</div>
            </div>
            <div class="footer-menu">
                <ul>
                    <li class="title">Menu</li>
                    <li><a href="{{ URL::route('support') }}" data-title="Mobile Service">Support</a></li>
                </ul>
                <ul>
                    <li class="title">Store Locations</li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="">1515 N Gilbert Rd, #D108, Gilbert, AZ 85234</a></li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="">1730 E Warner Rd, Suite 7, Tempe, AZ 85284</a></li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="">1730 E Warner Rd, Suite 7, Tempe, AZ 85284</a></li>
                </ul>
                <ul>
                    @foreach($categories as $category)
                        <li><a href="{{ URL::route('get-category', ['slug' => $category->getAttribute('slug')]) }}">{{ $category->getAttribute('name') }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-info">
                <a href="tel:4803168679" class="tel">(480)-316-8679</a>
                <p>Mon-Sun 9:00AM-8:00PM</p>
                <a href="" class="btn">Select Your Repair</a>
            </div>
        </div>
    </div>
</footer>
</div>
