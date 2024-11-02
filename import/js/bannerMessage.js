function makeDisappear(bannerID) {
    var message_banner = document.getElementById(bannerID);
    setTimeout(() => {
        message_banner.style.display = 'none';
    }, "5000");
}
function updateMessage(message, bannerID, bannerTextID) {
    // console.log("Updating message:", message); // Debugging line
    var message_banner = document.getElementById(bannerID);
    var message_text = document.getElementById(bannerTextID);
    message_banner.style.display = 'block';
    message_text.textContent = message;
    makeDisappear(bannerID);
} 