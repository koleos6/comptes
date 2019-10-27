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
$vir_id = init('vir_id');
sendVarToJS('vir_id', $vir_id);
include_file('desktop', 'panel', 'css', 'comptes'); 
$eqLogics = eqLogic::byType('comptes');
?>

<div id='div_VirementAlert' style="display: none;"></div>
<div class="row row-overflow">
	<div class="col-lg-5">
        <div class="bs-sidebar">
            <ul id="ul_virAuto" class="nav nav-list bs-sidenav">
                <a class="btn btn-default virAutoAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="addVirAuto"><i class="fas fa-plus-circle"></i> {{Ajouter un virement automatique}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
	
				$_cats=comptes_categories::all();
				$cat_i=-1;
				foreach ($_cats as $c) {			
					$cat_i++;
					$cats_level[$cat_i]=$c->getLevel();
					$cats_name[$cat_i]=$c->getName();
				}
				
                foreach (comptes_virements_auto::all() as $vir_auto) {
					$cat_id = $vir_auto->getCatId();
					
					if ($cat_id) {
						$cat = comptes_categories::byId($cat_id);
						
						//$cat_img=$cat->getImage();
						$cat_imgTagColor=$cat->getImage("tagColor");
						$cat_imgTagTextColor=$cat->getImage("tagTextColor");
						$cat_imgIcon=$cat->getImage("icon");
						$cat_txt=$cat->getName();
						$cat_level=$cat->getLevel();
						$cat_position=$cat->getPosition();
						$stop = 0;
						$cat_upper_level_name = "";
						if ($cat_level > 0) {	
							for ($i=$cat_position-1; ($i >= 0) && ($stop == 0); $i--) {			
								if ($cats_level[$i] == ($cat_level - 1))  {
									$cat_upper_level_name = $cats_name[$i];
									$cat_upper_level_name = $cat_upper_level_name . ' -> ';
									$stop = 1;
									
								}
							}
						}			
					}
					else {
						$cat_img = "";
						$cat_txt = "";
					}
                    //log::add('comptes', 'debug', $vir_auto->getEqLogic_id());
                    echo '<li class="cursor li_VirAuto bt_sortable" data-vir_auto_id="' . $vir_auto->getId() . '" data-vir_auto_eqLogic_id="' . $vir_auto->getEqLogic_id() . '" data-vir_auto_catid="' . $vir_auto->getCatId() 
					. '" data-vir_auto_title="' . $vir_auto->getTitle() . '" data-vir_auto_reference="' . $vir_auto->getReference() . '" data-vir_auto_amount="' . $vir_auto->getAmount() 
					. '" data-vir_auto_startdate="' . $vir_auto->getStartDate() . '" data-vir_auto_enddate="' . $vir_auto->getEndDate() . '" data-vir_auto_position="' . $vir_auto->getPosition() 
					. '" data-vir_auto_frequence="' . $vir_auto->getFrequence() . '" data-vir_auto_jour="' . $vir_auto->getJour()
					. '" data-vir_auto_cat_imgTagColor="' . $cat_imgTagColor . '" data-vir_auto_cat_imgTagTextColor="' . $cat_imgTagTextColor . '" data-vir_auto_cat_imgIcon="' . $cat_imgIcon . '" data-vir_auto_cat_name="' . $cat_txt
					. '" data-vir_auto_cat_upper_level_name="' . $cat_upper_level_name 
					. '" data-vir_auto_compteur_frequence="' . $vir_auto->getCompteur_frequence()
					. '" data-vir_auto_comments="' . $vir_auto->getComments() . '" style=""><a><span class="title_vir_auto" style="width:200px" data-vir_auto_id="' . $vir_auto->getId() .'">'
					. $vir_auto->getTitle() .'</span>'
					. '<span class="label_cpt_obj" style="height:25px;float:right;margin-top:-4px;text-shadow : none;background-color:#3399FF;color:white;" data-vir_auto_id="' . $vir_auto->getId() .'"> '. $vir_auto->getEndDate() .'</span>'
					. '</a></li>';
                }
                ?>
				
            </ul>
        </div>
    </div>
	
	<div class="col-lg-7 VirAutoThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px; " >
        <legend><center>{{Message}}</center>
        </legend>
        <br/><br/><br/>
		<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Sélectionnez un virement automatique sur la gauche}}</span></center>
    </div>
	
	<div class="col-lg-7 VirAuto" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
		<form class="form-horizontal">
			<fieldset>
				<legend><center> <i class="fas fa-arrow-circle-left virAutoAction cursor" data-action="returnToThumbnailDisplay"></i> {{Détails}} </center> </legend>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Titre}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control" data-l1key="id" style="display : none;" />
						<input type="text" class="VirAutoAttr form-control" data-l1key="compteur_frequence" style="display : none;" />
						<input type="text" class="VirAutoAttr form-control" data-l1key="position" style="display : none;" />
						<input type="text" class="VirAutoAttr form-control" data-l1key="Title" placeholder="{{Nom de l'opération}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Montant}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control" data-l1key="Amount" placeholder="{{Montant du virement}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Date du début de la période}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control dtimepicker" data-l1key="StartDate" placeholder="{{Date de début}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Date de fin de la période}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control dtimepicker" data-l1key="EndDate" placeholder="{{Date de fin}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Fréquence (en mois)}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control" data-l1key="frequence" placeholder="{{Fréquence}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Jour (du mois)}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control" data-l1key="jour" placeholder="{{Jour}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Banque}}</label>
					<div class="col-lg-8">
						<select id="selectBank" class="VirAutoAttr form-control" data-style="btn-primary" data-l1key="eqLogic_id" data-width="100%" >
						<option  value="0"> {{Aucune}}</option>
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
					<label class="col-sm-4 control-label">{{Catégorie}}</label>
					<div class="col-lg-8" style="height:40px">
						<input type="text" class="VirAutoAttr form-control" value="0" data-l1key="CatId" style="display : none;"  />
						<!--
						<div style="float:left"><a class="btn btn-primary bt_sel_cat" data-eqLogic_id="" data-op_id=""><i class="fas fa-th-large"></i></a></div>
						-->	
						<div style="float:left;margin-left:5px">
						<!--
						<img id="img_cat" data-img_name="" height="34" width="34" style="display:none"/>
						-->
						<div class="image_cat_va" style="font-size : 2em;color:#FFF;border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:#c266c2;" ><i class="icon plugin-comptes-billets1"></i>
                        </div>
						</div><div id="text_cat_upper" style="float:left;margin-top:8px;margin-left:5px"></div><div id="text_cat" style="float:left;margin-top:8px;margin-left:5px"></div>
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Référence}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control" data-l1key="Reference" placeholder="{{Référence}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">{{Commentaires}}</label>
					<div class="col-lg-8">
						<input type="text" class="VirAutoAttr form-control" data-l1key="Comments" placeholder="{{Commentaires}}"/>
					</div>
				</div>
				<div class="form-actions">
					<div class="col-sm-4 control-label">
						<a class="btn btn-danger virAutoAction" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
					</div>
					<div class="col-lg-8" style="margin-top:7px">
						<a class="btn btn-success virAutoAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
					</div>
				</div>
			</fieldset>
       </form>
	</div>
</div>
<script>

function virAutoSetOrder(_params) {
	var paramsRequired = ['virement_auto'];
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
		action: 'setVirAutoOrder',
		virement_auto: json_encode(_params.virement_auto)
	};
	$.ajax(paramsAJAX);
};
	
