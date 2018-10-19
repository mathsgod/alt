/*document.addEventListener("DOMContentLoaded", function () {

    var callback = function (mutationsList, observer) {
        for (var mutation of mutationsList) {
            if (mutation.type == 'childList') {
                console.log('A child node has been added or removed.');
            }
            else if (mutation.type == 'attributes') {
                console.log('The ' + mutation.attributeName + ' attribute was modified.');
            }
        }
    };
    var config = { attributes: true, childList: true, subtree: true };
    var observer = new MutationObserver(callback);
    observer.observe(document.body, config);

});*/


function VueRegisterElement(name) {
    document.addEventListener("DOMContentLoaded", function () {

        var config = { attributes: true, childList: true, subtree: true };

        // Callback function to execute when mutations are observed
        var callback = function (mutationsList, observer) {
            for (var mutation of mutationsList) {
                if (mutation.type == 'childList') {
                    for (var n of mutation.addedNodes) {
                        if (n.nodeName.toLowerCase() == name) {
                            new Vue({ el: n });
                        }
                        if (n.nodeType == 1) {
                            if (n.getAttribute("is") == name) {
                                new Vue({ el: n });
                            }

                            for (let c of n.querySelectorAll("[is='" + name + "']")) {
                                new Vue({ el: c });
                            }


                            for (let c of n.querySelectorAll(name)) {
                                new Vue({ el: c });
                            }
                        }
                    }
                }
                else if (mutation.type == 'attributes') {
                    if (mutation.attributeName == "is") {
                        if (mutation.target.getAttribute("is") == name) {
                            new Vue({ el: mutation.target });
                        }
                    }
                }
            }
        };

        var observer = new MutationObserver(callback);

        observer.observe(document.body, config);


        for (let c of document.body.querySelectorAll(name)) {
            new Vue({ el: c });
        }

        for (let c of document.body.querySelectorAll("[is='" + name + "']")) {
            new Vue({ el: c });
        }

    });
}
