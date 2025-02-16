<?php
session_start();

// 사용자 계정 정보 확인
if (!isset($_SESSION['username'])) {
    // 사용자가 로그인하지 않았으면 로그인 페이지로 이동 또는 에러 처리
    echo "<script>alert('로그인이 필요합니다.'); window.location.href='/login/login.html';</script>";
    exit();
}

// 현재 로그인한 사용자의 계정 정보
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $detail = $_POST["detail"];

    // 유효성 검사
    $errorMessage = "";

    if (!isValidInput($title)) {
        $errorMessage .= "제목을 입력해주세요.\\n";
    }

    if (!isValidInput($date)) {
        $errorMessage .= "날짜를 입력해주세요.\\n";
    }

    if (!isValidInput($time)) {
        $errorMessage .= "시간을 입력해주세요.\\n";
    }

    if (!isValidInput($detail)) {
        $errorMessage .= "내용을 입력해주세요.\\n";
    }

    if ($errorMessage !== "") {
        echo "<script>";
        echo "alert('$errorMessage');";
        echo "window.history.back();"; // 이전 페이지로 이동
        echo "</script>";
        exit(); // 오류가 발생했으므로 더 이상 진행하지 않음
    }

    // 데이터베이스 연결 설정
    $host = 'localhost';
    $user = 'korea';
    $pw = '1234';
    $dbName = 'app'; // 변경 필요
    $mysqli = new mysqli($host, $user, $pw, $dbName);

    // 연결 확인
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // 날짜와 시간을 데이터베이스에 맞게 변환
    $formattedDate = date("Y-m-d", strtotime($date));
    $formattedTime = date("H:i:s", strtotime($time));

    // 일정 정보 데이터베이스에 저장
    $insertSql = "INSERT INTO Add_Schedule (username, title, date, time, detail) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertSql);
    $stmt->bind_param("sssss", $username, $title, $formattedDate, $formattedTime, $detail);

    if ($stmt->execute()) {
        // 일정추가 완료 알림창 및 캘린더 페이지로 자동 이동
        echo "<script>";
        echo "alert('일정이 추가 되셨습니다!');";
        echo "window.location.replace('/calendar/calendar.html');"; // 캘린더 페이지로 자동 이동
        echo "</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    // 연결 종료
    $stmt->close();
    $mysqli->close();
}

// 입력이 되었는지 확인하는 함수
function isValidInput($value) {
    return !empty($value);
}
?>
