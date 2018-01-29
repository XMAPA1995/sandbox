<?php

namespace Drupal\crowdfunding\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * ModalForm class.
 */
class ModalForm extends FormBase {

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'crowdfunding_modal_form';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
		$form['#prefix'] = '<div id="modal_crowdfunding_form">';
		$form['#suffix'] = '</div>';

		$form['node_title'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Title'),
			'#default_value' => '',
			'#size' => 60,
			'#maxlength' => 128,
			'#required' => TRUE,
		];
		$form['node_body'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Body'),
			'#format' => 'clear_html',
			'#required' => TRUE,
		];
		$form['field_price'] = [
			'#type' => 'number',
			'#title' => $this->t('Price'),
		];
		$form['field_image'] = [
			'#type' => 'managed_file',
			'#upload_location' => 'public://cf_images/',
			'#multiple' => FALSE,
			'#upload_validators' => [
				'file_validate_extensions' => ['png gif jpg jpeg'],
				'file_validate_size' => [25600000],
			],
		];
		$form['image_alt'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Image alternative text'),
			'#default_value' => '',
			'#size' => 60,
			'#maxlength' => 128,
			'#required' => TRUE,
		];
		$form['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Create'),
			'#attributes' => [
				'class' => [
					'use-ajax',
				],
			],
			'#ajax' => [
				'callback' => [$this, 'submitModalFormAjax'],
				'event' => 'click',
			],
		];

		$form['#attached']['library'][] = 'core/drupal.dialog.ajax';

		return $form;
	}

	/**
	 * AJAX callback handler that displays any errors or a success message.
	 */
	public function submitModalFormAjax(array $form, FormStateInterface $form_state) {
		$response = new AjaxResponse();
		$user_input = $form_state->getUserInput();

		// Node saving.
		$node = Node::create([
			'type' => 'crowdfunding',
			'title' => $user_input['node_title'],
			'field_price_crowdfunding' => $user_input['field_price'],
			'body' => [
				0 => [
					'value' => $user_input['node_body'],
					'format' => 'clear_html',
				],
			],
			'created' => time(),
		]);

		$node->field_image_crowdfunding->setValue([
			'target_id' => $user_input['field_image']['fids'],
			'alt' => $user_input['image_alt'],
		]);
		$node->save();
		// END:Node saving.
		$options = ['width' => 800,'color' => 'white'];
		// If there are any form errors, re-display the form.
		if ($form_state->hasAnyErrors()) {
			$response->addCommand(new ReplaceCommand('#modal_crowdfunding_form', $form));
		}
		else {
			$response->addCommand(new OpenModalDialogCommand(
				"Success!",
				'<span class="crowdsucces">The creation crowdfunding has been completed.</span>',
				$options
				)
			);
		}

		return $response;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state) {
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
	}

	/**
	 * Gets the configuration names that will be editable.
	 *
	 * @return array
	 *   An array of configuration object names that are editable if called in
	 *   conjunction with the trait's config() method.
	 */
	protected function getEditableConfigNames() {
		return ['config.crowdfunding_modal_form'];
	}

}
