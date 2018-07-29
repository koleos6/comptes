<?php
if (!isConnect()) {
	throw new Exception(__('401 - Accès non autorisé'));
}

$plugin = plugin::byId('comptes');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <!--
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="addCompte"><i class="fa fa-plus-circle"></i> {{Ajouter un compte}}</a>-->
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
	
	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                  <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
              </div>
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 6em;color:#fcc505;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;;color:#fcc505"><center>Ajouter</center></span>
			</div>
        </div>
        <legend><i class="fa fa-table"></i>  {{Liste des comptes}}</legend>
        <?php
        if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#fcc505;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore créé de compte, cliquez sur le plus pour ajouter un compte.}}</span></center>";
        } else {
            ?>
            <div class="eqLogicThumbnailContainer">
            
                <?php
                foreach ($eqLogics as $eqLogic) {
					
					$opacity = '';
                    if ($eqLogic->getIsEnable() != 1) {
                        $opacity = 'opacity:0.3;';
                    }
					
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px; ' . $opacity . '" >';
                    echo "<center>";
                    echo '<img src="plugins/comptes/plugin_info/comptes_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
    </div>
	
    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 50px;display: none;">
        <div class='row'>
			<div class="col-sm-6">
				<form class="form-horizontal">
					<fieldset>
						<legend> <i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}} <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Nom du compte}}</label>
							<div class="col-sm-6">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du compte}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Objet parent}}</label>
							<div class="col-sm-6">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (object::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
                        <div class="form-group">
                          <label class="col-sm-6 control-label">{{Activer}}</label>
                          <div class="col-sm-6">
                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/></label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-6 control-label">{{Visible}}</label>
                          <div class="col-sm-6">
                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/></label>
                          </div>
                        </div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Historiser le solde du compte}}</label> 
							<div class="col-sm-3">
							<input type="checkbox" class="eqLogicAttr" data-label-text="{{Historiser}}" data-l1key="configuration" data-l2key="Historiser" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Devise du compte}}</label> 
							<div class="col-sm-3">
								<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="currency" />
							</div>
						</div>
						<legend>  {{Pointage}} </legend>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Activation de l'option de pointage des opérations}}</label>
							<div class="col-sm-3">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activation}}" data-l1key="configuration" data-l2key="ActivationPointage" checked/> 
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Affichage des opérations non pointées}}</label>
							<div class="col-sm-3">
							<!--
								<div class="btn-group" data-toggle="buttons_a_pointer" >
									<button class="btn btn-sm btn-danger" type="button" id="bsIsEnableYes_Apointer" autocomplete="off">{{Oui}}</button>
									<button class="btn btn-sm" type="button" id="bsIsEnableNo_Apointer" autocomplete="off">{{Non}}</button>
								</div>
								<input id="bt_AffAPointer" hidden type="checkbox"  class="eqLogicAttr"  data-l1key="configuration" data-l2key="AffAPointer" />
							-->
							<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="AffAPointer" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Affichage des opérations pointées}}</label>
							<div class="col-sm-3">
							<!--
								<div class="btn-group" data-toggle="buttons_pointees">
									<button class="btn btn-sm" type="button" id="bsIsEnableYes_Pointer" autocomplete="off">{{Oui}}</button>
									<button class="btn btn-sm  btn-success" type="button" id="bsIsEnableNo_Pointer" autocomplete="off">{{Non}}</button>
								</div>
								<input id="bt_AffPointees" hidden type="checkbox"  class="eqLogicAttr" data-l1key="configuration" data-l2key="AffPointees"/>
							-->
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="AffPointees" checked/>
							</div>
						</div>
						<legend>  {{Options}} </legend>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Activation de l'option d'identification du type de l'opération}}</label>
							<div class="col-sm-3">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activation}}" data-l1key="configuration" data-l2key="ActivationTypeOperation" checked/> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Activation de l'option d'utilisation d'une seule date d'opération au lieu d'une date d'opération et d'une date de valeur}}</label>
							<div class="col-sm-3">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activation}}" data-l1key="configuration" data-l2key="ActivationDateValeur" /> 
							</div>
						</div>
					</fieldset> 
				</form>
			</div>
			<div class="col-sm-6">
				<form class="form-horizontal">
					<fieldset>
						<legend>{{Banque}}</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label" >{{Nom de la banque}}</label>
							<div class="col-sm-3">
								<select id="sel_banque" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="banque">
									<option value="0">{{Aucune}}</option>
									<?php
									foreach (comptes_banques::all() as $bank) {
										echo '<option class="opt_bk" value="' . $bank->getId() . '" data-logo="'. $bank->getLogo_name() .'" data-bank="' . $bank->getId() . '">' . $bank->getName() . '</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-3">
								<img id="logo_banque" />
							</div>
							
						</div>
						<div class="form-group">
							<div class="col-sm-3">
								<a class="btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i>{{Icone}}</a>
								<span class="eqLogicAttr" data-l1key="configuration" data-l2key="icon" style="margin-left : 50px;font-size : 5em;"></span>
							</div>
						</div>
					</fieldset> 
				</form>
			</div>
		</div>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>

<?php 
include_file('desktop', 'comptes', 'js', 'comptes');
include_file('core', 'plugin.template', 'js');

?>