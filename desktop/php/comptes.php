<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('desktop', 'comptes', 'css', 'comptes'); 
$plugin = plugin::byId('comptes');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction ComptesColor" data-action="add"  >
                <i class="fas fa-plus-circle"></i>
                <br/>
                <span class="ComptesColor">{{Ajouter}}</span>
			</div>
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf" >
                <i class="fas fa-wrench"></i>
                <br/>
                <span>{{Configuration}}</span>
            </div>
        </div>
        <legend><i class="fas fa-table"></i>  {{Liste des comptes}}</legend>
        <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
        <div class="eqLogicThumbnailContainer">
        <?php
        if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#fcc505;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore créé de compte, cliquez sur le plus pour ajouter un compte.}}</span></center>";
        } else {
            foreach ($eqLogics as $eqLogic) {
                $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
                echo '<br>';
                echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                echo '</div>';
            }
        ?>
        </div>
        <?php } ?>
    </div>
	
    <div class="col-xs-12 eqLogic" style="display: none;">
        <div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
                <a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a>
                <a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
                <a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Compte}}</a></li>
            <!--
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
            -->
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
        <div role="tabpanel" class="tab-pane active" id="eqlogictab">
            <br/>
        <div class='row'>
			<div class="col-sm-6">
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas fa-tachometer-alt"></i> {{Général}}</legend>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Nom du compte}}</label>
							<div class="col-sm-6">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du compte}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" >{{Objet parent}}</label>
							<div class="col-sm-6">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (jeeObject::all() as $object) {
												echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"></label>
                            <div class="col-sm-6">
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                            </div>
                        </div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Historiser le solde du compte}}</label> 
							<div class="col-sm-6">
                                <input type="checkbox" class="eqLogicAttr" data-label-text="{{Historiser}}" data-l1key="configuration" data-l2key="Historiser" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Devise du compte}}</label> 
							<div class="col-sm-4">
								<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="currency" />
							</div>
						</div>
						<legend><i class="fas fa-check"></i> {{Pointage}} </legend>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Activation de l'option de pointage des opérations}}</label>
							<div class="col-sm-6">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activation}}" data-l1key="configuration" data-l2key="ActivationPointage" checked/> 
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Affichage des opérations non pointées}}</label>
							<div class="col-sm-6">
                                <input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="AffAPointer" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Affichage des opérations pointées}}</label>
							<div class="col-sm-6">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="AffPointees" checked/>
							</div>
						</div>
						<legend><i class="fas fa-cogs"></i>  {{Options}} </legend>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Activation de l'option d'identification du type de l'opération}}</label>
							<div class="col-sm-6">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activation}}" data-l1key="configuration" data-l2key="ActivationTypeOperation" checked/> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Activation de l'option d'utilisation d'une seule date d'opération au lieu d'une date d'opération et d'une date de valeur}}</label>
							<div class="col-sm-6">
								<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activation}}" data-l1key="configuration" data-l2key="ActivationDateValeur" /> 
							</div>
						</div>
					</fieldset> 
				</form>
			</div>
			<div class="col-sm-6">
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas jeedom2-money4"></i>{{Banque}}</legend>
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
								<a class="btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fas fa-flag"></i>{{Icone}}</a>
								<span class="eqLogicAttr" data-l1key="configuration" data-l2key="icon" style="margin-left : 50px;font-size : 5em;"></span>
							</div>
						</div>
					</fieldset> 
				</form>
			</div>
		</div>
        </div><!-- fin 1ère tab-->
        
        <div role="tabpanel" class="tab-pane" id="commandtab">	
            <br/>
            <!--<a class="btn btn-success btn-sm pull-right cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande}}</a><br/><br/>-->
            <table id="table_cmd" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>{{Nom}}</th>
                        <th>{{Action}}</th>
                        <th style="width:130px"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>


        </div><!-- fin 2eme tab--> 
        </div>    
    </div>
</div>

<?php 
include_file('desktop', 'comptes', 'js', 'comptes');
include_file('core', 'plugin.template', 'js');

?>