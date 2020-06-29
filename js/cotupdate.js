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
  if ( datacontrol == '1') {
    $("#MyModal").modal("show");
  }

  $("#btnClosePopup").click(function () {
    $("#MyModal").modal("hide");
    if (dataaction == "cotizacion/update/empresa") {
      $("#MyModal").bind("click", function(){window.location.href = "../cotizacion/read_ud.php?accion=empresa";});
    }"cotizacion/update/particular"
    if (dataaction == "cotizacion/update/particular") {
      $("#MyModal").bind("click", function(){window.location.href = "../cotizacion/read_ud.php?accion=particular";});
    }
    $("#MyModal").trigger('click');
  });

// build CLIENTES table for popup
  var rut_cliente = null;
  $("#clientes").on("click", "tr", function() {
    $("#nomcliente").html("");
    $("#rutcliente").html("");
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
    var contacto = data.slice(pos5+1);
    $("#nomcliente").html(nombre);
    $("#rutcliente").html(rut);
  });

//  Search CLIENTES
  $("#clientesSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listaClientes tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

//// build PARTES table for popup
  $("#calcular").show();
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
      if ($("#cliente").html() == "empresa") {
        if (index == 7) {
          descuento_cliente = ($("#descuento").html()!='') ? $("#descuento").html() : 0 ;
          datafin[index] = "<td id='des"+rowCount+"' class='text-center'>"+descuento_cliente+"</td>";
        } 
        if (index == 8)  {
          datafin[index] = "<td id='vcd"+rowCount+"' class='text-right'>"+datasplit[index];
        }
      }
      if ($("#cliente").html() == "particular") {
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
        datafin[index] = "<td id='del"+rowCount+"' class='text-center'><i id='deli"+rowCount+"' class='fas fa-trash-alt'></i></td>";
      }
    }
    datafin = "<tr id='row"+rowCount+"' >"+datafin+"</tr>";
    $('#cotizacionTabla').append(datafin);

  });

//  Search PARTES
  $("#partesSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listaPartes tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

// Actions for click on button CALCULAR
  $("#calcular").on("click", function() {
    var num = 0;
    var total = 0;
    var neto = 0;
    var iva = 0;
    var total_ac = 0;
    var total_foot = 0;
    var ident = null;

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

      if ($("#cliente").html() == "empresa") {
        var indice = "des"+num;
        $(this).find("td[id*='des']").each(function(indice) {        
          textVal = $(this).html();
          descuento = textVal;
        });
        var desc_val = ($("#descuento").val() == '') ? 0 : $("#descuento").val();
        $("#des"+num).attr('class','text-center');
        $("#des"+num).html(desc_val);

        var indice = "vcd"+num;
        valorcondesc = parseFloat(valor) * (1 - desc_val/100);
        $('#vcd'+num).html(valorcondesc.toFixed(2));

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

    data = "<tr id='rneto'><td colspan='8'></td><td class='font-weight-bold'>Total Neto</td><td id='neto' class='text-right font-weight-bold'>"+neto.toFixed(2)+"</td><td></td></tr>"+
          "<tr id='riva'><td colspan='8'>Entrega en su bodega 24/48 horas una vez recepcionada la O.C:</td><td class='font-weight-bold'>IVA 19%</td><td id='iva' class='text-right font-weight-bold'>"+iva.toFixed(2)+"</td><td></td></tr>"+
          "<tr id='rtotal'><td colspan='8'></td><td class='font-weight-bold'>Total</td><td id='total' class='text-right font-weight-bold'>"+total_ac.toFixed(2)+"</td><td></td></tr>";
      }

      if ($("#cliente").html() == "particular") {

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

    if (total_foot == 0) {
      $('#cotizacionTabla tfoot').append(data);
    } else {
      $("#cotizacionTabla tfoot tr").remove();
      $("#cotizacionTabla tfoot").append(data);
    }

    if ($("#neto").text() =="NaN") {
      $("#MyModalText").html("Uno o varios items no tienen la cantidad con valor numerico ...");
      $("#MyModal").modal("show");
    } else {
      $("#finalizar").show();  
    }

  });

// Actions for click on DELETE trash image
  $("#cotizacionTabla").on("click", "i", function(event) {
    $(this).closest('tr').remove();
    neto = 0;
    $("#rneto").remove("#rneto");
    $("#riva").remove("#riva");
    $("#rtotal").remove("#rtotal");
    $("#finalizar").hide();  
    $("#cotizacionTabla > tbody > tr").each(function( index ) {
      $(this).attr("id","row"+index);
      $(this).find("input[id*='inp']").attr("id","inp"+index);
      $(this).find("td[id*='val']").attr("id","val"+index);
      $(this).find("td[id*='des']").attr("id","des"+index);
      $(this).find("td[id*='vcd']").attr("id","vcd"+index);
      $(this).find("td[id*='tot']").attr("id","tot"+index);
    });
  });

// Actions for click on button FINALIZAR
  $("#finalizar").on("click", function() {

    if ($("#nomcliente").html() == "" && $("#nomcliente").val() == "") {
      $("#MyModalText").html("Cotizacion no tiene Cliente. Seleccionar un cliente...");
      $("#MyModal").modal("show");
    } else {

      rowsExists = $("#cotizacionTabla > tbody > tr").length;
      if (rowsExists == 0) {
        $("#MyModalText").html("Cotizacion no tiene Items. Agregar al menos un item a la Cotizacion...");
        $("#MyModal").modal("show");
      } else {

        rnetoExists = $('#rneto').length;
        if (rnetoExists == 0) {
          $("#MyModalText").html("Calcular Valor Neto, IVA y Total. Calcular Total...");
          $("#MyModal").modal("show");
        } else {

          if ($('#neto').text() == "NaN") {
            $("#MyModalText").html("Uno o varios items no tienen la cantidad con valor numerico ...");
            $("#MyModal").modal("show");
          } else {

//  Update COTIZACION in database
            var rut = $("#rutcliente").html();
            var num = $("#num_cotizacion").text();
//            var fechacot = $("#fechacotizacion").text();
//            var data_fecha = fechacot.split("/");
//            var fecha_bd = data_fecha[2]+'-'+data_fecha[1]+'-'+data_fecha[0];
            var fechacot = $("#fechacotizacion").html().split("/");
            fechacot = fechacot[2]+"-"+fechacot[1]+"-"+fechacot[0];
            var userid = $("#userid").text();
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
              sleep(250);
              if ($("#cliente").html() == "empresa") {
//console.dir("empresa : "+$("#cliente").html());
console.dir("Escribir BD..."+index+" / "+userid+" / "+rut+" / "+num+" / "+fechacot+" / "+cod+" / "+cant+" / "+movil+" / "+solcompra+" / "+desc);
                var rut = $("#rutcliente").html();
                $.ajax({
                    url: "update_post.php",
                    data: {
                            index:index,
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

              if ($("#cliente").html() == "particular") {
//                console.dir("particular");
                var rut = $("#rutcliente").val();
                var nombre = $("#nomcliente").val();
                var correo = $("#correo").val();
                $.ajax({
                    url: "update_post_part.php",
                    data: {
                            index:index,
                            userid:userid,
                            rut:rut,
                            nombre:nombre,
                            correo:correo,
                            num_cotizacion:num,
                            fecha_cotizacion:fechacot,
                            cod_parte:cod,
                            cantidad:cant
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