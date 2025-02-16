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
    $name = $_POST["name"];
    $phone = $_POST["phone"];

    // 여러 조건을 가진 하나의 쿼리 사용
    $query = "SELECT * FROM Users WHERE name = ? OR phone = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Error in query: " . $mysqli->error);
    }

    // 두 개의 변수를 바인딩
    $stmt->bind_param("ss", $name, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // 데이터베이스 조회 결과 확인
    if ($row = $result->fetch_assoc()) {
        // 사용자 정보 확인 성공
        $foundUsername = $row['username'];
    } else {
        // 사용자 정보 없음
        $foundUsername = "";
    }
}

if ($foundUsername) {
    echo "<p>회원님의 아이디는: $foundUsername 입니다.</p>";
} else {
    echo "<p>일치하는 정보가 없습니다.</p>";
}

// 데이터베이스 연결 확인
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

mysqli_close($mysqli);
?>
