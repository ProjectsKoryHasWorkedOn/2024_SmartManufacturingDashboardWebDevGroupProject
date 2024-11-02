var HasMessageBeenShown = false;
function getTime(eventSourceFilePath) {
  const currentTimeElement = document.getElementById("current_time");
  var currentTimeEventSource = new EventSource(eventSourceFilePath);
  currentTimeEventSource.onmessage = function (event) {
    if (currentTimeEventSource.readyState === EventSource.OPEN) {
      var data = JSON.parse(event.data);
      currentTimeElement.textContent = data.current_time;
      if (!HasMessageBeenShown) {
        /* Difference between these two values should be right */
        console.info('Clock in time: ' + data.clock_in_time);
        console.info('Projected clock off time: ' + data.projected_clock_off_time);
        HasMessageBeenShown = true;
      }
      /* This value should approach 0 and stop at 0 */
      // console.info('Remaining time: ' + data.remaining_time);
      var workedPercentage = data.worked_percentage;
      var breakPercentage = data.break_percentage;
      var remainingPercentage = data.remaining_percentage;
      document.documentElement.style.setProperty('--worked-percentage', workedPercentage + '%');
      document.documentElement.style.setProperty('--break-percentage', breakPercentage + '%');
      document.documentElement.style.setProperty('--remaining-percentage', remainingPercentage + '%');
    }
  };
  var showErrorMessageForGetTimeJSFunction = true;
  currentTimeEventSource.onerror = function (event) {
    if (showErrorMessageForGetTimeJSFunction) {
      console.warn('Error occurred:', event);
      console.warn('currentTimeEventSource readyState:', eventSource.readyState);
      console.warn('currentTimeEventSource URL:', eventSource.url);
      console.warn('Event data:', event.data);
    }
    // Make it reconnect if connection closes
    if (currentTimeEventSource.readyState === EventSource.CLOSED) {
      console.warn('Reconnecting');
      currentTimeEventSource = getTime(); // Recreate it
    }
  };
  return currentTimeEventSource;
}