$('.dtimepicker').datetimepicker({lang: 'fr',
	format: 'Y-m-d',
	timepicker:false,
	step: 15
});

$(document).ready(function(){
	if (vir_id != "") {
		//alert($('.li_VirAuto[data-vir_auto_id='+vir_id+']').text());
		setTimeout(function (){
				$('.li_VirAuto[data-vir_auto_id='+vir_id+']').click();
				},
				50
			);
		
	}
});


$('.image_cat_va').on('click', function () {
	$('#md_modal').dialog({
		autoOpen: false,
		modal: true,
		height: 600,
		width: 400, 
		title: "{{Catégories}}"
	});
	var vir_auto = $(this).closest('.VirAuto').getValues('.VirAutoAttr');
	vir_auto = vir_auto[0];
	
	$('#md_modal').load('index.php?v=d&plugin=comptes&modal=categories&cat_id='+vir_auto.CatId);
	$("#md_modal").dialog('option', 'buttons', {
		"{{Annuler}}": function() {
			$(this).dialog("close");
		},
		"{{Valider}}": function() {
			
			$('.VirAutoAttr[data-l1key=CatId]').value(mod_insertCatValue());
			$('#text_cat').text(mod_insertCatText());
			$('#text_cat_upper').text(mod_insertCatText_upper());
			//mise à jour dans l'affichage
			$('.image_cat_va').css('background-color',mod_insertCatImgBackgroundcolor());
			$('.image_cat_va').css('color',mod_insertCatImgColor());
			$('.image_cat_va').html(mod_insertCatImgIcon());
			//remise à jour dans le menu pour futurs affichage
			$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_cat_imgTagTextColor',mod_insertCatImgColor());
			$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_cat_imgTagColor',mod_insertCatImgBackgroundcolor());
			$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_cat_imgIcon',mod_insertCatImgIcon());
			
			/*
			var new_img=mod_insertCatImg();
			if (new_img != '') {
				var new_img_path="plugins/comptes/images/categories/" + new_img;
				$('#img_cat').attr('src',new_img_path);
				$('#img_cat').attr('data-img_name', new_img )
				$('#img_cat').show();
			}
			else {
				$('#img_cat').hide();
			}
			*/
			$(this).dialog('close');
		}
	});
	$('#md_modal').dialog('open');
});

