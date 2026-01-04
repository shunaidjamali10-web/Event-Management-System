<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all activities
$activities = $conn->query("SELECT * FROM activities ORDER BY name ASC");

// Check for edit mode
$edit_activity = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_activity = $stmt->get_result()->fetch_assoc();
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="manager.php" class="btn btn-outline-custom btn-sm mb-2">&larr; Back to Dashboard</a>
            <h2 class="fw-bold mb-0">Manage Activities</h2>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add/Edit Form -->
        <div class="col-md-4">
            <div class="card-custom p-4">
                <h5 class="mb-4"><?php echo $edit_activity ? 'Edit Activity' : 'Add New Activity'; ?></h5>
                <form action="../actions/activity_action.php" method="POST">
                    <input type="hidden" name="action" value="<?php echo $edit_activity ? 'edit' : 'add'; ?>">
                    <?php if ($edit_activity): ?>
                        <input type="hidden" name="activity_id" value="<?php echo $edit_activity['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control form-control-custom" 
                               value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['name']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control form-control-custom" rows="3"
                                  placeholder="Brief description of the activity"><?php echo $edit_activity ? htmlspecialchars($edit_activity['description']) : ''; ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Price (PKR)</label>
                        <input type="number" name="price" class="form-control form-control-custom" 
                               value="<?php echo $edit_activity ? $edit_activity['price'] : ''; ?>" 
                               min="0" step="100" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary-custom">
                            <?php echo $edit_activity ? 'Update Activity' : 'Add Activity'; ?>
                        </button>
                        <?php if ($edit_activity): ?>
                            <a href="manage_activities.php" class="btn btn-outline-custom">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activities List -->
        <div class="col-md-8">
            <div class="card-custom">
                <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
                    <h5 class="mb-0">All Activities (<?php echo $activities->num_rows; ?>)</h5>
                </div>
                <div class="p-4">
                    <?php if ($activities->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($activity = $activities->fetch_assoc()): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($activity['name']); ?></td>
                                            <td class="text-muted"><?php echo htmlspecialchars(substr($activity['description'], 0, 50)); ?>...</td>
                                            <td class="text-success fw-bold"><?php echo format_currency($activity['price']); ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="?edit=<?php echo $activity['id']; ?>" class="btn btn-outline-custom btn-sm">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <form action="../actions/activity_action.php" method="POST" onsubmit="return confirm('Delete this activity?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                                        <button class="btn btn-outline-custom btn-sm text-danger">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No activities added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
