<?php

// forbid direct calls of this file
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginMeshcentralConfig extends CommonDBTM {

   public $dohistory = true;

   /**
    * Check if can view item
    *
    * @return boolean
    */
    public static function canView()
    {
        $can_display = false;
        /*$profile = new PluginGlpiinventoryProfile();

        foreach ($profile->getAllRights() as $right) {
            if (Session::haveRight($right['field'], READ)) {
                $can_display = true;
                break;
            }
	}*/
        $can_display = true;
        return $can_display;
    }

   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   public function defineTabs($options = []){
      $ong = array();

      $this->addDefaultFormTab(__CLASS__, $ong, $options)
         ->addStandardTab('Notepad', $ong, $options)
         ->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   /**
    * Get Tab Name used for itemtype
    *
    * NB : Only called for existing object
    *      Must check right on what will be displayed + template
    *
    * @since 0.83
    *
    * @param CommonGLPI $item         Item on which the tab need to be displayed
    * @param boolean    $withtemplate is a template object ? (default 0)
    *
    *  @return string tab name
   **/
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      switch ($item->getType()) {
         case "Config":
            return self::createTabEntry(self::getTypeName());
      }
      return '';
   }

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
    public static function getTypeName($nb = 0)
    {

        return __('General setup','meshcentral');
    }

   /**
    * Define menu name
    */
   static function getMenuName($nb = 0) {
      // call class label
      return self::getTypeName($nb);
   }

   /**
    * Define additionnal links used in breacrumbs and sub-menu
    */
   static function getMenuContent() {
      $title  = self::getMenuName(2);
      $search = self::getSearchURL(false);
      $form   = self::getFormURL(false);

      // define base menu
      $front_mesh = Plugin::getPhpDir('meshcentral', false) . "/front";
      $menu = [
         'title' => __("MeshCentral", 'meshcentral'),
         //'page'  => "$front_mesh/config.php"
         'page'  => "$front_mesh/config.form.php"
	 //'icon'  => PluginFieldsContainer::getIcon(),
	 //'links' => [
        	// 'search' => $itemtype::getSearchURL(false)
         //]
      ];

      return $menu;
   }

   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
    public static function displayTabContentForItem($item, $tabnum = 1, $withtemplate = 0)
    {
      Toolbox::logDebug("Going with Form dTCFI");

        switch ($tabnum) {
            case 0:
                $item->showConfigForm();
                return true;
            case 1:
                $item->showConfigForm();
                return true;
        }

        return false;
    }

   /**
    * Display the menu of plugin
    *
    * @global array $CFG_GLPI
    * @param string $type
    */
    public static function displayMenu($options = []){
       global $CFG_GLPI;
       $pfConfig = new PluginMeshcentralConfig();
       $pfConfig->fields['id'] = 1;
       $pfConfig->showConfigForm();
       //$pfConfig->display();

       return true;

    }

   /**
    * Display form
    *
    * @param array $options
    * @return true
    */
    public function showConfigForm($options = [])
    {

	$this->initForm(1, $options);
        $this->showFormHeader($options);
	echo "<form name='form' action='".Toolbox::getItemTypeFormURL("PluginMeshcentralConfig")."' method='post'>";

	echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('MeshCentral private URL:', 'meshcentral') . "</td>";
        echo "<td>";
        echo "<input type='text' class='form-control' name='url' value='" .
        $this->getValue('url') . "' size='60' />";
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_2'>";
        echo "<td>" . __('User provided:', 'meshcentral') . "</td>";                 
        echo "<td>";
        echo "<input type='password' class='form-control' name='user' value='" .
        $this->getValue('user') . "' size='60' />";
        echo "</td>";
        echo "</tr>";

	echo "<tr class='tab_bg_2'>";
        echo "<td>" . __('Secure password:', 'meshcentral') . "</td>";                 
        echo "<td>";
        echo "<input type='password' class='form-control' name='password' value='" .
        $this->getValue('password') . "' size='60' />";
        echo "</td>";
        echo "</tr>";


        echo "<td>";
        echo Html::submit("<i class='fas fa-save me-1'></i>" . _x('button', 'Save'), [
            'name'  => 'update',
            'class' => 'btn btn-primary'
            ]);
        echo "</td>";

        Html::closeForm();

        $options['candel'] = false;
        $this->showFormButtons($options);

        return true;
    }

    /**
    * Get configuration value with name
    *
    * @global array $MC_CONFIG
    * @param string $name name in configuration
    * @return null|string|integer
    */
    public function getValue($name)
    {
        global $MC_CONFIG;

        if (isset($MC_CONFIG[$name])) {
            return $MC_CONFIG[$name];
        }

        $config = current($this->find(['type' => $name]));
        if (isset($config['value'])) {
            return $config['value'];
        }
        return null;
    }

    /**
    * Update configuration value
    *
    * @param string $name name of configuration
    * @param string $value
    * @return boolean
    */
    public function updateValue($name, $value)
    {
        global $MC_CONFIG;

       // retrieve current config
        $config = current($this->find(['type' => $name]));

       // set in db
        if (isset($config['id'])) {
            $result = $this->update(['id' => $config['id'], 'value' => $value]);
        } else {
            $result = $this->add(['type' => $name, 'value' => $value]);
        }
         // set cache
        if ($result) {
            $MC_CONFIG[$name] = $value;
        }

        return $result;
    }

    /**
    * Add name + value in configuration if not exist
    *
    * @param string $name
    * @param string $value
    * @return integer|false integer is the id of this configuration name
    */
    public function addValue($name, $value)
    {
        $existing_value = $this->getValue($name);
        if (!is_null($existing_value)) {
            return $existing_value;
        } else {
            return $this->add(['type'  => $name,
                                 'value' => $value]);
        }
    }

   /**
    * Add multiple configuration values
    *
    * @param array $values configuration values, indexed by name
    * @param boolean $update say if add or update in database
    */
    public function addValues($values, $update = true)
    {

        foreach ($values as $type => $value) {
            if ($this->getValue($type) === null) {
                $this->addValue($type, $value);
            } elseif ($update == true) {
                $this->updateValue($type, $value);
            }
        }
    }


   public function showForm($ID, $options = []) {
      global $CFG_GLPI;
      Toolbox::logDebug("Going with Form");

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<input type='text' class='form-control' name='server_upload_path' value='" .
         $pfConfig->getValue('server_upload_path') . "' size='60' />";

      if (!isset($options['display'])) {
         //display per default
         $options['display'] = true;
      }

      $params = $options;
      //do not display called elements per default; they'll be displayed or returned here
      $params['display'] = false;

      $out = '<tr>';
      $out .= '<th>' . __('My label', 'myexampleplugin') . '</th>';

      if($params['display'] == true) {
         echo $out;
         $this->showFormButtons($params);
      } else {
         return $out;
      }
   }	
}