$('.virAutoAction[data-action=returnToThumbnailDisplay]').on('click', function () {
    $('.VirAuto').hide();
    $('.VirAutoThumbnailDisplay').show();
    $('.li_VirAuto').removeClass('active');
});

$(".li_VirAuto").on('click', function () {
	if ($('.VirAutoThumbnailDisplay').html() != undefined) {
		$('.VirAutoThumbnailDisplay').hide();
	}
	
    
    
	//affichage des infos:
	$('.VirAutoAttr[data-l1key=id]').value($(this).attr('data-vir_auto_id'));
	$('.VirAutoAttr[data-l1key=position]').value($(this).attr('data-vir_auto_position'));
	$('.VirAutoAttr[data-l1key=Title]').value($(this).attr('data-vir_auto_title'));
	$('.VirAutoAttr[data-l1key=Amount]').value($(this).attr('data-vir_auto_amount'));
	$('.VirAutoAttr[data-l1key=Reference]').value($(this).attr('data-vir_auto_reference'));
	$('.VirAutoAttr[data-l1key=StartDate]').value($(this).attr('data-vir_auto_startdate'));
	$('.VirAutoAttr[data-l1key=EndDate]').value($(this).attr('data-vir_auto_enddate'));
	$('.VirAutoAttr[data-l1key=CatId]').value($(this).attr('data-vir_auto_catid'));
	$('.VirAutoAttr[data-l1key=compteur_frequence]').value($(this).attr('data-vir_auto_compteur_frequence'));
	$('#selectBank').value($(this).attr('data-vir_auto_eqlogic_id'));
    
	var cat_img = {};
	var new_text= $(this).attr('data-vir_auto_cat_name');
	var new_text_upper= $(this).attr('data-vir_auto_cat_upper_level_name');

	if ($(this).attr('data-vir_auto_catid') != 0)
	{
		cat_img.tagTextColor = $(this).attr('data-vir_auto_cat_imgTatTextColor');
		cat_img.tagColor = $(this).attr('data-vir_auto_cat_imgTagColor');
		cat_img.icon = $(this).attr('data-vir_auto_cat_imgIcon');
	}
	else
	{
		cat_img.tagTextColor = "#FFF";
		cat_img.tagColor = "#c266c2";
		cat_img.icon = "<i class='icon plugin-comptes-billets1'><\/i>";
		
	}
	
	$('.image_cat_va').css('background-color',cat_img.tagColor);
	$('.image_cat_va').css('color',cat_img.tagTextColor);
	$('.image_cat_va').html(cat_img.icon);
	
	if (new_text != '') {
		$('#text_cat_upper').text(new_text_upper);
		$('#text_cat').text(new_text);
	}
	else {
		$('#text_cat_upper').text("");
		$('#text_cat').text("");
	}
	$('.VirAutoAttr[data-l1key=frequence]').value($(this).attr('data-vir_auto_frequence'));
	$('.VirAutoAttr[data-l1key=jour]').value($(this).attr('data-vir_auto_jour'));
	$('.VirAutoAttr[data-l1key=Comments]').value($(this).attr('data-vir_auto_comments'));
	
	$('.VirAuto').show();
	$('.li_VirAuto').removeClass('active');
	$(this).addClass('active');
	
});



jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $('.virAutoAction[data-action=save]').click();
});

$('.virAutoAction[data-action=remove]').on('click', function (event) {
	var vir_auto = $(this).closest('.VirAuto').getValues('.VirAutoAttr');
	vir_auto = vir_auto[0];
	$(this).closest('.li_VirAuto').remove();
	$.ajax({
		type: 'POST',
		url: 'plugins/comptes/core/ajax/comptes.ajax.php',
		data: {
			action: 'deleteVirAuto',
			id: vir_auto.id
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
			//$('#div_VirementAlert').showAlert({message: '{{Virement auto supprimé avec succès}}', level: 'success'});
			//Refresh du modal
			$('.bt_ManageVirementsAuto').attr('data-default-id',"");
			$('.bt_ManageVirementsAuto').click();	
		}
	});	
});

