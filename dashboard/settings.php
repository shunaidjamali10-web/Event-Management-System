<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch current settings
$settings = $conn->query("SELECT * FROM site_settings WHERE id = 1")->fetch_assoc();

// Fetch all users for password reset
$users = $conn->query("SELECT id, username, email, role FROM users ORDER BY username ASC");
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="admin.php" class="btn btn-outline-custom btn-sm mb-2">&larr; Back to Dashboard</a>
            <h2 class="fw-bold mb-0">Site Settings</h2>
        </div>
    </div>

    <div class="row g-4">
        <!-- Site Settings -->
        <div class="col-md-6">
            <div class="card-custom p-4">
                <h5 class="mb-4"><i class="fa-solid fa-palette me-2"></i>Appearance & Branding</h5>
                <form action="../actions/update_settings.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control form-control-custom" 
                               value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <?php if (!empty($settings['logo_path'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo url($settings['logo_path']); ?>" alt="Current Logo" height="50" class="rounded">
                                <small class="text-muted d-block mt-1">Current logo</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="logo" class="form-control form-control-custom" accept="image/*">
                        <small class="text-muted">Leave empty to keep current logo. Recommended: PNG, max 200x200px.</small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Primary Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="primary_color" class="form-control form-control-color" 
                                       value="<?php echo htmlspecialchars($settings['primary_color']); ?>" style="width: 60px; height: 45px;">
                                <input type="text" class="form-control form-control-custom" 
                                       value="<?php echo htmlspecialchars($settings['primary_color']); ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Secondary Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="secondary_color" class="form-control form-control-color" 
                                       value="<?php echo htmlspecialchars($settings['secondary_color']); ?>" style="width: 60px; height: 45px;">
                                <input type="text" class="form-control form-control-custom" 
                                       value="<?php echo htmlspecialchars($settings['secondary_color']); ?>" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mb-4 p-3 rounded" style="background: var(--bg-secondary); border: 1px solid var(--card-border);">
                        <small class="text-muted d-block mb-2">Preview:</small>
                        <button type="button" class="btn btn-primary-custom btn-sm me-2">Primary Button</button>
                        <span class="badge-role role-admin">Badge</span>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Reset -->
        <div class="col-md-6">
            <div class="card-custom p-4">
                <h5 class="mb-4"><i class="fa-solid fa-key me-2"></i>Reset User Password</h5>
                <form action="../actions/reset_password.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Select User</label>
                        <select name="user_id" class="form-select form-control-custom" required>
                            <option value="">-- Select a user --</option>
                            <?php while($user = $users->fetch_assoc()): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['username']); ?> 
                                    (<?php echo $user['email']; ?>) 
                                    - <?php echo ucfirst($user['role']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control form-control-custom" 
                               placeholder="Enter new password" minlength="6" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-custom">Reset Password</button>
                    </div>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="card-custom p-4 mt-4">
                <h5 class="mb-3"><i class="fa-solid fa-info-circle me-2"></i>System Info</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom" style="border-color: var(--card-border) !important;">
                        <span class="text-muted">Last Updated</span>
                        <span>
                            <?php
                                if (!empty($settings['updated_at'])) {
                                    echo date('M d, Y h:i A', strtotime($settings['updated_at']));
                                } else {
                                    echo 'Never';
                                }
                            ?>
                        </span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">PHP Version</span>
                        <span><?php echo phpversion(); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
