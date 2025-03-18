<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Tambah Tugas</h2>
        <form action="../controllers/taskController.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Tugas</label>
                <input type="text" class="form-control" name="task" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Prioritas</label>
                <select class="form-control" name="priority">
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Deadline</label>
                <input type="date" class="form-control" name="due_date" required>
            </div>
            <button type="submit" name="add_task" class="btn btn-success">Tambah</button>
        </form>
    </div>
</body>
</html>