<?php
// calendar.php - 여기에 데이터베이스에서 해당 날짜의 일정을 가져오는 로직을 구현

// 예시: 데이터베이스에서 해당 날짜의 일정을 가져오는 함수
function getEventsByDate($year, $month, $day) {
    // 여기에 데이터베이스에서 해당 날짜의 일정을 가져오는 로직을 추가
    // 가져온 일정을 JSON 형식으로 반환
	// 오류가 많이 남. db 연결후 확인 필요.
    $events = array(
        array('date' => '2023-11-01', 'title' => '회의 일정'),
        array('date' => '2023-11-05', 'title' => '생일 축하파티'),
		array('date' => '2023-11-10', 'title' => '파티'),
        // 추가적인 일정들...
    );

    // 날짜에 해당하는 일정만 필터링
    $filteredEvents = array_filter($events, function ($event) use ($year, $month, $day) {
        return substr($event['date'], 0, 4) == $year && substr($event['date'], 5, 2) == $month && substr($event['date'], 8, 2) == $day;
    });

    return json_encode($filteredEvents);
}

// 현재 연도와 월, 일을 가져옴
$currYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$currMonth = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$currDay = isset($_GET['day']) ? intval($_GET['day']) : '';

// 가져온 연도, 월, 일에 해당하는 일정을 JSON 형식으로 출력
echo getEventsByDate($currYear, $currMonth, $currDay);
?>
