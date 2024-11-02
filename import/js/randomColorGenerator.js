function generateRandomColor() {
    var newRandomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
    return newRandomColor;
}