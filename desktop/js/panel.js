
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
 
var temp_new_op = 0;

var new_load = 0;


var lastOpDisplayed;


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    
	if (is_numeric(getUrlVars('id'))) {
        /* lecture de l'id de banque dans la barre d'adresse pour aller directement sur un compte si demandé */
		if ($('.itemAccount[data-eqLogic_id=' + getUrlVars('id') + ']').length != 0) {
			
            $('.itemAccount[data-eqLogic_id=' + getUrlVars('id') + ']').click();
            
		} else {
			if ($('.eqLogicThumbnailDisplay').html() == undefined) {
				$('.itemAccount:first').click();
			}
		}
	} 
	
});


if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch){
    $('#sd_ComptesList').hide();
    $('#comptes_msg_debut').removeClass('col-lg-10').addClass('col-lg-12');
    $('#comptes_operations').removeClass('col-lg-7').addClass('col-lg-9');
	$('#comptes_categories').removeClass('col-lg-10').addClass('col-lg-12');
    $('#bt_displayComptes').on('mouseenter',function(){
       var timer = setTimeout(function(){
        $('#bt_displayComptes').find('i').hide();
        $('#comptes_msg_debut').addClass('col-lg-10').removeClass('col-lg-12');
        $('#comptes_operations').addClass('col-lg-7').removeClass('col-lg-9');
        $('#comptes_categories').addClass('col-lg-10').removeClass('col-lg-12');
		$('#sd_ComptesList').show();
        $('.opContainer').packery();
    }, 100);
       $(this).data('timerMouseleave', timer)
   }).on("mouseleave", function(){
      clearTimeout($(this).data('timerMouseleave'));
  });

   $('#sd_ComptesList').on('mouseleave',function(){
    var timer = setTimeout(function(){
       $('#sd_ComptesList').hide();
       $('#bt_displayComptes').find('i').show();
       $('#comptes_msg_debut').removeClass('col-lg-10').addClass('col-lg-12');
       $('#comptes_operations').removeClass('col-lg-7').addClass('col-lg-9');
	   $('#comptes_categories').removeClass('col-lg-10').addClass('col-lg-12');
       $('.opContainer').packery();
   }, 1000);
    $(this).data('timerMouseleave', timer);
}).on("mouseenter", function(){
  clearTimeout($(this).data('timerMouseleave'));
});
}

/* Fonctions du menu à gauche */ 
//Bouton de test : désactivé sauf en cas d'essais
$('#bt_testCron').on('click', function () {
	$('#md_modalComptes3').dialog({
		autoOpen: false,
		modal: true,
		height: 800,
		width: 1000, 
		title: "{{Gestion des comptes}}"
	});

	$('#md_modalComptes3').load('index.php?v=d&m=comptes&p=comptes');
	$("#md_modalComptes3").dialog('option', 'buttons', {
		"{{Fermer}}": function() {
			$(this).dialog("close");
		}
	});
	$('#md_modalComptes3').dialog('open');
	/*
	$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'testCRON',
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});	
	*/
 });
 
//Bouton de mise à jour des graphes d'historique
$('.bt_RefreshGraph').on('click', function () {
	$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'refreshGraphs',
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});	
	
 });

 //Bouton de gestion des opérations automatiques
 $('.bt_ManageVirementsAuto').on('click', function () {

	$('#md_modalComptes3').dialog({
		autoOpen: false,
		modal: true,
		height: 800,
		width: 1000, 
		title: "{{Gestion des virements automatiques}}"
	});
	var vir_id = $(this).attr('data-default-id');
	$('#md_modalComptes3').load('index.php?v=d&plugin=comptes&modal=GestionVirementsAuto&vir_id='+vir_id);
	$("#md_modalComptes3").dialog('option', 'buttons', {
		"{{Fermer}}": function() {
			$(this).dialog("close");
		}
	});
	$('#md_modalComptes3').dialog('open');
	
});

//Bouton de gestion d'un virement d'un compte à un autre
$('.bt_VirBank').on('click', function () {

	$('#md_modalComptes4').dialog({
		autoOpen: false,
		modal: true,
		height: 650,
		width: 550, 
		title: "{{Transfert de compte à compte}}"
	});
	$('#md_modalComptes4').load('index.php?v=d&plugin=comptes&modal=GestionTransfertEntreComptes');
	$("#md_modalComptes4").dialog('option', 'buttons', {
		"{{Fermer}}": function() {
			$(this).dialog("close");
		}
	});
	$('#md_modalComptes4').dialog('open');
	
});

//Bouton pour aller gérer la liste des catégories
$('.bt_ManageCat').on('click', function () {

	$('#comptes_msg_debut').hide();
	$('#comptes_operations').hide();
	$('#comptes_stats').hide();
	$('#comptes_categories').show();
	$('#ul_infos_comptes').hide();
	$('.itemAccount').removeClass('active');
	$('.btn-group').hide();

});

$('.active_account').on('click', function (event) {

	$('.itemAccount[data-eqLogic_id=' + $(this).attr('data-eqLogic_id') + ']').click();
});

/*Gestion des opérations*/

//modification de la catégorie d'une opération
$(".li_cat").on('click', function (event) {
	 
	//id de l'op en cours de modification: 
	var opid = $('#comptes_operations').attr('data-opidforcatsel');
    
	$('.opAttr[data-l1key=CatId][data-op_id='+opid+']').value($(this).attr('data-cat_id'));
	$('.opAffAttr[data-l1key=CatId][data-op_id='+opid+']').text($(this).attr('data-cat_name_upper')+$(this).attr('data-cat_name'));
	
	if (opid == 0) {
		$('.image_cat_new[data-op_id='+opid+']').css('background-color',$(this).attr('data-cat_imgBackgroundcolor'));
		$('.image_cat_new[data-op_id='+opid+']').css('color',$(this).attr('data-cat_imgColor'));
		$('.image_cat_new[data-op_id='+opid+']').html($(this).attr('data-cat_imgIcon'));
	}else {
		
		$('.image_cat_edit[data-op_id='+opid+']').css('background-color',$(this).attr('data-cat_imgBackgroundcolor'));
		$('.image_cat_edit[data-op_id='+opid+']').css('color',$(this).attr('data-cat_imgColor'));
		$('.image_cat_edit[data-op_id='+opid+']').html($(this).attr('data-cat_imgIcon'));
	}
	
	
	//Suppression du modal
	$('#fadeCat , .CptNewOpModalCat').fadeOut(function() {
		$('#fadeCat').remove(); 
	});
	
	
});

//Filtrer les opérations action
$(".filter_cat").on('click', function (event) {
	
    //Action 
    $.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'getBankOperations_filter',
				type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
				id: $('#comptes_operations').attr('data-eqLogic_id'),
                catid: $(this).attr('data-cat_id')
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_eventOpAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				//alert("reussite");
				//alert(data.result.eqLogic_id);
                
                $('#comptes_operations').attr('data-mode', 1); //mode filtre
				$('#comptes_operations').attr('data-filterCatId', data.result.filterCatId); 
                
                //effacement des opérations déjà présentes: 
                $('.DivOp').remove();
                $('.OpEdit').remove();
                $('DivOpUpper').remove();
                
                //alert(data.result.op);
				
				add_new_op_in_div(data.result);
			}
	});	  
    
	
	//Suppression du modal
	$('#fadeCat , .CptFilterModalCat').fadeOut(function() {
		$('#fadeCat').remove(); 
	});
	
	
});


//Filtrer les opérations modal
$(".bt_filterCat").on('click', function (event) {
	 
    //Récupérer l'id du compte ? pas nécessaire ? 
             
	//Afficher le modal des catégories 
    $('body').append('<div id="fadeCat"></div>'); //Ajout du fond opaque noir
	//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
	$('#fadeCat').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
    
    $('.CptFilterModalCat').show();
    
    $('.filter_cat').removeClass("catFocus");
	
	
});

