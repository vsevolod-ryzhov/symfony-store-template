import '../css/app.scss';
require('bootstrap');
require('@coreui/coreui');
let Sortable = require('sortablejs');

let createAjaxRequest = function (url) {
    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    return xhttp;
}

let elSortable = document.getElementById('sortable');
if (elSortable) {
    let sortable = Sortable.default.create(elSortable);
    if (elSortable.dataset.callback !== undefined) {
        let store = function (elements) {
            let request = createAjaxRequest(elSortable.dataset.callback);
            request.send(JSON.stringify(elements.toArray()));
        };
        store(sortable);

        sortable.option('store', {set: store});
    }
}

const elNestedSortable = document.getElementsByClassName('nested-sortable');
if (elNestedSortable) {
    const root = document.getElementById('nested-sortable-root');

    for (let i = 0; i < elNestedSortable.length; i++) {
        let li_list = elNestedSortable[i].getElementsByTagName('li');
        for (let ii = 0; ii < li_list.length; ii++) {
            // All li items can be root now
            if (li_list[ii].getElementsByClassName('nested-sortable').length === 0) {
                let tag = document.createElement("ul");
                tag.className = 'nested-sortable nested-list-group';
                li_list[ii].appendChild(tag);
            }
        }

        new Sortable.default.create(elNestedSortable[i], {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onEnd: function (/**Event*/evt) {
                const moved = evt.item;
                const prev = moved.previousSibling;
                const next = moved.nextSibling;
                const parent = moved.parentNode.parentNode;
                const request = createAjaxRequest(root.dataset.callback);
                request.send(JSON.stringify({
                    'el': moved.dataset.id,
                    'prev': prev ? prev.dataset.id : null,
                    'next': next ? next.dataset.id : null,
                    'parent': parent ? parent.dataset.id ? parent.dataset.id : null : null
                }));
            }
        });
    }
}