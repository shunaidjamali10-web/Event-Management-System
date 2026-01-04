<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];
$event_id = intval($_GET['event_id'] ?? 0);

// Verify this event belongs to the manager
$check_sql = "SELECT * FROM events WHERE id = ? AND created_by = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $event_id, $manager_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    die("Event not found or access denied.");
}

// Fetch Attendees with full details
$sql = "SELECT u.id, u.username, u.full_name, u.email, u.phone, b.booking_date, b.status, b.total_price
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.event_id = ? 
        ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$attendees = $stmt->get_result();

// Fetch Singers for this event
$singers_sql = "SELECT s.name, s.genre, s.price FROM event_singers es JOIN singers s ON es.singer_id = s.id WHERE es.event_id = ?";
$stmt = $conn->prepare($singers_sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event_singers = $stmt->get_result();

// Fetch Activities for this event
$activities_sql = "SELECT a.name, a.price FROM event_activities ea JOIN activities a ON ea.activity_id = a.id WHERE ea.event_id = ?";
$stmt = $conn->prepare($activities_sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event_activities = $stmt->get_result();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="manager.php" class="btn btn-outline-custom btn-sm mb-2">&larr; Back to Dashboard</a>
            <h2 class="fw-bold mb-0"><?php echo htmlspecialchars($event['title']); ?></h2>
            <p class="text-muted mb-0">
                <i class="fa-solid fa-calendar me-1"></i> <?php echo date('M d, Y h:i A', strtotime($event['event_date'])); ?> 
                &bull; <i class="fa-solid fa-location-dot me-1"></i> <?php echo htmlspecialchars($event['location']); ?>
            </p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Event Singers -->
        <div class="col-md-6">
            <div class="card-custom p-4">
                <h5 class="mb-3"><i class="fa-solid fa-microphone me-2"></i>Performers</h5>
                <?php if ($event_singers->num_rows > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php while($s = $event_singers->fetch_assoc()): ?>
                            <li class="d-flex justify-content-between mb-2">
                                <span><?php echo htmlspecialchars($s['name']); ?> <small class="text-muted">(<?php echo $s['genre']; ?>)</small></span>
                                <span class="fw-bold text-success"><?php echo format_currency($s['price']); ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">No performers assigned.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Event Activities -->
        <div class="col-md-6">
            <div class="card-custom p-4">
                <h5 class="mb-3"><i class="fa-solid fa-star me-2"></i>Available Add-ons</h5>
                <?php if ($event_activities->num_rows > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php while($a = $event_activities->fetch_assoc()): ?>
                            <li class="d-flex justify-content-between mb-2">
                                <span><?php echo htmlspecialchars($a['name']); ?></span>
                                <span class="fw-bold text-success">+<?php echo format_currency($a['price']); ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">No add-ons available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Attendee List -->
    <div class="card-custom">
        <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Attendee List</h4>
                <span class="badge-role role-admin"><?php echo $attendees->num_rows; ?> Attendees</span>
            </div>
        </div>
        <div class="p-4">
            <?php if ($attendees->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Booked On</th>
                                <th>Total Paid</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; while($att = $attendees->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($att['full_name'] ?? $att['username']); ?></div>
                                        <small class="text-muted">@<?php echo htmlspecialchars($att['username']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($att['email']); ?></td>
                                    <td><?php echo htmlspecialchars($att['phone'] ?? '-'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($att['booking_date'])); ?></td>
                                    <td class="fw-bold text-success"><?php echo format_currency($att['total_price']); ?></td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?php echo ucfirst($att['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted mb-0">No attendees have booked this event yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
