<?php

namespace Drupal\module4\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateinterface;

/**
 * Main class for form table.
 */
class TableForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_table';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
