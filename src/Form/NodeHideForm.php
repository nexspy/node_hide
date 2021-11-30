<?php


namespace Drupal\node_hide\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the SimpleForm form controller.
 *
 * This example demonstrates a simple form with a single text input element. We
 * extend FormBase which is the simplest form base class used in Drupal.
 *
 * @see \Drupal\Core\Form\\Drupal\Core\Form\FormBase
 */
class NodeHideForm extends FormBase
{
    /**
     * Build the simple form.
     *
     * A build form method constructs an array that defines how markup and
     * other form elements are included in an HTML form.
     *
     * @param array $form
     *   Default form array structure.
     * @param \Drupal\Core\Form\\Drupal\Core\Form\FormStateInterface $form_state
     *   Object containing current form state.
     *
     * @return array
     *   The render array defining the elements of the form.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // get all entity types
        $all_entity_types = [];
        $node_types = \Drupal::entityTypeManager()
            ->getStorage('node_type')
            ->loadMultiple();
        foreach ($node_types as $node_type) {
            $all_entity_types[$node_type->id()] = $node_type->label();
        }

        // default master key
        $master_key = $this->config('node_hide.settings')->get('master_key');

        // default content region flag
        $hide_content_region = $this->config('node_hide.settings')->get('hide_content_region');

        // get selected entity types
        $content_types = $this->config('node_hide.settings')->get('content_types');
        if ($content_types == null) {
            $content_types = [];
        }

        $form['content_types'] = [
            '#type' => 'checkboxes',
            '#title' => 'Select Content Type',
            '#options' => $all_entity_types,
            '#default_value' => $content_types,
            '#description' => 'Select which content type content will be hidden for user to view.',
        ];

        $form['master_key'] = [
            '#type' => 'textfield',
            '#title' => 'Master Key',
            '#default_value' => $master_key,
            '#description' => 'This master key can be used to unlock any content. Be careful when using this value.',
        ];

        $form['hide_content_region'] = [
            '#type' => 'checkbox',
            '#title' => 'Hide Content Region',
            '#default_value' => $hide_content_region,
            '#description' => 'You can choose to hide whole content region if this option is enabled',
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
     * Getter method for Form ID.
     *
     * The form ID is used in implementations of hook_form_alter() to allow other
     * modules to alter the render array built by this form controller. It must be
     * unique site wide. It normally starts with the providing module's name.
     *
     * @return string
     *   The unique ID of the form defined by this class.
     */
    public function getFormId()
    {
        return 'node_hide_config_form';
    }

    /**
     * Implements form validation.
     *
     * The validateForm method is the default method called to validate input on
     * a form.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\\Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }

    /**
     * Implements a form submit handler.
     *
     * The submitForm method is the default method called for any submit elements.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\\Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $content_types = $form_state->getValue('content_types');
        $master_key = $form_state->getValue('master_key');
        $hide_content_region = $form_state->getValue('hide_content_region');

        $list = [];

        foreach ($content_types as $content_type) {
            if ($content_type) {
                $list[] = $content_type;
            }
        }

        \Drupal::service('config.factory')
            ->getEditable('node_hide.settings')
            ->set('content_types', $list)
            ->set('master_key', $master_key)
            ->set('hide_content_region', $hide_content_region)
            ->save();

        $this->messenger()->addMessage($this->t('Configuration was saved.'));
    }

}