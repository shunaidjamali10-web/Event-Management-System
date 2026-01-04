<?php
session_start();
require_once '../config/db.php';
require_once '../includes/settings_loader.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$booking_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$sql = "SELECT b.id, b.status, b.total_price, b.booking_date, e.title, e.description, e.event_date, e.location, e.price as base_price, 
               u.username, u.full_name, u.email, u.phone 
        FROM bookings b 
        JOIN events e ON b.event_id = e.id 
        JOIN users u ON b.user_id = u.id 
        WHERE b.id = ? AND b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("Ticket not found.");
}

// Fetch booked activities
$act_sql = "SELECT a.name, a.price FROM booking_activities ba JOIN activities a ON ba.activity_id = a.id WHERE ba.booking_id = ?";
$stmt = $conn->prepare($act_sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booked_activities = $stmt->get_result();

// Generate QR Code data (unique ticket identifier)
$qr_data = urlencode(json_encode([
    'ticket_id' => $booking['id'],
    'event' => $booking['title'],
    'attendee' => $booking['full_name'] ?? $booking['username'],
    'date' => $booking['event_date']
]));
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $qr_data;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?php echo $booking_id; ?> - <?php echo htmlspecialchars($site_settings['site_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: <?php echo htmlspecialchars($site_settings['primary_color']); ?>;
            --secondary: <?php echo htmlspecialchars($site_settings['secondary_color']); ?>;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: #09090b; 
            font-family: 'Inter', sans-serif; 
            color: #fafafa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .ticket {
            max-width: 750px;
            width: 100%;
            background: #1f1f23;
            border: 1px solid #2e2e35;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .ticket-header { 
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ticket-header h1 { 
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .ticket-header .booking-id {
            font-size: 0.75rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .ticket-body { padding: 2rem; }
        .event-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
        }
        .ticket-main {
            display: flex;
            gap: 2rem;
        }
        .ticket-details {
            flex: 1;
        }
        .ticket-qr {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #fff;
            border-radius: 16px;
        }
        .ticket-qr img {
            width: 150px;
            height: 150px;
        }
        .ticket-qr small {
            color: #09090b;
            margin-top: 0.5rem;
            font-weight: 600;
            font-size: 0.7rem;
        }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
        .info-item label { 
            display: block;
            font-size: 0.7rem;
            color: #71717a;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.25rem;
        }
        .info-item span { font-weight: 600; font-size: 0.95rem; }
        .addons { 
            background: #18181b;
            border: 1px solid #2e2e35;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .addons h4 { font-size: 0.75rem; color: #71717a; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; }
        .addon-item { display: flex; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid #2e2e35; font-size: 0.9rem; }
        .addon-item:last-child { border-bottom: none; }
        .addon-item .price { color: #22c55e; }
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 1px solid #2e2e35;
        }
        .total-section .total-label { font-size: 0.8rem; color: #71717a; text-transform: uppercase; letter-spacing: 0.1em; }
        .total-section .total-value { font-size: 1.75rem; font-weight: 800; color: #22c55e; }
        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-outline { background: transparent; color: #fafafa; border: 1px solid #2e2e35; }
        .btn-outline:hover { border-color: #71717a; }
        @media print {
            body { background: white; color: black; padding: 0; }
            .ticket { border: 2px solid #000; box-shadow: none; max-width: 100%; }
            .ticket-header { background: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .ticket-qr { background: #fff; border: 1px solid #000; }
            .actions { display: none; }
            .addons { background: #f5f5f5; }
        }
        @media (max-width: 600px) {
            .ticket-main { flex-direction: column; }
            .ticket-qr { margin-top: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <div>
                <h1><?php echo htmlspecialchars($site_settings['site_name']); ?></h1>
                <div class="booking-id">Ticket #<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></div>
            </div>
        </div>
        <div class="ticket-body">
            <h2 class="event-title"><?php echo htmlspecialchars($booking['title']); ?></h2>
            
            <div class="ticket-main">
                <div class="ticket-details">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Date & Time</label>
                            <span><?php echo date('M d, Y — h:i A', strtotime($booking['event_date'])); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Location</label>
                            <span><?php echo htmlspecialchars($booking['location']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Attendee</label>
                            <span><?php echo htmlspecialchars($booking['full_name'] ?? $booking['username']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Phone</label>
                            <span><?php echo htmlspecialchars($booking['phone'] ?? '-'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <span><?php echo htmlspecialchars($booking['email']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Booked On</label>
                            <span><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></span>
                        </div>
                    </div>

                    <?php if ($booked_activities->num_rows > 0): ?>
                        <div class="addons">
                            <h4>Selected Add-ons</h4>
                            <?php while($act = $booked_activities->fetch_assoc()): ?>
                                <div class="addon-item">
                                    <span><?php echo htmlspecialchars($act['name']); ?></span>
                                    <span class="price">+<?php echo format_currency($act['price']); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>

                    <div class="total-section">
                        <div class="total-label">Total Paid</div>
                        <div class="total-value"><?php echo format_currency($booking['total_price']); ?></div>
                    </div>
                </div>

                <div class="ticket-qr">
                    <img src="<?php echo $qr_url; ?>" alt="QR Code">
                    <small>SCAN AT ENTRY</small>
                </div>
            </div>
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn btn-primary">Print Ticket</button>
        <a href="attendee.php" class="btn btn-outline">Back to Dashboard</a>
    </div>
</body>
</html>
