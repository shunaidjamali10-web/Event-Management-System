<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'attendee') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Filter Logic
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = "WHERE event_date >= CURDATE()";
if ($search) {
    $where .= " AND (title LIKE ? OR location LIKE ?)";
}

// Fetch Available Events
$events_sql = "SELECT * FROM events $where ORDER BY event_date ASC";
$stmt = $conn->prepare($events_sql);

if ($search) {
    $param = "%$search%";
    $stmt->bind_param("ss", $param, $param);
}
$stmt->execute();
$events_result = $stmt->get_result();

// Fetch My Bookings
$bookings_sql = "SELECT b.id, b.total_price, e.title, e.event_date, e.location, b.status 
                 FROM bookings b 
                 JOIN events e ON b.event_id = e.id 
                 WHERE b.user_id = ? 
                 ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($bookings_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h2 class="fw-bold mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p class="text-muted">Discover events and manage your tickets.</p>
        </div>
    </div>

    <!-- My Bookings -->
    <div class="card-custom mb-5">
        <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
            <h4 class="mb-0"><i class="fa-solid fa-ticket me-2"></i>My Tickets</h4>
        </div>
        <div class="p-4">
            <?php if ($bookings_result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Total Paid</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($booking = $bookings_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo htmlspecialchars($booking['title']); ?></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($booking['event_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($booking['location']); ?></td>
                                    <td class="fw-bold text-success"><?php echo format_currency($booking['total_price']); ?></td>
                                    <td>
                                        <span class="badge bg-success">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="ticket.php?id=<?php echo $booking['id']; ?>" class="btn btn-outline-custom btn-sm">View Ticket</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted mb-0">You haven't booked any events yet. Explore below!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Available Events -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-calendar-days me-2"></i>Explore Events</h3>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-custom" placeholder="Search by name or location..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary-custom">Search</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <?php if ($events_result->num_rows > 0): ?>
            <?php while($event = $events_result->fetch_assoc()): ?>
                <?php 
                    // Fetch activities for this event
                    $act_stmt = $conn->prepare("SELECT a.* FROM event_activities ea JOIN activities a ON ea.activity_id = a.id WHERE ea.event_id = ?");
                    $act_stmt->bind_param("i", $event['id']);
                    $act_stmt->execute();
                    $event_activities = $act_stmt->get_result();
                    
                    // Fetch singers for this event
                    $sing_stmt = $conn->prepare("SELECT s.name FROM event_singers es JOIN singers s ON es.singer_id = s.id WHERE es.event_id = ?");
                    $sing_stmt->bind_param("i", $event['id']);
                    $sing_stmt->execute();
                    $event_singers = $sing_stmt->get_result();
                    $singer_names = [];
                    while($s = $event_singers->fetch_assoc()) {
                        $singer_names[] = $s['name'];
                    }
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card-custom h-100">
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-primary">
                                    <?php echo date('M d', strtotime($event['event_date'])); ?>
                                </span>
                                <span class="fw-bold text-success"><?php echo format_currency($event['price']); ?></span>
                            </div>
                            <h4 class="h5 fw-bold mb-2"><?php echo htmlspecialchars($event['title']); ?></h4>
                            <p class="text-muted small mb-2">
                                <i class="fa-solid fa-map-pin me-1"></i> <?php echo htmlspecialchars($event['location']); ?>
                            </p>
                            <?php if (!empty($singer_names)): ?>
                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-microphone me-1"></i> <?php echo implode(', ', $singer_names); ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-muted small mb-3">
                                <?php echo substr(htmlspecialchars($event['description']), 0, 80); ?>...
                            </p>
                            
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center" style="border-color: var(--card-border) !important;">
                                <small class="text-muted">
                                    <?php echo $event['available_tickets']; ?> spots left
                                </small>
                                <?php if ($event['available_tickets'] > 0): ?>
                                    <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#bookModal<?php echo $event['id']; ?>">
                                        Book Now
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-outline-custom btn-sm" disabled>Sold Out</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Modal -->
                <div class="modal fade" id="bookModal<?php echo $event['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Book: <?php echo htmlspecialchars($event['title']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="../actions/book_ticket.php" method="POST">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <input type="hidden" name="base_price" value="<?php echo $event['price']; ?>">
                                <div class="modal-body">
                                    <div class="mb-4">
                                        <p class="text-muted small mb-2">Base Ticket Price</p>
                                        <h3 class="fw-bold" id="basePrice<?php echo $event['id']; ?>"><?php echo format_currency($event['price']); ?></h3>
                                    </div>

                                    <?php if ($event_activities->num_rows > 0): ?>
                                        <p class="text-muted small fw-bold mb-3">Select Add-ons (Optional)</p>
                                        <?php $event_activities->data_seek(0); while($act = $event_activities->fetch_assoc()): ?>
                                            <label class="selection-card d-block mb-2 activity-select" data-price="<?php echo $act['price']; ?>" data-event="<?php echo $event['id']; ?>">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="activities[]" value="<?php echo $act['id']; ?>">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold"><?php echo htmlspecialchars($act['name']); ?></div>
                                                        <small class="text-muted"><?php echo $act['description']; ?></small>
                                                    </div>
                                                    <div class="price">+<?php echo format_currency($act['price']); ?></div>
                                                </div>
                                            </label>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-muted small">No add-ons available for this event.</p>
                                    <?php endif; ?>

                                    <hr style="border-color: var(--card-border);">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Total</span>
                                        <h3 class="fw-bold mb-0 text-success" id="totalPrice<?php echo $event['id']; ?>"><?php echo format_currency($event['price']); ?></h3>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary-custom">Confirm Booking</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card-custom p-5 text-center">
                    <p class="text-muted mb-0">No events found. Try a different search term.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.activity-select').forEach(card => {
    const checkbox = card.querySelector('input[type="checkbox"]');
    const eventId = card.dataset.event;
    const price = parseFloat(card.dataset.price);
    
    checkbox.addEventListener('change', () => {
        const form = card.closest('form');
        const basePrice = parseFloat(form.querySelector('input[name="base_price"]').value);
        let total = basePrice;
        
        form.querySelectorAll('.activity-select input:checked').forEach(checked => {
            total += parseFloat(checked.closest('.activity-select').dataset.price);
        });
        
        document.getElementById('totalPrice' + eventId).textContent = 'PKR ' + total.toLocaleString('en-PK');
        
        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
