<?php
// frontend/my-visit-requests.php
require_once '../config/database.php';
require_once '../includes/helpers.php';
include 'includes/header.php';

// Proper authentication check
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch User's Visit Requests
$stmtVisit = $pdo->prepare("SELECT vb.*, p.title as property_title, p.image as property_image 
                            FROM visit_bookings vb 
                            JOIN properties p ON vb.property_id = p.id 
                            WHERE vb.user_id = ? ORDER BY vb.created_at DESC");
$stmtVisit->execute([$user_id]);
$visitRequests = $stmtVisit->fetchAll();

include 'includes/navbar.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">📅 My Visit Requests</h2>
                <p class="text-muted">Track the status of your property viewing requests</p>
            </div>
            <a href="user-dashboard.php" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>

        <div class="row g-4" id="visitRequestsContainer">
            <?php if(empty($visitRequests)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-light mb-3"></i>
                    <h5 class="text-muted">No visit requests found.</h5>
                    <a href="properties.php" class="btn btn-primary rounded-pill mt-3">Browse Properties</a>
                </div>
            <?php else: ?>
                <?php foreach($visitRequests as $vr): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-scale">
                        <div class="position-relative">
                            <img src="../uploads/properties/<?php echo $vr['property_image']; ?>" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 p-3">
                                <?php 
                                    $statusClass = 'bg-warning text-dark';
                                    if($vr['booking_status'] == 'Approved') $statusClass = 'bg-success text-white';
                                    elseif($vr['booking_status'] == 'Rejected') $statusClass = 'bg-danger text-white';
                                    elseif($vr['booking_status'] == 'Completed') $statusClass = 'bg-primary text-white';
                                ?>
                                <span class="badge <?php echo $statusClass; ?> rounded-pill px-3 py-2 shadow-sm">
                                    <?php echo $vr['booking_status']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-truncate mb-3"><?php echo htmlspecialchars($vr['property_title']); ?></h5>
                            
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.6rem;">Visit Date</small>
                                        <span class="small fw-bold"><?php echo date('M d, Y', strtotime($vr['visit_date'])); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.6rem;">Visit Time</small>
                                        <span class="small fw-bold"><?php echo $vr['visit_time']; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <small class="text-muted">Requested on <?php echo date('M d, Y', strtotime($vr['created_at'])); ?></small>
                                <button class="btn btn-sm btn-primary rounded-pill px-3" data-visit='<?php echo htmlspecialchars(json_encode($vr), ENT_QUOTES, 'UTF-8'); ?>' onclick='viewVisitDetails(this)'>Details</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Visit Details Modal -->
<div class="modal fade" id="visitDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="text-center mb-4">
                    <div id="modalPropertyImg" class="rounded-4 overflow-hidden mb-3" style="height: 200px;"></div>
                    <h4 class="fw-bold mb-1" id="modalPropertyTitle"></h4>
                    <span id="modalStatusBadge" class="badge rounded-pill px-3 py-2"></span>
                </div>
                
                <div class="card bg-light border-0 rounded-4 p-3 mb-3">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Booking Information</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Visit Date</small>
                            <span class="fw-bold" id="modalVisitDate"></span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Visit Time</small>
                            <span class="fw-bold" id="modalVisitTime"></span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Booking Date</small>
                            <span class="fw-bold" id="modalBookingDate"></span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-0">
                    <h6 class="fw-bold mb-2">Notes</h6>
                    <p class="text-muted small mb-0" id="modalNotes"></p>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill w-100 fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewVisitDetails(btn) {
    const vr = JSON.parse(btn.getAttribute('data-visit'));
    document.getElementById('modalPropertyTitle').innerText = vr.property_title;
    document.getElementById('modalPropertyImg').innerHTML = `<img src="../uploads/properties/${vr.property_image}" class="img-fluid w-100 h-100" style="object-fit: cover;">`;
    
    const badge = document.getElementById('modalStatusBadge');
    badge.innerText = vr.booking_status;
    badge.className = 'badge rounded-pill px-3 py-2 ';
    if (vr.booking_status === 'Pending') badge.classList.add('bg-warning', 'text-dark');
    else if (vr.booking_status === 'Approved') badge.classList.add('bg-success', 'text-white');
    else if (vr.booking_status === 'Rejected') badge.classList.add('bg-danger', 'text-white');
    else if (vr.booking_status === 'Completed') badge.classList.add('bg-primary', 'text-white');

    document.getElementById('modalVisitDate').innerText = new Date(vr.visit_date).toLocaleDateString();
    document.getElementById('modalVisitTime').innerText = vr.visit_time;
    document.getElementById('modalBookingDate').innerText = new Date(vr.created_at).toLocaleDateString();
    document.getElementById('modalNotes').innerText = vr.notes || 'No special notes provided.';

    const modal = new bootstrap.Modal(document.getElementById('visitDetailsModal'));
    modal.show();
}
</script>

<?php include 'includes/footer.php'; ?>
