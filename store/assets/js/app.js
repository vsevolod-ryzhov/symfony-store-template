import '../css/app.scss';
require('bootstrap');
require('@coreui/coreui');
let Sortable = require('sortablejs');


let el = document.getElementById('sortable');
if (el) {
    let sortable = Sortable.default.create(el);
    if (el.dataset.callback !== undefined) {
        let store = function (elements) {
            let xhttp = new XMLHttpRequest();
            xhttp.open("POST", el.dataset.callback, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(JSON.stringify(elements.toArray()));
        };
        store(sortable);

        sortable.option('store', {set: store});
    }
}