<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-4">
        <div class="app-header">
            <h2>Tambah Tugas Baru</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-card">
                    <form action="../controllers/taskController.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Tugas</label>
                            <input type="text" class="form-control" name="task" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3" required style="border-radius: 15px;"></textarea>
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
                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" name="add_task" class="btn btn-primary">Tambah Tugas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>