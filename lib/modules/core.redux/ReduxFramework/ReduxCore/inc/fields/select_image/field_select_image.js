function updateSelectImage(obj, imgID) {
    tochange = obj.value;
    document.getElementById(imgID).src = tochange;
    document.getElementById(imgID).style = 'visibility:show;';
}
