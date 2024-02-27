function copyToClipboard(elementId) {
    var copyText = document.querySelector(elementId).innerText;
    var textarea = document.createElement("textarea");
    textarea.textContent = copyText;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
    var toastElement = new bootstrap.Toast(document.getElementById('clipboard'));
    toastElement.show();
}
