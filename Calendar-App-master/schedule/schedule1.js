function addSchedule() {
    var title = document.getElementById('title').value;
    var date = document.getElementById('date').value;
    var time = document.getElementById('time').value;
    var memo = document.getElementById('memo').value;

    // 서버로 전송할 데이터를 생성
    var data = {
        title: title,
        date: date,
        time: time,
        detail: memo
    };

    // fetch API를 사용하여 서버로 데이터 전송
    fetch('/schedule/schedule1.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
        credentials: 'include'
    })
    .then((response) => response.text())
    .then((data) => {
        // data는 이제 서버에서 반환한 문자열입니다.
        if (data === '일정이 추가 되셨습니다!') {
            alert(data);
            window.location.href = '/calendar/calendar.html';
        } else {
            console.error('Error:', data);
        }
    })    
    .catch((error) => {
        console.error('Error:', error);
    });
}
