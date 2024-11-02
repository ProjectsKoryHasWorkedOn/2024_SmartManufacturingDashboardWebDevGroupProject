class VideoPlayer {
    setVideoSource(videoID) {
        this.video = document.getElementById(videoID);
    }
    play() {
        this.video.play();
    }
    unmute() {
        this.video.muted = false;
    }
    unmuteAfterXSeconds(seconds) {
        setTimeout(() => {
            this.unmute();
        }, returnMillisecondsFromSeconds(seconds));
    }
}