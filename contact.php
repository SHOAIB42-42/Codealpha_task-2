<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="py-5 bg-primary-dark text-white text-center">
    <div class="container py-4">
        <h1 class="fw-bold display-4 mb-3">Contact Us</h1>
        <p class="lead opacity-75">We are here to help you with any questions or concerns.</p>
    </div>
</section>

<section class="py-5">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Contact Cards -->
            <div class="col-lg-4" data-aos="fade-up">
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="fas fa-map-marker-alt fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Our Office</h6>
                            <p class="text-muted small mb-0">123 Real Estate Ave, Lahore, Pakistan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="fas fa-phone-alt fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Phone Number</h6>
                            <p class="text-muted small mb-0">+92 300 1234567</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4 border-start border-4 border-primary">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="fas fa-envelope fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Email Address</h6>
                            <p class="text-muted small mb-0">info@estatehub.com</p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Follow Us</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-primary rounded-circle" style="width: 45px; height: 45px;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-primary rounded-circle" style="width: 45px; height: 45px;"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-primary rounded-circle" style="width: 45px; height: 45px;"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-primary rounded-circle" style="width: 45px; height: 45px;"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white p-5 rounded-4 shadow-sm h-100">
                    <h3 class="fw-bold mb-4">Send Us A Message</h3>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-2">Your Name</label>
                                <input type="text" class="form-control bg-light border-0 py-3" placeholder="Enter your name">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-2">Email Address</label>
                                <input type="email" class="form-control bg-light border-0 py-3" placeholder="Enter your email">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold text-muted mb-2">Subject</label>
                                <input type="text" class="form-control bg-light border-0 py-3" placeholder="Enter subject">
                            </div>
                            <div class="col-12">
                                <label class="small fw-bold text-muted mb-2">Message</label>
                                <textarea class="form-control bg-light border-0 py-3" rows="5" placeholder="Write your message here..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="button" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm" onclick="alert('Thank you! Your message has been sent.')">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Google Map Placeholder -->
        <div class="mt-5 pt-5" data-aos="zoom-in">
            <div class="rounded-4 overflow-hidden shadow-sm" style="height: 400px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108911.77583641753!2d74.223067184277!3d31.455018610313495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3919015f2081f211%3A0x6b6284f18d727b14!2sLahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1716644500000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
