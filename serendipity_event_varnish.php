<?php 

/*

    Varnish Plugin for Serendipity
    E. Camden Fisher <fishnix@gmail.com>
    
*/

if (IN_serendipity != true) {
    die ("Don't hack!"); 
}
    
$time_start = microtime(true);

// Probe for a language include with constants. Still include defines later on, if some constants were missing
$probelang = dirname(__FILE__) . '/' . $serendipity['charset'] . 'lang_' . $serendipity['lang'] . '.inc.php';

if (file_exists($probelang)) {
    include $probelang;
}

include_once dirname(__FILE__) . '/lang_en.inc.php';

class serendipity_event_varnish extends serendipity_event
{

    function example() 
    {
      echo PLUGIN_EVENT_VARNISH_INSTALL;
    }

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',         PLUGIN_EVENT_VARNISH_NAME);
        $propbag->add('description',  PLUGIN_EVENT_VARNISH_DESC);
        $propbag->add('stackable',    false);
        $propbag->add('groups',       array('IMAGES'));
        $propbag->add('author',       'E Camden Fisher <fish@fishnix.net>');
        $propbag->add('version',      '0.0.1');
        $propbag->add('requirements', array(
            'serendipity' => '1.5.0',
            'smarty'      => '2.6.7',
            'php'         => '5.2.0'
        ));

      // make it cacheable
      // $propbag->add('cachable_events', array(
      //       'frontend_display' => true));
            
      $propbag->add('event_hooks',   array(
        'backend_publish' => true
        ));

      // $this->markup_elements = array(
      //     array(
      //       'name'     => 'ENTRY_BODY',
      //       'element'  => 'body',
      //     ),
      //     array(
      //       'name'     => 'EXTENDED_BODY',
      //       'element'  => 'extended',
      //     ),
      //     array(
      //       'name'     => 'HTML_NUGGET',
      //       'element'  => 'html_nugget',
      //     )
      // );

        $conf_array = array();

        // foreach($this->markup_elements as $element) {
        //     $conf_array[] = $element['name'];
        // }

        $conf_array[] = 'varnish_servers';
        $conf_array[] = 'varnish_secret';

        $propbag->add('configuration', $conf_array);
    }

    function generate_content(&$title) {
      $title = $this->title;
    }

    function introspect_config_item($name, &$propbag) {
      switch($name) {
        case 'varnish_servers':
          $propbag->add('name',           PLUGIN_EVENT_VARNISH_SERVER);
          $propbag->add('description',    PLUGIN_EVENT_VARNISH_SERVER_DESC);
          $propbag->add('default',        '127.0.0.1:6082');
          $propbag->add('type',           'string');
        break;
        case 'varnish_secret':
          $propbag->add('name',           PLUGIN_EVENT_VARNISH_SECRET);
          $propbag->add('description',    PLUGIN_EVENT_VARNISH_SECRET_DESC);
          $propbag->add('default',        'secret');
          $propbag->add('type',           'string');
        break;
        default:
          return false;
        break;
        
      }
      
      return true;
    }
    
    /*
     * install plugin
    function install() {
    }
    */

    /*
     * uninstall plugin
    function uninstall() {
    }
    */

    function event_hook($event, &$bag, &$eventData) {
        global $serendipity;
        
        $hooks = &$bag->get('event_hooks');
        
        if (isset($hooks[$event])) {
          switch($event) {
            case 'backend_publish':
              # code...
            break;

            default:
              return false;
            } 
        } else {
        return false;
      }
    }
    
    function outputMSG($status, $msg) {
        switch($status) {
            case 'notice':
                echo '<div class="serendipityAdminMsgNotice"><img style="width: 22px; height: 22px; border: 0px; padding-right: 4px; vertical-align: middle" src="' . serendipity_getTemplateFile('admin/img/admin_msg_note.png') . '" alt="" />' . $msg . '</div>' . "\n";
                break;

            case 'error':
                echo '<div class="serendipityAdminMsgError"><img style="width: 22px; height: 22px; border: 0px; padding-right: 4px; vertical-align: middle" src="' . serendipity_getTemplateFile('admin/img/admin_msg_error.png') . '" alt="" />' . $msg . '</div>' . "\n";
                break;

            default:
            case 'success':
                echo '<div class="serendipityAdminMsgSuccess"><img style="height: 22px; width: 22px; border: 0px; padding-right: 4px; vertical-align: middle" src="' . serendipity_getTemplateFile('admin/img/admin_msg_success.png') . '" alt="" />' . $msg . '</div>' . "\n";
                break;
        }
    }
}

?>
