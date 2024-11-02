function getTime(eventSourceFilePath) {
  const timeElement = document.getElementById("time");
  var currentTimeEventSource = new EventSource(eventSourceFilePath);
  currentTimeEventSource.onmessage = function (event) {
    if (currentTimeEventSource.readyState === EventSource.OPEN) {
      timeElement.textContent = event.data;
    }
  };
  // Event source alternates between 
  // currentTimeEventSource.readyState = 1
  // currentTimeEventSource.readyState = 0
  // While loop seems to have fixed this
  var showErrorMessageForGetTimeJSFunction = true;
  currentTimeEventSource.onerror = function (event) {
    if (showErrorMessageForGetTimeJSFunction) {
      console.warn('Error occurred:', event);
      // Async: EventHandlerNonNull
      console.warn('currentTimeEventSource readyState:', currentTimeEventSource.readyState);
      // 0
      console.warn('currentTimeEventSource URL:', currentTimeEventSource.url);
      // URL is correct
      console.warn('Event data:', event.data);
      // undefined value
    }
    // Make it reconnect if connection closes
    if (currentTimeEventSource.readyState === EventSource.CLOSED) {
      console.log('Reconnecting');
      currentTimeEventSource = getTime(); // Recreate it
    }
  };
  return currentTimeEventSource;
}
