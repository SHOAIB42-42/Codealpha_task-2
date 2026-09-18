<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Fetch Featured Properties (Limit 6)
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM properties p LEFT JOIN property_categories c ON p.category_id = c.id WHERE p.status = 'Available' ORDER BY p.id DESC LIMIT 6");
$featured_properties = $stmt->fetchAll();

// Fetch Top Agents
$agents = $pdo->query("SELECT * FROM agents ORDER BY rating DESC LIMIT 4")->fetchAll();

$testimonials = getTestimonials();
?>

<!-- Premium SaaS Hero Section -->
<section class="hero-section position-relative overflow-hidden" style="min-height: 95vh; display: flex; align-items: center; padding: 140px 0; background-color: #0f172a;">
    <!-- Cinematic Video Background Wrapper -->
    <div class="hero-bg-wrapper position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;">
        <!-- Vibrant Luxury Real Estate Video -->
        <video autoplay muted loop playsinline class="w-100 h-100 position-absolute top-0 start-0 video-vibrant" style="object-fit: cover; opacity: 0; transition: opacity 2s ease-in-out;" onplay="this.style.opacity=1">
            <source src="https://player.vimeo.com/external/517729219.hd.mp4?s=4e43f3f2a89326e6328373b5f39642055653228d&profile_id=172" type="video/mp4">
            <source src="https://player.vimeo.com/external/434045526.hd.mp4?s=c27dc3699b051b69f1441ed4a30dfcbb0ae085d6&profile_id=172" type="video/mp4">
        </video>
        
        <!-- Fallback High-Quality Luxury Image (Vibrant) -->
        <div class="hero-bg-fallback position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1613490493576-7fde63acd811?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat; z-index: -1;"></div>

        <!-- Advanced Cinematic Overlay: Vignette + Focal Blur -->
        <div class="hero-overlay-vignette position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 50%, rgba(15, 23, 42, 0.1) 100%); z-index: 1;"></div>
        <div class="hero-overlay-depth position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 20% 50%, transparent 0%, rgba(15, 23, 42, 0.4) 60%, rgba(15, 23, 42, 0.8) 100%); z-index: 2;"></div>
    </div>

    <!-- Abstract Shape Overlays with Neon Glow -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 1;">
        <div class="position-absolute bg-primary opacity-20 rounded-circle neon-glow animate-pulse" style="width: 500px; height: 500px; top: -150px; left: -150px; filter: blur(120px);"></div>
        <div class="position-absolute bg-accent opacity-10 rounded-circle animate-pulse-slow" style="width: 400px; height: 400px; bottom: -100px; right: -100px; filter: blur(100px);"></div>
    </div>

    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center justify-content-between g-xl-5 g-4">
            <div class="col-lg-7 text-start" data-aos="fade-right" data-aos-duration="1200">
                <span class="badge bg-primary-light text-primary rounded-pill px-4 py-2 mb-4 fw-bold shadow-sm text-uppercase tracking-widest animate__animated animate__fadeInDown hero-badge-glow" style="letter-spacing: 2px;">Elite Real Estate & Rental Platform</span>
                <h1 class="display-3 fw-bold text-white mb-4 lh-base tracking-tighter text-shadow-sm">
                    Find Your <span class="text-accent text-glow">Dream Property</span> <br> With Ease
                </h1>
                <p class="lead text-white-50 mb-5 fs-4 pe-lg-5 lh-lg text-shadow-sm">Experience a new standard of luxury. Browse our curated collection of premier properties in the nation's most prestigious areas.</p>
                
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="properties.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg transition-all hover-scale">Explore Properties <i class="fas fa-arrow-right ms-2"></i></a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold transition-all hover-scale">Contact Agent</a>
                </div>

                <!-- Integrated Statistics -->
                <div class="row g-4 mt-2">
                    <div class="col-4 col-md-3">
                        <div class="stat-item">
                            <h3 class="fw-bold text-white mb-0 counter" data-target="500">0</h3>
                            <p class="text-white-50 small text-uppercase mb-0 fw-semibold">Properties</p>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="stat-item">
                            <h3 class="fw-bold text-white mb-0 counter" data-target="120">0</h3>
                            <p class="text-white-50 small text-uppercase mb-0 fw-semibold">Agents</p>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="stat-item">
                            <h3 class="fw-bold text-white mb-0 counter" data-target="1000">0</h3>
                            <p class="text-white-50 small text-uppercase mb-0 fw-semibold">Happy Clients</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Glassmorphism Search Form -->
            <div class="col-xl-5 col-lg-6 col-md-11 mx-auto mx-lg-0" data-aos="fade-left" data-aos-duration="1200">
                <div class="search-box-container glass p-4 p-md-5 rounded-5 shadow-2xl mt-lg-0 mt-5">
                    <h4 class="fw-bold text-white mb-4">Quick Property Search</h4>
                    <form action="properties.php" method="GET" class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-white-50 small fw-bold text-uppercase ms-2">Location</label>
                            <div class="input-group glass-input rounded-pill px-3 border-0">
                                <span class="input-group-text bg-transparent border-0 pe-1"><i class="fas fa-map-marker-alt text-accent"></i></span>
                                <input type="text" name="city" class="form-control bg-transparent border-0 py-3 text-white fw-bold" placeholder="City or Neighborhood" style="min-width: 0;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-white-50 small fw-bold text-uppercase ms-2">Property Type</label>
                            <div class="input-group glass-input rounded-pill px-3 border-0">
                                <select name="type" class="form-select bg-transparent border-0 py-3 text-white fw-bold">
                                    <option value="" class="text-dark">Any Type</option>
                                    <option value="Rent" class="text-dark">For Rent</option>
                                    <option value="Sale" class="text-dark">For Sale</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-white-50 small fw-bold text-uppercase ms-2">Budget</label>
                            <div class="input-group glass-input rounded-pill px-3 border-0">
                                <select name="price" class="form-select bg-transparent border-0 py-3 text-white fw-bold">
                                    <option value="" class="text-dark">Max Budget</option>
                                    <option value="1000000" class="text-dark">1M</option>
                                    <option value="5000000" class="text-dark">5M</option>
                                    <option value="50000000" class="text-dark">50M+</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg transition-all hover-scale">Search Properties <i class="fas fa-search ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Featured Listings Section -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div class="section-title mb-0 text-start">
                <span class="text-primary fw-bold text-uppercase" style="letter-spacing: 3px;">Elite Showcase</span>
                <h2 class="mt-2 fw-bold display-5">Featured Listings</h2>
                <p class="text-muted">Discover our hand-picked premium properties across the country.</p>
            </div>
            <a href="properties.php" class="btn btn-outline-dark rounded-pill px-5 py-2 fw-bold mb-2 shadow-sm transition-all hover-scale">View All Properties</a>
        </div>
        
        <div class="row g-4" id="featured-listings-container">
            <?php foreach($featured_properties as $prop): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="property-card h-100 shadow-lg border-0 overflow-hidden bg-white">
                    <div class="property-image-wrapper">
                        <!-- Main Image -->
                        <img src="<?php echo getPropertyImage($prop, 0); ?>" alt="<?php echo $prop['title']; ?>" loading="lazy">
                        
                        <!-- Badges -->
                        <div class="position-absolute top-0 start-0 p-3 d-flex flex-column gap-2">
                            <span class="badge bg-primary text-white shadow-sm fw-bold px-3 py-2">FOR <?php echo strtoupper($prop['type']); ?></span>
                            <?php if($prop['price'] > 50000000): ?>
                                <span class="badge badge-luxury shadow-sm fw-bold px-3 py-2"><i class="fas fa-crown me-1"></i> LUXURY</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 p-3">
                            <span class="badge bg-white text-dark shadow-sm fw-bold px-3 py-2 rounded-pill"><?php echo $prop['status']; ?></span>
                        </div>

                        <!-- Favorite & Quick Actions -->
                        <div class="favorite-btn shadow-sm" onclick="toggleFavorite(<?php echo $prop['id']; ?>)">
                            <i class="far fa-heart"></i>
                        </div>
                        
                        <div class="property-quick-actions">
                            <button class="btn btn-quick-view shadow-lg" onclick="openQuickView(<?php echo htmlspecialchars(json_encode($prop)); ?>)">
                                <i class="fas fa-expand me-1"></i> Quick View
                            </button>
                        </div>

                        <!-- Gradient & Price -->
                        <div class="property-overlay p-4 d-flex align-items-end">
                            <h4 class="fw-bold text-white mb-0">Rs. <?php echo number_format($prop['price']); ?></h4>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-2">
                            <span class="text-primary small fw-bold text-uppercase tracking-wider"><?php echo $prop['category_name']; ?></span>
                        </div>
                        <h5 class="fw-bold text-truncate mb-2"><?php echo $prop['title']; ?></h5>
                        <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo $prop['location_area'] . ', ' . $prop['city']; ?></p>
                        
                        <div class="row g-2 py-3 border-top border-light mb-4">
                            <div class="col-4 text-center border-end">
                                <i class="fas fa-bed text-primary mb-1"></i>
                                <span class="d-block small fw-bold text-dark"><?php echo $prop['bedrooms'] ?: 'N/A'; ?> Bed</span>
                            </div>
                            <div class="col-4 text-center border-end">
                                <i class="fas fa-bath text-primary mb-1"></i>
                                <span class="d-block small fw-bold text-dark">2 Bath</span>
                            </div>
                            <div class="col-4 text-center">
                                <i class="fas fa-vector-square text-primary mb-1"></i>
                                <span class="d-block small fw-bold text-dark">1200 ft²</span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <div class="row g-2">
                                <div class="col-8">
                                    <a href="property-details.php?id=<?php echo $prop['id']; ?>" class="btn btn-dark w-100 rounded-pill py-2 fw-bold shadow-sm transition-all hover-scale">Details & Inquiry</a>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-outline-primary w-100 rounded-pill py-2 fw-bold shadow-sm transition-all hover-scale" onclick="showToast('Contacting Agent...')">
                                        <i class="fas fa-phone-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <button id="load-more-featured" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg transition-all hover-scale">
                <i class="fas fa-sync-alt me-2"></i> Load More Properties
            </button>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-10 bg-white p-2 rounded-circle" data-bs-dismiss="modal"></button>
                <div class="row g-0">
                    <div class="col-md-6">
                        <img id="qv-image" src="" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;">
                    </div>
                    <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                        <span id="qv-category" class="text-primary fw-bold text-uppercase small tracking-widest mb-2"></span>
                        <h3 id="qv-title" class="fw-bold mb-3"></h3>
                        <p id="qv-location" class="text-muted small mb-4"></p>
                        
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <div class="property-info-pill">
                                <i class="fas fa-bed text-primary"></i> <span id="qv-beds"></span> Beds
                            </div>
                            <div class="property-info-pill">
                                <i class="fas fa-vector-square text-primary"></i> 1200 ft²
                            </div>
                            <div class="property-info-pill">
                                <i class="fas fa-tag text-primary"></i> For <span id="qv-type"></span>
                            </div>
                        </div>

                        <h2 id="qv-price" class="text-primary fw-bold mb-4"></h2>
                        
                        <div class="d-grid gap-2">
                            <a id="qv-link" href="#" class="btn btn-dark rounded-pill py-3 fw-bold">Full Property Details</a>
                            <button class="btn btn-outline-primary rounded-pill py-3 fw-bold" onclick="showToast('Booking Request Sent!')">Request A Tour</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Us with Parallax Background -->
