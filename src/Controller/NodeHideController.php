<?php

namespace Drupal\node_hide\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for blog module routes.
 */
class NodeHideController extends ControllerBase {

  /**
   * Verify : the key submitted with node id
   */
  public function verifyPage() {
    $code = \Drupal::request()->request->get('code');
    $nid = \Drupal::request()->request->get('nid');
    $success = false;
    $message = 'failed to get node';
    
    
    // TODO: verify the data submitted

    if (1 || $this->verify($nid, $code)) {
      $node = Node::load($nid);
      $prepared = $this->prepareNode($node);

      $nodePrepared = [
        '#theme' => 'nodehide_node',
        '#node' => $prepared,
      ];

      if ($node) {
        $success = true;
        $message = 'node was found';
      } else {
        // TODO: add 404 error
      }
    }

    $result = (object) [
      'success' => $success,
      'message' => $message,
      'node' => \Drupal::service('renderer')->render($nodePrepared),
    ];

    return new JsonResponse($result);
  }

  public function prepareNode($node) {
    $result = [];
    
    $result['title'] = $node->getTitle();
    // dd($node->get('body')->getValue());
    // $result['body'] = $node->body->value;
    $result['description'] = 'ok';

    return $result;
  }

  public function verify($nid, $code) {
    // return false;
    $database = \Drupal::database();
    $query = $database->select('node_hide', 'ic');
    $query->fields('ic');
    $query->condition('entity_id', $nid);
    $query->condition('key', $code);
    $result = $query->execute();

    $list = [];
    foreach ($result as $record) {
      $list[] = $record;
    }

    return (!empty($list));
  }

}
