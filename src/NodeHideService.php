<?php

namespace Drupal\node_hide;

class NodeHideService
{

    private $content_types;
    private $master_key;
    private $hide_content_region;

    /**
     * Constructor
     */
    public function __construct()
    {
        // try fetching from the form configuration
        $content_types = \Drupal::config('node_hide.settings')->get('content_types');

        $content_types = ($content_types) ? $content_types : [];

        $this->master_key = \Drupal::config('node_hide.settings')->get('master_key');

        $this->hide_content_region = \Drupal::config('node_hide.settings')->get('hide_content_region');

        $this->content_types = $content_types;
    }

    /**
     * Verify : the key for given nid
     * @param integer $nid
     * @param string $code
     * @return boolean
     */
    static public function verify($nid, $code)
    {
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

    public function get_content_types() {
        return $this->content_types;
    }

    public function get_master_key() {
        return $this->master_key;
    }

    public function get_hide_content_region() {
        return $this->hide_content_region;
    }

    public function is_content_hidden($node) {
        $content_type = $node->getType();
        $applicable_content_types = $this->get_content_types();
        $isContentHidden = (in_array($content_type, $applicable_content_types));
        return $isContentHidden;
    }

    public function get_key($nid) {
        $database = \Drupal::database();
        $query = $database->select('node_hide', 'ic');
        $query->fields('ic');
        $query->condition('entity_id', $nid);
        $result = $query->execute();

        $key = '';
        foreach ($result as $record) {
            $key = $record->key;
        }

        return $key;
    }
}
