<div class="footer-container">
    <section class="support-section">
        <div class="container">
            <div class="support-content">
                <img class="support-image" src="{{ asset('client/images/footer_girl.png') }}" alt="" />
                <div class="support-block">
                    <h1>Still have questions?</h1>
                    <div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim</div>
                    <a href="#contact-popup" class="btn red-btn popup-open" data-effect="mfp-zoom-in">Contact us <img src="{{ asset('client/images/white_arrow.png') }}" alt="" /></a>
                </div>
            </div>
        </div>
    </section>

    <div id="contact-popup" class="popup-modal mfp-hide mfp-with-anim">
        <div class="popup-content">
            <form class="simple-form popup-form" action="#" method="post">
                <h1>Contact us</h1>
                <input type="text" placeholder="Julia" />
                <input type="text" placeholder="Phone number" />
                <input type="text" placeholder="E-mail" />
                <textarea placeholder="Your question"></textarea>
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
                <div class="copyright">&copy; 2013-2020. All rights reserved. Rapid iPhone Repair.</div>
            </div>
            <div class="footer-menu">
                <ul>
                    <li class="title">Menu</li>
                    <li><a href="">About us</a></li>
                    <li><a href="">FAQ</a></li>
                    <li><a href="">Contact us</a></li>
                    <li><a href="">Financing</a></li>
                </ul>
                <ul>
                    <li class="title">Store Locations</li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="">1515 N Gilbert Rd, #D108, Gilbert, AZ 85234</a></li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="">1730 E Warner Rd, Suite 7, Tempe, AZ 85284</a></li>
                    <li class="location"><img src="{{ asset('client/images/marker_white.png') }}" alt="" /><a href="">1730 E Warner Rd, Suite 7, Tempe, AZ 85284</a></li>
                </ul>
                <ul>
                    <li class="title">Device Repairs</li>
                    <li><a href="">Phones</a></li>
                    <li><a href="">Ipads and Tablets</a></li>
                    <li><a href="">Macbooks and Computers</a></li>
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
