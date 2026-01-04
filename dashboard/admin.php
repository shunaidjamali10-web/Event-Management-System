<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch Stats
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$total_events = $conn->query("SELECT COUNT(*) as c FROM events")->fetch_assoc()['c'];
$total_bookings = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];
$total_revenue = $conn->query("SELECT SUM(total_price) as r FROM bookings")->fetch_assoc()['r'] ?? 0;

// Fetch Users
$users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

// Fetch Chart Data
$chart_sql = "SELECT title, (total_tickets - available_tickets) as sold FROM events";
$chart_res = $conn->query($chart_sql);
$labels = [];
$data = [];
while($row = $chart_res->fetch_assoc()) {
    $labels[] = $row['title'];
    $data[] = $row['sold'];
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Admin Dashboard</h2>
        <a href="settings.php" class="btn btn-primary-custom">
            <i class="fa-solid fa-cog me-2"></i>Site Settings
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_users; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_events; ?></div>
                <div class="stat-label">Total Events</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_bookings; ?></div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-value"><?php echo format_currency($total_revenue); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    <!-- Analytics Chart -->
    <div class="card-custom mb-5">
        <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
            <h4 class="mb-0">Ticket Sales by Event</h4>
        </div>
        <div class="p-4">
            <canvas id="salesChart" height="80"></canvas>
        </div>
    </div>

    <!-- User Management -->
    <div class="card-custom">
        <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
            <h4 class="mb-0">System Users</h4>
        </div>
        <div class="p-4">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge-role role-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <form action="../actions/delete_user.php" method="POST" onsubmit="return confirm('Delete this user?');" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button class="btn btn-outline-custom btn-sm text-danger">Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">Current</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Tickets Sold',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: '<?php echo $site_settings['primary_color']; ?>40',
                borderColor: '<?php echo $site_settings['primary_color']; ?>',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#888' } }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#888' }
                },
                x: {
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#888' }
                }
            }
        }
    });
</script>
