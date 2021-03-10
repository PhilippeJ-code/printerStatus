<?php
  if (!isConnect('admin')) 
  {
	 throw new Exception('{{401 - Accès non autorisé}}');
  }

  $plugin = plugin::byId('printerStatus');
  sendVarToJS('eqType', $plugin->getId());
  $eqLogics = eqLogic::byType($plugin->getId());

?>

<div class="row row-overflow">
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
    </div>
    <legend><i class="fas fa-table"></i> {{Mes équipements}}</legend>
	  <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
    <div class="eqLogicThumbnailContainer">
      <?php

        // Affiche la liste des équipements
        //
        foreach ($eqLogics as $eqLogic) 
        {
	        $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	        echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
	        echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
	        echo '<br>';
	        echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
	        echo '</div>';
        }
      ?>
    </div>
  </div>

  <div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
	   		<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#oidscartab" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i>{{Oids Infos}}</a></li>
      <li role="presentation"><a href="#oidstontab" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i>{{Oids Toners}}</a></li>
      <li role="presentation"><a href="#oidspagtab" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i>{{Oids Pages}}</a></li>
      <li role="presentation"><a href="#widgettab" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i>{{Widget}}</a></li>
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <br/>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-wrench"></i> {{Général}}</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" >{{Objet parent}}</label>
              <div class="col-sm-3">
                <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                  <option value="">{{Aucun}}</option>
                  <?php
                    foreach (jeeObject::all() as $object) 
                    {
	                    echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                    }
                  ?>
                </select>
              </div>
            </div>
	          <div class="form-group">
              <label class="col-sm-3 control-label">{{Catégorie}}</label>
              <div class="col-sm-9">
                <?php
                  foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) 
                  {
                    echo '<label class="checkbox-inline">';
                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                  }
                ?>
              </div>
            </div>
	          <div class="form-group">
		          <label class="col-sm-3 control-label"></label>
		          <div class="col-sm-9">
			          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
			          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
		          </div>
	          </div>

            <legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{Adresse IP de l'imprimante}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="adresse_ip"
                  placeholder="{{Adresse IP}}" />
              </div>
            </div>

          </fieldset>
        </form>
      </div>

      <div role="tabpanel" class="tab-pane" id="widgettab">
        <form class="form-horizontal">
          <fieldset>
            <br /><br />

            <div class="form-group">
              <label class="col-sm-3 control-label">{{Utiliser le widget du plugin}}</label>
              <div class="col-sm-3 form-check-input">
                <input type="checkbox" required class="eqLogicAttr" data-l1key="configuration"
                  data-l2key="isWidgetPlugin" checked /></label>
              </div>
            </div>

          </fieldset>
        </form>
      </div>

      <div role="tabpanel" class="tab-pane" id="oidscartab">
        <form class="form-horizontal">
          <fieldset>
            <br /><br />
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Nom système}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_system_name"
                  placeholder="{{OID Nom système}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Modèle}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_model"
                  placeholder="{{OID Modèle}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Numéro de série}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_serial"
                  placeholder="{{OID Série}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Nom d'hôte}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_hote"
                  placeholder="{{OID Nom d'hôte}}" />
              </div>
            </div>
            </fieldset>
        </form>
      </div>

      <div role="tabpanel" class="tab-pane" id="oidstontab">
        <form class="form-horizontal">
          <fieldset>
            <br /><br />
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner noir}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_noir"
                  placeholder="{{OID Niveau toner noir}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner jaune}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_jaune"
                  placeholder="{{OID Niveau toner jaune}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner magenta}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_magenta"
                  placeholder="{{OID Niveau toner magenta}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner cyan}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_cyan"
                  placeholder="{{OID Niveau toner cyan}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner noir max}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_noir_max"
                  placeholder="{{OID Niveau toner noir max}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner jaune max}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_jaune_max"
                  placeholder="{{OID Niveau toner jaune max}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner magenta max}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_magenta_max"
                  placeholder="{{OID Niveau toner magenta max}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau toner cyan max}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_cyan_max"
                  placeholder="{{OID Niveau toner cyan max}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Référence Toner noir}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_ref_noir"
                  placeholder="{{OID Référence Toner noir}}" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Référence Toner jaune}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_ref_jaune"
                  placeholder="{{OID Référence Toner jaune}}" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Référence Toner magenta}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_ref_magenta"
                  placeholder="{{OID Référence Toner magenta}}" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Référence Toner cyan}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_ref_cyan"
                  placeholder="{{OID Référence Toner cyan}}" />
              </div>
            </div>

            </fieldset>
        </form>
      </div>

      <div role="tabpanel" class="tab-pane" id="oidspagtab">
        <form class="form-horizontal">
          <fieldset>
            <br /><br />
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Pages couleur}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_pages_couleur"
                  placeholder="{{OID Pages couleur}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Pages monochrome}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_pages_monochrome"
                  placeholder="{{OID Pages monochrome}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Pages total}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_pages_total"
                  placeholder="{{OID Pages total}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau bac polyvalent}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_bac_polyvalent"
                  placeholder="{{OID Niveau bac polyvalent}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau bac cassette 1}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_bac_cassette_1"
                  placeholder="{{OID Niveau bac cassette 1}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{OID Niveau bac cassette 1 max}}</label>
              <div class="col-sm-9">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="oid_bac_cassette_1_max"
                  placeholder="{{OID Niveau bac cassette 1 max}}" />
              </div>
            </div>
            </fieldset>
        </form>
      </div>

      <div role="tabpanel" class="tab-pane" id="commandtab">
        <a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th>{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, nom_du_plugin) -->
<?php include_file('desktop', 'printerStatus', 'js', 'printerStatus');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>
