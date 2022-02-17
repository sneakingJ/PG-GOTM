require('./bootstrap');

buttonLoad = function (el) {
    el.classList.add('is-loading');

    setTimeout(() => {
        el.classList.remove('is-loading');
    }, 3000);
}
