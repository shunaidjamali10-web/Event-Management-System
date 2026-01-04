<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

$manager_id = $_SESSION['user_id'];
$event_id = intval($_GET['id'] ?? 0);

// Fetch event and verify ownership
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND created_by = ?");
$stmt->bind_param("ii", $event_id, $manager_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    die("Event not found or access denied.");
}

// Fetch all singers
$singers = $conn->query("SELECT * FROM singers ORDER BY name");

// Fetch all activities
$activities = $conn->query("SELECT * FROM activities ORDER BY name");

// Fetch selected singers for this event
$selected_singers = [];
$ss = $conn->query("SELECT singer_id FROM event_singers WHERE event_id = $event_id");
while ($r = $ss->fetch_assoc()) {
    $selected_singers[] = $r['singer_id'];
}

// Fetch selected activities for this event
$selected_activities = [];
$sa = $conn->query("SELECT activity_id FROM event_activities WHERE event_id = $event_id");
while ($r = $sa->fetch_assoc()) {
    $selected_activities[] = $r['activity_id'];
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="manager.php" class="btn btn-outline-custom btn-sm mb-2">&larr; Back to Dashboard</a>
                    <h2 class="fw-bold mb-0">Edit Event</h2>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <div class="card-custom p-4">
                <form action="../actions/update_event.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label">Event Title</label>
                            <input type="text" name="title" class="form-control form-control-custom" 
                                   value="<?php echo htmlspecialchars($event['title']); ?>" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-custom" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="event_date" class="form-control form-control-custom" 
                                   value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control form-control-custom" 
                                   value="<?php echo htmlspecialchars($event['location']); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Total Tickets</label>
                            <input type="number" name="total_tickets" class="form-control form-control-custom" 
                                   value="<?php echo $event['total_tickets']; ?>" min="1" required>
                            <small class="text-muted">Currently sold: <?php echo ($event['total_tickets'] - $event['available_tickets']); ?></small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Base Price (PKR)</label>
                            <input type="number" name="price" class="form-control form-control-custom" 
                                   value="<?php echo $event['price']; ?>" min="0" step="100" required>
                        </div>

                        <!-- Singer Selection -->
                        <div class="col-md-12">
                            <label class="form-label mb-3">Select Performers</label>
                            <div class="row g-3">
                                <?php while($singer = $singers->fetch_assoc()): ?>
                                    <div class="col-md-6">
                                        <label class="selection-card d-block <?php echo in_array($singer['id'], $selected_singers) ? 'selected' : ''; ?>">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" name="singers[]" 
                                                       value="<?php echo $singer['id']; ?>"
                                                       <?php echo in_array($singer['id'], $selected_singers) ? 'checked' : ''; ?>>
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
                        </div>

                        <!-- Activity Selection -->
                        <div class="col-md-12">
                            <label class="form-label mb-3">Available Add-ons for Attendees</label>
                            <div class="row g-3">
                                <?php while($activity = $activities->fetch_assoc()): ?>
                                    <div class="col-md-6">
                                        <label class="selection-card d-block <?php echo in_array($activity['id'], $selected_activities) ? 'selected' : ''; ?>">
                                            <div class="form-check d-flex align-items-start">
                                                <input class="form-check-input me-3 mt-1" type="checkbox" name="activities[]" 
                                                       value="<?php echo $activity['id']; ?>"
                                                       <?php echo in_array($activity['id'], $selected_activities) ? 'checked' : ''; ?>>
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

                    <div class="d-flex justify-content-between mt-5">
                        <a href="manager.php" class="btn btn-outline-custom">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.selection-card input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', function() {
        if (this.checked) {
            this.closest('.selection-card').classList.add('selected');
        } else {
            this.closest('.selection-card').classList.remove('selected');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
