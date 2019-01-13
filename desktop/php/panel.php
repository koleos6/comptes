<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}


include_file('3rdparty', 'bootstrap-select/dist/css/bootstrap-select', 'css', 'comptes'); 
include_file('3rdparty', 'bootstrap-select/dist/css/bootstrap-select.min', 'css', 'comptes'); 
include_file('3rdparty', 'datetimepicker/jquery.datetimepicker', 'css', 'comptes');
include_file('desktop', 'panel', 'css', 'comptes'); 
include_file('3rdparty', 'c3/c3', 'css', 'comptes'); 
$eqLogics = eqLogic::byType('comptes');
sendVarToJS('eqType', 'comptes');

$allCats = comptes_categories::all();



?>

<div id="md_modalComptes1"></div>
<div id="md_modalComptes2"></div>
<div id="md_modalComptes3"></div>
<div id="md_modalComptes4"></div>

<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" id="bt_displayComptes"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>

<div class="row row-overflow" id="div_comptes" >
	<!-- Partie menu gauche -->
	<div class="col-lg-2" id="sd_ComptesList" style="z-index:999">
        <div class="bs-sidebar">
            <ul id="ul_comptes" class="nav nav-list ">
                
				<legend><center>{{Mes Comptes Actifs}}</center></legend>
                
                <!--<table class="table-hover comptes_list">-->
                
				<?php
                foreach ($eqLogics as $eqLogic) {
					if ($eqLogic->getIsEnable()) {
						$object = $eqLogic->getObject();
						if (is_object($object)) {
							if ($object->getDisplay('tagColor') != '') {
								$color = $object->getDisplay('tagColor');
								$color_obj_text = $object->getDisplay('tagTextColor', 'white');
								$name = $object->getName();
							} 
						}
						$bt_APointer = $eqLogic->getConfiguration("AffAPointer");
						$bt_Pointer = $eqLogic->getConfiguration("AffPointees");
						$devise = $eqLogic->getConfiguration("currency");
						$OptionPointage = $eqLogic->getConfiguration("ActivationPointage");
						$OptionType = $eqLogic->getConfiguration("ActivationTypeOperation");
                        $OptionDateUnique = $eqLogic->getConfiguration("ActivationDateValeur");
                        
						$id = $eqLogic->getConfiguration("banque");
						if ($id != 0) {
							$bank = comptes_banques::byId($id);
							$minilogo = $bank->getLogo_mini_name();
						}
                        //echo '<tr>';
						echo '<li class="cursor compte_menu_item itemAccount" data-eqLogic_id="' . $eqLogic->getId() . '" data-soldereel="'. $eqLogic->computeSolde() .'" data-apointer="'. $eqLogic->computeAPointer() .'" data-soldefinmois="'. $eqLogic->computeSoldeFinDeMois() .'" data-color-obj="' . $color .'" data-bt_APointer="' . $bt_APointer .'" data-bt_Pointer="' . $bt_Pointer .'"data-color-obj-text="' .$color_obj_text .'" data-name="' . $name .'" data-devise="' . $devise .'" data-minilogo="' . $minilogo .'" data-optPointage="' . $OptionPointage .'" data-optType="' . $OptionType .'" data-optDateUnique="' . $OptionDateUnique . '">';
						//echo '<li class="cursor li_eqLogic compte_menu_item" data-eqLogic_id="' . $eqLogic->getId() . '">';
						echo '<a>';
						echo '<span class="label_cpt_obj" style="text-shadow : none;background-color:'. $color .';color:' . $color_obj_text .';">';
						
						
						echo '<img src="plugins/comptes/images/banques/'. $minilogo . '" /> ';
						echo $name ;
						echo '</span>';
						echo '<span class="label_cpt_obj_right"> ';
						echo $eqLogic->getName();
						echo '</span>';
		
						echo '</a>';
						echo '</li>';
                        //echo '</tr>';
					}
				}
                ?>
                
               <!--</table>-->
                

            </ul>
			<br />
			<center>
			<legend></legend>
			<!--
			
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			<fieldset>
			 <div class="form-group form-group-sm">
									<span id="bt_downloadOperations"  class="form-control btn-primary fileinput-button">
                                        <i class="fa fa-download"></i>
                                        <input class="form-control" type="file" id="bt_downloadOperationsInput" name="CsvOps" data-url="plugins/widget/core/ajax/widget.ajax.php?action=fontUpload"/>
                                    </span>
			 </div>
			</fieldset>        
			</form>
			-->
			<!--
			margin-bottom : 10px;
			margin-left : 10px;
			-->
			<div class="col-lg-2 col-md-3 col-sm-4 eqLogicThumbnailContainer" style="width : 500px;">
			<div class="cursor bt_ManageCat" style="margin-left : -15px;margin-top:-15px;height : 60px;padding : 5px;border-radius: 2px;width : 60px;" >
				<center>
					<i class="plugin-comptes-dircats" style="font-size : 3em;color:#C266C2;" data-toggle="tooltip" title="{{Gestion des catégories}}"></i>
				</center>
			</div>
			<div class="cursor bt_ManageVirementsAuto" style="margin-top:-15px;height : 60px;padding : 5px;border-radius: 2px;width : 60px;" >
				<center>
					<i class="plugin-comptes-vir-auto" style="font-size : 3em;color:#767676;" data-toggle="tooltip" title="{{Gestion des virements automatiques}}"></i>
				</center>
			</div>
			<div class="cursor bt_VirBank"  style="margin-top:-15px;height : 60px;padding : 5px;border-radius: 2px;width : 60px;" >
				<center>
					<i class="plugin-comptes-double-fleche" style="font-size : 3em;color:#3399FF;" data-toggle="tooltip" title="{{Virement Banque à Banque}}"></i>
				</center>
			</div>
			<div class="cursor bt_RefreshGraph" style="margin-top:-15px;height : 60px;padding : 5px;border-radius: 2px;width : 60px;" >
				<center>
					<i class="plugin-comptes-stats1" style="font-size : 3em;color:#767676;" data-toggle="tooltip" title="{{Forcer la mise à jour des historiques}}"></i>
				</center>
			</div>
            <!--
			<div class="cursor bt_budget" style="margin-top:-15px;height : 60px;padding : 5px;border-radius: 2px;width : 60px;" >
				<center>
					<i class="plugin-comptes-stats2" style="font-size : 3em;color:#5CD65C;" data-toggle="tooltip" title="{{Gestion de budgets}}"></i>
				</center>
			</div>
            -->
			<!--

			-->
			</div>
			</center>
		<br />
		<!--
			<a id="bt_testCron" class="btn btn-primary" data-default-id="" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa techno-fleches"></i> {{Test}}</a>
			<br />-->
			<legend></legend>
			<br />
			<!--
			<ul id="ul_infos_comptes" class="nav nav-list " style="display:none">
				<li class="nav-header"><legend><center>{{Solde Réel}}<span id="solde_reel"style="margin-left:30px"></span> <?php echo config::byKey('currency', 'comptes', '€') ?></center></legend></li>
				<li class="nav-header"><legend><center>{{A Pointer}}<span id="a_pointer"style="margin-left:30px"></span> <?php echo config::byKey('currency', 'comptes', '€') ?></center></legend></li>
				<li class="nav-header"><legend><center>{{Solde fin du mois}}<span id="solde_fin_mois"style="margin-left:30px"></span> <?php echo config::byKey('currency', 'comptes', '€') ?></center></legend></li>
			</ul>
			-->
			<!--
			<ul class="btn-group" data-toggle="buttons_a_pointer" style="display:none">
                <button class="btn btn-sm btn-danger" type="button" id="bsIsEnableYes_Apointer" autocomplete="off">{{Oui}}</button>
                <button class="btn btn-sm" type="button" id="bsIsEnableNo_Apointer" autocomplete="off">{{Non}}</button>
            </ul>
			<ul class="btn-group" data-toggle="buttons_pointees" style="display:none">
                <button class="btn btn-sm" type="button" id="bsIsEnableYes_Pointer" autocomplete="off">{{Oui}}</button>
                <button class="btn btn-sm  btn-success" type="button" id="bsIsEnableNo_Pointer" autocomplete="off">{{Non}}</button>
            </ul>
			-->
			</div>
    </div>
	<!-- Partie Page d'acceuil -->
	<div class="col-lg-10" id="comptes_msg_debut" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor bt_ManageCat" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="plugin-comptes-dircats" style="font-size : 5em;color:#C266C2;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: normal;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Catégories}}</center></span>
			</div>
			<div class="cursor bt_ManageVirementsAuto" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="plugin-comptes-vir-auto" style="font-size : 5em;color:#767676;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: normal;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Virements automatiques}}</center></span>
			</div>
			<div class="cursor bt_VirBank" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="plugin-comptes-double-fleche" style="font-size : 5em;color:#3399FF;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: normal;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Virement Banque à Banque}}</center></span>
			</div>
			<div class="cursor bt_RefreshGraph"   style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="plugin-comptes-stats1" style="font-size : 5em;color:#767676;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: normal;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Forcer la mise à jour des historiques}}</center></span>
			</div>
            <!--
			<div class="cursor bt_budget" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="plugin-comptes-stats2" style="font-size : 5em;color:#5CD65C;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: normal;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Budget}}</center></span>
			</div>
            -->
		</div>
		<legend><i class="fa fa-table"></i>  {{Mes Comptes Actifs}}</legend>
		<div class="eqLogicThumbnailContainer">
		<?php
			foreach ($eqLogics as $eqLogic) {
				if ($eqLogic->getIsEnable()) {
						$object = $eqLogic->getObject();
						$color = "#767676";
						if (is_object($object)) {
							if ($object->getDisplay('tagColor') != '') {
								$color = $object->getDisplay('tagColor');
							} 
						}
	  echo '<div class=" cursor active_account" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	  
	  //echo '<div class="eqLogicDisplayCard cursor itemAccount" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	  echo "<center>";
	/*  $id = $eqLogic->getConfiguration("banque");
	if ($id != 0) {
		$bank = comptes_banques::byId($id);
		echo '<img src="plugins/comptes/images/banques/'. $bank->getLogo_mini_name() . '" style=""/><br /> ';
	}
	*/
	$conf_icon = $eqLogic->getConfiguration('icon');
	if ($conf_icon) {
		echo '<span style="font-size : 5em;color:'.$color.';">';
		echo $conf_icon;
		echo '</span>';
	}
	else {
		echo '<span style="font-size : 5em;color:'.$color.';">';
		echo '<i class="plugin-comptes-money-pig"></i>';
		echo '</span>';
	}
	
	
	echo "</center>";
	echo '<span style="font-size : 1.1em; top : 15px;word-break: normal;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
	}
}
?>
		
		</div>
    </div>
    
    
	<!-- Partie Gestion des opérations d'un compte -->
	<div  class="col-lg-9" id="comptes_operations" style="padding-right:25px;border-left: solid 1px #EEE; padding-left: 25px;display:none;" data-eqLogic_id="" data-last_id="" data-devise="" data-opidforcatsel="" data-catidforcatselfocus="" data-mode=0 data-filterCatId=0 data-search="">
		<legend class="cpt_legend">
		<div class="HeaderContainer">
			<div class="CptTitle">
			
			<span  class="label_cpt_obj2" ><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> <span id="cpt_obj"  ></span> - <span id="cpt_title"></span>&nbsp;</span>
			<div style="float:right" ><i class="fa fa-search bt_search cursor"></i>&nbsp;<i class="fa fa-filter bt_filterCat cursor"></i>&nbsp;<i class="fas fa-cogs bt_configurationBank cursor" data-eqLogic_id=""></i>&nbsp;<img id="cpt_bank" /></div>
			</div>
			<div>
                
				<div class = "CptNewOp cursor tooltips" title="{{Ajouter une opération}}">
					<i class="fa fa-plus-circle " ></i>
				</div>
				<div class= "CptNewOpModal" style="display:none"> 
					<form class="form-horizontal" style="margin-top:10px;">
						 <div id="NewOpDate" class="form-group form-group-sm">
							<label class="col-md-5 control-label">{{Date de l'opération}}</label>
							<div class="col-md-7">
								<input type="text" class="opAttr dtimepicker" data-l1key="operationDate" value="<?php echo date("Y-m-d", strtotime("now")); ?>" />
							</div>
						 </div>
						 <div id="NewOpDateValeur" class="form-group form-group-sm">
							<label id="NewOpDateValeurLabel1" class="col-md-5 control-label">{{Date de valeur}}</label>
                            <label id="NewOpDateValeurLabel2" class="col-md-5 control-label">{{Date}}</label>
							<div class="col-md-7">
								<input type="text" class="opAttr dtimepicker" data-l1key="CheckedOn" value="<?php echo date("Y-m-d", strtotime("+1 day")); ?>" />
							</div>
						 </div>
						 <div class="form-group form-group-sm">
							<label class="col-md-5 control-label">{{Catégorie}}</label>
							<div class="col-md-7" style="height:30px;font-size: 14px;">
								<input type="text" class="opAttr form-control" data-l1key="CatId" data-op_id="0" style="display: none;" value="0" />	
								<div style="float:left;margin-left:5px">
									<div class="image_cat_new" data-op_id="0" style="font-size : 2em;color:#FFF;border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:#c266c2;" ><i class="icon plugin-comptes-billets1"></i></div>
								</div>
								<div id="text_cat_upper" style="float:left;margin-top:8px;margin-left:5px"></div><div class="opAffAttr" data-l1key="CatId" data-op_id="0" style="float:left;margin-top:8px;margin-left:5px">{{Aucune}}</div>
						
							</div>
						 </div>
						 <div class="form-group form-group-sm">
							<label class="col-md-5 control-label">{{Nom de l'opération}}</label>
							<div class="col-md-7">
								<input type="text" class="opAttr " data-l1key="BankOperation" />
							</div>
						 </div>
						 <div id="NewOpOptionType" class="form-group form-group-sm">
							<label class="col-md-5 control-label">{{Type}}</label>
							<div class="col-md-7">
								<select class="opAttr selectpicker" data-style="btn-primary" data-l1key="Type" data-width="65%">
									<option value=0 >{{Aucun}}</option>
									<option value=1 data-icon="fa fa-credit-card">{{Carte}}</option>
									<option value=2 data-icon="fa fa-money">{{Chèque}}</option>
									<option value=3 data-icon="fa techno-fleches">{{Virement}}</option>
								</select>
							</div>
						 </div>

                        <div id="NewOpOptionPointage" class="form-group form-group-sm">
							<label class="col-md-5 control-label">{{Opération pointée ou non ?}}</label>
							<div class="col-md-7">
								<input type="checkbox" class="opAttr" data-label-text="{{Pointée?}}" data-l1key="Checked" checked/>
							</div>
						 </div>

						 <div class="form-group form-group-sm">
							<label class="col-md-5 control-label">{{Montant}}</label>
							<div class="col-md-7">
								<input type="text" class="opAttr EnterAvailable" data-l1key="Amount" /> <span class="affDevise" style="color:#fff"></span>
							</div>
						 </div>
						 <div class="form-group form-group-sm">
							<div class="col-lg-9 control-label">
								<a class="btn btn-primary add_op"><i class="fa fa-plus-circle"></i> {{Ajouter l'opération}}</a>
							</div>
						</div>
						<input type="text" class="opAttr " data-l1key="hide" style="display: none;" value ="0" />
						<input type="text" class="opAttr " data-l1key="id" style="display: none;" value="0" />
						<input type="text" class="opAttr bankid" data-l1key="eqLogic_id" style="display: none;" />
					</form>
				</div>
				
                <div class="CptSearchModal" style="display:none">
                    <span class="label_cpt_obj2" > 
                    <div class="col-md-7" style="margin-top:15px"> <input class="form-control input-sm searchField" placeholder="{{Rechercher}}" ></div>
                    <div class="col-md-1"><i class="fa fa-search bt_LaunchSearch cursor"></i></div>
                    </span>
                </div>
			</div>
		</div>

		</legend>
		<div class="row">
		
			<div id='div_eventOpAlert' style="display: none;"></div>
			
		<!--
			<div class="opContainer" class="">
			</div>
        -->
			<table id="table_op" class="">
                
                <tbody>

                </tbody>
            </table>
		
		</div>
	</div>
	
	
<!-- Partie droite stats -->
	<div  class="col-lg-3" id="comptes_stats" style="padding-right:25px;border-left: solid 1px #EEE; padding-left: 25px;display:none;">
		<center>
		<span class="badge tooltips" title="{{Solde Réel}}" style="background-color : #fcc505;font-size: 30px;min-width:180px">
			<span id="solde_reel" ></span> <span class="affDevise" ></span>			
		</span>
        <div id="FinDeMoisAffichage">
            <span class="badge tooltips" title="{{Solde à la fin du mois}}" style="background-color : #fcc505;"> 
                Fin de mois: <span id="solde_fin_mois"></span> <span class="affDevise" ></span>
            </span> 
        </div>
		<div><i id="refreshAmounts" class="fa fa-refresh"> </i> </div>
		</center>
		
		<br />
		<br />
		<center>
		<legend>{{Graphiques}}</legend>
			<a class="move_date_left"><i class="fa fa-chevron-left"></i></a>
			<span id="chart_title" data-date-pie-chart="<?php echo date('Y-m-01', strtotime('now'))?>"></span>
			<a class="move_date_right" style="display:none"><i class="fa fa-chevron-right"></i></a>
		</center>
		<center><div id="chart_comptes_1"></div></center>
		<center><div id="chart_comptes_2"></div></center>
	</div>
<!-- Partie droite : selection des catégories -cas modification -->

<div class= "CptNewOpModalCat col-lg-3" style="padding-right:25px;border-left: solid 1px #EEE; padding-left: 25px;height:30px;font-size: 14px;display:none;"> 
    <?php

        ModalCatRight($allCats, 0); //0= fonctin de choix de catégorie pour modification 1 = pour filtres
	?>
</div>

<!-- Partie droite : selection des catégories -cas filtres -->

<div class= "CptFilterModalCat col-lg-3" style="padding-right:25px;border-left: solid 1px #EEE; padding-left: 25px;height:30px;font-size: 14px;display:none;"> 
    <?php

        ModalCatRight($allCats, 1); //0= fonctin de choix de catégorie pour modification 1 = pour filtres
	?>
</div>
	
	<!-- Partie Gestion des catégories -->
	<div  class="col-lg-10" id="comptes_categories" style="padding-right:25px;border-left: solid 1px #EEE; padding-left: 25px;display:none;">
	<form class="form-horizontal">
        <fieldset>
			<legend>
			<center>
			<i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Gestion des catégories}}
			</center>
			</legend>
		
			<table id="ul_Gestcat" class="table table-bordered table-hover table_cat table-bordered-comptes">
			<thead>
				<tr class="bg-primary">
					<th style="width: 60px;">{{Icone}}</th>
					<th style="width: 55px;">{{Couleur fond}}</th>
					<th style="width: 55px;">{{Couleur icone}}</th>
					<th style="width: 55px;"></th>
					<th>{{Nom de la catégorie}}</th>
					<th colspan=3 ><center>{{Niveau}}</center></th>
					<th style="width: 50px;"><center>{{Action}}</center></th>
				</tr>
			</thead>
			<tbody>
				<tr class="li_cat info" data-cat_id="0">
					<td>
					<!--
						<input type="text" id="img_new" class="catAttr input-comptes-new " data-l1key="image" />
					-->
					<a class="catAction btn btn-default btn-sm btn-cat" data-cat_id="0"><i class="fa fa-flag"></i> {{Choisir}}</a>
					</td>
					<td><input type="color" data-cat_id="0" class="catAttr form-control" data-l1key="image" data-l2key="tagColor" value="#c266c2" /></td>
					<td><input type="color" data-cat_id="0" class="catAttr form-control" data-l1key="image" data-l2key="tagTextColor" value="#ffffff" /></td>
					<td>
					<div class="image_cat"  data-cat_id="0" style="font-size : 2em;color:#ffffff;border: 1px solid #c266c2;border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:#c266c2;" ></div>
					<input type="hidden" data-cat_id="0" class="catAttr form-control" data-l1key="image" data-l2key="icon" value=""/></td>
					<td>
						<input type="text" id="name_new" class="catAttr input-comptes-new " data-l1key="name" />
					</td>
					<td colspan=3></td>
					<td>
						<center>
						<input type="text" class="catAttr form-control " data-l1key="id" style="display: none;" value="0" />
						<a class="btn btn-primary add_cat" ><i class="fa fa-plus"></i></a>
						</center>
					</td>
				</tr>
				<?php
				$allCats = comptes_categories::all();
				foreach ($allCats as $cat) {
                    if ($cat->getId()>0) {
                        $pn = $cat->getLevel();
                        
                        echo '<tr class="li_cat bt_sortable" data-cat_id="' . $cat->getId() .'">'
                        .'<td>'
                        //.'<input type="text" class="catAttr input-comptes" data-l1key="image" value="'
                        //.$cat->getImage()
                        //.'"/>'
                        .'<a class="catAction btn btn-default btn-sm btn-cat" data-cat_id="' . $cat->getId() .'" ><i class="fa fa-flag"></i> {{Choisir}}</a>'
                        .'</td><td>'
                        .'<input type="color" data-cat_id="' . $cat->getId() .'" class="catAttr form-control" data-l1key="image" data-l2key="tagColor" value="'.$cat->getImage('tagColor').'" /></td><td>';
                        //echo '<img  src="plugins/comptes/images/categories/' . $cat->getImage() . '" height="40" width="40" data-cat_id="' . $cat->getId() .'" ';
                        /*if ($cat->getImage() == '') {
                            //echo 'style="display: none;"';
                        }*/
                        
                        //echo '/>';
                        echo '<input type="color" data-cat_id="' . $cat->getId() .'" class="catAttr form-control" data-l1key="image" data-l2key="tagTextColor" value="'.$cat->getImage('tagTextColor').'"/></td>'
                        .'<td>'
                        .'<div class="image_cat"  data-cat_id="' . $cat->getId() .'" style="font-size : 2em;color:'.$cat->getImage('tagTextColor')
                        .';border: 1px solid '.$cat->getImage('tagColor') . ';border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:'.$cat->getImage('tagColor'). ';" >'.$cat->getImage('icon').'</div>'
                        .'<input type="hidden" data-cat_id="' . $cat->getId() .'" class="catAttr form-control" data-l1key="image" data-l2key="icon" value="'.$cat->getImage('icon').'"/>'
                        .'</td>'
                        
                        .'<td>';
                        
                        
                        $width=0;
                        for ($i=0;$i<$pn;$i++) {
                            //echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                            //echo $i;
                            $width+=30;
                        }
                        
                        
                        
                        echo '<div style="width:Auto;padding-left:';
                        echo $width;
                        echo 'px;"><input type="text" class="catAttr input-comptes" data-l1key="name" value="';
                        echo $cat->getName();
                        echo '"/></div>'
                        .'</div>'
                        .'</td>'
                        .'<td style="width: 50px;" class="move_left" data-cat_id="';
                        echo $cat->getId();
                        echo '">';
                        if ($pn) {
                            echo '<a class="btn btn-success move_cat_up"><i class="fa fa-chevron-left"></i></a>';
                        
                        } else {
                            echo '<a class="btn btn-success move_cat_up" style="display:none"><i class="fa fa-chevron-left"></i></a>';
                        }
                        echo '</td><td class="level_aff"  style="width: 20px;">';
                        echo $cat->getLevel();
                        echo '</td><td style="width: 50px;">';
                        //if ($cat->getPosition() > 1) {
                            echo '<a class="btn btn-success move_cat_down"><i class="fa fa-chevron-right"></i></a></td>';
                        //}
                        echo '<td>';
                        echo '<a class="btn btn-danger supp_cat"><i class="fa fa-trash-o"></i></a>';
                        
                        echo '<input type="text" class="catAttr input-comptes" data-l1key="id" style="display: none;" value="';
                        echo $cat->getId();
                        echo '" />'
                        . '<input type="text" class="catAttr input-comptes" data-l1key="level" data-cat_id="';
                        echo $cat->getId();
                        echo '" style="display: none;" value="';
                        echo $pn;
                        echo '" />'
                        .'</td>'
                        .'</tr>'; 
                    }
				}
				?>
			</table>
			</tbody>
		</fieldset>
	</form>
	</div>
	
	
	
</div>



<?php 


function ModalCatRight($allCats, $filter=0) {//0= fonctin de choix de catégorie pour modification 1 = pour filtres

    if ($filter)
        $classinfo= 'filter_cat';
    else 
        $classinfo= 'li_cat';
    
    echo '<ul id="ul_cat" class="nav nav-list bs-sidenav">';
	echo '<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>';
       
	
	
	$i=0;
	foreach ($allCats as $cat) {
		$margin = 25 * $cat->getLevel();
		//Search upper level cat if exists: 
		$cat_i=$i;
		$cat_level=$cat->getLevel();
		$cat_name=$cat->getName();
		$cat_upper_level_name=$cat->getName();
		$stop=0;
		
		if (($cat->getId() != '') && ($cat_level > 0)) {
			for ($j=$cat_i; ($j >= 0) && ($stop == 0); $j--) {
				if ($allCats[$j]->getLevel() == ($cat_level - 1))  {
					$cat_upper_level_name = $allCats[$j]->getName();
					$cat_upper_level_name = $cat_upper_level_name . ' -> ';
					$stop = 1;
				}
			}
							
		}
		if (($cat->getId() != '') && ($cat_level == 0)) {
			$cat_upper_level_name =  "";
		
		}
		echo '<li style="height:60px;" class="cursor '. $classinfo . '" data-cat_id="' . $cat->getId() . '" data-cat_name_upper="' . $cat_upper_level_name . '" data-cat_name="' . $cat_name . '" data-cat_imgBackgroundcolor="' . $cat->getImage("tagColor") . '" data-cat_imgColor="' . $cat->getImage("tagTextColor") . '" data-cat_imgIcon="' . $cat->getImage("icon") . '">'
		. '<a style="position:relative;left:' . $margin . 'px;">';
		echo '<div class="image_cat" style="font-size : 2em;color:' . $cat->getImage("tagTextColor")
		. ';border: 1px solid ' . $cat->getImage("tagColor")	. ';border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:' . $cat->getImage("tagColor") . ';" >' 
		. $cat->getImage("icon") . '</div>';
		echo '<div style="float:left;margin-left:50px;margin-top:-30px">';
		echo $cat->getName();
		echo '</div>';
		echo '</a>'
		. '</li>';
		$i++;
	}
    
    echo '</ul>';
}

include_file('3rdparty', 'd3-master/d3', 'js', 'comptes'); 
include_file('3rdparty', 'd3-master/d3.min', 'js', 'comptes'); 
include_file('3rdparty', 'd3-master/d3pie.min', 'js', 'comptes'); 
include_file('3rdparty', 'c3/c3.min', 'js', 'comptes'); 
include_file('3rdparty', 'bootstrap-select/dist/js/bootstrap-select', 'js', 'comptes'); 
include_file('3rdparty', 'rightClick/jquery.rightClick', 'js', 'comptes'); 
include_file('3rdparty', 'bootstrap-select/dist/js/bootstrap-select.min', 'js', 'comptes'); 
include_file('3rdparty', 'datetimepicker/jquery.datetimepicker', 'js', 'comptes'); 
include_file('desktop', 'panel', 'js', 'comptes');
include_file('core', 'plugin.template', 'js');
//include_file('3rdparty', 'jquery.fileupload/jquery.fileupload', 'js');

?>