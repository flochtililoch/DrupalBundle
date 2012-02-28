<?php

namespace Floch\DrupalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 *  Provinding access to Drupal API within Symfony
 *  
 *  @author Florent Bonomo
 */
class BaseController extends Controller
{
  /**
   *  Bootstrapping Drupal
   */
  public function bootstrap()
  {
    static $bootstrapped;
    if( ! $bootstrapped)
    {
      // Changing current directory to Drupal installation path
      define("DRUPAL_PATH", $this->container->getParameter('drupal_path'));
      chdir(DRUPAL_PATH);

      // Basic replacement of settings.php
      global $db_url, $db_prefix;
      $db_url = $this->container->getParameter('drupal_db_url');
      $db_prefix = $this->container->getParameter('drupal_db_prefix');

      // Bootstraping 'light' Drupal
      require_once DRUPAL_PATH.'/includes/bootstrap.inc';
      require_once DRUPAL_PATH.'/includes/common.inc';
      require_once DRUPAL_PATH.'/includes/path.inc';
      require_once DRUPAL_PATH.'/includes/module.inc';
      //require_once DRUPAL_PATH.'/includes/theme.inc';
      //require_once DRUPAL_PATH.'/includes/pager.inc';
      //require_once DRUPAL_PATH.'/includes/menu.inc';
      //require_once DRUPAL_PATH.'/includes/tablesort.inc';
      //require_once DRUPAL_PATH.'/includes/file.inc';
      require_once DRUPAL_PATH.'/includes/unicode.inc';
      //require_once DRUPAL_PATH.'/includes/image.inc';
      //require_once DRUPAL_PATH.'/includes/form.inc';
      //require_once DRUPAL_PATH.'/includes/mail.inc';
      //require_once DRUPAL_PATH.'/includes/actions.inc';

      global $user;
      $user = drupal_anonymous_user();
      drupal_bootstrap(DRUPAL_BOOTSTRAP_DATABASE);
      drupal_init_language();
      module_load_all();

      $bootstrapped = true;
    }
  }
  
  /**
   *  Returns a list of nodes formatted as a JSON object
   *  
   *  @param  $type         string  Type of node to return
   *  @param  $properties   array   List of properties to include in returned object
   */
  public function nodeListAction($type, $properties = array('title'))
  {
    $this->bootstrap();
    
    $query = db_query("SELECT r.nid, r.title, r.body FROM {node} AS n LEFT JOIN {node_revisions} AS r ON r.nid = n.nid WHERE type = '%s';", array($type));
    $nodes = array();
    while ($node = db_fetch_object($query))
    {
      foreach($properties as $property)
      {
        $nodes[$node->nid][$property] = $node->{$property};
      }
    }
    
    return new Response(json_encode(
      $nodes
      ));
  }
  
  /**
   *  Returns a node formatted as a JSON object
   *  
   *  @param  $nid  string  Node id
   */
  public function nodeLoadAction($nid)
  {
    $this->bootstrap();
    
    return new Response(json_encode(
      node_load($nid)
      ));
  }

}