<footer class="footer bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="footer-title mb-3">
                    <i class="bi bi-building-add me-2"></i>
                    {{ config('app.name', 'Trivelo') }}
                </h5>
                <p class="mb-3">
                    Your trusted partner for premium hotel bookings worldwide. 
                    Experience luxury, comfort, and exceptional service with our curated selection of hotels.
                </p>
                <div class="d-flex">
                    <a href="#" class="text-light me-3" aria-label="Facebook">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="Twitter">
                        <i class="bi bi-twitter fs-5"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="Instagram">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-light me-3" aria-label="LinkedIn">
                        <i class="bi bi-linkedin fs-5"></i>
                    </a>
                    <a href="#" class="text-light" aria-label="YouTube">
                        <i class="bi bi-youtube fs-5"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="bi bi-house me-1"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#hotels" class="text-decoration-none">
                            <i class="bi bi-building me-1"></i> Hotels
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#about" class="text-decoration-none">
                            <i class="bi bi-info-circle me-1"></i> About Us
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#contact" class="text-decoration-none">
                            <i class="bi bi-envelope me-1"></i> Contact
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#careers" class="text-decoration-none">
                            <i class="bi bi-briefcase me-1"></i> Careers
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title mb-3">Support</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#help" class="text-decoration-none">
                            <i class="bi bi-question-circle me-1"></i> Help Center
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#faq" class="text-decoration-none">
                            <i class="bi bi-chat-dots me-1"></i> FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#booking-help" class="text-decoration-none">
                            <i class="bi bi-calendar-check me-1"></i> Booking Help
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#cancellation" class="text-decoration-none">
                            <i class="bi bi-x-circle me-1"></i> Cancellation
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#support" class="text-decoration-none">
                            <i class="bi bi-headset me-1"></i> 24/7 Support
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Legal -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title mb-3">Legal</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#privacy" class="text-decoration-none">
                            <i class="bi bi-shield-check me-1"></i> Privacy Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#terms" class="text-decoration-none">
                            <i class="bi bi-file-text me-1"></i> Terms of Service
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#cookies" class="text-decoration-none">
                            <i class="bi bi-cookie me-1"></i> Cookie Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#refund" class="text-decoration-none">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refund Policy
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#accessibility" class="text-decoration-none">
                            <i class="bi bi-universal-access me-1"></i> Accessibility
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="footer-title mb-3">Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        <small>123 Business St<br>New York, NY 10001</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:+1234567890" class="text-decoration-none">
                            <small>+1 (234) 567-8900</small>
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:info@trivelo.com" class="text-decoration-none">
                            <small>info@trivelo.com</small>
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <small>24/7 Support</small>
                    </li>
                </ul>

                <!-- App Download -->
                <div class="mt-3">
                    <h6 class="mb-2">Download Our App</h6>
                    <a href="#" class="d-block mb-2">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" 
                             alt="Download on App Store" style="height: 32px;">
                    </a>
                    <a href="#" class="d-block">
                        <img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png" 
                             alt="Get it on Google Play" style="height: 48px;">
                    </a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="footer-divider my-4">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Trivelo') }}. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end justify-content-start flex-wrap">
                    <span class="me-3 small text-muted">
                        <i class="bi bi-shield-check me-1"></i> Secure Booking
                    </span>
                    <span class="me-3 small text-muted">
                        <i class="bi bi-award me-1"></i> Best Price Guarantee
                    </span>
                    <span class="small text-muted">
                        <i class="bi bi-headset me-1"></i> 24/7 Support
                    </span>
                </div>
            </div>
        </div>

        <!-- Newsletter Signup -->
        <div class="row mt-4">
            <div class="col-lg-8 mx-auto text-center">
                <h6 class="mb-3">Stay Updated with Our Latest Offers</h6>
                <form class="row g-2 justify-content-center" action="#" method="POST">
                    @csrf
                    <div class="col-auto">
                        <input type="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-envelope me-1"></i> Subscribe
                        </button>
                    </div>
                </form>
                <small class="text-muted mt-2 d-block">
                    No spam, unsubscribe anytime. Read our 
                    <a href="#privacy" class="text-decoration-none">Privacy Policy</a>.
                </small>
            </div>
        </div>
    </div>
</footer>