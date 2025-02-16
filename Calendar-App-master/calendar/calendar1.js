//초기 HTML 문서가 완전히 로드되고 파싱된 시점에 발생하는 'DOMContentLoaded' 이벤트에 대한 이벤트 리스너를 추가합니다.
document.addEventListener('DOMContentLoaded', function () { 
    // 현재 날짜 정보 가져오기
    const date = new Date(); // 현재 날짜와 시간을 나타내는 Date 객체를 생성합니다.
    let currYear = date.getFullYear(); // Date 객체에서 현재 연도를 가져옵니다.
    let currMonth = date.getMonth(); // Date 객체에서 현재 월 (0부터 시작)을 가져옵니다.
  
    // 월 이름 배열
    const months = [
      '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'
    ];
  
    // 현재 날짜를 표시하는 DOM 요소 가져오기
    const currentDate = document.querySelector('.current-date');
    // 날짜를 표시하는 DOM 요소 가져오기
    const daysTag = document.querySelector('.days');
  
    // 캘린더 렌더링 함수
    const renderCalendar = () => {
      // 현재 연도와 월을 표시
      currentDate.innerHTML = `${currYear} ${months[currMonth]}`;
  
      // 이번 달 1일의 요일과 저번 달의 마지막 날짜, 이번 달의 마지막 날짜 구하기
      const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay();
      const lastDateOfPrevMonth = new Date(currYear, currMonth, 0).getDate();
      const lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate();
  
      // 날짜를 표시하는 HTML 태그 생성
      let liTag = '';
      for (let i = lastDateOfPrevMonth - firstDayOfMonth + 1; i <= lastDateOfPrevMonth; i++) {
        // 저번 달 날짜 표시
        liTag += `<li class="prev-date" data-month="${currMonth - 1}" data-year="${currMonth === 0 ? currYear - 1 : currYear}">${i}</li>`;
      }
  
      for (let i = 1; i <= lastDateOfMonth; i++) {
        // 이번 달 날짜 표시
        const isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear() ? 'active' : '';
        liTag += `<li class="${isToday}" data-month="${currMonth}" data-year="${currYear}">${i}</li>`;
      }
  
      const remainingDays = 42 - (firstDayOfMonth + lastDateOfMonth);
      for (let i = 1; i <= remainingDays; i++) {
        // 다음 달 날짜 표시
        liTag += `<li class="next-date" data-month="${currMonth + 1}" data-year="${currMonth === 11 ? currYear + 1 : currYear}">${i}</li>`;
      }
  
      // 날짜를 표시하는 DOM에 HTML 추가
      daysTag.innerHTML = liTag;
  
      // 캘린더에 해당하는 일정 정보 가져오기
      fetch(`calendar1.php?year=${currYear}&month=${currMonth + 1}`)
        .then(response => response.json())
        .then(data => {
          const eventsContainer = document.getElementById('event-list');
          const eventListDate = document.getElementById('event-list-date');
  
          if (Array.isArray(data)) {
            // 배열이 비어있지 않은 경우
            if (data.length > 0) {
              const events = data.map(event => `<div>${event.date}: ${event.title}</div>`).join('');
              eventsContainer.innerHTML = events;
            } else {
              eventsContainer.innerHTML = '저장된 일정 없음';
            }
          } else {
            // 배열이 아닌 경우, 오류 메시지 출력
            eventsContainer.innerHTML = '일정 데이터를 가져오지 못했습니다.';
          }
        })
        .catch(error => {
          console.error('Error fetching events:', error);
          const eventsContainer = document.getElementById('event-list');
          eventsContainer.innerHTML = '일정 데이터를 가져오는 중 오류가 발생했습니다.';
        });
    };
  
    daysTag.addEventListener('click', (event) => {
      const clickedDate = event.target;
      const clickedMonth = parseInt(clickedDate.dataset.month);
      const clickedYear = parseInt(clickedDate.dataset.year);
      const eventList = document.getElementById('event-list');
      const eventListDate = document.getElementById('event-list-date');
  
      if (!isNaN(clickedMonth) && !isNaN(clickedYear)) {
          currMonth = clickedMonth;
          currYear = clickedYear;
          renderCalendar();
  
          const day = clickedDate.innerText;
          const selectedDate = new Date(currYear, currMonth, day);
  
          // 클릭된 날짜를 일정란에 출력
          eventListDate.innerHTML = `${selectedDate.toLocaleDateString()}`;
  
          // 클릭된 날짜에 해당하는 일정 가져오기
          fetch(`calendar1.php?year=${currYear}&month=${currMonth + 1}&day=${day}`)
              .then(response => response.json())
              .then(data => {
                  if (Array.isArray(data)) {
                      if (data.length > 0) {
                          const events = data.map(event => `<div>${event.title}</div>`).join('');
                          eventList.innerHTML = events;
                      } else {
                          eventList.innerHTML = '저장된 일정 없음';
                      }
                  } else {
                      eventList.innerHTML = '일정 데이터를 가져오지 못했습니다.';
                  }
              })
              .catch(error => {
                  console.error('Error fetching events:', error);
                  eventList.innerHTML = `일정 데이터를 가져오는 중 오류가 발생했습니다. (${error.message})`;
              });
      }
  });
  
    // 페이지 로딩 시 캘린더 렌더링
    renderCalendar();
  
    // 이전, 다음 달 아이콘에 대한 클릭 이벤트 리스너 등록
    const prevIcon = document.querySelector('.last-icons');
    const nextIcon = document.querySelector('.next-icons');
  
    prevIcon.addEventListener('click', () => {
      // 이전 달로 이동
      currMonth--;
      if (currMonth < 0) {
        currMonth = 11;
        currYear--;
      }
      renderCalendar();
    });
  
    nextIcon.addEventListener('click', () => {
      // 다음 달로 이동
      currMonth++;
      if (currMonth > 11) {
        currMonth = 0;
        currYear++;
      }
      renderCalendar();
    });
  });
  
  // 일정 추가 페이지로 이동하는 함수
  function goToAddEventPage() {
    // 일정 추가 페이지로 이동
    window.location.href = '/schedule/schedule.html';
  }
  