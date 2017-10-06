var check_j = false;
var check_s = false;
var flag = false;
$(function(){



    /*dsdsds*/
    /*$('#campo_principale1').on('keypress', function (e) {

        // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46, ',' = 188


        var field = $(this);
        $('#campo_principale1').val('');

        key = e.keyCode ? e.keyCode : e.which;

        if (key == 8) return true;

        if (key > 47 && key < 58) {

            if (field.val() === "") return true;

            var existePto = (/[.]/).test(field.val());
            var existComma = (/[,]/).test(field.val());

            if (existePto === false){

                regexp = /.[0-9]{10}$/;
            }
            else {
                regexp = /.[0-9]{2}$/;
            }



            return !(regexp.test(field.val()));

        }

        if (key == 46) {

            if (field.val() === "") return false;

            regexp = /^[0-9]+$/;

            return regexp.test(field.val());

        }

        /*if(key == 188){
            regexp = /^[0-9]+$/;

            return regexp.test(field.val());

        }

        return false;

    });*/
    /*end dsds*/


    /*Inicializar el datepicker componet*/
    App.init();
    FormComponents.init();


    $('#content-altro').css('display','none');

    $('#criteria').change(function(){
        if($(this).val() == 3)
            $('#content-altro').css('display','block');
        else
            $('#content-altro').css('display','none');
    });


    // FormComponents.init();
    var value = 0;
    // $('#list_junior').css('display','none');
    //$('#list_senior').css('display','none');

    $("[id*=campo_principale1]").each(
        function(index, value) {
            $(this).change(change_cantidad)
        }
    );

    //show fields junior
    $('#junior').click(function(){
        if($(this).is(':checked')){
            check_j = true;
            $('#list_junior').css('display','block');


        }else{
            $('#list_junior').css('display','none');
            check_j = false;
        }
        // comprobarCheck();

    }).change(comprobarCheck);

    //show fields senior

    /*$('#senior').click(function(){
     if($(this).is(':checked')){
     check_s = true;
     // $('#list_senior').css('display','block');

     }else{
     // $('#list_senior').css('display','none');
     check_s = false;
     }


     }).change(comprobarCheck);*/




    //recorrer los input checkbox value
    $('input[name=check_value]').each(function(){
        $(this).click(function(){
            value =parseFloat($('#txt_totale').val()) ;
            var valor_check =  $('#txt-check').val();
            if( valor_check !=1){
                if($(this).is(':checked')){
                    //alert(valor_check);
                    $('#txt_totale').val(parseFloat($(this).val()) + value);
                }else{
                    // alert(value);
                    $('#txt_totale').val(value - parseFloat($(this).val()));
                }
            }


        });

    });




    //seleccionar opcion  mporto progettazione
    $('#slect_main').change(function(){
        // alert($(this).val());
        $('#txt_value').val($(this).val());
        $('#campo_principale1').val('');

        if($('#txt_totale').val()> 0)
            $('#txt_totale').val(0);


        //seteo los valores segun rango seleccionado

        if($(this).val()==1){
            $("#list_junior input[type=checkbox]").each(function(){

                $(this).val(1200);

            });
            $("#list_senior input[type=checkbox]").each(function(){

                $(this).val(1600);

            });

                $('#intermediario').val(1760);
                $('#coordinatore').val(2800);
                $('#servizio').val(3600);



        }else{
            if($(this).val()==2){
                $("#list_junior input[type=checkbox]").each(function(){

                    $(this).val(1500);

                });
                $("#list_senior input[type=checkbox]").each(function(){

                    $(this).val(2000);

                });
                $('#intermediario').val(2200);
                $('#coordinatore').val(3500);
                $('#servizio').val(4500);

            }else{
                if($(this).val()==3){
                    $("#list_junior input[type=checkbox]").each(function(){

                        $(this).val(2250);

                    });
                    $("#list_senior input[type=checkbox]").each(function(){

                        $(this).val(3000);

                    });

                    $('#intermediario').val(3300);
                    $('#coordinatore').val(5250);
                    $('#servizio').val(6750);


                }else{
                    if($(this).val()==4){
                        $("#list_junior input[type=checkbox]").each(function(){

                            $(this).val(3000);

                        });
                        $("#list_senior input[type=checkbox]").each(function(){

                            $(this).val(4000);

                        });
                        $('#intermediario').val(4400);
                        $('#coordinatore').val(7000);
                        $('#servizio').val(9000);

                    }else{
                        if($(this).val()==5){

                            $('#txt-check').val(1);

                            alert('In base alla selezione effettuata, completare tutti i campi e inviare la richiesta. A breve riceverà una email con un preventivo ad hoc.');
                           /* $("#list_junior input[type=checkbox]").each(function(){

                                $(this).val(3500);

                            });
                            $("#list_senior input[type=checkbox]").each(function(){

                                $(this).val(4000);

                            });*/


                        }
                    }
                }
            }

        }

    })
	
	//validate email
	$('#email').change(function(){
      /*  // Expresion regular para validar el correo
        var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

        // Se utiliza la funcion test() nativa de JavaScript
        if (!regex.test($('#email').val().trim())) {
            alert("L'indirizzo email non è valido");
        } else{
			return;		
		}*/

    });


});

function validateField(){
    var txt_value = $('#txt_value').val();
    var dato = $('#campo_principale1').val();
    //alert(d);
    var value = dato.split(",")[0]+''+dato.split(",")[1];

    //alert(parseFloat(value));

   if(txt_value == 1){
        if((parseFloat(value) > 0) && (parseFloat(value) <= 100000 ))
            //alert(1);
            flag = true;
    }else{
        if(txt_value == 2){
            if(parseFloat(value) >= 100000 && parseFloat(value) <= 200000)
                flag = true;
        }else{
            if(txt_value == 3){
                if(parseFloat(value) > 200000 && parseFloat(value) <= 500000)
                    flag = true;
            }else{
                if(txt_value == 4){
                    if(parseFloat(value) > 500000 && parseFloat(value) <= 1000000)
                        flag = true;
                }else{
                    if(txt_value == 4){
                        if(parseFloat(value) > 1000000)
                            flag = true;
                    }
                }
            }
        }
    }
    return flag;

}

function validateExp(field){
    var regexp =  /^\d+([,]?\d{3})?([.]\d{2})$/ ;

    if(regexp.test(field.val())==true){
       // alert('OKKKK')
       return true;
    }else{
        return false;
        //alert('No OKKKK');
    }
}

function change_cantidad(){
    //alert(validateField($('#txt_value').val()));

    var field = $('#campo_principale1');
    if(validateExp($('#campo_principale1'))==true){
        if(!validateField()){
            alert('Valore fuori rango');
            $('#campo_principale1').val('');
            //$('#txt_totale').val($('#campo_principale1').val());
        }/*else{
         alert('Valore fuori rango');
         }*/

    }else{
        alert('Valore incorrecto!!!');
    }
}

function comprobarCheck(){
    if(check_j == true && check_s == true)
        alert('Opzione selezionata Junior e Senior contemporaneamente');
}



