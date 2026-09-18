<?php
// frontend/user-dashboard.php
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

// Fetch Unread Notifications Count
$stmtNotifCount = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
$stmtNotifCount->execute([$user_id]);
$unreadCount = $stmtNotifCount->fetchColumn();

// Fetch User's Bookings
$stmt = $pdo->prepare("SELECT b.*, p.title as property_title, p.image as property_image, a.full_name as agent_name 
                       FROM bookings b 
                       JOIN properties p ON b.property_id = p.id 
                       JOIN agents a ON b.agent_id = a.id 
                       WHERE b.user_id = ? ORDER BY b.booking_date DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

// Fetch Saved Properties (using property_favorites table)
$stmtFav = $pdo->prepare("SELECT f.*, p.*, c.name as category_name 
                          FROM property_favorites f 
                          JOIN properties p ON f.property_id = p.id 
                          LEFT JOIN property_categories c ON p.category_id = c.id
                          WHERE f.user_id = ?");
$stmtFav->execute([$user_id]);
$favorites = $stmtFav->fetchAll();

include 'includes/navbar.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-sticky" style="top: 100px;">
                    <div class="card-body p-4 text-center border-bottom border-light">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=198754&color=fff" class="rounded-circle shadow-sm mb-3" width="80" height="80" alt="User Avatar">
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($username); ?></h5>
                        <p class="text-muted small mb-0">Premium Member</p>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#dashboard" class="list-group-item list-group-item-action py-3 fw-bold active bg-primary-light text-primary border-0" data-bs-toggle="pill"><i class="fas fa-tachometer-alt me-2"></i> Dashboard Overview</a>
                        <a href="my-visit-requests.php" class="list-group-item list-group-item-action py-3 fw-bold border-0"><i class="fas fa-calendar-day me-2 text-muted"></i> My Visit Requests</a>
                        <a href="#bookings" class="list-group-item list-group-item-action py-3 fw-bold border-0" data-bs-toggle="pill"><i class="fas fa-calendar-check me-2 text-muted"></i> My Bookings</a>
                        <a href="#saved" class="list-group-item list-group-item-action py-3 fw-bold border-0" data-bs-toggle="pill"><i class="fas fa-heart me-2 text-muted"></i> Saved Properties</a>
                        <a href="#messages" class="list-group-item list-group-item-action py-3 fw-bold border-0 d-flex justify-content-between align-items-center" data-bs-toggle="pill">
                            <span><i class="fas fa-envelope me-2 text-muted"></i> Messages</span>
                            <span class="badge bg-danger rounded-pill">2</span>
                        </a>
                        <a href="#settings" class="list-group-item list-group-item-action py-3 fw-bold border-0" data-bs-toggle="pill"><i class="fas fa-cog me-2 text-muted"></i> Account Settings</a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="col-lg-9">
                <div class="tab-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold mb-0">Welcome back, <?php echo htmlspecialchars($username); ?>!</h3>
                            <div class="dropdown">
                                <button class="btn btn-white shadow-sm rounded-circle position-relative" type="button" data-bs-toggle="dropdown" id="notificationBell">
                                    <i class="fas fa-bell text-primary"></i>
                                    <?php if ($unreadCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge">
                                        <?php echo $unreadCount; ?>
                                    </span>
                                    <?php endif; ?>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0 overflow-hidden" style="width: 320px;">
                                    <div class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold">Notifications</h6>
                                        <button class="btn btn-sm btn-light rounded-pill small py-0 px-2" onclick="markAllAsRead()">Mark all as read</button>
                                    </div>
                                    <div class="notification-list" id="notificationList" style="max-height: 400px; overflow-y: auto;">
                                        <!-- Notifications will be loaded here via AJAX -->
                                        <div class="text-center py-4">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        </div>
                                    </div>
                                    <div class="p-2 border-top text-center bg-light">
                                        <small class="text-muted" id="unreadText">You have <?php echo $unreadCount; ?> unread notifications</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100 transition-all hover-scale">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="fw-bold mb-0"><?php echo count($bookings); ?></h2>
                                            <p class="small text-white-50 text-uppercase tracking-widest mb-0">Total Bookings</p>
                                        </div>
                                        <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white h-100 transition-all hover-scale">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="fw-bold mb-0"><?php echo count($favorites); ?></h2>
                                            <p class="small text-white-50 text-uppercase tracking-widest mb-0">Saved Homes</p>
                                        </div>
                                        <i class="fas fa-heart fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm rounded-4 bg-white border h-100 transition-all hover-scale">
                                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="fw-bold mb-0 text-primary">2</h2>
                                            <p class="small text-muted text-uppercase tracking-widest mb-0">New Messages</p>
                                        </div>
                                        <i class="fas fa-envelope fa-3x text-light"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Notifications -->
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-0 py-3 px-4 fw-bold">Recent Notifications</div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-4 py-3 d-flex align-items-start">
                                        <div class="bg-primary-light text-primary rounded-circle p-2 me-3"><i class="fas fa-calendar-check"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Booking Confirmed</h6>
                                            <p class="text-muted small mb-0">Your visit to "The Diamond Sky Villa" on Oct 25 at 10:00 AM is confirmed.</p>
                                        </div>
                                        <small class="text-muted ms-auto">2h ago</small>
                                    </div>
                                    <div class="list-group-item px-4 py-3 d-flex align-items-start">
                                        <div class="bg-danger-subtle text-danger rounded-circle p-2 me-3"><i class="fas fa-fire"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Price Drop Alert</h6>
                                            <p class="text-muted small mb-0">A property in your favorites just dropped its price by 5%.</p>
                                        </div>
                                        <small class="text-muted ms-auto">1d ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visit Requests Tab -->
                    <div class="tab-pane fade" id="visit-requests">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold mb-0">📅 My Visit Requests</h3>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="refreshVisitRequests()">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
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
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-scale">
                                        <div class="row g-0">
                                            <div class="col-4">
                                                <img src="../uploads/properties/<?php echo $vr['property_image']; ?>" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 120px;">
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <?php 
                                                            $statusClass = 'bg-warning text-dark';
                                                            if($vr['booking_status'] == 'Approved') $statusClass = 'bg-success text-white';
                                                            elseif($vr['booking_status'] == 'Rejected') $statusClass = 'bg-danger text-white';
                                                            elseif($vr['booking_status'] == 'Completed') $statusClass = 'bg-primary text-white';
                                                        ?>
                                                        <span class="badge <?php echo $statusClass; ?> rounded-pill small px-2 py-1">
                                                            <?php echo $vr['booking_status']; ?>
                                                        </span>
                                                    </div>
                                                    <h6 class="fw-bold text-truncate mb-1"><?php echo htmlspecialchars($vr['property_title']); ?></h6>
                                                    <p class="text-muted small mb-2">
                                                        <i class="far fa-calendar-alt me-1"></i><?php echo date('M d, Y', strtotime($vr['visit_date'])); ?> 
                                                        <i class="far fa-clock ms-2 me-1"></i><?php echo $vr['visit_time']; ?>
                                                    </p>
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill w-100" data-visit='<?php echo htmlspecialchars(json_encode($vr), ENT_QUOTES, 'UTF-8'); ?>' onclick='viewVisitDetails(this)'>View Details</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bookings Tab -->
                    <div class="tab-pane fade" id="bookings">
                        <h3 class="fw-bold mb-4">My Scheduled Visits</h3>
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted small text-uppercase">
                                        <tr>
                                            <th class="px-4 py-3">Property</th>
                                            <th>Date & Time</th>
                                            <th>Agent</th>
                                            <th>Status</th>
                                            <th class="text-end px-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($bookings)): ?>
                                        <tr><td colspan="5" class="text-center py-4 text-muted">No bookings found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($bookings as $booking): ?>
                                            <tr>
                                                <td class="px-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="../uploads/properties/<?php echo $booking['property_image']; ?>" class="rounded-3 me-3" width="50" height="50" style="object-fit: cover;">
                                                        <span class="fw-bold"><?php echo htmlspecialchars($booking['property_title']); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></div>
                                                    <small class="text-muted"><?php echo $booking['booking_time']; ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($booking['agent_name']); ?></td>
                                                <td>
                                                    <?php 
                                                        $badgeClass = 'bg-warning-subtle text-warning-emphasis';
                                                        if($booking['status'] == 'Confirmed') $badgeClass = 'bg-success-subtle text-success';
                                                        elseif($booking['status'] == 'Cancelled') $badgeClass = 'bg-danger-subtle text-danger';
                                                    ?>
                                                    <span class="badge rounded-pill <?php echo $badgeClass; ?> px-3 py-1"><?php echo $booking['status']; ?></span>
                                                </td>
                                                <td class="text-end px-4">
                                                    <button class="btn btn-sm btn-outline-dark rounded-pill">Reschedule</button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Saved Properties Tab -->
                    <div class="tab-pane fade" id="saved">
                        <h3 class="fw-bold mb-4">Saved Properties</h3>
                        <div class="row g-4">
                            <?php if(empty($favorites)): ?>
                                <div class="col-12 text-center py-5">
                                    <i class="far fa-heart fa-4x text-light mb-3"></i>
                                    <h5 class="text-muted">You haven't saved any properties yet.</h5>
                                    <a href="properties.php" class="btn btn-primary rounded-pill mt-3">Explore Properties</a>
                                </div>
                            <?php else: ?>
                                <?php foreach($favorites as $fav): ?>
                                <div class="col-md-6">
                                    <div class="property-card h-100 shadow-sm border-0 bg-white">
                                        <div class="property-image-wrapper" style="height: 200px;">
                                            <img src="<?php echo getPropertyImage($fav); ?>" alt="<?php echo $fav['title']; ?>" loading="lazy">
                                            <div class="favorite-btn shadow-sm text-danger" onclick="toggleFavorite(<?php echo $fav['id']; ?>)">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-truncate mb-1"><?php echo $fav['title']; ?></h6>
                                            <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo $fav['city']; ?></p>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="text-primary fw-bold">Rs. <?php echo number_format($fav['price']); ?></span>
                                                <a href="property-details.php?id=<?php echo $fav['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Messages Tab -->
                    <div class="tab-pane fade" id="messages">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="height: 600px;">
                            <div class="row g-0 h-100">
                                <div class="col-md-4 border-end bg-light overflow-auto h-100">
                                    <div class="p-3 border-bottom">
                                        <h5 class="fw-bold mb-0">Conversations</h5>
                                    </div>
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action py-3 px-3 border-bottom active">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative me-3">
                                                    <img src="https://i.pravatar.cc/150?u=agent1" class="rounded-circle" width="45" height="45">
                                                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle p-1"></span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <h6 class="fw-bold mb-0 text-truncate">Hamza Ali</h6>
                                                        <small class="text-muted" style="font-size: 0.65rem;">10:30 AM</small>
                                                    </div>
                                                    <p class="text-muted small mb-0 text-truncate">Yes, the property is available for viewing tomorrow.</p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- More conversation items can go here -->
                                    </div>
                                </div>
                                <div class="col-md-8 d-flex flex-column h-100">
                                    <div class="p-3 border-bottom d-flex align-items-center">
                                        <img src="https://i.pravatar.cc/150?u=agent1" class="rounded-circle me-3" width="40" height="40">
                                        <div>
                                            <h6 class="fw-bold mb-0">Hamza Ali</h6>
                                            <small class="text-success"><i class="fas fa-circle" style="font-size: 0.5rem;"></i> Online</small>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 p-4 overflow-auto bg-white d-flex flex-column gap-3">
                                        <div class="align-self-start bg-light p-3 rounded-4 shadow-sm" style="max-width: 75%;">
                                            <p class="mb-0 small">Hi there! Are you interested in the Grand Luxury Villa?</p>
                                            <small class="text-muted mt-1 d-block" style="font-size: 0.65rem;">10:25 AM</small>
                                        </div>
                                        <div class="align-self-end bg-primary text-white p-3 rounded-4 shadow-sm" style="max-width: 75%;">
                                            <p class="mb-0 small">Yes, I'd like to schedule a viewing for tomorrow.</p>
                                            <small class="text-white-50 mt-1 d-block" style="font-size: 0.65rem;">10:28 AM</small>
                                        </div>
                                        <div class="align-self-start bg-light p-3 rounded-4 shadow-sm" style="max-width: 75%;">
                                            <p class="mb-0 small">Yes, the property is available for viewing tomorrow. Does 10 AM work for you?</p>
                                            <small class="text-muted mt-1 d-block" style="font-size: 0.65rem;">10:30 AM</small>
                                        </div>
                                    </div>
                                    <div class="p-3 border-top bg-light">
                                        <div class="input-group bg-white rounded-pill overflow-hidden border shadow-sm">
                                            <input type="text" class="form-control border-0 px-4 py-2" placeholder="Type a message...">
                                            <button class="btn btn-primary px-4"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div class="tab-pane fade" id="settings">
                        <h3 class="fw-bold mb-4">Account Settings</h3>
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <form>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                                        <input type="text" class="form-control bg-light border-0 py-2" value="<?php echo htmlspecialchars($username); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                                        <input type="email" class="form-control bg-light border-0 py-2" value="demo@estatehub.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Phone Number</label>
                                        <input type="text" class="form-control bg-light border-0 py-2" placeholder="+92 300 0000000">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Preferred Language</label>
                                        <select class="form-select bg-light border-0 py-2">
                                            <option>English</option>
                                            <option>Urdu</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" onclick="showToast('Profile updated successfully!')">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
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

<!-- Add custom script for tab interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simplify tab handling - let Bootstrap handle the switching, we just sync the sidebar classes
    const pills = document.querySelectorAll('.list-group-item[data-bs-toggle="pill"]');
    pills.forEach(pill => {
        pill.addEventListener('shown.bs.tab', function (event) {
            pills.forEach(p => p.classList.remove('bg-primary-light', 'text-primary', 'active'));
            event.target.classList.add('bg-primary-light', 'text-primary', 'active');
        });
    });

    // Load notifications on dropdown open
    const notificationBell = document.getElementById('notificationBell');
    if (notificationBell) {
        notificationBell.addEventListener('show.bs.dropdown', function () {
            loadNotifications();
        });
    }
});

function loadNotifications() {
    const list = document.getElementById('notificationList');
    fetch('ajax/get-notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.notifications.length === 0) {
                    list.innerHTML = '<div class="text-center py-4 text-muted small">No notifications found.</div>';
                } else {
                    let html = '';
                    data.notifications.forEach(notif => {
                        const timeAgo = getTimeAgo(new Date(notif.created_at));
                        html += `
                            <div class="list-group-item list-group-item-action px-3 py-3 border-0 border-bottom ${notif.is_read == 0 ? 'bg-light' : ''}">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary-light text-primary rounded-circle p-2 me-3"><i class="fas fa-bell"></i></div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1 small">${notif.title}</h6>
                                        <p class="text-muted mb-1" style="font-size: 0.75rem;">${notif.message}</p>
                                        <small class="text-muted" style="font-size: 0.65rem;">${timeAgo}</small>
                                    </div>
                                    ${notif.is_read == 0 ? '<span class="badge bg-primary rounded-pill p-1 ms-2" style="width: 8px; height: 8px;"> </span>' : ''}
                                </div>
                            </div>
                        `;
                    });
                    list.innerHTML = html;
                }
            }
        });
}

