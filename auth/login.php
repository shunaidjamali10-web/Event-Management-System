<?php include '../includes/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card-custom p-4 p-md-5">
            <h3 class="text-center mb-4">Welcome Back</h3>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>

            <form action="auth_process.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-custom">Sign In</button>
                </div>
            </form>
            <div class="text-center mt-4">
                <span class="text-muted small">Don't have an account?</span> 
                <a href="register.php" class="text-primary fw-bold small text-decoration-none">Create one</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
