<?php

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

if (!isConnect()) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
include_file('desktop', 'panel', 'css', 'comptes'); 
$eqLogics = eqLogic::byType('comptes');
?>

<div id='div_TransfertBankAlert' style="display: none;"></div>
<div class="row row-overflow transfert_cac">
		<form class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Nom de l'opération}}</label>
					<div class="col-lg-7">
						<input type="text" class="VirCompteAttr form-control" data-l1key="Title" placeholder="{{Nom de l'opération}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Montant du transfert}}</label>
					<div class="col-lg-7">
						<input type="text" class="VirCompteAttr form-control" data-l1key="Amount" placeholder="{{Montant du transfert}}"/>
					</div>
					<!-- Rajouter info bulle exemple -->
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Date de l'opération}}</label>
					<div class="col-lg-7">
						<input type="text" class="VirCompteAttr form-control dtimepicker" data-l1key="Date" placeholder="{{Date de l'opération}}"/>
					</div>
				</div>
				<!--
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Date de valeur de l'opération}}</label>
					<div class="col-lg-7">
						<input type="text" class="VirCompteAttr form-control dtimepicker" data-l1key="DateValeur" placeholder="{{Date de valeur de l'opération}}"/>
					</div>
				</div>
				-->
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Banque débitrice}}</label>
					<div class="col-lg-7">
						<select id="selectBankDebit" class="VirCompteAttr selectpicker " data-style="btn-primary" data-l1key="BankDebit" data-width="100%" >
						<?php
				
							foreach ($eqLogics as $eqLogic) {
								if ($eqLogic->getIsEnable()) 
									echo '<option  value='. $eqLogic->getId() .' >'. $eqLogic->getHumanName(true)  .'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Banque créditrice}}</label>
					<div class="col-lg-7">
						<select id="selectBankCredit" class="VirCompteAttr selectpicker " data-style="btn-primary" data-l1key="BankCredit" data-width="100%" >
						<?php
				
							foreach ($eqLogics as $eqLogic) {
								if ($eqLogic->getIsEnable())
									echo '<option  value='. $eqLogic->getId() .' >'. $eqLogic->getHumanName(true)  .'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="form-group" >
					<label class="col-sm-4 control-label">{{Catégorie opération débitrice}}</label>
					<div class="col-lg-7" style="height:30px">
						<input type="text" class="VirCompteAttr form-control" value="0" data-l1key="CatIdDebit" style="display : none;"  />
						<!--
						<div style="float:left"><a class="btn btn-primary bt_sel_cat_debit" data-eqLogic_id="" data-op_id=""><i class="fa fa-th-large"></i></a></div>
						-->
						<div style="float:left;margin-left:5px">
						<!-- <img id="img_catDebit" data-img_name="" height="34" width="34" style="display:none"/>
						-->
						<div class="image_catDebit" style="font-size : 2em;color:#FFF;border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:#c266c2;" ><i class="icon plugin-comptes-billets1"></i></div>
						</div><div id="text_cat_upper_debit" style="float:left;margin-top:8px;margin-left:5px"></div><div id="text_cat_debit" style="float:left;margin-top:8px;margin-left:5px"></div>
						
					</div>
				</div>
				<div class="form-group" >
					<label class="col-sm-4 control-label">{{Catégorie opération créditrice}}</label>
					<div class="col-lg-7" style="height:30px">
						<input type="text" class="VirCompteAttr form-control" value="0" data-l1key="CatIdCredit" style="display : none;"  />
						<!-- 
						<div style="float:left"><a class="btn btn-primary bt_sel_cat_credit" data-eqLogic_id="" data-op_id=""><i class="fa fa-th-large"></i></a></div>
						-->
						<div style="float:left;margin-left:5px">
						<!-- <img id="img_catCredit" data-img_name="" height="34" width="34" style="display:none"/>
						-->
						<div class="image_catCredit" style="font-size : 2em;color:#FFF;border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:#c266c2;" ><i class="icon plugin-comptes-billets1"></i></div>
						
						</div><div id="text_cat_upper_credit" style="float:left;margin-top:8px;margin-left:5px"></div><div id="text_cat_credit" style="float:left;margin-top:8px;margin-left:5px"></div>
						
					</div>
				</div>
				<div class="form-actions">
					<div class="col-lg-9 control-label">
						<a class="btn btn-primary virCompteAction" data-action="add"><i class="fa fa-exchange"></i> {{Effectuer le transfert}}</a>
					</div>
				</div>
			</fieldset>
       </form>
</div>
<div id='modal_transfert' style="display: none;"></div>
<script>
	
$('.dtimepicker').datetimepicker({lang: 'fr',
	format: 'Y-m-d',
	timepicker:false,
	step: 15
});


$('.image_catDebit').on('click', function () {
	
	$('#modal_transfert').dialog({
		autoOpen: false,
		modal: true,
		height: 600,
		width: 400, 
		title: "{{Catégories}}"
	});
	var transfert = $(this).closest('.transfert_cac').getValues('.VirCompteAttr');
	transfert = transfert[0];
	
	$('#modal_transfert').load('index.php?v=d&plugin=comptes&modal=categories&cat_id='+transfert.CatIdDebit);
	$("#modal_transfert").dialog('option', 'buttons', {
		"{{Annuler}}": function() {
			$(this).dialog("close");
		},
		"{{Valider}}": function() {
			
			$('.VirCompteAttr[data-l1key=CatIdDebit]').value(mod_insertCatValue());
			$('#text_cat_debit').text(mod_insertCatText());
			$('#text_cat_upper_debit').text(mod_insertCatText_upper());
			
			/*
			var new_img=mod_insertCatImg();
			if (new_img != '') {
				var new_img_path="plugins/comptes/images/categories/" + new_img;
				$('#img_catDebit').attr('src',new_img_path);
				$('#img_catDebit').attr('data-img_name', new_img )
				$('#img_catDebit').show();
			}
			else {
				$('#img_catDebit').hide();
			}
			*/
			$('.image_catDebit').css('background-color',mod_insertCatImgBackgroundcolor());
			$('.image_catDebit').css('color',mod_insertCatImgColor());
			$('.image_catDebit').html(mod_insertCatImgIcon());
			$(this).dialog('close');
		}
	});
	$('#modal_transfert').dialog('open');
});

$('.image_catCredit').on('click', function () {
	
	$('#modal_transfert').dialog({
		autoOpen: false,
		modal: true,
		height: 600,
		width: 400, 
		title: "{{Catégories}}"
	});
	var transfert = $(this).closest('.transfert_cac').getValues('.VirCompteAttr');
	transfert = transfert[0];
	
	$('#modal_transfert').load('index.php?v=d&plugin=comptes&modal=categories&cat_id='+transfert.CatIdCredit);
	$("#modal_transfert").dialog('option', 'buttons', {
		"{{Annuler}}": function() {
			$(this).dialog("close");
		},
		"{{Valider}}": function() {
			
			$('.VirCompteAttr[data-l1key=CatIdCredit]').value(mod_insertCatValue());
			$('#text_cat_credit').text(mod_insertCatText());
			$('#text_cat_upper_credit').text(mod_insertCatText_upper());
			/*
			var new_img=mod_insertCatImg();
			if (new_img != '') {
				var new_img_path="plugins/comptes/images/categories/" + new_img;
				$('#img_catCredit').attr('src',new_img_path);
				$('#img_catCredit').attr('data-img_name', new_img )
				$('#img_catCredit').show();
			}
			else {
				$('#img_catCredit').hide();
			}
			*/
			$('.image_catCredit').css('background-color',mod_insertCatImgBackgroundcolor());
			$('.image_catCredit').css('color',mod_insertCatImgColor());
			$('.image_catCredit').html(mod_insertCatImgIcon());
			$(this).dialog('close');
		}
	});
	$('#modal_transfert').dialog('open');
});



$('.selectpicker').selectpicker();



$('.virCompteAction[data-action=add]').on('click', function () {
	//Mise à jour des infos dans la base de donnée
	var transfert = $(this).closest('.transfert_cac').getValues('.VirCompteAttr');
	transfert = transfert[0];
	var error_content = 0;
	var msg_error = "";
	if (transfert.Title == "") {
		error_content = 1;
		msg_error += "{{Le titre de l'opération ne doit pas être vide}} ";
	}
	if (transfert.Amount == "") {
		error_content = 1;
		msg_error += "{{La montant du transfert ne doit pas être vide}} ";
	}
	if (isNaN(transfert.Amount) == true) {
		error_content = 1;
		msg_error += "{{La montant du transfert doit être un chiffre}} ";
	}	
	if (transfert.Amount <= 0) {
		error_content = 1;
		msg_error += "{{Le montant du transfert doit être un nombre positif}} ";
	}


	if (transfert.BankDebit == 0) {
		error_content = 1;
		msg_error += "{{Un compte bancaire d'origine (débit) doit être sélectionné}} ";
	}
	if (transfert.BankCredit == 0) {
		error_content = 1;
		msg_error += "{{Un compte bancaire de destination (crédit) doit être sélectioné}} ";
	}

	if (error_content == 0) {
		
	
		
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'performTransfertCompte',
				event: json_encode(transfert)
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_VirementAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_VirementAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				location.reload();
				/*
				$('#div_VirementAlert').showAlert({message: '{{Virement auto sauvegardé avec succès}}', level: 'success'});
				
				setTimeout(function (){
					$('#div_VirementAlert').hide();
					},
					2000
				);
				*/
			}
		});
		
	} else {
		$('#div_TransfertBankAlert').showAlert({message: msg_error, level: 'danger'});
		setTimeout(function (){
			$('#div_TransfertBankAlert').hide();
			},
			3000
		);
	}
});

</script>