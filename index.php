<?php 
include 'includes/header.php'; 

// Fetch upcoming events
$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 6";
$result = $conn->query($sql);

// Stats
$total_events = $conn->query("SELECT COUNT(*) as c FROM events")->fetch_assoc()['c'];
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1>The Future of<br>Event Experiences</h1>
        <p>Discover extraordinary cultural events. Book premium tickets. Create unforgettable memories.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="auth/register.php" class="btn btn-primary-custom btn-lg">Get Started</a>
            <a href="auth/login.php" class="btn btn-outline-custom btn-lg ms-2">Sign In</a>
        <?php else: ?>
            <a href="dashboard/<?php echo $_SESSION['role']; ?>.php" class="btn btn-primary-custom btn-lg">Go to Dashboard</a>
        <?php endif; ?>
        
        <div class="row justify-content-center mt-5 pt-4">
            <div class="col-auto">
                <div class="stat-card text-center" style="min-width: 150px;">
                    <div class="stat-value"><?php echo $total_events; ?></div>
                    <div class="stat-label">Events</div>
                </div>
            </div>
            <div class="col-auto">
                <div class="stat-card text-center" style="min-width: 150px;">
                    <div class="stat-value"><?php echo $total_users; ?></div>
                    <div class="stat-label">Members</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Preview -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="h3 fw-bold mb-0">Upcoming Events</h2>
            </div>
        </div>

        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <?php
                        // Fetch singers
                        $sing_stmt = $conn->prepare("SELECT s.name FROM event_singers es JOIN singers s ON es.singer_id = s.id WHERE es.event_id = ?");
                        $sing_stmt->bind_param("i", $row['id']);
                        $sing_stmt->execute();
                        $singers = $sing_stmt->get_result();
                        $singer_names = [];
                        while($s = $singers->fetch_assoc()) {
                            $singer_names[] = $s['name'];
                        }
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card-custom h-100">
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="badge bg-primary">
                                        <?php echo date('M d', strtotime($row['event_date'])); ?>
                                    </span>
                                    <span class="fw-bold text-success"><?php echo format_currency($row['price']); ?></span>
                                </div>
                                <h3 class="h5 fw-bold mb-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p class="text-muted small mb-2">
                                    <i class="fa-solid fa-location-dot me-1"></i> <?php echo htmlspecialchars($row['location']); ?>
                                </p>
                                <?php if (!empty($singer_names)): ?>
                                    <p class="text-muted small mb-3">
                                        <i class="fa-solid fa-microphone me-1"></i> <?php echo implode(', ', $singer_names); ?>
                                    </p>
                                <?php endif; ?>
                                <p class="text-muted small mb-4">
                                    <?php echo substr(htmlspecialchars($row['description']), 0, 80) . '...'; ?>
                                </p>
                                <a href="auth/login.php" class="btn btn-outline-custom btn-sm w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No upcoming events. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-custom p-4 text-center h-100">
                    <div class="mb-3" style="color: var(--primary-accent);">
                        <i class="fa-solid fa-ticket fa-2x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Digital Tickets</h4>
                    <p class="text-muted small mb-0">Instant booking with printable QR-enabled tickets.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom p-4 text-center h-100">
                    <div class="mb-3" style="color: var(--primary-accent);">
                        <i class="fa-solid fa-star fa-2x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Premium Add-ons</h4>
                    <p class="text-muted small mb-0">VIP seating, backstage passes, and more.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom p-4 text-center h-100">
                    <div class="mb-3" style="color: var(--primary-accent);">
                        <i class="fa-solid fa-shield-halved fa-2x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Secure Platform</h4>
                    <p class="text-muted small mb-0">Role-based access with encrypted data.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
