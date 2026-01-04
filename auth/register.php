<?php 
include '../includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card-custom p-4 p-md-5">
                <h2 class="fw-bold mb-4 text-center">Create Account</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <form action="auth_process.php" method="POST">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control form-control-custom" placeholder="Your full name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control form-control-custom" placeholder="Choose a username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-custom" placeholder="your@email.com" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control form-control-custom" placeholder="03XX-XXXXXXX" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-custom" placeholder="Minimum 6 characters" minlength="6" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Register As</label>
                        <select name="role" class="form-select form-control-custom">
                            <option value="attendee">Attendee (Book Events)</option>
                            <option value="manager">Manager (Create Events)</option>
                        </select>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom">Create Account</button>
                    </div>
                </form>
                
                <p class="text-center mt-4 mb-0 text-muted">
                    Already have an account? <a href="login.php">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
