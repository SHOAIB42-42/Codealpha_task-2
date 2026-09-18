<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

// Initial fetch
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM properties p LEFT JOIN property_categories c ON p.category_id = c.id WHERE p.status = 'Available' ORDER BY p.id DESC");
$properties = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM property_categories")->fetchAll();
?>

<!-- Header -->
<section class="py-5 bg-dark text-white text-center position-relative" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1449844908441-8829872d2607?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
    <div class="container py-4">
        <h1 class="fw-bold display-4 mb-3 hero-title-animate"><span>Exclusive</span> <span class="text-primary">Listings</span></h1>
        <p class="lead opacity-75">Refine your search to find the perfect luxury property tailored to your needs.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Advanced Sidebar Filters -->
            <div class="col-lg-4 col-xl-3">
                <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top glass" style="top: 100px; z-index: 100;">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <h5 class="fw-bold mb-0">Elite Search</h5>
                        <i class="fas fa-sliders-h text-primary"></i>
                    </div>
                    
                    <form id="advanced-search-form">
                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase tracking-wider">Search Keywords</label>
                            <div class="input-group bg-light rounded-pill px-3 border-0 shadow-sm">
                                <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control bg-transparent border-0 py-3 small" placeholder="e.g. Modern Villa...">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase tracking-wider">Preferred City</label>
                            <select name="city" class="form-select bg-light border-0 rounded-pill py-3 px-4 small shadow-sm">
                                <option value="">All Regions</option>
                                <option value="Lahore">Lahore</option>
                                <option value="Islamabad">Islamabad</option>
                                <option value="Karachi">Karachi</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase tracking-wider">Acquisition Type</label>
                            <div class="d-flex gap-2 p-1 bg-light rounded-pill shadow-sm">
                                <input type="radio" class="btn-check" name="type" id="type-all" value="" checked>
                                <label class="btn btn-sm rounded-pill flex-grow-1 border-0" for="type-all">Both</label>
                                
                                <input type="radio" class="btn-check" name="type" id="type-rent" value="Rent">
                                <label class="btn btn-sm rounded-pill flex-grow-1 border-0" for="type-rent">Rent</label>
                                
                                <input type="radio" class="btn-check" name="type" id="type-sale" value="Sale">
                                <label class="btn btn-sm rounded-pill flex-grow-1 border-0" for="type-sale">Sale</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase tracking-wider">Property Category</label>
                            <select name="category" class="form-select bg-light border-0 rounded-pill py-3 px-4 small shadow-sm">
                                <option value="">Select Category</option>
                                <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <label class="small fw-bold text-muted text-uppercase tracking-wider">Budget Ceiling</label>
                                <span class="small fw-bold text-primary" id="priceValue">Rs. 50,000,000</span>
                            </div>
                            <input type="range" class="form-range" name="max_price" min="10000" max="150000000" step="50000" id="priceRange" value="50000000">
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-2 text-uppercase tracking-wider">Bedrooms (Min)</label>
                            <select name="bedrooms" class="form-select bg-light border-0 rounded-pill py-3 px-4 small shadow-sm">
                                <option value="">Any Configuration</option>
                                <option value="1">1+ Bed</option>
                                <option value="2">2+ Beds</option>
                                <option value="3">3+ Beds</option>
                                <option value="4">4+ Beds</option>
                                <option value="5">5+ Beds</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg mt-2 transition-all hover-scale">Apply Filters</button>
                    </form>
                </div>
            </div>
            
            <!-- Dynamic Grid -->
            <div class="col-lg-8 col-xl-9">
                <div class="row g-4" id="properties-grid">
                    <?php foreach ($properties as $prop): ?>
                    <div class="col-md-6 col-xl-4" data-aos="fade-up">
                        <div class="property-card h-100 shadow-sm border-0 bg-white">
                            <div class="property-image-wrapper">
                                <img src="<?php echo getPropertyImage($prop); ?>" alt="<?php echo $prop['title']; ?>" loading="lazy">
                                <div class="property-overlay"></div>
                                <div class="favorite-btn shadow-sm" onclick="toggleFavorite(<?php echo $prop['id']; ?>)">
                                    <i class="far fa-heart"></i>
                                </div>
                                <span class="property-badge bg-primary text-white shadow-sm fw-bold">FOR <?php echo strtoupper($prop['type']); ?></span>
                                <span class="property-badge bg-white text-dark shadow-sm fw-bold px-3 py-1" style="left: auto; right: 20px; font-size: 0.7rem;"><?php echo strtoupper($prop['category_name']); ?></span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-truncate mb-2"><?php echo $prop['title']; ?></h5>
                                <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo $prop['city']; ?></p>
                                <div class="d-flex gap-3 mb-4 py-3 border-top border-bottom border-light">
                                    <span class="small text-muted fw-bold"><i class="fas fa-bed text-primary me-2"></i><?php echo $prop['bedrooms']; ?> Bed</span>
                                    <span class="small text-muted fw-bold"><i class="fas fa-bath text-primary me-2"></i>2 Bath</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <h4 class="text-primary fw-bold mb-0">Rs. <?php echo number_format($prop['price']); ?></h4>
                                    <a href="property-details.php?id=<?php echo $prop['id']; ?>" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('priceRange');
    const output = document.getElementById('priceValue');
    if(slider) {
        slider.oninput = function() {
            output.innerHTML = 'Rs. ' + parseInt(this.value).toLocaleString();
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