$('.itemAccount').on('click', function (event) {
		
	var bank_id =$(this).attr('data-eqLogic_id');
	//alert(bank_id);
	$('.opAttr.bankid').attr('value',bank_id);
	var devise = $(this).attr('data-devise');
	
	var minilogo = $(this).attr('data-minilogo');
	var obj_name = $(this).attr('data-name');
	//alert(obj_name);
	var obj_color =$(this).attr('data-color-obj');
	var obj_color_text =$(this).attr('data-color-obj-text');
    
    
	
	$('#ul_infos_comptes').show();
	//$('#bt_downloadOperations').show();
	$('#solde_reel').text($(this).attr('data-soldereel'));
	$('#a_pointer').text($(this).attr('data-apointer'));
	$('#solde_fin_mois').text($(this).attr('data-soldefinmois'));
	//alert(title);
	$('#comptes_msg_debut').hide();
	$('#comptes_operations').show();
	$('#comptes_stats').show();
	$('#comptes_categories').hide();
	if (temp_new_op == 0) {
		$('#div_eventOpAlert').hide();
	}
	else {
		temp_new_op = 0;
	}
	$('.btn-group').show();

    //Options: 
    var optionPointage = $(this).attr('data-optPointage');
    if (optionPointage == 1) {
        $('#NewOpOptionPointage').show();
        $('#FinDeMoisAffichage').show();
  
    }else {
        $('#NewOpOptionPointage').hide();
         $('#FinDeMoisAffichage').hide();
    }
    
    var optionType = $(this).attr('data-optType');
    if (optionType == 1) {
        $('#NewOpOptionType').show();
    }else {
        $('#NewOpOptionType').hide();
        
    }
    
    var optionDateUnique = $(this).attr('data-optDateUnique');
    if (optionDateUnique == 1) {
        $('#NewOpDate').hide();
        $('#NewOpDateValeurLabel1').hide();
        $('#NewOpDateValeurLabel2').show();
        
    }else {
        $('#NewOpDate').show();
        $('#NewOpDateValeurLabel1').show();
        $('#NewOpDateValeurLabel2').hide();
        
    }

	//effacement des opérations déjà présentes: 
	$('.DivOp').remove();
	$('.OpEdit').remove();
	$('DivOpUpper').remove();
	
	operations_print({
        type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
        id: $(this).attr('data-eqLogic_id'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
				
				$('#comptes_operations').attr('data-eqLogic_id',data.eqLogic_id);
				$('#comptes_operations').attr('data-devise',devise);
                
                $('#comptes_operations').attr('data-mode', 0); //Mode opération normales
                
				$('#cpt_title').text(data['name']);
				$('#cpt_obj').text(obj_name);
                
				$('.CptTitle').css('background-color',obj_color);
				$('.CptTitle').css('color',obj_color_text);
				$('#cpt_bank').attr('src','plugins/comptes/images/banques/' + minilogo );
                
                //Gestion du modal pour afficher la configuration de la banque
                $('.bt_configurationBank').attr('data-eqLogic_id',data.eqLogic_id);
                $('.bt_configurationBank').on('click',function(){
                    $('#md_modal').dialog({title: "{{Configuration du compte}}"});
                    $("#md_modal").load('index.php?v=d&m=comptes&p=comptes&ajax=1&id='+$(this).attr('data-eqLogic_id')).dialog('open');
                    
                    
                    
                });
                //FIN
				
				$('.HeaderContainer').packery();
               
                
				
				//Ajout des opérations
				//add_new_op_in_tables(data, bank_id);
				add_new_op_in_div(data);
				
				
				//alert (data.DepensesDuMois);
				//alert($('#comptes_operations').attr('data-eqLogic_id'));
				
				//Javascript pour la gestion d'une nouvelle opération: 
				$('.CptNewOp').css('background-color',obj_color);
				$('.CptNewOp').css('opacity',0.6);
				$('.CptNewOpModal').css('background-color',obj_color);
				//$('.CptNewOpModal').css('opacity',0.6);

				$('.dtimepicker').datetimepicker({lang: 'fr',
					format: 'Y-m-d',
					timepicker:false,
					step: 15
				});
				
				//$('.opContainer').packery();
				updatePieCharts('update');
				
				$('.affDevise').text(devise);
                
                setTimeout(function (){
                        $('#div_alert ').hide();
                    },
                    1500
                );
		}
    });
});

function updateOperation(_op) {
	$.ajax({
						type: 'POST',
						url: 'plugins/comptes/core/ajax/comptes.ajax.php',
						data: {
							action: 'updateOperation',
							event: json_encode(_op)
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error, $('#div_eventOpAlert'));
						},
						success: function (data) {
							if (data.state != 'ok') {
								$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
								return;
							}
							//Rafraichir ? 
						}
	});

}