function markAllAsRead() {
    fetch('ajax/mark-notifications-read.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const badge = document.getElementById('notificationBadge');
                if (badge) badge.remove();
                const unreadText = document.getElementById('unreadText');
                if (unreadText) unreadText.innerText = 'You have 0 unread notifications';
                loadNotifications();
            }
        });
}

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

    document.getElementById('modalVisitDate').innerText = formatDate(vr.visit_date);
    document.getElementById('modalVisitTime').innerText = vr.visit_time;
    document.getElementById('modalBookingDate').innerText = formatDate(vr.created_at);
    document.getElementById('modalNotes').innerText = vr.notes || 'No special notes provided.';

    if (typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(document.getElementById('visitDetailsModal'));
        modal.show();
    } else {
        console.error('Bootstrap is not loaded');
    }
}

function formatDate(dateStr) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateStr).toLocaleDateString(undefined, options);
}

function getTimeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    let interval = seconds / 31536000;
    if (interval > 1) return Math.floor(interval) + " years ago";
    interval = seconds / 2592000;
    if (interval > 1) return Math.floor(interval) + " months ago";
    interval = seconds / 86400;
    if (interval > 1) return Math.floor(interval) + " days ago";
    interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + "h ago";
    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + "m ago";
    return Math.floor(seconds) + "s ago";
}

