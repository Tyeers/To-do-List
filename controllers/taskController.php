<?php
include '../config/database.php';

if (isset($_POST['add_task'])) {
    $task = $_POST['task'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    if (!empty($task) && !empty($description) && !empty($priority) && !empty($due_date)) {
        $query = "INSERT INTO task (task, description, priority, due_date, status) VALUES ('$task', '$description', '$priority', '$due_date', 0)";
        if ($conn->query($query)) {
            echo "<script>alert('Task berhasil ditambahkan'); window.location='../views/index.php';</script>";
        }
    }
}

if (isset($_POST['edit_task'])) {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $query = "UPDATE task SET task='$task', description='$description', priority='$priority', due_date='$due_date' WHERE id='$id'";
    if ($conn->query($query)) {
        echo "<script>alert('Task berhasil diperbarui'); window.location='../views/index.php';</script>";
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM task WHERE id='$id'";
    
    if ($conn->query($query)) {
        header("Location: ../views/index.php");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus tugas!'); window.location='../views/index.php';</script>";
    }
}

if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    $query = "UPDATE task SET status=1 WHERE id='$id'";
    if ($conn->query($query)) {
        header("Location: ../views/index.php");
        exit();
    } else {
        echo "<script>alert('Gagal menyelesaikan tugas!'); window.location='../views/index.php';</script>";
    }
}