<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 유효성 검사
    $errorMessage = "";

    if (!isValidInput($name)) {
        $errorMessage .= "이름을 입력해주세요.\\n";
    }

    if (!isValidInput($phone)) {
        $errorMessage .= "전화번호를 입력해주세요.\\n";
    }

    if (!isValidEmail($username)) {
        $errorMessage .= "이메일 형식으로 입력해주세요.\\n";
    }

    if (!isValidPassword($password)) {
        $errorMessage .= "숫자, 대문자, 소문자, 특수문자를 포함하여 8~20자 이내로 입력해주세요.\\n";
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

    // 사용자 정보 데이터베이스에 저장
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // 비밀번호 해싱
    $insertSql = "INSERT INTO Users (name, phone, username, password) VALUES ('$name', '$phone', '$username', '$password')";

    if ($mysqli->query($insertSql) === TRUE) {
        // 회원가입 완료 알림창 및 로그인 페이지로 자동 이동
        echo "<script>";
        echo "alert('회원가입이 완료되었습니다. 환영합니다, $name 님!');";
        echo "window.location.replace('/login/login.html');"; // 로그인 페이지로 자동 이동
        echo "</script>";
    } else {
        echo "Error: " . $insertSql . "<br>" . $mysqli->error;
    }

    // 연결 종료
    $mysqli->close();
}

// 입력이 되었는지 확인하는 함수
function isValidInput($value) {
    return !empty($value);
}

// 이메일 형식인지 확인하는 함수
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 비밀번호 유효성 검사 함수
function isValidPassword($password) {
    // 비밀번호는 숫자, 대문자, 소문자, 특수문자 포함, 8~20자 제한
    $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,20}$/';
    return preg_match($pattern, $password);
}
?>
