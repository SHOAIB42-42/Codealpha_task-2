<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/navbar.php';

$agents = $pdo->query("SELECT * FROM agents ORDER BY rating DESC")->fetchAll();
?>

<section class="py-5 bg-primary-dark text-white text-center">
    <div class="container py-4">
        <h1 class="fw-bold display-4 mb-3">Meet Our Agents</h1>
        <p class="lead opacity-75">Connect with the best real estate experts in your area.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach($agents as $agent): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center p-4 h-100 hover-scale glass">
                    <div class="position-relative d-inline-block mx-auto mb-4">
                        <img src="<?php echo getAgentAvatar($agent['id'], $agent['full_name']); ?>" class="rounded-circle border border-4 border-light shadow-sm" width="120" height="120" style="object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 border border-3 border-white shadow-sm">
                            <i class="fas fa-check-circle" style="font-size: 0.8rem;"></i>
                        </span>
                    </div>
                    
                    <h4 class="fw-bold mb-1"><?php echo $agent['full_name']; ?></h4>
                    <p class="text-primary fw-bold small text-uppercase mb-3"><i class="fas fa-map-marker-alt me-1"></i> <?php echo $agent['operational_area']; ?></p>
                    
                    <div class="d-flex justify-content-center text-warning mb-3">
                        <?php 
                        $r = floor($agent['rating']);
                        for($i=1; $i<=5; $i++) echo '<i class="'.($i<=$r ? 'fas' : 'far').' fa-star mx-1"></i>';
                        ?>
                    </div>
                    
                    <div class="row g-3 mb-4 py-3 border-top border-bottom border-light">
                        <div class="col-6 border-end">
                            <h5 class="fw-bold mb-0 counter"><?php echo rand(15, 60); ?></h5>
                            <small class="text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">Properties</small>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">99%</h5>
                            <small class="text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">Success Rate</small>
                        </div>
                    </div>
                    
                    <p class="text-muted small mb-4">Dedicated professional with over 5 years of experience helping clients find their perfect real estate match.</p>
                    
                    <div class="d-flex gap-2">
                        <a href="tel:<?php echo $agent['cell_no']; ?>" class="btn btn-outline-primary rounded-pill flex-grow-1 fw-bold small shadow-sm">Call Now</a>
                        <button class="btn btn-primary rounded-pill flex-grow-1 fw-bold shadow-lg small">WhatsApp</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
