var Vue = window.Vue;
Vue.use(VueLocalStorage);
var $=window.$;
var vm = new Vue({
    el: "#app",
    data: {
        username: "",
        password: "",
        code: "",
        message: "Sign in to start your session",
        error: false
    },
    created() {
        if ('credentials' in navigator) {
            navigator.credentials.get({
                password: true
            }).then(creds => {
                if (creds) {
                    //Do something with the credentials.
                    this.login(creds.id, creds.password);
                }
            });
        }
    }, mounted() {
        $(this.$refs.rememberMe).iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $(this.$refs.rememberMe).on("ifChecked", () => {
            this.$localStorage.set("app.remember_me", true);
        });

        $(this.$refs.rememberMe).on("ifUnchecked", () => {
            this.$localStorage.set("app.remember_me", false);
        });

        if (this.$localStorage.get("app.remember_me") == "true") {
            this.username = this.$localStorage.get("app.username");
            $(this.$refs.rememberMe).iCheck('check');
            $(this.$refs.rememberMe).iCheck('update');
        }
    },
    methods: {
        login(username, password) {
            this.$http.post("System/login", {
                username: username,
                password: password,
                code: this.code
            }).then(resp => {
                if (resp.data.code == 200) {

                    if (this.$localStorage.get("app.remember_me") == "true") {
                        this.$localStorage.set("app.username", username);
                    }

                    var redirect = this.$refs.redirect.value;

                    if (redirect != "") {
                        window.self.location = redirect;
                    } else {
                        window.self.location.reload();
                    }
                } else {
                    this.message = resp.data.error.message;
                    this.error = true;
                }
            });
        },
        signIn() {
            if (this.username == "") {
                this.error = true;
                this.message = "Please input username";
                return;
            }
            if (this.password == "") {
                this.error = true;
                this.message = "Please input password";
                return;
            }
            this.login(this.username, this.password);
        },
        forgetPassword() {
            var bb = bootbox.dialog({
                title: "Forget password",
                message: $("#forget_dialog").html()

            }).on('shown.bs.modal', function () {
                $(this).find("form").on("submit", function () {
                    $(this).ajaxSubmit(function (data) {
                        bb.modal('hide');
                        if (data.error) {
                            bootbox.alert(data.error.message);
                        } else {
                            bootbox.alert("Password sent to your email if information correct");
                        }


                    });
                    return false;
                });
            });
        }
    }
});