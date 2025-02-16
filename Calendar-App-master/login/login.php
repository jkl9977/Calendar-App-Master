<?php
session_start();
// 데이터베이스 연결 설정
$host = 'localhost';
$user = 'korea';
$pw = '1234';
$dbName = 'app';
$mysqli = new mysqli($host, $user, $pw, $dbName);

// 데이터 입력
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 여기에서 데이터베이스에서 사용자 정보를 확인하세요.
    $query = "SELECT * FROM Users WHERE username = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Error in query: " . $mysqli->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}

    if ($row = $result->fetch_assoc()) {
        // 사용자 정보 확인 성공
        if ($password === $row['password']) {     //if (password_verify($password, $row['password'])) {
            // 비밀번호 일치
            session_start();
            $_SESSION["username"] = $username;
            header("Location: /calendar/calendar.html"); // 다음 화면으로 이동
            exit();
        } else {
            // 비밀번호 불일치
            echo "<script>alert('비밀번호가 일치하지 않습니다.'); window.location.href='login.html';</script>";
            exit();
        }
    } else {
        // 사용자 정보 없음
        echo "<script>alert('사용자 정보를 찾을 수 없습니다.'); window.location.href='login.html';</script>";
        exit();
    }

// 데이터베이스 연결 확인
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

mysqli_close($mysqli);
?>