function addOpToTable(_op,_cats, optPointage, optType, optionDateUnique) {
	var tr = '<tr class="op">';
    tr += '<td>';
    
    if (!isset(_op)) {
        var _op = {};
    }
	
	var devise = $('#comptes_operations').attr('data-devise');
	
	//$('#comptes_operations').attr('data-last_id',init(_op.id));
	var cat_title = "";
	var cat_id = _op.CatId;
	var cat_i;
	var cat_img = {};
	var cat_level;
	
	for (var i in _cats) {
        if (_cats[i].id == cat_id) {
			cat_title = _cats[i].name;
			cat_img.tagTextColor = _cats[i].image.tagTextColor;
			cat_img.tagColor = _cats[i].image.tagColor;
			cat_img.icon = _cats[i].image.icon;
			cat_i = i;
			cat_level = _cats[i].level;
		}
    }
	//Search upper level cat if exists: 
	var cat_upper_level_name = '';
	var stop = 0;
    
    
	if ((cat_id != '') && (cat_level > 0)) {
		for (var i=cat_i; (i >= 0) && (stop == 0); i--) {
			if (_cats[i].level == (cat_level - 1))  {
				cat_upper_level_name = _cats[i].name;
				cat_upper_level_name = cat_upper_level_name + ' -> ';
				stop = 1;
			}
		}
	}
	if (cat_title == "") {
		cat_title = "{{Aucune catégorie}}"
	}
	
	if (!isset(cat_img.tagTextColor)) {
		cat_img.tagTextColor = "#FFF";
	}	
	if (!isset(cat_img.tagColor)) {
		cat_img.tagColor = "#c266c2";
	}	
	if (!isset(cat_img.icon)) {
		cat_img.icon = "<i class='icon plugin-comptes-billets1'><\/i>";
	}	
	var div = '<div class="DivOpUpper"><div class="DivOp " data-op_id="'+_op.id+'" >';
	//div += '<div>';
	
	lastOpDisplayed = _op.id;
	
    
    
	
	
	//Date normale 
    if (optionDateUnique == 1) {
        var date = new Date(_op.CheckedOn);
    }else {
        var date = new Date(_op.OperationDate);
    }
	var month = "";
	switch (date.getMonth()) {
		case 0: month = "{{Janv.}}";
		break;
		case 1: month = "{{Fév.}}";
		break;
		case 2: month = "{{Mars}}";
		break;
		case 3: month = "{{Avril}}";
		break;
		case 4: month = "{{Mai}}";
		break;
		case 5: month = "{{Juin}}";
		break;
		case 6: month = "{{Juil.}}";
		break;
		case 7: month = "{{Août}}";
		break;
		case 8: month = "{{Sept.}}";
		break;
		case 9: month = "{{Oct.}}";
		break;
		case 10: month = "{{Nov.}}"; 
		break; 
		case 11: month = "{{Déc.}}";
		break;
		default: 
		break;
	}
	div += '<div class="op_left" ><span class="OpDate" ><center>'+ date.getDate() + '<br />' + month + '<br />' + date.getFullYear() +' </center></span></div>';
	
	//image catégorie: 
	div += '<div class="op_left">';

	
	div += '<div class="image_cat" data-op_id="'+_op.id+'" style="font-size : 2em;color:' + cat_img.tagTextColor
		+ ';border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:' + cat_img.tagColor + ';margin-top:7px;margin-left:7px;" >' + cat_img.icon + '</div>';
		
	div += '</div>';
	

	//Titre de l'opération
	div += '<div class="op_right"><span><b>' + _op.BankOperation + '</b></span>';
	
	//Catégorie de l'opération
	div += '<br /> <span style="" ><i>'+ cat_upper_level_name + cat_title + '</i></span></div>';
	
	//Montant de l'opération avec devise
	div += '<div class="op_amount" >'+ _op.Amount + ' ' + devise +'</div>';
	
	
    
    
	if (optPointage == 1) {
        var color_check="red";
	
        if (init(_op.Checked) == 1) {
            color_check="green";
        }
        if (init(_op.Checked) == 0) {
            color_check="red";
        }
        div += '<div class="op_right_icons">';
        //Opération pointée ou non pointée affichage
        
        div += '<a class="cursor" data-l1key="Checked" data-op_id="'+_op.id+'" data-op_checked="'+_op.Checked+'">';
        div += '<i class="fa fa-check" data-op_id="'+_op.id+'" style="color:'+color_check+';"></i></a> ';
        div += '</div>';
    }
    if (optType == 1) {
        var type_icone = "";
	
        if (init(_op.Type) == 1) {
            type_icone += 'fa-credit-card';
        }
        if (init(_op.Type) == 2) {
            type_icone += 'fa-money';
        }
        if (init(_op.Type) == 3) {
            type_icone += 'techno-fleches';
        }
        div += '<div class="op_right_icon_type">';
        div += '<i class="fa '+type_icone+'" style=""></i>';
        div += '</div>';
    }
	div += '</div>';
    //partie édition
	
	div += '<div class="OpEdit" data-op_id="'+_op.id+'" style="display:none">';
	div += '<form class="form-horizontal" style="margin-top:10px;">';
    if (optionDateUnique == 1) {
        div += '    <div class="form-group form-group-sm hidden">';
	}
    else {
        div += '    <div class="form-group form-group-sm">';
    }
    div += "        <label class='col-md-5 control-label'>{{Date de l'opération}}</label>";
	div += '        <div class="col-md-7">';
	div += '        	<input type="text" class="opAttr dtimepicker" data-l1key="operationDate" value="'+_op.OperationDate+'" />';
	div += '        </div>';
	div += '    </div>';
	
	div += '    <div class="form-group form-group-sm">';
    if (optionDateUnique == 1) {
        div += '        <label class="col-md-5 control-label">{{Date}}</label>';
	}
    else {
        div += '        <label class="col-md-5 control-label">{{Date de valeur}}</label>';
    }
    div += '        <div class="col-md-7">';
	div += '        	<input type="text" class="opAttr dtimepicker" data-l1key="CheckedOn" value="'+_op.CheckedOn+'" />';
	div += '        </div>';
	div += '    </div>';
	div += '    <div class="form-group form-group-sm">';
	div += '        <label class="col-md-5 control-label">{{Catégorie}}</label>';
	div += '        <div class="col-md-7" style="height:30px;font-size: 14px;">';
	div += '        	<input type="text" class="opAttr form-control" data-l1key="CatId" data-op_id="'+_op.id+'" style="display: none;" value="'+_op.CatId +'" />';	
	div += '        	<div style="float:left;margin-left:5px">';
	div += '        		<div class="image_cat_edit" data-op_id="'+_op.id+'" style="font-size : 2em;color:' + cat_img.tagTextColor
		+ ';border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:' + cat_img.tagColor + ';" >' + cat_img.icon + '</div>';
	div += '        	</div>';
	div += '        	<div class="opAffAttr" data-l1key="CatId" data-op_id="'+_op.id+'" style="">'+ cat_upper_level_name + cat_title +'</div>';
						
	div += '        </div>';
	div += '    </div>';
	div += '    <div class="form-group form-group-sm">';
	div += "        <label class='col-md-5 control-label'>{{Nom de l'opération}}</label>";
	div += '        <div class="col-md-7">';
	div += '        	<input type="text" class="opAttr " data-l1key="BankOperation" value="'+_op.BankOperation+'" />';
	div += '        </div>';
	div += '    </div>';
	
    if (optType == 1) {
        div += '    <div class="form-group form-group-sm">';
    }else {
        div += '    <div class="form-group form-group-sm hidden">';
    }
    div += '        <label class="col-md-5 control-label">{{Type}}</label>';
    div += '        <div class="col-md-7">';
    div += '        	<select class="opAttr selectpicker" data-style="btn-primary" data-l1key="Type" data-width="65%">';
    div += '				<option ';
                            if (_op.Type == 0) {
    div += '					selected';
                            }
    div += ' 					value=0 > {{Aucun}}</option>';
    div += '				<option ';
                            if (_op.Type == 1) {
    div += '					selected';
                            }
    div += ' 					value=1 data-icon="fa fa-credit-card">{{Carte}}</option>';
    div += '				<option ';
                            if (_op.Type == 2) {
    div += '					selected';
                            }
    div += ' 					value=2 data-icon="fa fa-money">{{Chèque}}</option>';
    div += '				<option '
                            if (_op.Type == 3) {
    div += '					selected';
                            }
    div += ' 					value=3 data-icon="fa techno-fleches">{{Virement}}</option>';
    div += '        	</select>';
    div += '        </div>';
    div += '    </div>';
	
    if (optPointage == 1) {
        div += '    <div class="form-group form-group-sm">';
    }else  {
        div += '    <div class="form-group form-group-sm hidden">';
    }
    div += '    	<label class="col-md-5 control-label">{{Opération pointée ou non ?}}</label>';
    div += '    	<div class="col-md-7">';
    div += '    		<input type="checkbox" class="opAttr" data-l1key="Checked" ';
                        if (_op.Checked == 1) {
    div += '            checked';
                        }
    div += '            />';
    div += '    	</div>';
    div += '     </div>';
	
    div += '     <div class="form-group form-group-sm">';
	div += '     	<label class="col-md-5 control-label">{{Montant}}</label>';
	div += '     	<div class="col-md-7">';
	div += '     		<input type="text" class="opAttr EnterAvailable" data-l1key="Amount" value="'+_op.Amount+'" /> <span class="affDevise" style="color:#000"></span>';
	div += '     	</div>';
	div += '     </div>';
	div += '     <div class="form-group form-group-sm">';
	div += '     	<div class="col-md-5 control-label">';
	div += "     		<a class='btn btn-primary update_op'><i class='fa fa-refresh' ></i> {{Mise à jour}}</a>";
	div += '     	</div>';
	div += '     	<div class="col-md-5 control-label">';
	div += "     		<a class='btn btn-danger supp_op'><i class='fa fa-trash-o' ></i> {{Suppression}}</a>";
	div += '     	</div>';	
	div += '     </div>';
	div += '     <input type="text" class="opAttr " data-l1key="hide" style="display: none;" value ="'+_op.hide+'" />';
	div += '     <input type="text" class="opAttr " data-l1key="id" style="display: none;" value="'+_op.id+'" />';
	div += '     <input type="text" class="opAttr" data-l1key="eqLogic_id" style="display: none;" value="'+_op.eqLogic_id+'" />';
	
	div += '</form>';
	div += '</div>';		
	div += '</div>';
	div += '</div>';

	tr += div;
    
    tr += '</td> </tr>';
    
    //$('.opContainer').append(div);
	$('#table_op tbody').append(tr);
	
}

function modalCat() {
	//if ($('.CptNewOpModalCat').is(':hidden')) 
		
	
	//Effet fade-in du fond opaque
	
	$('body').append('<div id="fadeCat"></div>'); //Ajout du fond opaque noir
	//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
	$('#fadeCat').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
	
	$('.CptNewOpModalCat').show();
   
 
    $('.li_cat').removeClass("catFocus");
    $('.li_cat[data-cat_id='+$('#comptes_operations').attr('data-catidforcatselfocus')+']').addClass("catFocus");
    
  
   
	//alert($('#comptes_operations').attr('data-catidforcatselfocus'));
}