<section class="py-5 bg-light my-5" style="border-radius: 80px 0;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-5 shadow-2xl" alt="">
                    <div class="glass position-absolute bottom-0 end-0 m-4 p-4 rounded-4 shadow-lg d-none d-md-block" style="width: 250px;">
                        <h5 class="fw-bold text-primary mb-1">5-Star Rated</h5>
                        <p class="small text-muted mb-0">Trusted by over 8,000 families worldwide.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="text-primary fw-bold small text-uppercase tracking-widest">Why Choose EstateHub</span>
                <h2 class="fw-bold display-4 mt-2 mb-4">We Define <span class="text-primary">Next-Gen</span> Real Estate</h2>
                <p class="text-muted mb-5 fs-5">Our platform combines cutting-edge AI technology with years of industry expertise to bring you a seamless property experience.</p>
                
                <div class="row g-4">
                    <?php 
                    $features = [
                        ['icon' => 'fa-shield-check', 'title' => 'Verified Listings', 'desc' => 'Every property undergoes a 20-point verification.'],
                        ['icon' => 'fa-robot', 'title' => 'AI Guidance', 'desc' => 'Intelligent chatbot to find your perfect match.'],
                        ['icon' => 'fa-file-invoice-dollar', 'title' => 'Transparent Billing', 'desc' => 'Automated, secure, and hassle-free payments.'],
                        ['icon' => 'fa-headset', 'title' => 'VIP Support', 'desc' => 'Dedicated concierge for your every need.']
                    ];
                    foreach($features as $f): ?>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary-light text-primary p-3 rounded-4 me-3 shadow-sm">
                                <i class="fas <?php echo $f['icon']; ?> fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1"><?php echo $f['title']; ?></h6>
                                <p class="text-muted small mb-0"><?php echo $f['desc']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Elite Testimonials Swiper -->
