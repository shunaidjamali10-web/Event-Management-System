<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get user's stats based on role
if ($_SESSION['role'] === 'attendee') {
    $stats = $conn->query("SELECT COUNT(*) as bookings FROM bookings WHERE user_id = $user_id")->fetch_assoc();
} elseif ($_SESSION['role'] === 'manager') {
    $stats = $conn->query("SELECT COUNT(*) as events FROM events WHERE created_by = $user_id")->fetch_assoc();
} else {
    $stats = ['users' => $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c']];
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Profile Card -->
            <div class="card-custom p-4 text-center">
                <div class="mb-3">
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary-accent), var(--secondary-accent)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 2.5rem; font-weight: 800; color: white;">
                        <?php echo strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1)); ?>
                    </div>
                </div>
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h4>
                <p class="text-muted mb-3">@<?php echo htmlspecialchars($user['username']); ?></p>
                <span class="badge-role role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span>
                
                <hr style="border-color: var(--card-border);">
                
                <div class="text-start">
                    <p class="mb-2">
                        <i class="fa-solid fa-envelope me-2 text-muted"></i>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </p>
                    <p class="mb-2">
                        <i class="fa-solid fa-phone me-2 text-muted"></i>
                        <?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?>
                    </p>
                    <p class="mb-0">
                        <i class="fa-solid fa-calendar me-2 text-muted"></i>
                        Joined <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                    </p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card-custom p-4 mt-4">
                <h6 class="text-muted mb-3">Quick Stats</h6>
                <?php if ($_SESSION['role'] === 'attendee'): ?>
                    <div class="d-flex justify-content-between">
                        <span>Total Bookings</span>
                        <span class="fw-bold"><?php echo $stats['bookings']; ?></span>
                    </div>
                <?php elseif ($_SESSION['role'] === 'manager'): ?>
                    <div class="d-flex justify-content-between">
                        <span>Events Created</span>
                        <span class="fw-bold"><?php echo $stats['events']; ?></span>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between">
                        <span>Total Users</span>
                        <span class="fw-bold"><?php echo $stats['users']; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Edit Profile Form -->
            <div class="card-custom p-4">
                <h4 class="fw-bold mb-4">Edit Profile</h4>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <form action="../actions/update_profile.php" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control form-control-custom" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control form-control-custom" 
                                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-custom" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control form-control-custom" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="03XX-XXXXXXX">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" name="action" value="update_info" class="btn btn-primary-custom">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card-custom p-4 mt-4">
                <h4 class="fw-bold mb-4">Change Password</h4>
                <form action="../actions/update_profile.php" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control form-control-custom" minlength="6" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" name="action" value="change_password" class="btn btn-outline-custom">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