function add_new_op_in_div(data) {
				//alert(data.op);
                bank_id = data.eqLogic_id;
                for (var i in data.op) {
                    //addOpToDiv(data.op[i],data.cat);
                    addOpToTable(data.op[i],data.cat, data.optPointage, data.optType, data.optDateUnique);
                }
                //Gestion des actions
				$('.DivOp').on('click', function (event) {
					
					var op_id =$(this).attr('data-op_id');
                    
                    if (data.optPointage == 1) {
                        var test = $('a[data-l1key=Checked][data-op_id='+op_id+']');
                        if(!$(event.target).is(test)&&!$.contains(test[0],event.target)) {//pour ne pas dérouler si on clic sur la validation de l'opération

                            if ($('.OpEdit[data-op_id='+op_id+']').is(':hidden')) {
                                $('.OpEdit[data-op_id='+op_id+']').show();
                                $('.OpEdit[data-op_id='+op_id+']').css('background-color','#F2F1EF');
                                $('.DivOp[data-op_id='+op_id+']').css('background-color','#F2F1EF');
                                
                            }
                            else {
                                $('.OpEdit[data-op_id='+op_id+']').hide();
                                $('.OpEdit[data-op_id='+op_id+']').css('background-color','#f8f8f8');
                                $('.DivOp[data-op_id='+op_id+']').css('background-color','#f8f8f8');
                            }
                        }
                        
                    }else {
                        if ($('.OpEdit[data-op_id='+op_id+']').is(':hidden')) {
                                $('.OpEdit[data-op_id='+op_id+']').show();
                                $('.OpEdit[data-op_id='+op_id+']').css('background-color','#F2F1EF');
                                $('.DivOp[data-op_id='+op_id+']').css('background-color','#F2F1EF');
                                
                            }
                        else {
                                $('.OpEdit[data-op_id='+op_id+']').hide();
                                $('.OpEdit[data-op_id='+op_id+']').css('background-color','#f8f8f8');
                                $('.DivOp[data-op_id='+op_id+']').css('background-color','#f8f8f8');
                        }
                    }
					
					
					
				});
				
				//Javascript associé à chaque opération  
				//pointage d'une opération
                if (data.optPointage == 1) {
                    $('a[data-l1key=Checked]').on('click', function(event) {
                        
                        //récupération des informations
                        var cid = $(this).attr('data-op_id');
                        var cchecked = $(this).attr('data-op_checked');
                        
                        //action spécifique en fonction du type de pointage
                        if (cchecked == 1) {
                            cchecked = 0;
                            $('i.fa.fa-check[data-op_id='+ cid +']').css('color', 'red');
                        }
                        else {
                            cchecked = 1;
                            $('i.fa.fa-check[data-op_id='+ cid +']').css('color', 'green');
                        }
                        $('a[data-l1key=Checked][data-op_id='+ cid +']').attr('data-op_checked',cchecked);
                        
                        //mise à jour de la bdd
                        $.ajax({
                            type: 'POST',
                            url: 'plugins/comptes/core/ajax/comptes.ajax.php',
                            data: {
                                action: 'updateCheckedStatus',
                                id: cid, 
                                checked: cchecked
                            },
                            dataType: 'json',
                            error: function (request, status, error) {
                                handleAjaxError(request, status, error, $('#div_Alert'));
                            },
                            success: function (data) {
                                if (data.state != 'ok') {
                                    $('#div_Alert').showAlert({message: data.result, level: 'danger'});
                                    return;
                                }
                                //mise à jour des graphs et tableaux
                                updatePieCharts('update');
                                updateInfos($('#comptes_operations').attr('data-eqLogic_id'));
                            }
                        });
                        
                    });
                }
							
				//Affichage du menu déroulant (layout)
				$('.selectpicker').selectpicker();
				//Action quand click sur bouton de mise à jour
				$('.update_op').on('click', function (event) {
					var ComptesOperation = $(this).closest('.OpEdit').getValues('.opAttr');
					ComptesOperation = ComptesOperation[0];
					
					if (ComptesOperation.id != 0) {
						
						updateOperation(ComptesOperation);
						$('#ul_comptes .itemAccount[data-eqLogic_id='+bank_id+']').click();
						
					}	
					
				});
				//Action quand click sur bouton de suppression
				$('.supp_op').on('click', function (event) {
					//alert("click supp op");
					var ComptesOperation = $(this).closest('.OpEdit').getValues('.opAttr');
					ComptesOperation = ComptesOperation[0];
					
					$.ajax({
						type: 'POST',
						url: 'plugins/comptes/core/ajax/comptes.ajax.php',
						data: {
							action: 'deleteOperation',
							id: ComptesOperation.id
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error, $('#div_eventOpAlert'));
						},
						success: function (data) {
							if (data.state != 'ok') {
								$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
								return;
							}
							$('#ul_comptes .itemAccount[data-eqLogic_id='+bank_id+']').click();
						}
					});
					
					
				});
				//Selection de la catégorie (aussi bien pour new que pour edit)
				$('.image_cat_edit').on('click',function(event) {

					var ComptesOperation = $(this).closest('.OpEdit').getValues('.opAttr');
					ComptesOperation = ComptesOperation[0];
					
					$('#comptes_operations').attr('data-opidforcatsel',ComptesOperation.id);
					$('#comptes_operations').attr('data-catidforcatselfocus',ComptesOperation.CatId);
                    
					modalCat();
					
				});
				/*
				$('.bt_sel_cat').on('click', function () {
					$('#md_modalComptes1').dialog({
						autoOpen: false,
						modal: true,
						height: 600,
						width: 400, 
						title: "{{Catégories}}"
					});
					var op_id = $(this).attr('data-op_id');
					var cat_id = $(this).closest('.op').getValues('.opAttr');
					cat_id = cat_id[0];
						
					$('#md_modalComptes1').load('index.php?v=d&plugin=comptes&modal=categories&cat_id='+cat_id.CatId);
					$("#md_modalComptes1").dialog('option', 'buttons', {
						"{{Annuler}}": function() {
							$(this).dialog("close");
						},
						"{{Valider}}": function() {
							
							$('.opAttr[data-l1key=CatId][data-op_id='+op_id+']').value(mod_insertCatValue());
							$('.opAffAttr[data-l1key=CatId][data-op_id='+op_id+']').text(mod_insertCatText_upper()+mod_insertCatText());
							var new_img=mod_insertCatImg();
							if (new_img != '') {
								var new_img_path="plugins/comptes/images/categories/" + new_img;
								$('img[data-op_id='+op_id+']').attr('src',new_img_path);
								$('img[data-op_id='+op_id+']').show();
							}
							else {
								$('img[data-op_id='+op_id+']').hide();
							}
							//alert(op_id);
							//alert(mod_insertCatValue());
							$(this).dialog('close');
						}
					});
					$('#md_modalComptes1').dialog('open');
				});
				$('.image_cat').on('click', function () {
					$('#md_modalComptes1').dialog({
						autoOpen: false,
						modal: true,
						height: 600,
						width: 400, 
						title: "{{Catégories}}"
					});
					var op_id = $(this).attr('data-op_id');
					var cat_id = $(this).closest('.op').getValues('.opAttr');
					cat_id = cat_id[0];
						
					$('#md_modalComptes1').load('index.php?v=d&plugin=comptes&modal=categories&cat_id='+cat_id.CatId);
					$("#md_modalComptes1").dialog('option', 'buttons', {
						"{{Annuler}}": function() {
							$(this).dialog("close");
						},
						"{{Valider}}": function() {
							
							$('.opAttr[data-l1key=CatId][data-op_id='+op_id+']').value(mod_insertCatValue());
							$('.opAffAttr[data-l1key=CatId][data-op_id='+op_id+']').text(mod_insertCatText_upper()+mod_insertCatText());
							
							$('.image_cat[data-op_id='+op_id+']').css('background-color',mod_insertCatImgBackgroundcolor());
							$('.image_cat[data-op_id='+op_id+']').css('color',mod_insertCatImgColor());
							$('.image_cat[data-op_id='+op_id+']').html(mod_insertCatImgIcon());

							$(this).dialog('close');
						}
					});
					$('#md_modalComptes1').dialog('open');
				});
				$('td.cpt_validated').on('click', function (event) {
					var ComptesOperation = $(this).closest('.op').getValues('.opAttr');
					ComptesOperation = ComptesOperation[0];
					var validated_state = ComptesOperation.Checked;		
					
					if(validated_state == 1) {
						$('a[data-l1key=Checked][data-op_id='+ComptesOperation.id+']').removeClass('btn-success');
						$('a[data-l1key=Checked][data-op_id='+ComptesOperation.id+']').addClass('btn-danger');
						$(':input[data-l1key=Checked][data-op_id='+ComptesOperation.id+']').val(0);
						ComptesOperation.Checked = 0;
					}
					if(validated_state == 0) {
						$('a[data-l1key=Checked][data-op_id='+ComptesOperation.id+']').addClass('btn-success');
						$('a[data-l1key=Checked][data-op_id='+ComptesOperation.id+']').removeClass('btn-danger');
						$(':input[data-l1key=Checked][data-op_id='+ComptesOperation.id+']').val(1);
						ComptesOperation.Checked = 1;
					}
					if (ComptesOperation.id != 0) {
						updateOperation(ComptesOperation);
						setTimeout(function (){
							updateInfos(ComptesOperation.eqLogic_id)
							setTimeout(function (){
									updatePieCharts('update');
								},
								100
							);
							},
							50
						);
					}
				});
				
				
				
				
				
				$('td img').noContext();
				$('td img').rightClick(function (e) {
					var ComptesOperation = $(this).closest('.op').getValues('.opAttr');
					ComptesOperation = ComptesOperation[0];
					$.ajax({
						type: 'POST',
						url: 'plugins/comptes/core/ajax/comptes.ajax.php',
						data: {
							action: 'updateHide',
							id: ComptesOperation.id
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error, $('#div_eventOpAlert'));
						},
						success: function (data) {
							if (data.state != 'ok') {
								$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
								return;
							}
							//$('#div_eventOpAlert').showAlert({message: '{{Opération supprimée avec succès}}', level: 'success'});
							
							//alert(data.result.hide);
							if (data.result.hide == 1) {
								$('.opAttr[data-l1key=Amount][data-op_id='+data.result.id+']').hide();
							} else {
								$('.opAttr[data-l1key=Amount][data-op_id='+data.result.id+']').show();
							}
							
							//$('#ul_comptes .li_eqLogic[data-eqLogic_id='+bank_id+']').click();
						}
					});
					
				});
				*/

                
                
                
}

