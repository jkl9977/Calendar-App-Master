function addSchedule() {
    var title = document.getElementById('title').value;
    var date = document.getElementById('date').value;
    var time = document.getElementById('time').value;
    var memo = document.getElementById('memo').value;

    // 여기에서 서버로 일정 정보를 전송하는 AJAX 코드를 작성
    // 예를 들면, fetch API를 사용할 수 있습니다.

    // 서버로 전송 후, 캘린더 페이지로 이동
    window.location.href = '/calendar/calendar.html';
}
