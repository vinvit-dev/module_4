<?php

namespace Drupal\module4\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateinterface;
use Symfony\Component\HttpFoundation\RequestStack;

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
   * Contain counts rows in tables.
   *
   * @var array
   *   Array with counts.
   */
  protected $rows = [1];

  /**
   * Contain count tables on page.
   *
   * @var array
   *   Count tables.
   */
  protected $countTable = 1;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $table_headers = [
      $this->t('Year'),
      $this->t('Jan'),
      $this->t('Feb'),
      $this->t('Mar'),
      $this->t('Q1'),
      $this->t('Apr'),
      $this->t('May'),
      $this->t('Jun'),
      $this->t('Q2'),
      $this->t('Jul'),
      $this->t('Aug'),
      $this->t('Sep'),
      $this->t('Q3'),
      $this->t('Oct'),
      $this->t('Nov'),
      $this->t('Dec'),
      $this->t('Q4'),
      $this->t('YTD'),
    ];

    for ($table = 0; $table < $this->countTable; $table++) {

      $form["add-row-$table"] = [
        '#type' => 'submit',
        '#value' => $this->t("Add year"),
        '#name' => $table,
        "#submit" => ["::addRow"],
        '#attributes' => [
          'class' => ['add-row-btn'],
        ],
      ];

      $form["table-$table"] = [
        '#type' => 'table',
        '#header' => $table_headers,
      ];

      for ($i = $this->rows[$table]; $i > 0; $i--) {
        foreach ($table_headers as $header) {
          if ($header == 'Year') {
            $form["table-$table"][$i]["$header"] = [
              "#type" => "number",
              "#disabled" => TRUE,
              '#default_value' => date('Y') - $i + 1,
            ];
          }
          elseif (in_array($header, ['Q1', 'Q2', 'Q3', 'Q4', 'YTD'])) {
            $form["table-$table"][$i]["$header"] = [
              "#type" => "number",
              "#disabled" => TRUE,
            ];
          }
          else {
            $form["table-$table"][$i]["$header"] = [
              '#type' => 'number',
            ];
          }
        }
      }
    }

    $form['add-table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add table'),
      '#submit' => ['::addTable'],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#name' => 'submit',
    ];

    $form["#attached"]["library"][] = "module4/module4";
    return $form;
  }

  /**
   * Callback function for add new table.
   */
  public function addTable(array &$form, FormStateInterface $form_state) {
    $this->countTable++;
    $this->rows[] = 1;
    $form_state->setRebuild();
  }

  /**
   * Callback function for add row button.
   */
  public function addRow(array &$form, FormStateInterface $form_state) {
    $i = $form_state->getTriggeringElement()['#name'];
    $this->rows[$i]++;
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($i = $form_state->getTriggeringElement()['#name'] !== 'submit') {
      return;
    }

    $values = $form_state->getValues();

    for ($t = 0; $t < $this->countTable; $t++) {
      $isValue = FALSE;
      $isEmpty = FALSE;
      for ($r = $this->rows[$t]; $r > 0; $r--) {
        foreach ($values["table-$t"][$r] as $head => $val) {
          if (in_array($head, ['Year', 'Q1', 'Q2', 'Q3', 'Q4', 'YTD'])) {
            continue;
          }
          if (!$isValue && !$isEmpty && $val !== '') {
            $isValue = TRUE;
          }
          if ($isValue && !$isEmpty && $val == '') {
            $isEmpty = TRUE;
            $isValue = FALSE;
          }
          if (!$isValue && $isEmpty && $val !== '') {
            $form_state->setErrorByName('Invalid', 'Invalid');
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
