<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="app-header">
            <h2>To-Do List</h2>
        </div>
        
        <!-- Task Statistics Section -->
        <div class="glass-card mb-4">
            <div class="row stats-container text-center">
                <?php
                include '../config/database.php';
                
                // Get total tasks count
                $total_query = "SELECT COUNT(*) as total FROM task";
                $total_result = $conn->query($total_query);
                $total_row = $total_result->fetch_assoc();
                $total_tasks = $total_row['total'];
                
                // Get completed tasks count
                $completed_query = "SELECT COUNT(*) as completed FROM task WHERE status = 1";
                $completed_result = $conn->query($completed_query);
                $completed_row = $completed_result->fetch_assoc();
                $completed_tasks = $completed_row['completed'];
                
                // Calculate incomplete tasks
                $incomplete_tasks = $total_tasks - $completed_tasks;
                ?>
                
                <div class="col-md-4">
                    <div class="stat-box">
                        <h3 class="stat-number"><?php echo $total_tasks; ?></h3>
                        <p class="stat-label">Total Tasks</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box completed-box">
                        <h3 class="stat-number"><?php echo $completed_tasks; ?></h3>
                        <p class="stat-label">Completed</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box incomplete-box">
                        <h3 class="stat-number"><?php echo $incomplete_tasks; ?></h3>
                        <p class="stat-label">Incomplete</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="app-container">            
            <!-- Filter Section -->
            <div class="filter-container" id="filterContainer">
                <div class="glass-card filter-box">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Filter</h4>
                    </div>
                    <form method="GET" action="index.php">
                        <div class="mb-3">
                            <label class="form-label">Prioritas</label>
                            <select class="form-control" name="priority">
                                <option value="">Semua</option>
                                <option value="High" <?php echo (isset($_GET['priority']) && $_GET['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                                <option value="Medium" <?php echo (isset($_GET['priority']) && $_GET['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                <option value="Low" <?php echo (isset($_GET['priority']) && $_GET['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="">Semua</option>
                                <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : ''; ?>>Selesai</option>
                                <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : ''; ?>>Belum Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-control" name="due_date" value="<?php echo isset($_GET['due_date']) ? $_GET['due_date'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tugas</label>
                            <input type="text" class="form-control" name="task_name" value="<?php echo isset($_GET['task_name']) ? $_GET['task_name'] : ''; ?>">
                        </div>

                        <?php 
                        // Preserve page parameter if it exists
                        if(isset($_GET['page'])) {
                            echo '<input type="hidden" name="page" value="'.$_GET['page'].'">';
                        }
                        ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Tugas -->
            <div class="task-container">
                <div class="glass-card">
                    <div class="task-header">
                        <h4>Daftar Tugas</h4>
                        <a href="add_task.php" class="btn btn-primary">+ Tambah Tugas</a>
                    </div>
                    
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tugas</th>
                                <th>Prioritas</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $current_date = date('Y-m-d');

                            // Base query
                            $query = "SELECT * FROM task WHERE 1=1";
                            
                            // Apply filters
                            if (isset($_GET['priority']) && $_GET['priority'] != '') {
                                $priority = $_GET['priority'];
                                $query .= " AND priority='$priority'";
                            }
                            if (isset($_GET['status']) && $_GET['status'] != '') {
                                $status = $_GET['status'];
                                $query .= " AND status='$status'";
                            }
                            if (isset($_GET['due_date']) && $_GET['due_date'] != '') {
                                $due_date = $_GET['due_date'];
                                $query .= " AND due_date='$due_date'";
                            }
                            if (isset($_GET['task_name']) && $_GET['task_name'] != '') {
                                $task_name = $_GET['task_name'];
                                $query .= " AND task LIKE '%$task_name%'";
                            }
                            
                            // Order by
                            $query .= " ORDER BY status ASC, priority DESC, due_date ASC";
                            
                            // Get total records for pagination
                            $total_records_result = $conn->query($query);
                            $total_records = $total_records_result->num_rows;
                            
                            // Pagination setup
                            $limit = 10; // Items per page
                            $total_pages = ceil($total_records / $limit);
                            
                            // Current page
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $page = max(1, min($page, max($total_pages, 1))); // Ensure page is within valid range
                            
                            // Calculate the offset
                            $offset = ($page - 1) * $limit;
                            
                            // Add pagination to query
                            $query .= " LIMIT $offset, $limit";
                            
                            // Execute the final query
                            $result = $conn->query($query);
                            
                            if ($result->num_rows > 0) {
                                $no = $offset + 1;
                                while ($row = $result->fetch_assoc()) {
                                    $statusClass = $row['status'] == 1 ? 'status-complete' : 'status-incomplete';
                                    $statusText = $row['status'] == 1 ? 'Selesai' : 'Belum Selesai';

                                    // Determine date class
                                    if ($row['due_date'] < $current_date && $row['status'] == 0) {
                                        $dateClass = "deadline-late";
                                    } elseif ($row['due_date'] == $current_date && $row['status'] == 0) {
                                        $dateClass = "deadline-today";
                                    } else {
                                        $dateClass = "deadline-future";
                                    }

                                    $priorityClass = ($row['priority'] == 'High') ? "priority-high" : (($row['priority'] == 'Medium') ? "priority-medium" : "priority-low");

                                    echo "<tr>
                                        <td class='text-center'>" . $no++ . "</td>
                                        <td>" . $row['task'] . "</td>
                                        <td class='text-center'><span class='priority-badge " . $priorityClass . "'>" . $row['priority'] . "</span></td>
                                        <td class='text-center'><span class='date-badge " . $dateClass . "'>" . $row['due_date'] . "</span></td>
                                        <td class='text-center'>
                                            <span class='status-badge " . $statusClass . "' 
                                                onclick='toggleStatus(" . $row['id'] . ", " . $row['status'] . ")'>
                                                " . $statusText . "
                                            </span>
                                        </td>
                                        <td class='text-center'>
                                            <a href='edit_task.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='../controllers/taskController.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Tidak ada tugas yang ditemukan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page-1])); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php
                            // Limit the number of visible page links
                            $visiblePages = 5;
                            $startPage = max(1, min($page - floor($visiblePages/2), $total_pages - $visiblePages + 1));
                            $endPage = min($startPage + $visiblePages - 1, $total_pages);
                            
                            // Add first page if not included in range
                            if ($startPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '">1</a></li>';
                                if ($startPage > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                            }
                            
                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; 
                            
                            // Add last page if not included in range
                            if ($endPage < $total_pages) {
                                if ($endPage < $total_pages - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $total_pages])) . '">' . $total_pages . '</a></li>';
                            }
                            ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page+1])); ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Add click handler to task names
        $(".table tbody tr td:nth-child(2)").addClass("task-name-cell").click(function() {
            let taskId = $(this).closest("tr").find("a.btn-warning").attr("href").split("=")[1];
            showTaskDetails(taskId);
        });
    });

    function showTaskDetails(taskId) {
        // Fetch task details using AJAX
        $.ajax({
            url: '../controllers/taskController.php',
            type: 'GET',
            data: {
                get_task_details: taskId
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    const task = response.data;
                    
                    // Create and show modal with task details
                    const modal = `
                    <div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taskDetailModalLabel">Detail Tugas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h4 class="task-title">${task.task}</h4>
                                    <div class="task-detail-container">
                                        <div class="detail-row">
                                            <div class="detail-label">Prioritas:</div>
                                            <div class="detail-value">
                                                <span class="priority-badge priority-${task.priority.toLowerCase()}">${task.priority}</span>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Deadline:</div>
                                            <div class="detail-value">
                                                <span class="date-badge">${task.due_date}</span>
                                            </div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status:</div>
                                            <div class="detail-value">
                                                <span class="status-badge ${task.status == 1 ? 'status-complete' : 'status-incomplete'}">
                                                    ${task.status == 1 ? 'Selesai' : 'Belum Selesai'}
                                                </span>
                                            </div>
                                        </div>
                                        ${task.description ? `
                                        <div class="detail-row">
                                            <div class="detail-label">Deskripsi:</div>
                                            <div class="detail-value description-text">${task.description}</div>
                                        </div>
                                        ` : ''}
                                        <div class="detail-row">
                                            <div class="detail-label">Dibuat pada:</div>
                                            <div class="detail-value">${task.created_at}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="edit_task.php?id=${task.id}" class="btn btn-warning">Edit</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    
                    // Remove any existing modal first
                    $("#taskDetailModal").remove();
                    $("body").append(modal);
                    
                    // Initialize and show modal
                    const modalElement = new bootstrap.Modal(document.getElementById('taskDetailModal'));
                    modalElement.show();
                } else {
                    alert("Gagal memuat detail tugas");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching task details:", error);
                alert("Terjadi kesalahan saat memuat detail tugas");
            }
        });
    }
    
    function toggleStatus(taskId, currentStatus) {
        let newStatus = currentStatus == 1 ? 0 : 1;
        $.ajax({
            url: '../controllers/taskController.php',
            type: 'POST',
            data: {
                toggle_status: taskId,
                status: newStatus
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error updating status:", error);
                alert("Terjadi kesalahan saat mengubah status.");
            }
        });
    }
    </script>
</body>
</html>