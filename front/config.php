<?php

include ('../../../inc/includes.php');

Html::header(PluginMeshcentralConfig::getTypeName(),
             $_SERVER['PHP_SELF'],
             "admin",
             "pluginmeshcentralconfig",
             "config");

//Search::show('PluginMeshcentralConfig');

Html::footer();