$('#refreshAmounts').on('click', function (event) {
	updateInfos(getUrlVars('id')); 
});

function updateInfos(_id) {
	$.ajax({
						type: 'POST',
						url: 'plugins/comptes/core/ajax/comptes.ajax.php',
						data: {
							action: 'getCompteInfos',
							id: _id
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error);
						},
						success: function (data) {
							if (data.state != 'ok') {
								$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
								return;
							}
							
							$('#solde_reel').text(data.result.solde);
							$('#a_pointer').text(data.result.apointer);
							$('#solde_fin_mois').text(data.result.soldefinmois);
							
                            //TODO à voir si dans le cas de l'option de non pointage on met pas certaines valeurs à autre chose ? 
							$('.itemAccount[data-eqLogic_id='+ data.result.bankid +']').attr('data-soldereel',data.result.solde);
							$('.itemAccount[data-eqLogic_id='+ data.result.bankid +']').attr('data-apointer',data.result.apointer);
							$('.itemAccount[data-eqLogic_id='+ data.result.bankid +']').attr('data-soldefinmois',data.result.soldefinmois);

						}
					});
}

$('.eqLogicAction[data-action=returnToThumbnailDisplay]').on('click', function () {

    $('#comptes_operations').hide();
	$('#comptes_categories').hide();
	$('#comptes_stats').hide();
	$('#comptes_msg_debut').show();
    $('.itemAccount').removeClass('active');
    $('.eqLogicThumbnailContainer').packery();
	$('#ul_infos_comptes').hide();
	$('.btn-group').hide();
});

$('.CptNewOp').on('click',function(event) {
	if ($('.CptNewOpModal').is(':hidden')) {
		$('.CptNewOpModal').show();
		$('.CptNewOp').css('opacity',1);
	}
	else {
		$('.CptNewOpModal').hide();
		$('.CptNewOp').css('opacity',0.6);
	}
	
});

$('.image_cat_new').on('click',function(event) {
					
					
	var ComptesOperation = $(this).closest('.CptNewOpModal').getValues('.opAttr');
	ComptesOperation = ComptesOperation[0];
	
	$('#comptes_operations').attr('data-opidforcatsel',ComptesOperation.id);
    $('#comptes_operations').attr('data-catidforcatselfocus',ComptesOperation.CatId);
    
					
	modalCat();
					
});

$('.EnterAvailable').keyup(function(e) {    
	if(e.keyCode == 13) { // KeyCode de la touche entrée
		$('.btn.btn-primary.add_op').click();
	}
});


$('.btn.btn-primary.add_op').on('click', function (event) {
	var ComptesOperation = $(this).closest('.CptNewOpModal').getValues('.opAttr');
	ComptesOperation = ComptesOperation[0];
	$.ajax({
		type: 'POST',
		url: 'plugins/comptes/core/ajax/comptes.ajax.php',
		data: {
			action: 'updateOperation',
			event: json_encode(ComptesOperation)
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error, $('#div_eventOpAlert'));
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			//$('#div_eventOpAlert').showAlert({message: '{{Opération ajoutée avec succès}}', level: 'success'});
			temp_new_op = 1;
			setTimeout(function (){
				updateInfos(ComptesOperation.eqLogic_id)
				},
				50
			);
			$('.CptNewOp').click();
			$('#ul_comptes .itemAccount[data-eqLogic_id='+ComptesOperation.eqLogic_id+']').click();
		}
	});
	
});

function element_in_scroll(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();
 
    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
 
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

$('#comptes_operations').scroll(function(e){
	//class="DivOp " data-op_id="'+_op.id+'"
	if (element_in_scroll(".DivOp[data-op_id="+lastOpDisplayed+"]") && new_load == 0) {
		new_load = 1;
		//alert("test");
		//alert($('#comptes_operations').attr('data-eqLogic_id'));
		//alert($('#comptes_operations').attr('data-last_id'));
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'getBankOperations_suite',
				type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
				id: $('#comptes_operations').attr('data-eqLogic_id'),
				last_id: lastOpDisplayed, 
                mode: $('#comptes_operations').attr('data-mode'), 
                filterCatId : $('#comptes_operations').attr('data-filterCatId')
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_eventOpAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				//alert("reussite");
				//alert(data.result.eqLogic_id);
                
				add_new_op_in_div(data.result);
				
				
				new_load = 0;
				
			}
		});	
		
    };
});

