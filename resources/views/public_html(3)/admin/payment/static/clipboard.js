function copyToClipboard(elementId) {
    var copyText = document.querySelector(elementId).innerText;
    var textarea = document.createElement("textarea");
    textarea.textContent = copyText;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
    // Display a toast message when the data has been copied to the clipboard
    var toastHTML = '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">';
    toastHTML += '<div class="toast-header"><strong class="mr-auto">Clipboard</strong><button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    toastHTML += '<div class="toast-body">Data has been copied to clipboard.</div></div>';
    document.body.insertAdjacentHTML('beforeend', toastHTML);
    $('.toast').toast('show');
}
