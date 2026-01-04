<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];

// Fetch My Events
$sql = "SELECT * FROM events WHERE created_by = ? ORDER BY event_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all Singers
$singers = $conn->query("SELECT * FROM singers ORDER BY name");

// Fetch all Activities
$activities = $conn->query("SELECT * FROM activities ORDER BY name");

// Stats
$total_events = $result->num_rows;
$total_revenue = $conn->query("SELECT SUM(b.total_price) as r FROM bookings b JOIN events e ON b.event_id = e.id WHERE e.created_by = $manager_id")->fetch_assoc()['r'] ?? 0;
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Manager Dashboard</h2>
        <div class="d-flex gap-2">
            <a href="manage_singers.php" class="btn btn-outline-custom">
                <i class="fa-solid fa-microphone me-2"></i>Manage Singers
            </a>
            <a href="manage_activities.php" class="btn btn-outline-custom">
                <i class="fa-solid fa-star me-2"></i>Manage Activities
            </a>
            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="fa-solid fa-plus me-2"></i>Create Event
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_events; ?></div>
                <div class="stat-label">My Events</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-value"><?php echo format_currency($total_revenue); ?></div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    <!-- Events List -->
    <div class="card-custom">
        <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
            <h4 class="mb-0">My Events</h4>
        </div>
        <div class="p-4">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($event = $result->fetch_assoc()): ?>
                                <?php 
                                    $sold = $event['total_tickets'] - $event['available_tickets'];
                                    $progress = $event['total_tickets'] > 0 ? ($sold / $event['total_tickets']) * 100 : 0;
                                    
                                    $rev_stmt = $conn->prepare("SELECT SUM(total_price) as rev FROM bookings WHERE event_id = ?");
                                    $rev_stmt->bind_param("i", $event['id']);
                                    $rev_stmt->execute();
                                    $revenue = $rev_stmt->get_result()->fetch_assoc()['rev'] ?? 0;
                                ?>
                                <tr>
                                    <td class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td style="min-width: 150px;">
                                        <div class="d-flex align-items-center">
                                            <small class="me-2"><?php echo $sold; ?>/<?php echo $event['total_tickets']; ?></small>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-success"><?php echo format_currency($revenue); ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-custom btn-sm" title="Edit Event">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="view_attendees.php?event_id=<?php echo $event['id']; ?>" class="btn btn-outline-custom btn-sm" title="View Attendees">
                                                <i class="fa-solid fa-users"></i>
                                            </a>
                                            <form action="../actions/delete_event.php" method="POST" onsubmit="return confirm('Delete this event?');" class="d-inline">
                                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                <button class="btn btn-outline-custom btn-sm text-danger"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted mb-3">You haven't created any events yet.</p>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createEventModal">Create Your First Event</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../actions/create_event.php" method="POST">
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label">Event Title</label>
                            <input type="text" name="title" class="form-control form-control-custom" placeholder="e.g. Musical Night 2026" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-custom" rows="3" placeholder="Describe your event..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="event_date" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control form-control-custom" placeholder="e.g. Nawabshah" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Tickets</label>
                            <input type="number" name="total_tickets" class="form-control form-control-custom" min="1" placeholder="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Base Price (PKR)</label>
                            <input type="number" name="price" class="form-control form-control-custom" min="0" step="100" placeholder="5000" required>
                        </div>
                        
                        <!-- Singer Selection -->
                        <div class="col-md-12">
                            <label class="form-label mb-3">Select Performers</label>
                            <div class="row g-3">
                                <?php $singers->data_seek(0); while($singer = $singers->fetch_assoc()): ?>
                                    <div class="col-md-6">
                                        <label class="selection-card d-block">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" name="singers[]" value="<?php echo $singer['id']; ?>">
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($singer['name']); ?></div>
                                                    <small class="text-muted"><?php echo $singer['genre']; ?></small>
                                                </div>
                                                <div class="price"><?php echo format_currency($singer['price']); ?></div>
                                            </div>
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <small class="text-muted mt-2 d-block">Note: Singer costs are organizational. Ticket price is set separately.</small>
                        </div>

                        <!-- Activity Selection -->
                        <div class="col-md-12">
                            <label class="form-label mb-3">Available Add-ons for Attendees</label>
                            <div class="row g-3">
                                <?php $activities->data_seek(0); while($activity = $activities->fetch_assoc()): ?>
                                    <div class="col-md-6">
                                        <label class="selection-card d-block">
                                            <div class="form-check d-flex align-items-start">
                                                <input class="form-check-input me-3 mt-1" type="checkbox" name="activities[]" value="<?php echo $activity['id']; ?>">
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($activity['name']); ?></div>
                                                    <small class="text-muted"><?php echo $activity['description']; ?></small>
                                                </div>
                                                <div class="price">+<?php echo format_currency($activity['price']); ?></div>
                                            </div>
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Create Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
