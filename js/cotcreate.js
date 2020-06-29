$(document).ready(function(){

  $(window).bind('scroll', function() {
      var navHeight = $("#box1").height();
      ($(window).scrollTop() > navHeight) ? $('nav').addClass('goToTop') : $('nav').removeClass('goToTop');
  });

  var wWidth = $(window).width();
  if(wWidth > 768) {
    $("ul.bts-nav li").hover(function(){
       $(this).children("ul").slideToggle(400);
    });
  }
  else {
    $("ul.bts-nav li a").click(function(){
       $(this).next("ul").slideToggle(400);
    });
  }

  var datacontrol = $("#MyModal").attr("data-control");
  var datacliente = $("#MyModal").attr("data-cliente");
  var dataaction = $("#MyModal").attr("data-action");

  $("#btnClosePopup").click(function () {
    $("#MyModal").modal("hide");
    if (dataaction == "/cotizacion/read") {
      $("#MyModal").bind("click", function(){window.location.href = "../cotizacion/read.php";});
    }
    if (dataaction == "remain") {
      $("#MyModal").modal("hide");
    }
//    $("#MyModal").trigger('click');
  });

// build CLIENTES table for popup
  $("#clientes").on("click", "tr", function() {
    $("#nomcliente").html("");
    $("#rutcliente").html("");
    $("#descuento").html("");
    var data = $(this).html();
    data = data.replace(/<\/td>/g, "");
    var pos1 = data.indexOf(">");
    var pos2 = data.indexOf("<",pos1+1);
    var nombre = data.slice(pos1+1, pos2);
    var pos3 = data.indexOf(">",pos2);
    var pos4 = data.indexOf("<",pos3+1);
    var rut = data.slice(pos3+1, pos4);
    var pos5 = data.indexOf(">",pos4);
    var pos6 = data.indexOf("<",pos5+1);
    var contacto = data.slice(pos5+1,pos6);
    var pos7 = data.indexOf(">",pos6);
    var pos8 = data.indexOf("<",pos7+1);
    var descuento = data.slice(pos7+1);
    $("#nomcliente").html(nombre);
    $("#rutcliente").html(rut);
    $("#descuento").html(descuento);
  });
// END of CLIENTES table for popup

//  Search CLIENTES
  $("#clientesSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listaClientes tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
// END of Search CLIENTES

// build PARTES table for popup
  $("#calcular").hide();
  $("#finalizar").hide();
  $("#partes").on("click", "tr", function() {
    $("#calcular").show();
    var datafin = new Array();
    var data = $(this).html();
    var datasplit = data.split("<td>");
    datasplit = datasplit.slice(1);
    var rowCount = $('#cotizacionTabla tbody tr').length;
    for (let index = 0; index < datasplit.length; index++) {
      datafin[index] = "<td>"+datasplit[index];
      if (index == 5) {
        datafin[index] = "<td><input id='inp"+rowCount+"' class='text-right' type='text' name='cantidad' size='3' value=''>"+datasplit[index];
      }
      if (index == 6) {
        datafin[index] = "<td id='val"+rowCount+"' class='text-right'>"+datasplit[index];
      }
      if ($('label[for="empresa"]').hasClass("active")) {
        if (index == 7) {
          descuento_cliente = ($("#descuento").html()!='') ? $("#descuento").html() : 0 ;
          datafin[index] = "<td id='des"+rowCount+"' class='text-center'>"+descuento_cliente+"</td>";
        } 
        if (index == 8)  {
          datafin[index] = "<td id='vcd"+rowCount+"' class='text-right'>"+datasplit[index];
        }
      }
      if ($('label[for="particular"]').hasClass("active")) {
        if (index == 7) {
          datafin[index] = "";
        } 
        if (index == 8)  {
          datafin[index] = "";
        }
      }
      if (index == 9) {
        datafin[index] = "<td id='tot"+rowCount+"' class='text-right'>"+datasplit[index];
      }
      if (index == 10) {
        datafin[index] = "<td id='del"+rowCount+"'><i id='deli"+rowCount+"' class='fas fa-trash-alt'></i></td>";
      }
    }
    datafin = "<tr id='row"+rowCount+"' >"+datafin+"</tr>";
    $('#cotizacionTabla').append(datafin);

  });
// END of build PARTES table for popup

//  Search PARTES
  $("#partesSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listaPartes tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
// END of Search PARTES

// Actions for click on button CALCULAR
  $("#calcular").on("click", function() {
    var num = 0;
    var total = 0;
    var neto = 0;
    var iva = 0;
    var total_ac = 0;
    var total_foot = 0;
    var ident = null;
    var no_fin = false;

    $("#cotizacionTabla > tbody > tr").each(function( index ) {
      var cantidad = 0;
      var valor = 0;

      ident = $(this).attr( "id" );
      if (ident != null) {
        var idnew = 'row'+num;
        $(this).attr('id', idnew);
      }

      $(this).find("td input:text").each(function() {
        textVal = $(this).val();
        cantidad = textVal;
      });

      var indice = "val"+num;
      $(this).find("td[id*='val']").each(function() {        
        textVal = $(this).html();
        valor = textVal;
      });

      if ($('label[for="empresa"]').hasClass("active")) {

        var desc_val = ($("#descuento").val() == '') ? 0 : $("#descuento").val();
        $("#des"+num).attr('class', 'text-center');
        $("#des"+num).html(desc_val);
        valord = $("#descuento").html();

        var indice = "vcd"+num;
        valorcondesc = valor * (1 - desc_val/100);
        $('#'+indice).html(valorcondesc.toFixed(2));

        indice = "tot"+num;
        $(this).find("td[id*='tot']").each(function() {
          total = (cantidad=='') ? 0 : (parseFloat(cantidad) * parseFloat(valorcondesc)).toFixed(2);
          $(this).html(total);
        });
        if (total == 0) {no_fin = true}
        neto = neto + parseFloat(total);
        iva = neto * 0.19;
        total_ac = neto + iva;
        ++num;

        data = "<tr id='rneto'><td colspan='8'></td><td>Total Neto</td><td id='neto' class='text-right'>"+neto.toFixed(2)+"</td><td></td></tr>"+
              "<tr id='riva'><td colspan='8'>Entrega en su bodega 24/48 horas una vez recepcionada la O.C:</td><td>IVA 19%</td><td id='iva' class='text-right'>"+iva.toFixed(2)+"</td><td></td></tr>"+
              "<tr id='rtotal'><td colspan='8'></td><td>Total</td><td id='total' class='text-right'>"+total_ac.toFixed(2)+"</td><td></td></tr>";
      }

      if ($('label[for="particular"]').hasClass("active")) {

        indice = "tot"+num;
        $(this).find("td[id*='tot']").each(function() {
          total = (cantidad=='') ? 0 : (parseFloat(cantidad) * parseFloat(valor)).toFixed(2);
          $(this).html(total);
        });
        if (total == 0) {no_fin = true}
        neto = neto + parseFloat(total);
        iva = neto * 0.19;
        total_ac = neto + iva;
        ++num;

        data = "<tr id='rneto'><td colspan='6'></td><td>Total Neto</td><td id='neto' class='text-right'>"+neto.toFixed(2)+"</td><td></td></tr>"+
              "<tr id='riva'><td colspan='6'>Entrega en su bodega 24/48 horas una vez recepcionada la O.C:</td><td>IVA 19%</td><td id='iva' class='text-right'>"+iva.toFixed(2)+"</td><td></td></tr>"+
              "<tr id='rtotal'><td colspan='6'></td><td>Total</td><td id='total' class='text-right'>"+total_ac.toFixed(2)+"</td><td></td></tr>";
      }

    });

    total_foot = $('#cotizacionTabla >tfoot >tr').length;

    if (isNaN(neto)) {
      $("#MyModalText").html("Uno o varios items no tienen la cantidad con valor numerico ...");
      $("#MyModal").modal("show");
    } else {

      if (total_foot == 0) {
        $('#cotizacionTabla tfoot').append(data);
      } else {
        $("#cotizacionTabla tfoot tr").remove();
        $("#cotizacionTabla tfoot").append(data);
      }
      if ($("#nomcliente").html() == "" && $("#nomcliente").val() == "") {
        $("#MyModal").attr("data-control","remain");
        $("#MyModalText").html("Cotizacion no tiene Cliente. Seleccionar un Cliente...");
        $("#MyModal").modal("show");
      } else {
        if (no_fin) {
          $("#MyModalText").html("Uno o varios items no tienen la cantidad con valor numerico ...");
          $("#MyModal").modal("show");
        } else {
          $("#finalizar").show();          
        }
      }
    }

  });
// END of Actions for click on button CALCULAR

// Actions for click on DELETE trash image
  $("#cotizacionTabla").on("click", "i", function(event) {

    $(this).closest('tr').remove();
    neto = 0;
    $("#rneto").remove("#rneto");
    $("#riva").remove("#riva");
    $("#rtotal").remove("#rtotal");
    $("#cotizacionTabla > tbody > tr").each(function( index ) {
      $(this).attr("id","row"+index);
      $(this).find("input[id*='inp']").attr("id","inp"+index);
      $(this).find("td[id*='val']").attr("id","val"+index);
      $(this).find("td[id*='des']").attr("id","des"+index);
      $(this).find("td[id*='vcd']").attr("id","vcd"+index);
      $(this).find("td[id*='tot']").attr("id","tot"+index);
    });
  });

// Actions for click on button FINALIZAR FINALIZAR FINALIZAR FINALIZAR FINALIZAR FINALIZAR FINALIZAR FINALIZAR
  $("#finalizar").on("click", function() {

    if ($("#nomcliente").html() == "" && $("#nomcliente").val() == "") {
      $("#MyModal").attr("data-control","remain");
      $("#MyModalText").html("Cotizacion no tiene Cliente. Seleccionar un Cliente...");
      $("#MyModal").modal("show");
    } else {

      itemNotExists = $('#cotizacionTabla >tbody >tr').length;
      if (itemNotExists < 1) {
        $("#MyModalText").html("Cotizacion no tiene Items. Agregar al menos un item a la Cotizacion...");
        $("#MyModal").modal("show");
      } else {

        rnetoNotExists = $('#rneto').length;
        if (rnetoNotExists == 0) {
          $("#MyModalText").html("Calcular Valor Neto, IVA y Total. Calcular Total...");
          $("#MyModal").modal("show");
        } else {

          if ($('#neto').text() == "NaN") {
            $("#MyModalText").html("Uno o varios items no tienen la cantidad con valor numerico ...");
            $("#MyModal").modal("show");
          } else {
            var cotizacionTabla = $("#cotizacionTabla").html();
            
//  Insert COTIZACION in database
            var fecha = $("#fechacotizacion").html();
            var userid = $("#userid").text();
            fecha = fecha.split("/");
            var num = $("#num_cotizacion").text();
            var desc = $("#descuento").html();
            var fechacot = $("#fechacotizacion").html().split("/");
            fechacot = fechacot[2]+"-"+fechacot[1]+"-"+fechacot[0];
            var movil = $("#movil").val();
            var solcompra = $("#solcompra").val();
            var cod = null;
            var cant = null;
            var estado = null;
            $("#cotizacionTabla > tbody > tr[id*='row']").each(function( index ) {
              $("#calcular").hide();
              $("#finalizar").hide();
              $("#botonClientes").hide();
              $("#botonPartes").hide();
              $(".texto").hide();
              $('label[for="empresa"]').hide();
              $('label[for="particular"]').hide();
              $("#cotizacionTabla > tbody > tr[id*='row'] > td[id*='del']").each(function( index ) {
                $(this).hide();
              });
              $(this).find("td").each(function(index) {
                if (index == 3) {
                  cod = $(this).html();
                }
                if (index == 5) {
                  cant = $(this).find("input").val();        
                }
                if (index == 7) {
                  desc = $(this).html();        
                }
              });
              $("#MyModalFooter").addClass("visible");
              sleep(100);
              if ($('label[for="empresa"]').hasClass("active")) {
                var rut = $("#rutcliente").html();
                $.ajax({
                    url: "insert_post.php",
                    data: {
                            userid:userid,
                            rut_cliente:rut,
                            num_cotizacion:num,
                            fecha_cotizacion:fechacot,
                            cod_parte:cod,
                            cantidad:cant,
                            movil:movil,
                            solcompra:solcompra,
                            desc:desc
                    },
                    type: "POST"
                })
                .done(function() {
                  $("#MyModalFooter").attr("class","modal-footer mostrar");
                  $("#MyModalText").html("Cotizacion Nº "+num+" registrada en base de datos ... !");
  //                $("#MyModalText").html(rut+"/"+num+"/"+fechacot+"/"+cod+"/"+cant);
                  $("#MyModal").modal("show");
                })
                .fail(function( xhr, status, errorThrown ) {
                  $("#MyModalText").html("Error: Ocurrió un error al insertar en la base de datos" );
                  $("#MyModal").modal("show");
                  console.log( "Error: " + errorThrown );
                  console.log( "Status: " + status );
                  console.dir( xhr );
                })
              }

              if ($('label[for="particular"]').hasClass("active")) {
                var rut = $("#rutcliente").val();
                var nombre = $("#nomcliente").val();
                var correo = $("#correo").val();
                $.ajax({
                    url: "insert_post_part.php",
                    data: {
                            userid:userid,
                            rut:rut,
                            nombre:nombre,
                            correo:correo,
                            num_cotizacion:num,
                            fecha_cotizacion:fechacot,
                            cod_parte:cod,
                            cantidad:cant,
                            desc:desc
                    },
                    type: "POST"
                })
                .done(function() {
                  $("#MyModalFooter").attr("class","modal-footer mostrar");
                  $("#MyModalText").html("Cotizacion Nº "+num+" registrada en base de datos ... !");
//                  $("#MyModalText").html(" rut "+rut+"/"+" num "+num+"/"+" nombre "+nombre+"/"+" correo "+correo+"/"+" fecha "+fechacot+"/"+" cod "+cod+"/"+" cant "+cant);
                  $("#MyModal").modal("show");
                })
                .fail(function( xhr, status, errorThrown ) {
                  $("#MyModalText").html("ERROR: Ocurrió un error al insertar en la base de datos" );
                  $("#MyModal").modal("show");
                  console.log( "Error: " + errorThrown );
                  console.log( "Status: " + status );
                  console.dir( xhr );
                })
              }
    // End of POST
            });

            $("#MyModalText").html(estado);
            $("#MyModal").modal("show");

          }
        }
      }
    }

  });
// END ofActions for click on button FINALIZAR

// Label PARTICULAR removes elements on click
  count_click = 0;
  divdesc = '<div id="divdesc" class="col-lg-1 d-flex flex-column text-right mt-2 px-2"><label for="descuento" class="control-label">Descuento</label><div class="input-group input-group-sm"><input id="descuento" class="form-control form-control-sm text-right w-25" type="text" name="descuent" value="" min="0" max="60" maxlength="2" onkeypress="return isNumeric(event)" /><div class="input-group-append"><span class="input-group-text">%</span></div></div></div>';
  divnombreinp = '<div id="divnombreinp" class="col-lg-4 d-flex flex-column ml-0 mt-2 pr-2"><label for="nomcliente" class="control-label">Nombre</label><input id="nomcliente" class="form-control form-control-sm" type="text" name="nomcliente" value="" /></div>';
  divrutinp = '<div id="divrutinp" class="col-lg-1 d-flex flex-column ml-0 mt-2 pr-2 pl-0"><label for="rutcliente" class="control-label">RUT</label><input id="rutcliente" class="form-control form-control-sm" type="text" name="rutcliente" value="" maxlength="10" /></div>';
  divcorreoinp = '<div id="divcorreoinp" class="col-lg-3 d-flex flex-column ml-0 mt-2 pl-0 pr-2"><label for="correo" class="control-label">Correo</label><input id="correo" class="form-control form-control-sm" type="text" name="correo" value=""  /></div>';
  divnombre = '<div id="divnombre" class="col-lg-4 d-flex flex-column ml-0 mt-2 pr-2"><label for="nomcliente" class="control-label">Nombre</label><div id="nomcliente" class="form-control form-control-sm"></div></div>';
  divrut = '<div id="divrut" class="col-lg-1 d-flex flex-column px-0 mt-2 mr-0"><label for="rutcliente" class="control-label">RUT</label><div id="rutcliente" class="form-control form-control-sm"></div></div>';
  divclientes = '<div id="divClientes" class="d-flex flex-column col-lg-2 align-items-start justify-content-end"><button id="botonClientes" type="submit" class="btn btn-dark btn-sm shadow sm" onclick="openClientes()">Seleccionar Cliente</button></div>';
  divmovil = '<div id="divmovil" class="col-lg-3 d-flex flex-row ml-0 pr-2"><label for="movil" class="control-label pr-3">Movil</label><input id="movil" class="form-control form-control-sm" type="text" name="movil" value="" onkeyup="this.value = this.value.toUpperCase();" /></div>';
  divsolcompra = '<div id="divsolcompra" class="col-lg-4 d-flex flex-row px-0"><label for="solcompra" class="col-lg-4 control-label pr-0 mr-2">Solicitud Compra</label><input id="solcompra" class="form-control form-control-sm pl-0 ml-0" type="text" name="solcompra" value="" onkeyup="this.value = this.value.toUpperCase();" /></div>';
  divespacio = '<div id="divespacio" class="d-flex flex-column col-lg-7"></div>';
  thdesc ='<th scope="col" class="align-middle text-center">Desc.%</th>';
  thvaldesc = '<th scope="col" class="align-middle text-right">Valor <br>c/desc</th>';
  consec_p = $("#consec_p").html();
  consec_e = $("#consec_e").html();

// COTIZACION NUMBER for empresa                ^[0-9]{7,8}-[0-9kK]{1}$
  if ($('label[for="empresa"]').hasClass("active")) { $("#num_cotizacion").html("<h3>"+consec_e+"</h3>"); }
// COTIZACION NUMBER for PARTICULAR
  if ($('label[for="particular"]').hasClass("active")) { $("#num_cotizacion").html("<h3>"+consec_p+"</h3>"); }

  $('label[for="particular"]').click(function() {
    count_click += 1;
    $("#num_cotizacion").html("<h3>"+consec_p+"</h3>");
    if (($("#divcorreo").length == 0) && (count_click == 1)) {
      $("#fecha").before(divcorreoinp);
      $("#divcorreoinp").before(divrutinp);
      $("#divrut").before(divnombreinp);      
      $("#divdesc").remove();
      $("#divrut").remove();
      $("#divnombre").remove();
      $('#cotizacionTabla tr').find('td:eq(8),th:eq(8)').remove();
      $('#cotizacionTabla tr').find('td:eq(7),th:eq(7)').remove();
      $("#botonClientes").hide(); 
      $("#divmovil").remove();
      $("#divsolcompra").remove();
      $(divespacio).insertBefore( $("#divselitems") );
      $('#cotizacionTabla tfoot tr').each(function( index ) {
        $(this).remove();
      });
      $('#cotizacionTabla tbody tr td[id*="tot"]').each(function( index ) {
        $(this).html("");
      });
      $("#finalizar").hide();

    }
    $("#divClientes").remove();
    Inputmask({regex:"^[0-9]{7,8}-[0-9kK]{1}$", casing: 'upper' }).mask("#rutcliente");
    Inputmask({regex:"[A-Z0-9\\s]*", casing: 'upper' }).mask("#nomcliente");
    $("#correo").inputmask("email");

  });

  $('label[for="empresa"]').click(function() {
    count_click = 0;
    $("#num_cotizacion").html("<h3>"+consec_e+"</h3>");
    if ($("#divnombre").length == 0) {
// divs for top of cotizacion
      $("#divnombreinp").remove();
      $("#divrutinp").remove();
      $("#divcorreoinp").remove();     
      $("#fecha").before(divdesc);
      $("#divdesc").before(divrut);
      $("#divrut").before(divnombre);
      $("#divsolcompra").remove();
      $("#botonClientes").show();
      $(divmovil).insertBefore( $("#divselitems") );
      $(divsolcompra).insertBefore( $("#divselitems") );
      $("#divespacio").remove();      
// divs for table header
      $(thdesc).insertBefore( $('#cotizacionTabla thead tr').find('td:eq(7),th:eq(7)'));
      $(thvaldesc).insertBefore( $('#cotizacionTabla thead tr').find('td:eq(8),th:eq(8)'));
      $('#cotizacionTabla tbody tr').each(function( index ) {
        var ix = index;
        $("<td id=vcd"+ix+" class='text-right'></td>").insertBefore( $(this).find('td[id="tot'+ix+'"]'));
        $("<td id=des"+ix+" class='text-right'></td>").insertBefore( $(this).find('td[id="vcd'+ix+'"]'));
      });
      $('#cotizacionTabla tfoot tr').each(function( index ) {
        $(this).remove();
      });
    }

  });
// END of Label

  $("nav .nav-link").on("click", function(e) {
//    e.preventDefault();
//    $(this).closest('ul').find('li.active,a.active').removeClass('active');
    $(this).closest('li,.nav-item').addClass('active');
    $(this).addClass('active');
  });   

  $(".dropdown-item").on("click", function(e) {
    $(this).parent('li,.nav-item').addClass('active');
//     $('a').removeClass('active');
//     $(this).addClass('active');
  });
  
  if ( $("#rol").text()  == "Vendedor" ) {
    $("#usedel").addClass("disabled");
    $("#usereg").addClass("disabled");
    $("#repcre").addClass("disabled");
    $("#repupd").addClass("disabled");
    $("#repdel").addClass("disabled");
    $("#cotupd").addClass("disabled");
    $("#cotdel").addClass("disabled");
    $("#venupd").addClass("disabled");
    $("#vendel").addClass("disabled");
    $("#cliupd").addClass("disabled");
    $("#clidel").addClass("disabled");
  }

});  //end of ready function



function openClientes() {
  document.getElementById("FormClientes").style.display = "block";
}
function closeClientes() {
  document.getElementById("FormClientes").style.display = "none";
}
function openPartes() {
  document.getElementById("FormPartes").style.display = "block";
}
function closePartes() {
  document.getElementById("FormPartes").style.display = "none";
}
function thousands_separators(num) {
  var num_parts = num.toString().split(".");
  num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return num_parts.join(".");
}
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function isNumeric (evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode (key);
  var regex = /[0-9]|\./;
  if ( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