operations_print = function (_params) {
    var paramsRequired = ['id', 'type'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'plugins/comptes/core/ajax/comptes.ajax.php';
    paramsAJAX.data = {
        action: 'getBankOperations',
        type: _params.type,
        id: _params.id
    };
    $.ajax(paramsAJAX);
}

function addNewOpToTable(bank_id) {
	var tr = '<tr class="op info">';
	tr += '<td class="cpt_validated"><div class="op_validated" data-op_id="0" style="display:none" data-val=0><a class="btn btn-success"><i class="fa fa-check"></i></a></div><div class="op_notvalidated" data-op_id="0" data-val=1><a class="btn btn-danger"><i class="fa fa-check"></i></a></div><input type="text" class="opAttr form-control" data-l1key="Checked" style="display: none;" value ="0" /></td>';
	tr += '<td class=""><input type="text" class="opAttr input-comptes-new dtimepicker" data-l1key="operationDate" /></td>';
	tr += '<td class=""><input type="text" class="opAttr input-comptes-new dtimepicker" data-l1key="CheckedOn" /></td>';
	tr += '<td >'
	tr += '<div class="op_left">';
	/*
	tr += '<img height="40" width="40" data-op_id="0" ';
	tr += ' src="plugins/comptes/images/categories/no_cat.png"';
	tr += '/>';
	*/
	

	tr += '<div class="image_cat" data-op_id="0" style="font-size : 2em;color:#FFF;border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:#c266c2;" ><i class="icon plugin-comptes-billets1"><\/i></div>';
	tr += '</div>'
	tr += '<div class="op_right"><b>';
	tr += '<input type="text" class="opAttr input-comptes-operations-new " data-l1key="BankOperation" /></b>';
	tr += ' &nbsp; <i><span style="margin-left:5px" class="opAffAttr" data-l1key="CatId" data-op_id="0" style="display: none;"></span></i></div>'
	tr += '</td>';

	//tr += '<td class=""><a class="btn btn-primary bt_sel_cat" data-eqLogic_id="'+bank_id+'" data-op_id="0"><i class="fa fa-th-large"></i></a></td>';
	/*
	tr += '<td style="height:40px"><div style="float:left;margin-top:5px"><img height="40" width="40" data-op_id="0" style="display: none;"/>';
	tr += '</div><div style="float:left;width:95%"><b>';
	tr += '<input type="text" class="opAttr input-comptes-operations-new " data-l1key="BankOperation" /></b>';
	tr += ' &nbsp; <i><span style="margin-left:5px" class="opAffAttr" data-l1key="CatId" data-op_id="0" style="display: none;"></span></i></div></td>';

	tr += '<td class=""><a class="btn btn-primary bt_sel_cat" data-eqLogic_id="'+bank_id+'" data-op_id="0"><i class="fa fa-th-large"></i></a></td>';
	*/
	
	tr += '<td>';
	tr += '<select class="opAttr selectpicker" data-style="btn-primary" data-l1key="Type" data-width="100%">';
	tr +=   '<option value=0 >{{Aucun}}</option>';
    tr += 	'<option value=1 data-icon="fa fa-credit-card">{{Carte}}</option>';
    tr += 	'<option value=2 data-icon="fa fa-money">{{Chèque}}</option>';
    tr += 	'<option value=3 data-icon="fa techno-fleches">{{Virement}}</option>';
    tr += '</select>';
	tr+= '</td>';
	tr += '<td class=""><input type="text" class="opAttr input-comptes-new " data-l1key="Amount" /></td>';
	tr += '<td class=""><input type="text" class="opAttr form-control " data-l1key="eqLogic_id" style="display: none;" value="'+bank_id+'" />';
	tr += '<input type="text" class="opAttr form-control" data-l1key="CatId" data-op_id="0" style="display: none;" value="0" /><input type="text" class="opAttr form-control" data-l1key="hide" style="display: none;" value ="0" /><input type="text" class="opAttr input-comptes" data-l1key="id" style="display: none;" value="0" /><a class="btn btn-primary add_op" ><i class="fa fa-plus"></i></a></td>';
    tr += '</tr>';

    $('#table_op tbody').append(tr);
    //$('#table_op tbody tr:last').setValues(_op, '.opAttr');
	var tr = $('#table_op tbody tr:last');
}

/* Partie Gestion catégories */
	function catSetOrder(_params) {
		var paramsRequired = ['categories'];
		var paramsSpecifics = {};
		try {
			jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
		} catch (e) {
			(_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
			return;
		}
		var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
		var paramsAJAX = jeedom.private.getParamsAJAX(params);
		paramsAJAX.url = 'plugins/comptes/core/ajax/comptes.ajax.php';
		paramsAJAX.data = {
			action: 'setCatOrder',
			categories: json_encode(_params.categories)
		};
		$.ajax(paramsAJAX);
	};
	function addCat(_cat) {
		var tr = '<tr class="li_cat bt_sortable" data-cat_id="' + _cat.id + '">';
		tr += '<td>';
		tr += '<a class="catAction btn btn-default btn-sm btn-cat" data-cat_id="' + _cat.id + '" ><i class="fa fa-flag"></i> {{Choisir}}</a>';
		tr += '</td>';
		tr += '<td>';
		tr += '<input type="color" data-cat_id="' + _cat.id + '" class="catAttr form-control" data-l1key="image" data-l2key="tagColor" value="'+ _cat.tagColor +'" />';
		tr += '</td>';
		tr += '<td>';
		tr += '<input type="color" data-cat_id="' + _cat.id + '" class="catAttr form-control" data-l1key="image" data-l2key="tagTextColor" value="'+ _cat.tagTextColor +'"/>';
		tr += '</td>';
		tr += '<td>';
		tr += '<div class="image_cat"  data-cat_id="' + _cat.id +'" style="font-size : 2em;color:'+ _cat.tagTextColor;
		tr += ';border: 1px solid '+ _cat.tagColor + ';border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:'+ _cat.tagColor + ';" >'+ _cat.icon +'</div>';
		tr += '<input type="hidden" data-cat_id="' + _cat.id + '" class="catAttr form-control" data-l1key="image" data-l2key="icon" value="'+ _cat.icon +'"/>';
		tr += '</td>';
		tr += '<td><div style="float:left">';
		var i=0;
		tr += '</div><div style="float:left"><input type="text" class="catAttr input-comptes" data-l1key="name" value="';
		tr += _cat.name;
		tr += '"/></div>';
		tr += '</td>';
		tr += '<td style="width: 50px;" class="move_left" data-cat_id="'+ _cat.id +'">';
		tr += '<a class="btn btn-success move_cat_up" style="display:none"><i class="fa fa-chevron-left"></i></a>';
		tr += '</td>';
		tr += '<td class="level_aff"  style="width: 20px;">0</td>';
		tr += '<td style="width: 50px;"><a class="btn btn-success move_cat_down"><i class="fa fa-chevron-right"></i></a></td>';
		tr += '<td>';
		tr += '<a class="btn btn-danger supp_cat"><i class="fa fa-trash-o"></i></a>';
		tr += '<input type="text" class="catAttr input-comptes" data-l1key="id" style="display: none;" value="'+ _cat.id +'" />';
		tr += '<input type="text" class="catAttr input-comptes" data-l1key="level" data-cat_id="'+ _cat.id +'" style="display: none;" value="0" />';
		tr += '</td>';
		tr += '</tr>';
		$('#ul_Gestcat tbody').append(tr);
	}
	function updateCat(_cat) {
		
		$.ajax({
						type: 'POST',
						url: 'plugins/comptes/core/ajax/comptes.ajax.php',
						data: {
							action: 'updateCat',
							event: json_encode(_cat)
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error, $('#div_CatAlert'));
						},
						success: function (data) {
							if (data.state != 'ok') {
								$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
								return;
							}
							//$('#div_CatAlert').showAlert({message: '{{Catégorie editée avec succès}}', level: 'success'});
						}
		});
		
	}
	$('.btn.btn-primary.add_cat').on('click', function (event) {
					var Cat = $(this).closest('.li_cat').getValues('.catAttr');
					Cat = Cat[0];
					//addCat(Cat);
					//$('#img_new').value('');
					$('#name_new').value('');
					$.ajax({
						type: 'POST',
						url: 'plugins/comptes/core/ajax/comptes.ajax.php',
						data: {
							action: 'updateCat',
							event: json_encode(Cat)
						},
						dataType: 'json',
						error: function (request, status, error) {
							handleAjaxError(request, status, error, $('#div_CatAlert'));
						},
						success: function (data) {
							if (data.state != 'ok') {
								$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
								return;
							}
							$('.bt_ManageCat').click();
							var newcat = {};
							newcat.id = data.result.id;
							newcat.name = data.result.name;
							newcat.tagColor = data.result.tagColor;
							newcat.tagTextColor = data.result.tagTextColor;
							newcat.icon = data.result.icon;
							addCat(newcat);
	$('.catAttr').on('change', function (event) {
					var Cat = $(this).closest('.li_cat').getValues('.catAttr');
					Cat = Cat[0];
					
					//mise à jour de l'image
					/*
					if (Cat.image != '') {
						var new_img_path="plugins/comptes/images/categories/" + Cat.image;
						//alert(new_img_path);
						//alert($('img[data-cat_id='+Cat.id+']').attr('src'));
						$('img[data-cat_id='+Cat.id+']').attr('src',new_img_path);
						$('img[data-cat_id='+Cat.id+']').show();
					}
					else {
						$('img[data-cat_id='+Cat.id+']').hide();
					}
					*/
					//alert(Cat.image.tagTextColor);
					 $('.image_cat[data-cat_id='+Cat.id+']').css('background-color',Cat.image.tagColor);
					 $('.image_cat[data-cat_id='+Cat.id+']').css('border-color',Cat.image.tagColor);
					 $('.image_cat[data-cat_id='+Cat.id+']').css('color',Cat.image.tagTextColor);
					
					if (Cat.id != 0) {
						updateCat(Cat);
					}
					
					
				});
	$('body').delegate('.bt_sortable', 'mouseenter', function () {
		$("#ul_Gestcat").sortable({
			axis: "y",
			cursor: "move",
			items: ".li_cat",
			placeholder: "ui-state-highlight",
			tolerance: "intersect",
			forcePlaceholderSize: true,
			dropOnEmpty: true,
			stop: function (event, ui) {
				var categories = [];
				$('#ul_Gestcat .li_cat').each(function () {
					categories.push($(this).attr('data-cat_id'));
				});
				
				catSetOrder({
					categories: categories,
					error: function (error) {
						$('#div_CatAlert').showAlert({message: error.message, level: 'danger'});
					}
				});
				
			}
		});
		$("#ul_Gestcat").sortable("enable");
	});
	$('body').delegate('.bt_sortable', 'mouseout', function () {
		$("#ul_Gestcat").sortable("disable");
	});
	$('body').delegate('.catAction.btn-cat', 'click', function () {
		var cat = $(this).closest('.catAction').attr('data-cat_id');
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
			Cat = Cat[0];
			//alert(Cat.id);
		var color = $('.catAttr[data-l1key=image][data-l2key=tagTextColor][data-cat_id='+cat+']').value();
		//alert(color);
		chooseIcon(function (_icon) {
			
			$('.image_cat[data-cat_id='+cat+']').empty().append(_icon);
			$('.catAttr[data-l1key=image][data-l2key=icon][data-cat_id='+cat+']').value(_icon);
			$('.image_cat[data-cat_id='+cat+']').css('color',color);
			
			//updateCat(Cat);
			//alert(_icon);
			//alert(Cat.image.icon);
		});
	});
	$('.btn.btn-success.move_cat_down').on('click', function (event) {
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
		Cat = Cat[0];
		//Ajouter le nouveau décallage dans le tableau
		var deca = "";
		var level = Cat.level;
		for (var i=0; i<= level; i++) {
			deca += "&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$('.cat_decalage[data-cat_id='+Cat.id+']').html(deca);
		//rajout du level up si c'était une catégorie root
		if (level == 0) {
			$('.move_left[data-cat_id='+Cat.id+'] > a').show();
			level = 0;
		}
		//remettre le level au bon niveau dans le tableau: 
		var newlevel = parseInt(level) + 1;
		$('[data-l1key=level][data-cat_id='+Cat.id+']').value(newlevel) ;
		//remettre à jour l'affichage du level
		$('.li_cat[data-cat_id='+Cat.id+'] > td.level_aff').html(newlevel);
		//mise à jour de la BDD:
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'moveCatDown',
				id: Cat.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});	
	});
	$('.btn.btn-success.move_cat_up').on('click', function (event) {
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
		Cat = Cat[0];
		//Ajouter le nouveau décallage dans le tableau
		var deca = "";
		var level = Cat.level;
		var newlevel = parseInt(level) - 1;	
		for (var i=0; i< newlevel; i++) {
			deca += "&nbsp;&nbsp;&nbsp;&nbsp;";
		}	
		$('.cat_decalage[data-cat_id='+Cat.id+']').html(deca);
		//suppression du level up si c'était une catégorie root
		if (newlevel == 0) {
			$('.move_left[data-cat_id='+Cat.id+'] > a').hide();
		}	
		//remettre le level au bon niveau dans le tableau: 
		$('[data-l1key=level][data-cat_id='+Cat.id+']').value(newlevel) ;
		//remettre à jour l'affichage du level
		$('.li_cat[data-cat_id='+Cat.id+'] > td.level_aff').html(newlevel);
		//mise à jour de la BDD:
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'moveCatUp',
				id: Cat.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});	
	});
	$('.btn.btn-danger.supp_cat').on('click', function (event) {
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
		Cat = Cat[0];
		$(this).closest('.li_cat').remove();
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'deleteCat',
				id: Cat.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				//$('#div_CatAlert').showAlert({message: '{{Catégorie supprimée avec succès}}', level: 'success'});
				$('#ul_comptes .itemAccount[data-eqLogic_id='+bank_id+']').click();
			}
		});	
	});
	
							//$('#div_CatAlert').showAlert({message: '{{Catégorie ajoutée avec succès}}', level: 'success'});
						}
					});
					
				});
	$('.input-comptes-new').keyup(function(e) {    
				   if(e.keyCode == 13) { // KeyCode de la touche entrée
					$('.btn.btn-primary.add_cat').click();
						 
				 }
				});
	$('.catAttr').on('change', function (event) {
					var Cat = $(this).closest('.li_cat').getValues('.catAttr');
					Cat = Cat[0];
					
					//mise à jour de l'image
					/*
					if (Cat.image != '') {
						var new_img_path="plugins/comptes/images/categories/" + Cat.image;
						//alert(new_img_path);
						//alert($('img[data-cat_id='+Cat.id+']').attr('src'));
						$('img[data-cat_id='+Cat.id+']').attr('src',new_img_path);
						$('img[data-cat_id='+Cat.id+']').show();
					}
					else {
						$('img[data-cat_id='+Cat.id+']').hide();
					}
					*/
					//alert(Cat.image.tagTextColor);
					 $('.image_cat[data-cat_id='+Cat.id+']').css('background-color',Cat.image.tagColor);
					 $('.image_cat[data-cat_id='+Cat.id+']').css('border-color',Cat.image.tagColor);
					 $('.image_cat[data-cat_id='+Cat.id+']').css('color',Cat.image.tagTextColor);
					
					if (Cat.id != 0) {
						updateCat(Cat);
					}
					
					
				});
	$('body').delegate('.bt_sortable', 'mouseenter', function () {
		$("#ul_Gestcat").sortable({
			axis: "y",
			cursor: "move",
			items: ".li_cat",
			placeholder: "ui-state-highlight",
			tolerance: "intersect",
			forcePlaceholderSize: true,
			dropOnEmpty: true,
			stop: function (event, ui) {
				var categories = [];
				$('#ul_Gestcat .li_cat').each(function () {
					categories.push($(this).attr('data-cat_id'));
				});
				
				catSetOrder({
					categories: categories,
					error: function (error) {
						$('#div_CatAlert').showAlert({message: error.message, level: 'danger'});
					}
				});
				
			}
		});
		$("#ul_Gestcat").sortable("enable");
	});
	$('body').delegate('.bt_sortable', 'mouseout', function () {
		$("#ul_Gestcat").sortable("disable");
	});
	$('body').delegate('.catAction.btn-cat', 'click', function () {
		var cat = $(this).closest('.catAction').attr('data-cat_id');
		
		var color = $('.catAttr[data-l1key=image][data-l2key=tagTextColor][data-cat_id='+cat+']').value();
		//alert(color);
		chooseIcon(function (_icon) {
			
			$('.image_cat[data-cat_id='+cat+']').empty().append(_icon);
			$('.catAttr[data-l1key=image][data-l2key=icon][data-cat_id='+cat+']').value(_icon);
			$('.image_cat[data-cat_id='+cat+']').css('color',color);
			
			//updateCat(Cat);
			//alert(_icon);
			//alert(Cat.image.icon);
		});
	});
	$('.btn.btn-success.move_cat_down').on('click', function (event) {
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
		Cat = Cat[0];
		//Ajouter le nouveau décallage dans le tableau
		var deca = "";
		var level = Cat.level;
		for (var i=0; i<= level; i++) {
			deca += "&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$('.cat_decalage[data-cat_id='+Cat.id+']').html(deca);
		//rajout du level up si c'était une catégorie root
		if (level == 0) {
			$('.move_left[data-cat_id='+Cat.id+'] > a').show();
			level = 0;
		}
		//remettre le level au bon niveau dans le tableau: 
		var newlevel = parseInt(level) + 1;
		$('[data-l1key=level][data-cat_id='+Cat.id+']').value(newlevel) ;
		//remettre à jour l'affichage du level
		$('.li_cat[data-cat_id='+Cat.id+'] > td.level_aff').html(newlevel);
		//mise à jour de la BDD:
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'moveCatDown',
				id: Cat.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});	
	});
	$('.btn.btn-success.move_cat_up').on('click', function (event) {
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
		Cat = Cat[0];
		//Ajouter le nouveau décallage dans le tableau
		var deca = "";
		var level = Cat.level;
		var newlevel = parseInt(level) - 1;	
		for (var i=0; i< newlevel; i++) {
			deca += "&nbsp;&nbsp;&nbsp;&nbsp;";
		}	
		$('.cat_decalage[data-cat_id='+Cat.id+']').html(deca);
		//suppression du level up si c'était une catégorie root
		if (newlevel == 0) {
			$('.move_left[data-cat_id='+Cat.id+'] > a').hide();
		}	
		//remettre le level au bon niveau dans le tableau: 
		$('[data-l1key=level][data-cat_id='+Cat.id+']').value(newlevel) ;
		//remettre à jour l'affichage du level
		$('.li_cat[data-cat_id='+Cat.id+'] > td.level_aff').html(newlevel);
		//mise à jour de la BDD:
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'moveCatUp',
				id: Cat.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});	
	});
	$('.btn.btn-danger.supp_cat').on('click', function (event) {
		var Cat = $(this).closest('.li_cat').getValues('.catAttr');
		Cat = Cat[0];
		$(this).closest('.li_cat').remove();
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'deleteCat',
				id: Cat.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_CatAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_CatAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				//$('#div_CatAlert').showAlert({message: '{{Catégorie supprimée avec succès}}', level: 'success'});
				//$('#ul_comptes .itemAccount[data-eqLogic_id='+bank_id+']').click();
			}
		});	
	});
	
