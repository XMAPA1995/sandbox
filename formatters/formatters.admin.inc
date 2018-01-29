<?php

/**
 * @file
 * Module config form.
 */

/**
 * Admin form for module.
 */
function formatters_admin_settings() {
  $form = array();
  $form['configuration'] = array(
    '#title' => 'Site configurations',
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['configuration']['ts_empty_symbol'] = array(
    '#title' => 'Empty symbol for technical specs',
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => variable_get('ts_empty_symbol'),
    '#description' => 'Enter your custom symbol for empty fields. Please use only html symbol codes for correctly work.' . '<a href="http://www.character-code.com/">Help link!</a>',
  );
  return system_settings_form($form);
}
