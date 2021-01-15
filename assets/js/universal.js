var loaderElement = `<div class="lds-ring"><div></div><div></div><div></div><div></div></div>`;

function wait(elem, remove = false) {
    if (!remove) {
        elem.html(loaderElement);
    } else {
        elem.html(``);
    }
}
