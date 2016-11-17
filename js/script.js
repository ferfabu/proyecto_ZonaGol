$(document).ready(function($){
    $("#footer").html("All Rights Reserved. ZONAGOL");
    //inicia el modal
    $("#loginbutton").click(function(event){
    	event.preventDefault();
    	$("#modalLogin").show();
    	$("#modalLogin").addClass('open');
    });

    //inicia el modal de products
    $("#productsbutton").click(function(event){
    	event.preventDefault();
    	$("#modalProducts").show();
    	$("#modalProducts").addClass('open');
    });

    //inicia el modal de Delete products
    $("#productsdeletebutton").click(function(event){
        event.preventDefault();
        $("#modalDeleteProducts").show();
        $("#modalDeleteProducts").addClass('open');
    });

    // CIERRA EL MODAL
    $("body").on('click', '.modal.open .close', function(event) {
        $(this).parents(".modal").hide();
        $(this).parents(".modal").removeClass("open");
        $("#emailLoginModal").val("");
        $("#passwordLoginModal").val("");
        $("#emailRegisterModal").val("");
        $("#passwordRegisterModal").val("");
        $("#fnameRegisterModal").val("");
        $("#lnameRegisterModal").val("");
        $("#txtnom2").val("");
    });

    // MODAL REGISTRATION
    $("body").on('click', '.modal.open #goRegister', function(event) {
        event.preventDefault();
        $(this).parents(".modal").hide();
        $(this).parents(".modal").removeClass("open");
        $("#modalRegistration").show();
        $("#modalRegistration").addClass('open');
    });

    // MODAL LOGIN
    $("body").on('click', '.modal.open #goLogin', function(event) {
        event.preventDefault();
        $(this).parents(".modal").hide();
        $(this).parents(".modal").removeClass("open");
        $("#modalLogin").show();
        $("#modalLogin").addClass('open');
    });

    //MODAL enviar foto producto
    $("body").on('click', '.modal.open #probutton', function(event) {
    	if ($("#txtnom2").val() === "") {
    		$("#productError").text("You need to fill the name field");
            return false;
    	}else{
    		$("#productError").text("");
    	}
       alert("Successfully insert image of the product");
    });

    // REGISTRAR
    $("body").on('click', '.modal.open #registerBtn', function(event) {
        var re = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
        if ($("#emailRegisterModal").val() === "" || $("#passwordRegisterModal").val() === ""
            || $("#fnameRegisterModal").val() === "" || $("#lnameRegisterModal").val() === "") {
            $("#registerError").text("You need to fill all the fields");
            return false;
        }
        else if(!$("#emailRegisterModal").val().match(re)){
            $("#registerError").text("This is not a valid email format");
            return false;
        }
        else {
            $("#registerError").text("");
        }
        // REGISTRAR
        $.ajax({
            url: 'data/applicationLayer.php',
            type: 'POST',
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            data: {
                action: "REGISTERSESSION",
                fName: $("#fnameRegisterModal").val(),
                lName: $("#lnameRegisterModal").val(),
                email: $('#emailRegisterModal').val(),
                password: $('#passwordRegisterModal').val()
            }
        })
        .done(function(response) {
            if (response.status === "SUCCESS") {
                console.log(response.message);
                $("#emailLoginModal").val("");
                $("#passwordLoginModal").val("");
                $("#emailRegisterModal").val("");
                $("#passwordRegisterModal").val("");
                $("#fnameRegisterModal").val("");
                $("#lnameRegisterModal").val("");
                $("#modalRegistration").hide();
                $("#modalRegistration").removeClass("open");
                $("#modalLogin").show();
                $("#modalLogin").addClass('open');
                alert("Correct registration.");
            }
        })
        .fail(function(errorMessage) {
            $("#registerError").text(errorMessage.responseText);
        });
    });

        //SECCION COMENTARIOS
    var $commentTemplate = $(".comment-item");
    $(".comment-item").remove();
    $("#sendComment").click(function(event) {
        event.preventDefault(); 
        if ($("#comment-input").val() === "") {
            $("#commentError").html("Write a comment!!!");
            return false;
        }
        else {
            $("#commentError").html("");
        }

        //CHECA SI ESTA INICIADA LA SESION
        $.ajax({
            url: 'data/applicationLayer.php',
            type: 'POST',
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            data: {
                action: "VERIFYSESSION"
            },
        })
        .done(function(response) {
            if (response.status === "SUCCESS") {
                $.ajax({
                    url: 'data/applicationLayer.php',
                    type: 'POST',
                    dataType: 'json',
                    contentType: "application/x-www-form-urlencoded",
                    data: {
                        action: "INSERTCOMMENT",
                        comment: $('#comment-input').val()
                    }
                })
                .done(function(responseOne) {
                    console.log(responseOne.message);
                    var $newComment = $commentTemplate.clone(true);
                    $newComment.find('.commEmail').html(response.email);
                    $newComment.find('.commBody').html($("#comment-input").val());
                    $newComment.find('.commName').html(response.firstName + " " + response.lastName);

                    ($newComment).show();
                    $("#initialMessage").remove();
                    $("#comment-display").prepend($newComment);
                    $("#comment-input").val("");
                })
                .fail(function(errorMessage) {
                    $("#commentError").text(errorMessage.responseText);
                });
            }
            else {
                $("#modalLogin").addClass('from-comment');
                $("#modalLogin").show();
                $("#modalLogin").addClass('open'); 
            }
        }) 
    });

    //PUBLICAR COMENTARIOS DESDE LA DATABASE
    $.ajax({
        url: 'data/applicationLayer.php',
        type: 'POST',
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        data: {
            action: "GETCOMMENTS"
        },
    })
    .done(function(jsonResponse) {
        $.each(jsonResponse, function(index, val) {
            if (jsonResponse.length) {
                $("#initialMessage").remove();
                var $currentComment = $commentTemplate.clone(true);
                $currentComment.find('.commName').html(val.fName + " " + val.lName);
                $currentComment.find('.commEmail').html(val.email);
                $currentComment.find('.commBody').html(val.commentDB);
                $currentComment.show();
                $("#comment-display").prepend($currentComment);
            }
        });
    })
    .fail(function(errorMessage) {
        console.log(errorMessage.responseText);
    });

    // SUBMIT LOGIN
    $("body").on('click', '.modal.open #loginBtn', function(event) {
        if ($("#emailLoginModal").val() === "" || $("#passwordLoginModal").val() === "") {
            $("#loginError").text("You need to fill the fields");
            return false;
        }
        else {
            $("#loginError").text("");
        }
        $.ajax({
            url: 'data/applicationLayer.php',
            type: 'POST',
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            data: {
                action: "LOGINSESSION",
                email: $("#emailLoginModal").val(),
                password: $("#passwordLoginModal").val(),
                rememberData: $("#rememberLoginModal").is(":checked")
            },
        })
        .done(function(jsonResponse) {
            $("#loginbuttonli").addClass('hidden');
            $("#logged-in").removeClass('hidden');
            $("#userInfo").html(jsonResponse.firstName);
            if (jsonResponse.rol == 1) {
                $("#productsbuttonli").removeClass('hidden');
                $("#productsdeletebuttonli").removeClass('hidden');
                $("#productseditarbuttonli").removeClass('hidden');
            }

            if ($("#modalLogin").hasClass('from-comment')) {
                $("#modalLogin").removeClass('from-comment');
                $.ajax({
                    url: 'data/applicationLayer.php',
                    type: 'POST',
                    dataType: 'json',
                    contentType: "application/x-www-form-urlencoded",
                    data: {
                        action: "INSERTCOMMENT",
                        comment: $('#comment-input').val()
                    }
                })
                .done(function(response) {
                    console.log(response.message);
                    var $newComment = $commentTemplate.clone(true);
                    $newComment.find('.commEmail').html($("#emailLoginModal").val());
                    $newComment.find('.commBody').html($("#comment-input").val());
                    $newComment.find('.commName').html(jsonResponse.firstName + " " + jsonResponse.lastName);

                    ($newComment).show();
                    $("#initialMessage").remove();
                    $("#comment-display").prepend($newComment);
                    $("#comment-input").val("");
                    
                    $(event.target).parents(".modal").hide();
                    $(event.target).parents(".modal").removeClass("open");
                })
                .fail(function(errorMessage) {
                    $("#commentError").text(errorMessage.responseText);

                    $(event.target).parents(".modal").hide();
                    $(event.target).parents(".modal").removeClass("open");
                });
            }
            else {
                $(event.target).parents(".modal").hide();
                $(event.target).parents(".modal").removeClass("open");
            }
        })
        .fail(function(errorMessage) {
            $("#loginError").text(errorMessage.responseText);
        });
    });

    // VERIFICA LA COOKIE
    $.ajax({
        url: 'data/applicationLayer.php',
        type: 'POST',
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        data: {
            action: "STARTSESSION"
        },
    })
    .done(function(response) {
        if (response.status === "SUCCESS") {
            console.log(response.message);
        }
        else {
            console.log(response.message);
        }
    })
    .fail(function(errorMessage) {
        console.log(errorMessage.responseText);
    });

    // VERIFICA LA SESION
    $.ajax({
        url: 'data/applicationLayer.php',
        type: 'POST',
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        data: {
            action: "VERIFYSESSION"
        },
    })
    .done(function(response) {
        if (response.status === "SUCCESS") {
            $("#loginbuttonli").addClass('hidden');
            $("#logged-in").removeClass('hidden');
            $("#userInfo").html(response.firstName);
            if (response.rol == 1) {
                $("#productsbuttonli").removeClass('hidden');
                $("#productsdeletebuttonli").removeClass('hidden');
                $("#productseditarbuttonli").removeClass('hidden');
            }
        }
    });

    // LOGOUT DE LA SESION
    $("#logout-btn").click(function() {
        $.ajax({
            url: 'data/applicationLayer.php',
            type: 'POST',
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            data: {
                action: "LOGOUTSESSION"
            },
        })
        .done(function(response) {
            alert(response.message);
            $("#loginbuttonli").removeClass('hidden');
            $("#logged-in").addClass('hidden');
            $("#userInfo").html("");
            $("#productsbuttonli").addClass('hidden');
            $("#productsdeletebuttonli").addClass('hidden');
            $("#productseditarbuttonli").addClass('hidden');
            $("#emailLoginModal").val();
            $("#passwordLoginModal").val();
            $("#fnameRegisterModal").val();
            $("#lnameRegisterModal").val();
            $("#emailRegisterModal").val();
            $("#passwordRegisterModal").val();
        }); 
    })

    //PUBLICAR IMAGENES DESDE LA DATABASE
    var $productTemplate = $(".producto2");
    var $cont = 0;
    $.ajax({
        url: 'data/applicationLayer.php',
        type: 'POST',
        dataType: 'json',
        contentType: "application/x-www-form-urlencoded",
        data: {
            action: "MOSTRARIMAGENES",
        },
    }) 
    .done(function(jsonResponse) {
        $("#divProductos").removeClass('hidden');
        $("#divProductosB").addClass('hidden');
        $.each(jsonResponse, function(index, val) {
            if (jsonResponse.length) {
                $("#initialProductMessage").remove();
                var $currentProduct = $productTemplate.clone(true);
                $currentProduct.html(val.nombre + '<br>' + '<img src="' + val.foto + '" width="100" height="200">');
                $currentProduct.show();
                $("#products-display").prepend($currentProduct);
            }
        }); 
    })
    .fail(function(errorMessage) {
        console.log(errorMessage.responseText);
    });

    $("#porNombre").on('click', function(event){
        event.preventDefault();
        $("#searchform").removeClass('hidden');
        $("#searchformcat").addClass('hidden');
    });

    $("#porCategoria").on('click', function(event){
        event.preventDefault();
        $("#searchformcat").removeClass('hidden');
        $("#searchform").addClass('hidden');
    });

    //PUBLICAR IMAGENES DESDE LA DATABASE CON "BUSCADOR DE PRODUCTOS POR NOMBRE"    
    $("#buttonBuscador").on('click', function(event) {
        event.preventDefault();
        $("#buttonLimpiar").removeClass('hidden');
        event.preventDefault();
        var $nameBuscador = $("#txtnameB").val();
        var $productTemplateB = $(".producto2B");
        $.ajax({
            url: 'data/applicationLayer.php',
            type: 'POST',
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            data: {
                action: "MOSTRARIMAGENESBUSCADORNOMBRE",
                name : $nameBuscador
            },
        }) 
        .done(function(jsonResponse) {
            $.each(jsonResponse, function(index, val) {
                $("#divProductos").addClass('hidden');
                $("#divProductosB").removeClass('hidden');
                if (jsonResponse.length) {
                    $("#initialProductMessageB").remove();
                    var $currentProductB = $productTemplateB.clone(true);
                    $currentProductB.html(val.nombre + '<br>' + '<img src="' + val.foto + '" width="100" height="200">');
                    $currentProductB.show();
                    $("#products-displayB").prepend($currentProductB);
                }
            }); 
        })
        .fail(function(errorMessage) {
            console.log(errorMessage.responseText);
        });
    });      

    //PUBLICAR IMAGENES DESDE LA DATABASE CON "BUSCADOR DE PRODUCTOS POR CATEGORIA"    
    $("#buttonBuscadorCat").on('click', function(event) {
        event.preventDefault();
        $("#buttonLimpiarCat").removeClass('hidden');
        event.preventDefault();
        var $nameBuscadorCat = $("#txtnameBCat").val();
        var $productTemplateB = $(".producto2B");
        $.ajax({
            url: 'data/applicationLayer.php',
            type: 'POST',
            dataType: 'json',
            contentType: "application/x-www-form-urlencoded",
            data: {
                action: "MOSTRARIMAGENESBUSCADORCATEGORIA",
                nameCat : $nameBuscadorCat
            },
        }) 
        .done(function(jsonResponse) {
            $.each(jsonResponse, function(index, val) {
                $("#divProductos").addClass('hidden');
                $("#divProductosB").removeClass('hidden');
                if (jsonResponse.length) {
                    $("#initialProductMessageB").remove();
                    var $currentProductB = $productTemplateB.clone(true);
                    $currentProductB.html(val.nombre + '<br>' + '<img src="' + val.foto + '" width="100" height="200">');
                    $currentProductB.show();
                    $("#products-displayB").prepend($currentProductB);
                }
            }); 
        })
        .fail(function(errorMessage) {
            console.log(errorMessage.responseText);
        });
    });
})

