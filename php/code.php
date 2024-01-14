<?php

include "config.php";
session_start();

?>

<?php
// reservations for users
if (isset($_POST['book'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    $time = $_POST['time'];
    $time = filter_var($time, FILTER_SANITIZE_STRING);

    $branch = $_POST['branch'];
    $branch = filter_var($branch, FILTER_SANITIZE_STRING);

    $date = $_POST['date'];
    $date = filter_var($date, FILTER_SANITIZE_STRING);

    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);

    $message = $_POST['message'];
    $message = filter_var($message, FILTER_SANITIZE_STRING);

    $select = "INSERT INTO reserve (name,mobile,time,branch,date,people,comment) VALUES (:name, :phone, :time, :branch, :date, :number, :message)";
    $select_run = $conn->prepare($select);

    $data = [
        ':name' => $name,
        ':phone' => $phone,
        ':time' => $time,
        ':branch' => $branch,
        ':date' => $date,
        ':number' => $number,
        ':message' => $message,
    ];
    $select_execute = $select_run->execute($data);

    if ($select_execute) {
        $_SESSION['message'] = "Message sent sucessfully";
        header("Location: ../index-user.php");
        exit(0);

    } else {

        $_SESSION['message'] = "Message not sent";
        header("Location: ../index-user.php");
        exit(0);
    }
}

//reservations for admins
if (isset($_POST['books'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    $time = $_POST['time'];
    $time = filter_var($time, FILTER_SANITIZE_STRING);

    $branch = $_POST['branch'];
    $branch = filter_var($branch, FILTER_SANITIZE_STRING);

    $date = $_POST['date'];
    $date = filter_var($date, FILTER_SANITIZE_STRING);

    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);

    $message = $_POST['message'];
    $message = filter_var($message, FILTER_SANITIZE_STRING);

    $select = "INSERT INTO reserve (name,mobile,time,branch,date,people,comment) VALUES (:name, :phone, :time, :branch, :date, :number, :message)";
    $select_run = $conn->prepare($select);

    $data = [
        ':name' => $name,
        ':phone' => $phone,
        ':time' => $time,
        ':branch' => $branch,
        ':date' => $date,
        ':number' => $number,
        ':message' => $message,
    ];
    $select_execute = $select_run->execute($data);

    if ($select_execute) {
        $_SESSION['message'] = "Message sent sucessfully";
        header("Location: ../index-admin.php");
        exit(0);

    } else {

        $_SESSION['message'] = "Message not sent";
        header("Location: ../index-admin.php");
        exit(0);
    }
}


// Code for registration
if (isset($_POST['submit-customer'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $username = $_POST['username'];
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    $password = md5($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM `user` WHERE email = ?");
    $select->execute([$email]);

    if ($select->rowCount() > 0) {
        $_SESSION['message'] = "User already exists";
    } else {
            $insert = $conn->prepare("INSERT INTO `user`(name, email, username, password) VALUES(?,?,?,?)");
            $insert->execute([$name, $email, $username, $password]);
            if ($insert) {
                header("Location: ../pages-login.php");
                $_SESSION['message'] = "User Created sucessfully";
            }

        }
    }


// Code for user login page
if (isset($_POST['login-user'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $password = md5($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM `user` WHERE email = ? AND password = ?");
    $select->execute([$email, $password]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($select->rowCount() > 0) {

        if ($row["user_type"] == 'admin') {

            $_SESSION['admin-id'] = $row['id'];
            header("Location: ../admin-profile.php");

        } elseif($row["user_type"] == 'user') {

            $_SESSION['user-id'] = $row['id'];
            header("Location: ../index-user.php");

        } else {
            $_SESSION['message'] = "User not found";
        }
    }else {
        $_SESSION['message'] = "Incorrect username or password";
        header("Location: ../pages-login.php");
    }
}

//Delete Users
if (isset($_POST['delete-user'])) {

    $user_id = $_POST['delete-user'];

    try {

        $query = "DELETE FROM `user` WHERE id = :user_id";
        $statement = $conn->prepare($query);

        $data = [
            ":user_id" => $user_id
        ];
        $query_execute = $statement->execute($data);

        if ($query_execute) {

            $_SESSION['message'] = "User Deleted";
            header("Location: ../tables-data.php");
            exit(0);

        } else {

            $_SESSION['message'] = "User Not Deleted";
            header("Location: ../tables-data.php");
            exit(0);

        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// Message

?>