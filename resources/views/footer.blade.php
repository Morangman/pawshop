<div class="footer-container">
    @php $phone = isset($settings->getAttribute('general_settings')['phone']) ? $settings->getAttribute('general_settings')['phone'] : ''; @endphp
    <section class="support-section">
        <div class="container">
            <div class="support-content">
                <img width="598" height="633" class="support-image" src="{{ asset('client/images/footer_girl.webp') }}" alt="" />
                <div class="support-block">
                    <h1>Still have questions?</h1>
                    <div class="text">Use this form to get answers.</div>
                    <a href="#contact-popup" class="btn red-btn popup-open" data-effect="mfp-zoom-in">Contact us <img width="17" height="12" src="{{ asset('client/images/white_arrow.png') }}" alt="" /></a>
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
                <input id="phone-number" name="phone" type="tel" placeholder="Phone number" />
                <input name="email" type="email" placeholder="E-mail" required />
                <textarea name="text" placeholder="Your question" required></textarea>
                <input type="hidden" name="recaptcha" id="recaptcha">
                <button type="submit" class="btn red-btn ">Send <img width="17" height="12" src="{{ asset('client/images/white_arrow.png') }}" alt="" /></button>
                <div class="recaptcha-block">
                    <p>This site is protected by ReCaptcha</p>
                </div>
            </form>
            <button class="mfp-close" type="button" title="Close (Esc)"><img width="21" height="22" src="{{ asset('client/images/close.png') }}" alt="" /><img width="21" height="22" class="sm-only" src="{{ asset('client/images/close_popup.png') }}" alt="" /></button>
        </div>
    </div>
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-copyright">
                <a class="main-logo" href="">
                    <img width="28" height="32" src="{{ asset('client/images/footer_logo.png') }}" alt="" />
                    <span>Rapid <i>Recycle</i></span>
                </a>
                <div class="copyright">&copy; 2013-2021. All rights reserved.</div>
            </div>
            <div class="footer-menu">
                <ul>
                    <li class="title">Menu</li>
                    <li><a href="{{ URL::route('support') }}" data-title="Mobile Service">Support</a></li>
                    @if($settings->getAttribute('user_agreement'))
                        <li><a href="{{ URL::route('user_agreement') }}" data-title="Mobile Service">User Agreement</a></li>
                    @endif
                    @if($settings->getAttribute('privacy_policy'))
                        <li><a href="{{ URL::route('privacy_policy') }}" data-title="Mobile Service">Privacy Policy</a></li>
                    @endif
                    @if($settings->getAttribute('terms'))
                        <li><a href="{{ URL::route('terms') }}" data-title="Mobile Service">Terms and Conditions </a></li>
                    @endif
                    @if($settings->getAttribute('law_enforcement'))
                        <li><a href="{{ URL::route('law_enforcement') }}" data-title="Mobile Service">Law Enforcement</a></li>
                    @endif
                </ul>
                <ul>
                    <li class="title">Our Contacts</li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="https://g.page/rapid-iphone-repair?share" target="_blank">1730 E Warner Rd, Suite 7, Tempe, AZ 85284</a></li>
                    <li class="location phone-mobile"><img src="{{ asset('client/images/phone.svg') }}" alt="" /><a href="tel:+{{ preg_replace('/\D+/', '', $phone) }}">{{ $phone }}</a></li>
                </ul>
                <ul>
                    @foreach($categories as $category)
                        <li><a href="{{ URL::route('get-category', ['slug' => $category->getAttribute('slug')]) }}">{{ $category->getAttribute('name') }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-info">
                <a href="tel:+{{ preg_replace('/\D+/', '', $phone) }}" class="tel">{{ $phone }}</a>
                <p>Mon-Sun 9:00AM-8:00PM</p>
                <a href="/#sell-device-section" class="btn">Sell Your Device</a>
            </div>
        </div>
    </div>
</footer>
</div>