/* Partie charts d3 */
var pie=null;
var pie2=null;
function updatePieCharts(sens) {
	$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'updatePieCharts',
				type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
				id: $('#comptes_operations').attr('data-eqLogic_id'),
				pie_chart_date: $('#chart_title').attr('data-date-pie-chart'),
				date_sens: sens
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_eventOpAlert'));
			},
			success: function (data) {
				
				if (data.state != 'ok') {
					$('#div_eventOpAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#chart_title').attr('data-date-pie-chart',data.result.new_pie_date)
				
				var devise = $('#comptes_operations').attr('data-devise');
				
				if (pie != null) {
					pie.destroy();
					pie = null;
				}
				if (pie2 != null) {
					pie2.destroy();
					pie2 = null;
				}
				$('#chart_title').text(data.result.new_pie_date_letterMonth + " " + data.result.new_pie_date_letterYear);
				
				
				pie = new d3pie("chart_comptes_1", {
					"header": {
						"title": {
							"text": ""+data.result.recettes + " " + devise,
							"color": "#1ec815",
							"fontSize": 26,
							"font": "verdana"
						},
						"subtitle": {
							"text": "{{Recettes du mois par catégories}}",
							"color": "#999999",
							"fontSize": 10,
							"font": "verdana"
						},
						"location": "pie-center",
						"titleSubtitlePadding": 15
					},
					"size": {
						"canvasHeight": $('#comptes_stats').width()*0.6,
						"canvasWidth": $('#comptes_stats').width(),
						"pieInnerRadius": "80%",
						"pieOuterRadius": "100%"
					},
					"data": {
						"content": data.result.RecettesDuMois
					},
					"labels": {
						"outer": {
							"format": "none",
							"pieDistance": 20
						},
						"inner": {
							"format": "none"
						},
						"mainLabel": {
							"font": "verdana",
							"fontSize": 11
						},
						"percentage": {
							"color": "#999999",
							"fontSize": 11,
							"decimalPlaces": 0
						},
						"value": {
							"color": "#000000",
							"font": "verdana",
							"fontSize": 11
						},
						"lines": {
							"enabled": true
						},
						"truncation": {
							"enabled": true
						}
					},
					"tooltips": {
						"enabled": true,
						"type": "placeholder",
						"string": '{label}: {value} '+devise+', {percentage}%'
					},
					"effects": {
						"pullOutSegmentOnClick": {
							"effect": "none",
							"speed": 400,
							"size": 8
						}
					},
					"misc": {
						"colors": {
							"segmentStroke": "#000000"
						},
						"canvasPadding": {
							"top":  5,
							"left": 5
						}
					}
				});
				pie2 = new d3pie("chart_comptes_2", {
					"header": {
						"title": {
							"text": ""+data.result.depenses +  " " + devise,
							"color": "#c81515",
							"fontSize": 26,
							"font": "verdana"
						},
						"subtitle": {
							"text": "{{Dépenses du mois par catégories}}",
							"color": "#999999",
							"fontSize": 10,
							"font": "verdana"
						},
						"location": "pie-center",
						"titleSubtitlePadding": 15
					},
					"size": {
						"canvasHeight": $('#comptes_stats').width()*0.6,
						"canvasWidth": $('#comptes_stats').width(),
						"pieInnerRadius": "80%",
						"pieOuterRadius": "100%"
					},
					"data": {
						"content": data.result.DepensesDuMois
					},
					"labels": {
						"outer": {
							"format": "none",
							"pieDistance": 20
						},
						"inner": {
							"format": "none"
						},
						"mainLabel": {
							"font": "verdana",
							"fontSize": 11
						},
						"percentage": {
							"color": "#000000",
							"fontSize": 11,
							"decimalPlaces": 0
						},
						"value": {
							"color": "#000000",
							"font": "verdana",
							"fontSize": 11
						},
						"lines": {
							"enabled": true
						},
						"truncation": {
							"enabled": true
						}
					},
					"tooltips": {
						"enabled": true,
						"type": "placeholder",
						"string": '{label}: {value} '+devise+', {percentage}%'
					},
					"effects": {
						"pullOutSegmentOnClick": {
							"effect": "none",
							"speed": 400,
							"size": 8
						}
					},
					"misc": {
						"colors": {
							"segmentStroke": "#000000"
						},
						"canvasPadding": {
							"top": 5,
							"left": 5
						}
					}
				});
	
				if (sens == 'left') {
					$('.move_date_right').show();
				}
				if (sens == 'right') {
					if (data.result.date_now == data.result.new_pie_date)
						$('.move_date_right').hide();
				}
			}
		});	

}

$('.move_date_left').on('click', function (event) {
	updatePieCharts('left');
});

$('.move_date_right').on('click', function (event) {
	updatePieCharts('right');
});