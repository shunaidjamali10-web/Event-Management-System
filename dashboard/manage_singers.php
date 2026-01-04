<?php 
require_once '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all singers
$singers = $conn->query("SELECT * FROM singers ORDER BY name ASC");

// Check for edit mode
$edit_singer = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM singers WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_singer = $stmt->get_result()->fetch_assoc();
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="manager.php" class="btn btn-outline-custom btn-sm mb-2">&larr; Back to Dashboard</a>
            <h2 class="fw-bold mb-0">Manage Singers</h2>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add/Edit Form -->
        <div class="col-md-4">
            <div class="card-custom p-4">
                <h5 class="mb-4"><?php echo $edit_singer ? 'Edit Singer' : 'Add New Singer'; ?></h5>
                <form action="../actions/singer_action.php" method="POST">
                    <input type="hidden" name="action" value="<?php echo $edit_singer ? 'edit' : 'add'; ?>">
                    <?php if ($edit_singer): ?>
                        <input type="hidden" name="singer_id" value="<?php echo $edit_singer['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control form-control-custom" 
                               value="<?php echo $edit_singer ? htmlspecialchars($edit_singer['name']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Genre</label>
                        <input type="text" name="genre" class="form-control form-control-custom" 
                               value="<?php echo $edit_singer ? htmlspecialchars($edit_singer['genre']) : ''; ?>" 
                               placeholder="e.g. Pop, Qawwali, Folk">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Price (PKR)</label>
                        <input type="number" name="price" class="form-control form-control-custom" 
                               value="<?php echo $edit_singer ? $edit_singer['price'] : ''; ?>" 
                               min="0" step="100" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary-custom">
                            <?php echo $edit_singer ? 'Update Singer' : 'Add Singer'; ?>
                        </button>
                        <?php if ($edit_singer): ?>
                            <a href="manage_singers.php" class="btn btn-outline-custom">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Singers List -->
        <div class="col-md-8">
            <div class="card-custom">
                <div class="p-4 border-bottom" style="border-color: var(--card-border) !important;">
                    <h5 class="mb-0">All Singers (<?php echo $singers->num_rows; ?>)</h5>
                </div>
                <div class="p-4">
                    <?php if ($singers->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Genre</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($singer = $singers->fetch_assoc()): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($singer['name']); ?></td>
                                            <td><?php echo htmlspecialchars($singer['genre']); ?></td>
                                            <td class="text-success fw-bold"><?php echo format_currency($singer['price']); ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="?edit=<?php echo $singer['id']; ?>" class="btn btn-outline-custom btn-sm">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                    <form action="../actions/singer_action.php" method="POST" onsubmit="return confirm('Delete this singer?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="singer_id" value="<?php echo $singer['id']; ?>">
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
                        <p class="text-muted mb-0">No singers added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
