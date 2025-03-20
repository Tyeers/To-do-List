<?php
include '../config/database.php';

// Add Task
if (isset($_POST['add_task'])) {
    $task = $_POST['task'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    
    $query = "INSERT INTO task (task, description, priority, due_date, status) VALUES ('$task', '$description', '$priority', '$due_date', 0)";
    
    if ($conn->query($query) === TRUE) {
        header("Location: ../views/index.php");
    } else {
        echo "Error: {$query}<br>{$conn->error}";
    }
}

// Edit Task
if (isset($_POST['edit_task'])) {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    
    $query = "UPDATE task SET task='$task', description='$description', priority='$priority', due_date='$due_date' WHERE id=$id";
    
    if ($conn->query($query) === TRUE) {
        header("Location: ../views/index.php");
    } else {
        echo "Error: {$query}<br>{$conn->error}";
    }
}

// Delete Task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $query = "DELETE FROM task WHERE id=$id";
    
    if ($conn->query($query) === TRUE) {
        header("Location: ../views/index.php");
    } else {
        echo "Error: {$query}<br>{$conn->error}";
    }
}

// Mark as Complete via GET (for backward compatibility)
if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    
    $query = "UPDATE task SET status=1 WHERE id=$id";
    
    if ($conn->query($query) === TRUE) {
        header("Location: ../views/index.php");
    } else {
        echo "Error: {$query}<br>{$conn->error}";
    }
}

// Toggle Status (new AJAX functionality)
if (isset($_POST['toggle_status'])) {
    $id = $_POST['toggle_status'];
    $status = $_POST['status'];
    
    $query = "UPDATE task SET status=$status WHERE id=$id";
    
    if ($conn->query($query) === TRUE) {
        echo "success";
    } else {
        http_response_code(500);
        echo "Error: {$query}<br>{$conn->error}";
    }
}

// Endpoint to get task details
if(isset($_GET['get_task_details'])) {
    $task_id = $_GET['get_task_details'];
    
    // Prepare query
    $query = "SELECT * FROM task WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => $task
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Task not found'
        ]);
    }
    exit;
}