$(document).ready(function() {
    $('#form_reg').validate({
        // правила для проверки
        rules:{
            "login":{
                required:true,
                minlength:5,
                maxlength:15,
                remote: {
                    type: "post",
                    url: "reg/check_login.php"
                }
            },
            "password":{
                required:true,
                minlength:7,
                maxlength:15
            },
            "surname":{
                required:true,
                minlength:3,
                maxlength:15
            },
            "name":{
                required:true,
                minlength:3,
                maxlength:15
            },
            "patronomic":{
                required:true,
                minlength:3,
                maxlength:25
            },
            "email":{
                required:true,
                email:true
            },
            "phone":{
                required:true
            },
            "address":{
                required:true
            },
            "captcha":{
                required:true,
                remote: {
                    type: "post",
                    url: "reg/check_captcha.php"

                }
            }
        },

        // выводимые сообщения при нарушении соответствующих правил
        messages:{
            "login":{
                required:"Укажите Логин!",
                minlength:"От 5 до 15 символов!",
                maxlength:"От 5 до 15 символов!",
                remote: "Логин занят!"
            },
            "password":{
                required:"Укажите Пароль!",
                minlength:"От 7 до 15 символов!",
                maxlength:"От 7 до 15 символов!"
            },
            "surname":{
                required:"Укажите вашу Фамилию!",
                minlength:"От 3 до 20 символов!",
                maxlength:"От 3 до 20 символов!"
            },
            "name":{
                required:"Укажите ваше Имя!",
                minlength:"От 3 до 15 символов!",
                maxlength:"От 3 до 15 символов!"
            },
            "patronomic":{
                required:"Укажите ваше Отчество!",
                minlength:"От 3 до 25 символов!",
                maxlength:"От 3 до 25 символов!"
            },
            "email":{
                required:"Укажите свой E-mail",
                email:"Не корректный E-mail"
            },
            "phone":{
                required:"Укажите номер телефона!"
            },
            "address":{
                required:"Необходимо указать адрес доставки!"
            },
            "captcha":{
                required:"Введите код с картинки!",
                remote: "Не верный код проверки!"
            }
        },
        submitHandler: function(form){
            $(form).ajaxSubmit({
                success: function(data) {
                    if (data == true) {
                        $("#block-form-registration").fadeOut(300,function() {
                            $(".reg_message").addClass("reg_message_good").fadeIn(400).html("Вы успешно зарегистрированы!");
                            $("#form_submit").hide();
                        });
                    } else {
                        $(".reg_message").addClass("reg_message_error").fadeIn(400).html(data);
                    }
                }
            });
        }
    });
});
