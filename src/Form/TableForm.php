<?php

namespace Drupal\module4\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateinterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;

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

    $form['add-table'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add table'),
      '#submit' => ['::addTable'],
      '#attributes' => [
        'class' => ['add-table-btn'],
      ],
    ];

    for ($table = 1; $table < $this->countTable; $table++) {

      $form["table-$table"] = [
        '#type' => 'table',
        '#header' => $table_headers,
      ];

      for ($i = 0; $i < $this->rows[$table]; $i++) {
        foreach ($table_headers as $header) {
          if ($header == 'Year') {
            $form["table-$table"][$i]["$header"] = [
              '#type' => 'html_tag',
              '#tag' => 'div',
              '#value' => date('Y') - $i,
            ];
          }
          elseif (in_array($header, ['Q1', 'Q2', 'Q3', 'Q4', 'YTD'])) {
            $form["table-$table"][$i]["$header"] = [
              '#type' => 'html_tag',
              '#tag' => 'div',
              '#value' => 0,
            ];
          }
          else {
            $form["table-$table"][$i]["$header"] = [
              '#type' => 'number',
              '#default_value' => 0,
            ];
          }
        }
      }

      $form["add-row-$table"] = [
        '#type' => 'submit',
        '#value' => $this->t("Add row"),
        '#name' => $table,
        "#submit" => ["::addRow"],
        '#attributes' => [
          'class' => ['add-row-btn'],
        ],
      ];
    }

    $form['action']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
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
    return $form;
  }

  /**
   * Callback function for add row button.
   */
  public function addRow(array &$form, FormStateInterface $form_state) {
    $i = $form_state->getTriggeringElement()['#name'];
    $this->rows[$i]++;
    $form_state->setRebuild();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
