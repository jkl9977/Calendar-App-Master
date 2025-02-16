document.addEventListener("DOMContentLoaded", function () {
    // DOM이 완전히 로드될 때까지 기다립니다.
    setTimeout(function () {
        // 1.5초 후 로그인 페이지로 이동합니다.
        window.location.href = "login/login.html";
    }, 3000);
});
