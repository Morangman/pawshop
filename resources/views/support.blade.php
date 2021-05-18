@extends('layouts.app')

@section('home-title', 'Support')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))

    <div class="main-content">
        <section class="sprt-section">
            <div class="container">
                <h1>Have Questions?</h1>
                <div class="description">We're here to help!</div>
                <div class="sprt-section">
                    <h2>rapid-recycle.com Support</h2>
                    <p>Have questions? Use this page to get answers.</p>
                    <p>Can't find the answers you want? Use the contact us form at the bottom of the page and we'll get back to you <span>REALLY FAST</span>, at max, within a business day.</p>
                </div>
                <div class="faqs-content">
                    <h2>About rapid-recycle.com</h2>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What's it all about?
                        </div>
                        <div class="faqs-answer">
                            rapid-recycle.com is a service that buys your used electronics online. We provide our customers with an easy way to sell their unwanted electronics without the hassle and risks associated with auctions.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Why Should I trust rapid-recycle.com?
                        </div>
                        <div class="faqs-answer">
                            We understand that you might feel uneasy about sending us your item(s). However, here at Rapid Recycle, nothing matters more to us than providing a phenomenal experience while creating customer value and building a relationship of trust with our customers. Rest assured that Rapid Recycle understands and acknowledges the trust our customers put into our business. We continuously strive to ensure that we deliver to our customers securely and in a timely manner. These actions are taken understanding the time invested into the development, organization and entrepreneurship of this website is focused to ensure it is both user friendly and offers top dollar for your electronics, it would be a bad business decision on our part not to honor our promise and your trust.
                            <br>
                            <br>
                            To help increase your comfort level, we suggest reviewing the following resources.
                            <br>
                            <br>
                            Check out our rating on ResellerRatings.com created through awesome service.
                            A+ rating with the Better Business Bureau
                        </div>
                    </div>
                </div>
                <div class="faqs-content">
                    <h2>How are the electronics evaluated?</h2>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            How are the electronics evaluated?
                        </div>
                        <div class="faqs-answer">
                            The electronics are evaluated via multiple steps. The first is making sure that the model you have selected is accurate compared to the device delivered. We assess the physical condition of the item(s) to see if the category you selected matches its description. We look for scratches, dents, etc. We also look to see if the item(s) is real or counterfeit, as well as if there was any tampering to the device. Your item(s) is then checked for water damage and the battery life is also tested. Lastly, we look to make sure that all passwords are removed so we can reset the phone and wipe out all personal information. If your assessment was accurate, we will send you the money in the method selected. If not, we will offer you a new price, which you can accept, or ask for your item(s) back. Rest assured, at Rapid Recycle, we will always give you the most value for your item.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What do I do with my SIM card?
                        </div>
                        <div class="faqs-answer">
                            We do not need your SIM card in order to evaluate your item. Please remove your SIM card prior to sending us the phone. If you forget, don't worry, we will dispose of your SIM card during our evaluation of your phone.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What does “unlocked” mean?
                        </div>
                        <div class="faqs-answer">
                            A phone that is “unlocked” is one that is not associated to a carrier. As we do offer more money for phones that are “unlocked”, you can ask your carrier to unlock your phone for you and then submit it as an “unlocked” phone. You would have to reach out to your carrier to see if they can unlock the phone for you.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if I do not agree with the evaluation of my phone?
                        </div>
                        <div class="faqs-answer">
                            In the event that you do not agree with the evaluation of your phone, you can ask for a reinspection where a fresh pair of eyes will evaluate your phone and see if they agree with the evaluation that was done already or if they can increase the offer amount.
                        </div>
                    </div>
                </div>
                <div class="faqs-content">
                    <h2>Payment</h2>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            How and when will I get paid?
                        </div>
                        <div class="faqs-answer">
                            We currently offer three methods of payment - Check, PayPal™, or Zelle™. You'll be asked to indicate your preference when you check out.
                            <br>
                            <br>
                            Your payment will be sent to you after we receive and inspect your item(s), which typically takes 3 to 7 business days. After your item(s) have been inspected, payment is processed within 24 to 48 hours of approval. We do offer an expedited option that guarantees 24-hour processing and a faster shipping method.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What should I do if I haven't received my payment?
                        </div>
                        <div class="faqs-answer">
                            Once we have inspected your item(s), we typically issue the payment within 24 to 48 hours of approval. For each payment, you will receive an email from us notifying you of the payment along with the confirmation number.
                            <br>
                            <br>
                            Please be aware that the time it takes for you to receive your payment will differ depending on your preferred method of getting paid.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if I put my wrong personal information for payment or wrong payment type?
                        </div>
                        <div class="faqs-answer">
                            If you want to change your payment type, you will have to contact us via email, for one of our representatives to make the changes in the backend. These changes will reflect under the “Adjustment” notes on your offer page. If you have the wrong address, or any other incorrect personal information associated with your account, you cannot modify that information for an offer that has already been submitted. This is done for security reasons. In the event the change requested can be completed you will be able to see these changes on your offer page.
                        </div>
                    </div>
                </div>
                <div class="faqs-content">
                    <h2>Selling to rapid-recycle.com</h2>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if I'm not sure about the condition of my product?
                        </div>
                        <div class="faqs-answer">
                            No problem, just select a condition that you feel is accurate. Once we receive your item(s), we will evaluate it and adjust the offer accordingly.
                            <br>
                            <br>
                            Please note that the final offer price could end up being higher or lower. If it's lower, we will provide a detailed email regarding the adjustment and you would then have 3 days to accept the offer. If you do not respond within the 3 day time period, we will assume you have accepted the offer and will initiate the payment to be processed.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Does rapid-recycle.com accept items from outside of the United States?
                        </div>
                        <div class="faqs-answer">
                            No. At the present, we do not accept shipments from outside of the United States.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Is there a limit on the number of items I can sell?
                        </div>
                        <div class="faqs-answer">
                            No. There is no limit, you can sell as many items as you want. However, for bulk sales (10 or more items), please contact us first before creating an offer. All bulk sales are subject to final approval and may be cancelled for any reason whatsoever as deemed necessary.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Is my personal data erased from the item(s)?
                        </div>
                        <div class="faqs-answer">
                            Yes. We personally see to it that every item that comes into our office is wiped of all data per NIST standards.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if I am unsure of the model of my item(s)?
                        </div>
                        <div class="faqs-answer">
                            Most items have their model number provided in the "About" area of the device's settings. This information may also be found with your previous cellular carrier and is commonly provided when requested
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What do I need to send in to get the quoted offer amount?
                        </div>
                        <div class="faqs-answer">
                            Depending on which item you have selected, you may have the option of selecting the accessories that you are including with your package. If you choose not to include any accessories, you do not need to send any accessories in. If you select headphones for example, we will expect to see a pair of headphones. For any items in the “New” condition, we expect all accessories to be included as we pay a premium for these items.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What do I do with my SIM card?
                        </div>
                        <div class="faqs-answer">
                            We do not need your SIM card in order to evaluate your item. Please remove your SIM card prior to sending us the phone. If you forget, don't worry, we will dispose of your SIM card during our evaluation of your phone.
                        </div>
                    </div>
                </div>
                <div class="faqs-content">
                    <h2>Shipping an item</h2>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            How do I ship an item to rapid-recycle.com?
                        </div>
                        <div class="faqs-answer">
                            Once you accept an offer and during the checkout process, you’ll be prompted to select your shipping carrier: FedEx or UPS. Please print out the shipping label and follow the instructions on the label. Once your package is ready to ship, please drop it off at the nearest FedEx or UPS location.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            How do I track my package?
                        </div>
                        <div class="faqs-answer">
                            We will provide you with a prepaid and trackable shipping label via FedEx or UPS, that you can easily print out. Delivery Confirmation is included with the label, so you can verify that the item successfully arrived at our location. We will also email you when we receive your package. If you want to get all updates on your package, it is crucial that you provide us with the correct email address and check it so that you can receive our updates on the process.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            My package weighs more than the weight shown on the shipping label. What should I do?
                        </div>
                        <div class="faqs-answer">
                            That's okay. FedEx or UPS will charge us for the difference in weight if at all there is any.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Is my package Insured?
                        </div>
                        <div class="faqs-answer">
                            All items are insured against theft and accidental damage whilst in transit from your FedEx or UPS drop-off for up to $100. If you'd like to fully insure your package, you can purchase our shipping insurance to cover the full value of your offer, during checkout, for a fee. Please note that Rapid Recycle will not be held liable for any damages incurred due to inadequate packaging.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Does rapid-recycle.com accept items from outside of the United States?
                        </div>
                        <div class="faqs-answer">
                            No. At present, we do not accept shipments from outside of the United States.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if I do not have a printer to print out my shipping label?
                        </div>
                        <div class="faqs-answer">
                            If you do not have a printer, that's okay. Contact us via email, chat, or phone and one of our representatives will take your information and have your shipping label mailed to you via USPS. Please be sure to have your offer number ready so that our representative and locate the appropriate shipping label and send it to you as soon as possible.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if my item is damaged in shipping?
                        </div>
                        <div class="faqs-answer">
                            It is your responsibility to make sure that your item(s) are fully secure when you ship them. If you ship using our prepaid shipping labels, all packages are insured up to $100. If a package is received and looks to have been damaged due to mishandling, we will claim insurance for that package. However, if the box is secure and does not look as though anything happened during transit and the item inside was not secure, we will offer you an amount based off the condition we receive the phone in.
                        </div>
                    </div>
                </div>
                <div class="faqs-content">
                    <h2>Your rapid-recycle.com account</h2>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            Why should I sign up for an account?
                        </div>
                        <div class="faqs-answer">
                            Signing up allows you to track your offers and/or any referral commission that you may have earned. You will also have your information saved with us and can checkout much faster the next time you choose to sell your electronics to us.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            I haven't received a Confirmation Email. What Should I do?
                        </div>
                        <div class="faqs-answer">
                            Please ensure that you have entered a valid email address, and that the confirmation email has not been sent to your Junk or Spam Mail Folder. Some email programs may mistakenly misroute your confirmation email. We recommend adding rapid-recycle.com to your list of Safe Senders to ensure proper email delivery.
                        </div>
                    </div>
                    <div class="faqs-block">
                        <div class="faqs-question">
                            What if I put my wrong personal information for payment or wrong payment type?
                        </div>
                        <div class="faqs-answer">
                            If you want to change your payment type, you will have to contact us via email, chat, or phone for one of our representatives to make the changes in the backend. These changes will reflect under the “Adjustment” notes on your offer page. If you have the wrong address, or any other incorrect personal information associated with your account, you cannot modify that information for an offer that has already been submitted. This is done for security reasons. You will have to contact us to modify the information for an offer that has already been submitted. Once again, you will be able to see these changes on your offer page.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @yield('footer', View::make('footer', ['categories' => $categories, 'settings' => $settings]))
@endsection
