<?php
include ("../../../inc/includes.php");

$plugin = new Plugin();

if (!$plugin->isInstalled('meshcentral') || !$plugin->isActivated('meshcentral')) {
   Html::displayNotFoundError();
}

Html::header(PluginMeshcentralConfig::getTypeName(),
             $_SERVER['PHP_SELF'],
             "admin",
             "pluginmeshcentralconfig",
	     "config");

$pfConfig = new PluginMeshcentralConfig();
Toolbox::logDebug("Opening mesh");

if (isset($_POST['update'])) {
    Toolbox::logDebug("MEESH UPTATING");
    $data = $_POST;
    unset($data['update']);
    unset($data['id']);
    unset($data['_glpi_csrf_token']);
    foreach ($data as $key => $value) {
        $pfConfig->updateValue($key, $value);
    }
    Html::back();
}

PluginMeshcentralConfig::displayMenu();

Html::footer();

//} else {
   //View is not granted.
//   Html::displayRightError();
//}
