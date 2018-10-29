import bsInput from "./Input.vue";
import bsFormGroup from "./FormGroup.vue";
import bsInputSelect from "./InputSelect.vue";

Vue.component("bs-input", bsInput);
Vue.component("bs-form-group", bsFormGroup);
Vue.component("bs-input-select", bsInputSelect);

new Vue({
    created() {
        document.addEventListener("DOMContentLoaded", () => {
            this.VueRegisterElement("bs-input");
            this.VueRegisterElement("bs-form-group");
            this.VueRegisterElement("bs-input-select");
        });
    }, methods: {
        VueRegisterElement(name) {

            var config = { attributes: true, childList: true, subtree: true };

            // Callback function to execute when mutations are observed
            var callback = function (mutationsList) {
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
        }
    }
});