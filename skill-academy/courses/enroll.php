<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit(); }
if (!isset($_POST['course_id']) || !is_numeric($_POST['course_id'])) { header("Location: list.php"); exit(); }

$user_id   = (int)$_SESSION['user_id'];
$course_id = (int)$_POST['course_id'];

$chk = $conn->prepare("SELECT id FROM enrollments WHERE user_id=? AND course_id=?");
$chk->bind_param("ii", $user_id, $course_id);
$chk->execute();
if ($chk->get_result()->num_rows > 0) { header("Location: course_detail.php?id=$course_id"); exit(); }

$sc = $conn->prepare("SELECT slots FROM courses WHERE id=?");
$sc->bind_param("i", $course_id);
$sc->execute();
$cd = $sc->get_result()->fetch_assoc();
if (!$cd || $cd['slots'] <= 0) { header("Location: course_detail.php?id=$course_id"); exit(); }

$ins = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
$ins->bind_param("ii", $user_id, $course_id);
$ins->execute();

$upd = $conn->prepare("UPDATE courses SET slots = slots - 1 WHERE id = ?");
$upd->bind_param("i", $course_id);
$upd->execute();

header("Location: course_detail.php?id=$course_id");
exit();