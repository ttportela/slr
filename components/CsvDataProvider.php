<?php
namespace app\components;

use yii\data\BaseDataProvider;

class CsvDataProvider extends BaseDataProvider
{
  /**
   * @var string nome do arquivo CSV que será lido
   */
  public $filename;
  
  /**
   * @var string|nome da coluna chave ou função que a retorne
   */
  public $key;
  
  /**
   * @var SplFileObject
   */
  protected $fileObject; // SplFileObject é muito conveniente para procurar uma linha específica em um arquivo
  
  /**
   * {@inheritdoc}
   */
  public function init()
  {
      parent::init();
      
      // abre o arquivo
      $this->fileObject = new \SplFileObject($this->filename);
      $this->fileObject->setCsvControl("\t");
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareModels()
  {
      $models = [];
      $pagination = $this->getPagination();
      if ($pagination === false) {
          // no caso não há paginação, lê todas as linhas
          while (!$this->fileObject->eof()) {
              $models[] = $this->fileObject->fgetcsv();
              $this->fileObject->next();
          }
      } else {
          // no caso existe paginação, lê somente uma página
          $pagination->totalCount = $this->getTotalCount();
          $this->fileObject->seek($pagination->getOffset());
          $limit = $pagination->getLimit();
          for ($count = 0; $count < $limit; ++$count) {
              $models[] = $this->fileObject->fgetcsv();
              $this->fileObject->next();
          }
      }
      return $models;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareKeys($models)
  {
      if ($this->key !== null) {
          $keys = [];
          foreach ($models as $model) {
              if (is_string($this->key)) {
                  $keys[] = $model[$this->key];
              } else {
                  $keys[] = call_user_func($this->key, $model);
              }
          }
          return $keys;
      } else {
          return array_keys($models);
      }
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareTotalCount()
  {
      $count = 0;
      while (!$this->fileObject->eof()) {
          $this->fileObject->next();
          ++$count;
      }
      return $count;
  }
}