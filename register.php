<?php
// frontend/register.php
require_once '../config/database.php';
include 'includes/header.php';
?>

<div class="auth-wrapper" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <div class="container d-flex justify-content-center">
        <div class="auth-card glass fade-in" style="max-width: 500px; padding: 50px;">
            <div class="text-center mb-5">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-user-plus fs-2"></i>
                </div>
                <h2 class="fw-bold text-dark display-6">Create Account</h2>
                <p class="text-muted fs-5">Join the elite real estate marketplace</p>
            </div>

            <form action="../login.php" method="POST">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-widest ms-2">Account Username</label>
                    <div class="input-group bg-light rounded-pill px-4 border-0 shadow-inner">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="far fa-user"></i></span>
                        <input type="text" name="username" class="form-control bg-transparent border-0 py-3 fw-bold" placeholder="e.g. john_doe" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-widest ms-2">Email Address</label>
                    <div class="input-group bg-light rounded-pill px-4 border-0 shadow-inner">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="far fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control bg-transparent border-0 py-3 fw-bold" placeholder="name@luxury.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase tracking-widest ms-2">Secure Password</label>
                    <div class="input-group bg-light rounded-pill px-4 border-0 shadow-inner">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="far fa-lock"></i></span>
                        <input type="password" name="password" class="form-control bg-transparent border-0 py-3 fw-bold" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-check mb-5 px-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label small text-muted" for="terms">
                        I accept the <a href="#" class="text-primary text-decoration-none fw-bold">Privary Policy</a> and <a href="#" class="text-primary text-decoration-none fw-bold">Terms of Service</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-2xl mb-4 transition-all hover-scale">Begin Journey <i class="fas fa-chevron-right ms-2"></i></button>

                <div class="text-center">
                    <p class="small text-muted mb-0">Already an elite member? <a href="../login.php" class="text-primary fw-bold text-decoration-none border-bottom border-primary pb-1">Sign In Now</a></p>
                </div>
            </form>
            
            <div class="mt-5 pt-4 border-top border-light text-center">
                <a href="index.php" class="btn btn-link btn-sm text-decoration-none text-muted fw-bold"><i class="fas fa-home me-2"></i>Return to Homepage</a>
            </div>
        </div>
    </div>
</div>

<style>
.shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06) !important; }
.shadow-2xl { box-shadow: 0 25px 50px -12px rgba(25, 135, 84, 0.25) !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
