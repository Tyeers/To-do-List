<?php
include '../config/database.php';
$id = $_GET['id'];
$query = "SELECT * FROM task WHERE id='$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="app-header">
            <h2>Edit Tugas</h2>
        </div>
        
        <div class="glass-card">
            <form action="../controllers/taskController.php" method="POST" id="editTaskForm">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Nama Tugas</label>
                    <input type="text" class="form-control" name="task" value="<?php echo $row['task']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" rows="3" required><?php echo $row['description']; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Prioritas</label>
                    <select class="form-control" name="priority">
                        <option value="High" <?php echo ($row['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                        <option value="Medium" <?php echo ($row['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="Low" <?php echo ($row['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tanggal Deadline</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo $row['due_date']; ?>" required>
                </div>
                
                <div class="mt-4">
                    <button type="submit" name="edit_task" class="btn btn-success me-2">Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Past Date Error Modal -->
    <div class="modal fade" id="pastDateModal" tabindex="-1" aria-labelledby="pastDateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pastDateModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">Tanggal deadline tidak boleh berada di masa lalu. Silakan pilih tanggal hari ini atau di masa depan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editTaskForm = document.getElementById('editTaskForm');
            const dueDateInput = document.getElementById('due_date');
            const pastDateModal = new bootstrap.Modal(document.getElementById('pastDateModal'));

            editTaskForm.addEventListener('submit', function(event) {
                // Get current date without time component
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Get selected due date
                const selectedDate = new Date(dueDateInput.value);
                selectedDate.setHours(0, 0, 0, 0);
                
                // Compare dates
                if (selectedDate < today) {
                    event.preventDefault();
                    pastDateModal.show();
                }
            });
        });
    </script>
</body>
</html>