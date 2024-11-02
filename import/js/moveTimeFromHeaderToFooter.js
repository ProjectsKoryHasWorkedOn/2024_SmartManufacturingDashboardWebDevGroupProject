function checkIfWeShouldMoveCurrentTime() {
  const timeElement = document.getElementById('time');
  const loggedInTimeElement = document.getElementById('log-in_date_time_text');
  const headerMiddle = document.getElementById('header_middle');
  const footerLeft = document.getElementById('footer_left');
  if (window.innerWidth <= 800) {
    if (timeElement.parentNode === headerMiddle) {
      // Move it from header middle to footer left if it's in header middle and window <= 800px
      footerLeft.appendChild(timeElement);
      // Replace the element that was there
      loggedInTimeElement.style.display = 'none';
    }
  } else {
    if (timeElement.parentNode === footerLeft) {
      // Move it from footer left to header middle if its in footer left and window > 800px
      headerMiddle.appendChild(timeElement);
      // Re-show the element that was there in case it's hidden
      loggedInTimeElement.style.display = 'block';
    }
  }
}
window.addEventListener('resize', checkIfWeShouldMoveCurrentTime);
document.addEventListener('DOMContentLoaded', function () {
  checkIfWeShouldMoveCurrentTime();
});