$('.virAutoAction[data-action=save]').on('click', function () {
	//Mise à jour des infos dans la base de donnée
	var vir_auto = $(this).closest('.VirAuto').getValues('.VirAutoAttr');
	vir_auto = vir_auto[0];
	var error_content = 0;
	var msg_error = "";
	if (vir_auto.Amount == "") {
		error_content = 1;
		msg_error += "{{La montant du virement automatique ne doit pas être vide}} ";
	}
	if (isNaN(vir_auto.Amount) == true) {
		error_content = 1;
		msg_error += "{{La montant du virement automatique doit être un chiffre}} ";
	}	
	if (vir_auto.frequence <= 0) {
		error_content = 1;
		msg_error += "{{La fréquence doit être positive}} ";
	}
	if (vir_auto.frequence != parseInt(vir_auto.frequence)) {
		error_content = 1;
		msg_error += "{{La fréquence doit être un nombre entier}} ";
	}	
	if (vir_auto.jour <= 0) {
		error_content = 1;
		msg_error += "{{Le jour doit être positif}} ";
	}
	if (vir_auto.jour != parseInt(vir_auto.jour)) {
		error_content = 1;
		msg_error += "{{Le jour doit être un nombre entier}} ";
	}
	if (vir_auto.jour > 31) {
		error_content = 1;
		msg_error += "{{Le jour doit être inférieur ou égal à 31}} ";
	}
	if (vir_auto.eqLogic_id == 0) {
		error_content = 1;
		msg_error += "{{Un compte bancaire doit être sélectionné}} ";
	}
	if (vir_auto.StartDate >= vir_auto.EndDate)
	{
		error_content = 1;
		msg_error += "{{La date de fin doit être supérieure à la date de début}} ";
	}
	if (vir_auto.StartDate == "") 
	{
		error_content = 1;
		msg_error += "{{Une date de début doit être indiquée}} ";
	}
	if (vir_auto.EndDate == "") 
	{
		error_content = 1;
		msg_error += "{{Une date de fin doit être indiquée}} ";
	}
	if (error_content == 0) {
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'updateVirAuto',
				event: json_encode(vir_auto)
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
				//Mise à jour des infos dans le menu: 
				$('.title_vir_auto[data-vir_auto_id='+vir_auto.id+']').text(vir_auto.Title);
				$('.label_cpt_obj[data-vir_auto_id='+vir_auto.id+']').text(vir_auto.EndDate);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_amount',vir_auto.Amount);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_title',vir_auto.Title);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_reference',vir_auto.Reference);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_startdate',vir_auto.StartDate);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_enddate',vir_auto.EndDate);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_frequence',vir_auto.frequence);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_jour',vir_auto.jour);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_comments',vir_auto.Comments);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_compteur_frequence',vir_auto.compteur_frequence);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_eqlogic_id',vir_auto.eqLogic_id);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_catid',vir_auto.CatId);
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_cat_img_src',$('#img_cat').attr('data-img_name'));
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_cat_name',$('#text_cat').text());
				$('.li_VirAuto[data-vir_auto_id='+vir_auto.id+']').attr('data-vir_auto_cat_upper_level_name',$('#text_cat_upper').text());
				//message save ok
				$('#div_VirementAlert').showAlert({message: '{{Virement auto sauvegardé avec succès}}', level: 'success'});
				
				setTimeout(function (){
					$('#div_VirementAlert').hide();
					},
					2000
				);
			}
		});
	} else {
		$('#div_VirementAlert').showAlert({message: msg_error, level: 'danger'});
		setTimeout(function (){
			$('#div_VirementAlert').hide();
			},
			3000
		);
	}
});

$('.virAutoAction[data-action=addVirAuto]').on('click', function () {
	$.ajax({
		type: 'POST',
		url: 'plugins/comptes/core/ajax/comptes.ajax.php',
		data: {
			action: 'newVirAuto',
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
			//Refresh du modal
			$('.bt_ManageVirementsAuto').attr('data-default-id',data.result.id);
			$('.bt_ManageVirementsAuto').click();	
		}
	});
});

$('body').delegate('.bt_sortable', 'mouseenter', function () {
	$("#ul_virAuto").sortable({
		axis: "y",
		cursor: "move",
		items: ".li_VirAuto",
		placeholder: "ui-state-highlight",
		tolerance: "intersect",
		forcePlaceholderSize: true,
		dropOnEmpty: true,
		stop: function (event, ui) {
			var virement_auto = [];
			$('#ul_virAuto .li_VirAuto').each(function () {
				virement_auto.push($(this).attr('data-vir_auto_id'));
			});
			
			virAutoSetOrder({
				virement_auto: virement_auto,
				error: function (error) {
					$('#div_CatAlert').showAlert({message: error.message, level: 'danger'});
				}
			});
			
		}
	});
	$("#ul_virAuto").sortable("enable");
});
$('body').delegate('.bt_sortable', 'mouseout', function () {
	$("#ul_virAuto").sortable("disable");
});
</script>