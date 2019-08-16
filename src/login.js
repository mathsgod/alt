var Vue = window.Vue;
Vue.use(VueLocalStorage);
var $ = window.$;
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
            if (localStorage.getItem("app.fido2")) {
                var username = localStorage.getItem("app.fido2");
                this.$gql.query("api", {
                    credentialRequestOptions: {
                        __args: {
                            username
                        }
                    }
                }).then(resp => {
                    var a = new WebAuthn();
                    a.authenticate(resp.data.data.credentialRequestOptions).then(info => {
                        this.$gql.query("api", {
                            loginWebAuthn: {
                                __args: {
                                    username: username,
                                    assertion: JSON.stringify(info)
                                }
                            }
                        }).then(resp => {
                            if (resp.data.data.loginWebAuthn) {
                                window.self.location.reload();
                            } else {
                                bootbox.alert("login error");
                            }
                        });
                    }).catch(resp => {
                        this.passwordLogin();
                    });
                });
            } else {
                this.passwordLogin();
            }


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
        passwordLogin() {
            navigator.credentials.get({
                password: true
            }).then(creds => {
                if (creds) {
                    //Do something with the credentials.
                    this.login(creds.id, creds.password);
                }
            });
        }, login(username, password, code) {
            this.$gql.mutation("api", {
                login: {
                    __args: {
                        username: username,
                        password: password,
                        code: code ? code : ""
                    },
                    username: true
                }
            }).then(resp => {
                var r = resp.data;
                if (r.data.login) {
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
                    if (r.error.message == "2-step verification") {
                        bootbox.prompt("Please input 2-step verification code", result => {
                            if (result) {
                                this.login(username, password, result);
                            }
                        });

                    } else {
                        this.message = r.error.message;
                        this.error = true;
                    }
                }
            });
        }, signIn() {
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
        }, forgetPassword() {
            var self = this;
            var bb = bootbox.dialog({
                title: "Forget password",
                message: $("#forget_dialog").html()
            }).on('shown.bs.modal', function () {
                $(this).find("form").on("submit", function () {
                    var data = $(this).getFormData();
                    self.$gql.query("api", {
                        forgotPassword: {
                            __args: data
                        }
                    }).then(resp => {
                        var data = resp.data;
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