<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM properties p LEFT JOIN property_categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$prop = $stmt->fetch();

if (!$prop) {
    echo "<div class='container py-5 text-center'><h3 class='fw-bold'>Property Not Found</h3><p class='text-muted'>The listing you are looking for might have been moved or removed.</p><a href='properties.php' class='btn btn-primary rounded-pill px-4'>Explore Listings</a></div>";
    include 'includes/footer.php';
    exit();
}

$stmtGal = $pdo->prepare("SELECT image_path FROM property_images WHERE property_id = ?");
$stmtGal->execute([$id]);
$gallery = $stmtGal->fetchAll();

$stmtSim = $pdo->prepare("SELECT p.*, c.name as category_name FROM properties p LEFT JOIN property_categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? LIMIT 3");
$stmtSim->execute([$prop['category_id'], $id]);
$similar = $stmtSim->fetchAll();

$amenities = getAmenitiesList();
?>

<!-- Premium Gallery Showcase -->
<section class="py-5 bg-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted small fw-bold">Home</a></li>
                <li class="breadcrumb-item"><a href="properties.php" class="text-decoration-none text-muted small fw-bold">Properties</a></li>
                <li class="breadcrumb-item active text-primary small fw-bold"><?php echo $prop['title']; ?></li>
            </ol>
        </nav>
        
        <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-right">
                <!-- Elite Swiper Gallery -->
                <div class="rounded-5 overflow-hidden shadow-2xl mb-5 position-relative">
                    <div class="swiper property-gallery">
                        <div class="swiper-wrapper">
                            <!-- Main Exterior -->
                            <div class="swiper-slide">
                                <img src="<?php echo getPropertyImage($prop, 0); ?>" class="w-100" style="height: 600px; object-fit: cover;">
                            </div>
                            <!-- Dynamic Interior Gallery -->
                            <div class="swiper-slide">
                                <img src="<?php echo getPropertyImage($prop, 1); ?>" class="w-100" style="height: 600px; object-fit: cover;">
                            </div>
                            <div class="swiper-slide">
                                <img src="<?php echo getPropertyImage($prop, 2); ?>" class="w-100" style="height: 600px; object-fit: cover;">
                            </div>
                            <div class="swiper-slide">
                                <img src="<?php echo getPropertyImage($prop, 3); ?>" class="w-100" style="height: 600px; object-fit: cover;">
                            </div>
                            <?php foreach($gallery as $img): ?>
                            <div class="swiper-slide">
                                <img src="../uploads/properties/<?php echo $img['image_path']; ?>" class="w-100" style="height: 600px; object-fit: cover;">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="position-absolute top-0 start-0 m-4 z-10">
                        <span class="badge bg-primary rounded-pill px-4 py-2 shadow-lg fs-6">Elite Collection</span>
                    </div>
                </div>

                <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5 mb-5 glass">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
                        <div class="mb-3 mb-md-0">
                            <h1 class="fw-bold mb-2 display-6"><?php echo $prop['title']; ?></h1>
                            <p class="text-muted mb-0 fs-5"><i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo $prop['location_area'] . ', ' . $prop['city']; ?></p>
                        </div>
                        <div class="text-md-end">
                            <h1 class="text-primary fw-bold mb-0 display-6">Rs. <?php echo number_format($prop['price']); ?></h1>
                            <span class="badge bg-primary-light text-primary px-4 py-2 rounded-pill fw-bold mt-2 shadow-sm">Listed for <?php echo $prop['type']; ?></span>
                        </div>
                    </div>
                    
                    <div class="row g-3 py-5 border-top border-bottom border-light mb-5">
                        <div class="col-6 col-md-3 text-center border-end border-light">
                            <div class="bg-light p-3 rounded-4 mb-2 d-inline-block">
                                <i class="fas fa-bed text-primary fs-3"></i>
                            </div>
                            <p class="small text-muted mb-1 text-uppercase fw-bold">Bedrooms</p>
                            <span class="fw-bold fs-5"><?php echo $prop['bedrooms']; ?> Rooms</span>
                        </div>
                        <div class="col-6 col-md-3 text-center border-md-end border-light">
                            <div class="bg-light p-3 rounded-4 mb-2 d-inline-block">
                                <i class="fas fa-bath text-primary fs-3"></i>
                            </div>
                            <p class="small text-muted mb-1 text-uppercase fw-bold">Bathrooms</p>
                            <span class="fw-bold fs-5">2 Baths</span>
                        </div>
                        <div class="col-6 col-md-3 text-center border-end border-light mt-4 mt-md-0">
                            <div class="bg-light p-3 rounded-4 mb-2 d-inline-block">
                                <i class="fas fa-layer-group text-primary fs-3"></i>
                            </div>
                            <p class="small text-muted mb-1 text-uppercase fw-bold">Property Type</p>
                            <span class="fw-bold fs-5"><?php echo $prop['category_name']; ?></span>
                        </div>
                        <div class="col-6 col-md-3 text-center mt-4 mt-md-0">
                            <div class="bg-light p-3 rounded-4 mb-2 d-inline-block">
                                <i class="fas fa-vector-square text-primary fs-3"></i>
                            </div>
                            <p class="small text-muted mb-1 text-uppercase fw-bold">Living Space</p>
                            <span class="fw-bold fs-5">1200 Sqft</span>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-4">Sophisticated Description</h4>
                    <p class="text-muted lh-lg mb-5 fs-5"><?php echo getPropertyDescription($prop['description']); ?></p>
                    
                    <h4 class="fw-bold mb-4">Elite Amenities & Features</h4>
                    <div class="row g-4">
                        <?php foreach(getEliteAmenities() as $a): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="amenity-card shadow-sm border h-100">
                                <i class="fas <?php echo $a['icon']; ?> <?php echo $a['class']; ?>"></i>
                                <h6 class="fw-bold mb-0"><?php echo $a['title']; ?></h6>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <h4 class="fw-bold mb-4 mt-5">Property Video Tour</h4>
                    <?php if($prop['video_url']): ?>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg mb-5">
                        <iframe src="<?php echo $prop['video_url']; ?>" title="Property Video" allowfullscreen></iframe>
                    </div>
                    <?php else: ?>
                    <div class="bg-light p-5 rounded-4 text-center mb-5 border">
                        <i class="fas fa-video-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Virtual tour not available for this property.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lead Generation Sidebar -->
            <div class="col-lg-4" data-aos="fade-left">
                <!-- Booking Form Card -->
                <div class="card border-0 shadow-2xl rounded-5 p-4 mb-4 glass sticky-top" style="top: 100px; z-index: 99;">
                    <div class="text-center mb-4 border-bottom border-light pb-3">
                        <h4 class="fw-bold text-primary mb-1">Schedule a Tour</h4>
                        <p class="text-muted small">Book a visit with our luxury concierge</p>
                    </div>
                    
                    <form class="booking-form" id="bookingForm">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#bookingModal" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg mb-3 transition-all hover-scale">Request Private Tour <i class="fas fa-calendar-alt ms-2"></i></button>
                    </form>
                    
                    <div class="d-flex align-items-center mt-4 pt-3 border-top border-light">
                        <div class="position-relative me-3">
                            <img src="<?php echo getAgentAvatar($prop['id'], 'Expert'); ?>" class="rounded-circle shadow-lg" width="60" height="60" style="object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 bg-success border border-3 border-white rounded-circle p-2"></span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Luxury Concierge</h6>
                            <p class="text-muted small mb-0"><i class="fas fa-star text-warning me-1"></i> 5.0 Platinum Agent</p>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn btn-dark rounded-pill py-2 small fw-bold transition-all hover-scale" onclick="openChat()"><i class="fas fa-comments me-2"></i>Live Chat</button>
                        <a href="#" class="btn btn-outline-success rounded-pill py-2 small fw-bold transition-all hover-scale"><i class="fab fa-whatsapp me-2"></i>Secure WhatsApp</a>
                    </div>
                </div>

                <!-- Google Map with Premium Styling -->
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden mb-4 glass">
                    <div class="card-header bg-transparent border-0 py-3 px-4 fw-bold">Geographic Location</div>
                    <div style="height: 300px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d108911.77583641753!2d74.223067184277!3d31.455018610313495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3919015f2081f211%3A0x6b6284f18d727b14!2sLahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1716644500000!5m2!1sen!2s" width="100%" height="100%" style="border:0; filter: grayscale(1) invert(0.1) opacity(0.8);" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(!empty($similar)): ?>
        <div class="mt-5 pt-5" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold">Discover Similar Assets</h2>
                <a href="properties.php" class="text-primary text-decoration-none fw-bold small tracking-widest">EXPLORE ALL <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            <div class="row g-4">
                <?php foreach($similar as $sprop): ?>
                <div class="col-md-4">
                    <div class="property-card h-100 shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                        <div class="property-image-wrapper" style="height: 250px;">
                            <img src="<?php echo getPropertyImage($sprop); ?>" alt="<?php echo $sprop['title']; ?>" loading="lazy">
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold text-truncate mb-2"><?php echo $sprop['title']; ?></h5>
                            <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo $sprop['city']; ?></p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <span class="text-primary fw-bold fs-5">Rs. <?php echo number_format($sprop['price']); ?></span>
                                <a href="property-details.php?id=<?php echo $sprop['id']; ?>" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-5">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5 pt-0 text-center">
                <div class="bg-primary-light text-primary rounded-circle d-inline-block p-4 mb-4">
                    <i class="fas fa-calendar-check fa-3x"></i>
                </div>
                <h3 class="fw-bold mb-2">Schedule a Visit</h3>
                <p class="text-muted mb-4">Experience the luxury in person. Fill in your details below.</p>
                
                <form id="detailedBookingForm" class="text-start">
                    <input type="hidden" name="property_id" value="<?php echo $id; ?>">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small fw-bold text-muted text-uppercase mb-1 ms-2">Full Name</label>
                            <input type="text" name="visitor_name" class="form-control bg-light border-0 py-3 px-4 rounded-pill shadow-inner" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted text-uppercase mb-1 ms-2">Email Address</label>
                            <input type="email" name="visitor_email" class="form-control bg-light border-0 py-3 px-4 rounded-pill shadow-inner" placeholder="john@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted text-uppercase mb-1 ms-2">Phone Number</label>
                            <input type="tel" name="visitor_phone" class="form-control bg-light border-0 py-3 px-4 rounded-pill shadow-inner" placeholder="+1 234 567 890" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted text-uppercase mb-1 ms-2">Visit Date</label>
                            <input type="date" name="visit_date" class="form-control bg-light border-0 py-3 px-4 rounded-pill shadow-inner" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted text-uppercase mb-1 ms-2">Preferred Time</label>
                            <select name="visit_time" class="form-select bg-light border-0 py-3 px-4 rounded-pill shadow-inner" required>
                                <option value="">Select Time</option>
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="03:00 PM">03:00 PM</option>
                                <option value="05:00 PM">05:00 PM</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-muted text-uppercase mb-1 ms-2">Special Notes</label>
                            <textarea name="notes" class="form-control bg-light border-0 py-3 px-4 rounded-4 shadow-inner" rows="3" placeholder="Anything else you'd like us to know?"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg mt-4 transition-all hover-scale">Confirm Booking Request <i class="fas fa-paper-plane ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper(".property-gallery", {
        loop: true,
        spaceBetween: 10,
        effect: "fade",
        fadeEffect: { crossFade: true },
        autoplay: { delay: 6000 },
        pagination: { el: ".swiper-pagination", clickable: true },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
    });

    // Booking Submission Logic
    const bookingForm = document.getElementById('detailedBookingForm');
    if(bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
            
            const formData = new FormData(this);
            
            fetch('ajax/book-visit.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    showToast(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
                    this.reset();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
