<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$plan3dHeader = plan3dHeader::byId(init('plan3dHeader_id'));
if (!is_object($plan3dHeader)) {
	throw new Exception('Impossible de trouver le plan');
}
sendVarToJS('id', $plan3dHeader->getId());
sendVarToJS('plan3dHeader', utils::o2a($plan3dHeader));
?>
<div id="div_alertplan3dHeaderConfigure"></div>


<div id="div_plan3dHeaderConfigure">
    <form class="form-horizontal">
        <fieldset>
            <legend><i class="fa fa-cog"></i> {{Général}}
                <a class='btn btn-danger btn-xs pull-right cursor' style="color: white;" id='bt_removeConfigureplan3dHeader'><i class="fa fa-times"></i> {{Supprimer}}</a>
                <a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigureplan3dHeader'><i class="fa fa-check"></i> {{Sauvegarder}}</a>
            </legend>
            <input type="text"  class="plan3dHeaderAttr form-control" data-l1key="id" style="display: none;"/>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Nom}}</label>
                <div class="col-lg-2">
                    <input class="plan3dHeaderAttr form-control" data-l1key="name" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Code d'accès}}</label>
                <div class="col-lg-2">
                    <input type="password" class="plan3dHeaderAttr form-control" data-l1key="configuration" data-l2key="accessCode" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Icône}}</label>
                <div class="col-lg-2">
                    <div class="plan3dHeaderAttr" data-l1key="configuration" data-l2key="icon" ></div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                    <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir}}</a>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Model 3D}}</label>
                <div class="col-lg-8">
                  <span class="btn btn-default btn-file">
                    <i class="fa fa-cloud-upload"></i> {{Envoyer}}<input  id="bt_upload3dModel" type="file" name="file" style="display: inline-block;">
                </span>
            </div>
        </div>
    </div>
</fieldset>
</form>
</div>

<script>
    $('.plan3dHeaderAttr[data-l1key=configuration][data-l2key=icon]').on('dblclick',function(){
        $('.plan3dHeaderAttr[data-l1key=configuration][data-l2key=icon]').value('');
    });

     $('#bt_upload3dModel').fileupload({
        replaceFileInput: false,
        url: 'core/ajax/plan3d.ajax.php?action=uploadModel&id=' + id+'&jeedom_token='+JEEDOM_AJAX_TOKEN,
        dataType: 'json',
        done: function (e, data) {
            if (data.result.state != 'ok') {
                $('#div_alertplan3dHeaderConfigure').showAlert({message: data.result.result, level: 'danger'});
                return;
            }
             $('#div_alertplan3dHeaderConfigure').showAlert({message: '{{Chargement réussi merci de recharger la page pour voir le résultat}}', level: 'success'});
        }
    });

    $('#bt_chooseIcon').on('click', function () {
        chooseIcon(function (_icon) {
            $('.plan3dHeaderAttr[data-l1key=configuration][data-l2key=icon]').empty().append(_icon);
        });
    });

    $('#bt_saveConfigureplan3dHeader').on('click', function () {
      jeedom.plan3d.saveHeader({
        plan3dHeader: $('#div_plan3dHeaderConfigure').getValues('.plan3dHeaderAttr')[0],
        error: function (error) {
            $('#div_alertplan3dHeaderConfigure').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            window.location.reload();
        },
    });
  });

    $('#bt_removeConfigureplan3dHeader').on('click', function () {
      jeedom.plan3d.removeHeader({
        id: $('#div_plan3dHeaderConfigure').getValues('.plan3dHeaderAttr')[0].id,
        error: function (error) {
            $('#div_alertplan3dHeaderConfigure').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            window.location.reload();
        },
    });
  });

    if (isset(id) && id != '') {
     $('#div_plan3dHeaderConfigure').setValues(plan3dHeader, '.plan3dHeaderAttr');
 }
</script>