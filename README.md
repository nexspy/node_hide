# Node Hide

Hide the content of the node using short pass codes.

Any content type can have this feature. Just go to the settings page and enable it.


## Installation

Follow these instructions.

1. Enable the module "Node Hide"
2. Go to settings page "admin/node_hide"
3. Add Content Types to enable this feature.
4. Go to any content of this content type (enabled) and add keys.


## Node related Blocks

Other part of the node page like blocks can use the service **NodeHideService** to check if the content was blocked.

```php
$node_hide_service = \Drupal::service('node_hide.node_hide');
$isContentHidden = $node_hide_service->is_content_hidden($node);
```