<section class="py-5">
    <div class="container py-5 text-center">
        <div class="section-title mb-5" data-aos="fade-up">
            <span class="text-primary fw-bold text-uppercase tracking-widest">Client Voices</span>
            <h2 class="mt-2 fw-bold display-5">What Our Elite Members Say</h2>
        </div>
        
        <div class="swiper testimonial-slider pb-5" data-aos="zoom-in">
            <div class="swiper-wrapper">
                <?php foreach($testimonials as $t): ?>
                <div class="swiper-slide px-2">
                    <div class="testimonial-card glass p-5 text-center h-100 border-0 shadow-lg rounded-5">
                        <div class="text-warning mb-4">
                            <?php for($i=1; $i<=5; $i++) echo '<i class="'.($i<=$t['stars'] ? 'fas' : 'far').' fa-star mx-1"></i>'; ?>
                        </div>
                        <p class="text-muted italic mb-4 fs-6">"<?php echo $t['text']; ?>"</p>
                        <div class="d-flex align-items-center justify-content-center border-top border-light pt-4">
                            <img src="<?php echo $t['img']; ?>" class="rounded-circle me-3 shadow-sm" width="60" height="60" style="object-fit: cover;">
                            <div class="text-start">
                                <h6 class="fw-bold mb-0 text-dark"><?php echo $t['name']; ?></h6>
                                <small class="text-primary fw-bold text-uppercase" style="font-size: 0.6rem;"><?php echo $t['role']; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination mt-5"></div>
        </div>
    </div>
