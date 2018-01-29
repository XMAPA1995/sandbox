<?php
/**
 * @file
 * Created by PhpStorm.
 */

use Symfony\Component\Yaml\Yaml;

/**
 * Run-time rendering of the body of the block (content type).
 */
$plugin = [
  'single' => TRUE,
  'title' => t('Related products'),
  'category' => 'Wmr',
  'render callback' => 'related_products',
];
/**
 * Custom related function.
 */
function related_products($subtype, $conf, $args, $contexts) {
  $block = new stdClass();
  $nid = arg(1);
  $conf['nid'] = $nid;
  $node = node_load($nid);
  $conf['amount'] = $node->field_msrp['und'][0]['amount'];
  $module_path = drupal_get_path('module', 'retailers_panes');
  $conf['style'] = '/' . $module_path . '/plugins/content_types/related_products/style.css';

  // Get related products.
  $products = [
    'name' => 'product',
    'alias' => 'p',
  ];
  $node_ref_field = [
    'name' => 'field_data_field_reference_to_product',
    'alias' => 'nr',
    'condition' => 'p.id = nr.field_reference_to_product_target_id',
  ];
  $currency_field = [
    'name' => 'field_data_field_ent_currency',
    'alias' => 'cf',
    'condition' => 'p.id = cf.entity_id',
  ];
  $query = db_select($products['name'], $products['alias']);
  $query->innerJoin(
    $node_ref_field['name'],
    $node_ref_field['alias'],
    $node_ref_field['condition']
  );
  $query->innerJoin(
    $currency_field['name'],
    $currency_field['alias'],
    $currency_field['condition']
  );
  $query->fields('p');
  $query->fields('nr',
    [
      'field_reference_to_product_target_id',
      'entity_id',
    ]);
  $query->fields('cf', ['field_ent_currency_value']);
  $query->condition('nr.entity_id', $nid, '=');
  $related_products = $query->execute()->fetchAll();
  $field = field_info_field('field_ent_currency');
  $allowed_values = list_allowed_values($field);
  $conf['allowed_values'] = $allowed_values;
  foreach ($related_products as $product) {
    if ($product->ent_status == 1) {
      $currency = new Currency();
      $currency->resourceLoad($product->field_ent_currency_value);
      $product->sign = $currency->sign;
      $conf['products'][$product->field_ent_currency_value][$product->id] = $product;
    }
  }
  if (!empty($conf['products'])) {
    foreach ($conf['products'] as $key => $items) {
      uasort($items, function ($a, $b) {
        return ($a->ent_discount - $b->ent_discount);
      });
      $conf['products'][$key] = array_reverse($items);
    }
  }
  $content = theme('related_products', $conf);
  $block->content = $content;
  $block->title = '';
  return $block;
}