function refreshVisitRequests() {
    const container = document.getElementById('visitRequestsContainer');
    container.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
    
    fetch('ajax/get-visit-requests.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.visitRequests.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-light mb-3"></i>
                            <h5 class="text-muted">No visit requests found.</h5>
                            <a href="properties.php" class="btn btn-primary rounded-pill mt-3">Browse Properties</a>
                        </div>
                    `;
                } else {
                    let html = '';
                    data.visitRequests.forEach(vr => {
                        let statusClass = 'bg-warning text-dark';
                        if(vr.booking_status === 'Approved') statusClass = 'bg-success text-white';
                        else if(vr.booking_status === 'Rejected') statusClass = 'bg-danger text-white';
                        else if(vr.booking_status === 'Completed') statusClass = 'bg-primary text-white';
                        
                        html += `
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-scale">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            <img src="../uploads/properties/${vr.property_image}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 120px;">
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge ${statusClass} rounded-pill small px-2 py-1">${vr.booking_status}</span>
                                                </div>
                                                <h6 class="fw-bold text-truncate mb-1">${vr.property_title}</h6>
                                                <p class="text-muted small mb-2">
                                                    <i class="far fa-calendar-alt me-1"></i>${formatDate(vr.visit_date)} 
                                                    <i class="far fa-clock ms-2 me-1"></i>${vr.visit_time}
                                                </p>
                                                <button class="btn btn-sm btn-outline-primary rounded-pill w-100" data-visit='${JSON.stringify(vr).replace(/'/g, "&apos;")}' onclick='viewVisitDetails(this)'>View Details</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                }
            }
        });
}
</script>

<?php include 'includes/footer.php'; ?>