</section>

<script>
function openQuickView(prop) {
    document.getElementById('qv-title').innerText = prop.title;
    document.getElementById('qv-category').innerText = prop.category_name;
    document.getElementById('qv-location').innerHTML = '<i class="fas fa-map-marker-alt text-danger me-2"></i>' + prop.location_area + ', ' + prop.city;
    document.getElementById('qv-beds').innerText = prop.bedrooms;
    document.getElementById('qv-type').innerText = prop.type;
    document.getElementById('qv-price').innerText = 'Rs. ' + parseInt(prop.price).toLocaleString();
    document.getElementById('qv-link').href = 'property-details.php?id=' + prop.id;
    
    // Use the same image logic as card
    const dummyImg = getPropertyDummyImage(prop);
    document.getElementById('qv-image').src = dummyImg;

    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    modal.show();
}

// Simple logic to mirror the PHP getPropertyImage in JS for Quick View
function getPropertyDummyImage(prop) {
    const category = prop.category_name.toLowerCase();
    const id = prop.id;
    let keyword = 'house';
    if (category.includes('villa')) keyword = 'mansion-villa';
    else if (category.includes('apartment')) keyword = 'luxury-apartment';
    else if (category.includes('office') || category.includes('commercial')) keyword = 'modern-office';
    
    return `https://source.unsplash.com/800x600/?${keyword}&sig=${id}`;
}
</script>

<?php include 'includes/footer.php'; ?>
