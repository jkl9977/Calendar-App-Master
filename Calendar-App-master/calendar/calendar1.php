<?php
// calendar.php - 여기에 데이터베이스에서 해당 날짜의 일정을 가져오는 로직을 구현

// 데이터베이스 연결 설정
$host = 'localhost';
$user = 'korea';
$pw = '1234';
$dbName = 'app'; // 실제 데이터베이스 이름으로 변경 필요
$mysqli = new mysqli($host, $user, $pw, $dbName);

// 연결 확인
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 예시: 데이터베이스에서 해당 날짜의 일정을 가져오는 함수
function getEventsByDate($mysqli, $year, $month, $day) {
    // 가져온 일정을 JSON 형식으로 반환
    $query = "SELECT * FROM Add_Schedule WHERE YEAR(date) = ? AND MONTH(date) = ? AND DAY(date) = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iii", $year, $month, $day);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = array();
    while ($row = $result->fetch_assoc()) {
        $events[] = array(
            'date' => $row['date'],
            'title' => $row['title']
            // 추가적인 필드들...
        );
    }

    $stmt->close();
    return json_encode($events);
}

// 현재 연도와 월, 일을 가져옴
$currYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$currMonth = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$currDay = isset($_GET['day']) ? intval($_GET['day']) : '';

// 가져온 연도, 월, 일에 해당하는 일정을 JSON 형식으로 출력
echo getEventsByDate($mysqli, $currYear, $currMonth, $currDay);

// 연결 종료
$mysqli->close();
?>
