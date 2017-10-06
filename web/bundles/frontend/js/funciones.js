$(function () {
    var emailreg = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
    $("#btn-calcula").click(function (){
        $(".error").remove();
        if( $("#nome").val() == "" ){
            $("#nome").focus().after("<span class='error'>Inserisci il tuo nome</span>");
            return false;
        }else if( $("#email").val() == "" || !emailreg.test($("#email").val()) ){
            $("#email").focus().after("<span class='error'>Inserisci una corretta e-mail</span>");
            return false;
        }else if( $("#regione").val() == ""){
            $("#regione").focus().after("<span class='error'>Inserisci la tua regione</span>");
            return false;
        }else if( $("#iva").val() == "" ){
            $("#iva").focus().after("<span class='error'>Inserisci il tuo IVA</span>");
            return false;
        }else if( $("#committente").val() == "" ){
            $("#committente").focus().after("<span class='error'>Inserisci il tuo Committente</span>");
            return false;
        }else if( $("#oggetto").val() == "" ){
            $("#oggetto").focus().after("<span class='error'>Inserisci il tuo Oggetto</span>");
            return false;
        }else if( $("#importo").val() == "" ){
            $("#importo").focus().after("<span class='error'>Inserisci il tuo Importo</span>");
            return false;
        }else if( $("#campo_principale1").val() == "" ) {
            $("#campo_principale1").focus().after("<span class='error2'>Inserisci  l'importo esatto del corrispettivo</span>");
            return false;
        }
    });
    $("#nome, #regione, #iva,#committente,#oggetto,#importo").keyup(function(){
        if( $(this).val() != "" ){
            $(".error").fadeOut();
            return false;
        }
    });
    $("#campo_principale1").keyup(function(){
        if( $(this).val() != "" ){
            $(".error2").fadeOut();
            return false;
        }
    });
    $("#email").keyup(function(){
        if( $(this).val() != "" && emailreg.test($(this).val())){
            $(".error").fadeOut();
            return false;
        }
    });
});