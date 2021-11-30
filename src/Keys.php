<?php

namespace Drupal\node_hide;

/**
 * Defines a class to access and modify secret keys used by this module 'node_hide'.
 * 
 * Each node is protected by this key and is accessible only using these keys.
 * 
 * Each key has expiry date which is updated / checked in cron job.
 */
class Keys {
    
    public function save($nid, $key) {
        $expiry_date = '1231231221321';
        
        $connection = \Drupal::database();
        $result = $connection->insert('node_hide')
            ->fields([
                'key' => $key,
                'entity_id' => $nid,
                'entity_type' => 'node',
                'expiry_date' => $expiry_date,
            ])
            ->execute();
    }
}