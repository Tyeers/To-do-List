<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">To-Do List</h2>
        <a href="add_task.php" class="btn btn-primary mb-3">Tambah Tugas</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tugas</th>
                    <th>Deskripsi</th>
                    <th>Prioritas</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../config/database.php';
                $query = "SELECT * FROM task ORDER BY status ASC, priority DESC, due_date ASC";
                $result = $conn->query($query);
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    $statusClass = $row['status'] == 1 ? 'text-success fw-bold' : 'text-danger fw-bold';
                    $statusText = $row['status'] == 1 ? 'Selesai' : 'Belum Selesai';
                    echo "<tr>
                        <td>" . $no++ . "</td>
                        <td>" . $row['task'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>" . $row['priority'] . "</td>
                        <td>" . $row['due_date'] . "</td>
                        <td class='" . $statusClass . "'>" . $statusText . "</td>
                        <td>
                            <a href='edit_task.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                            <a href='../controllers/taskController.php?delete=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                            <a href='../controllers/taskController.php?complete=" . $row['id'] . "' class='btn btn-success'>Selesai</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>