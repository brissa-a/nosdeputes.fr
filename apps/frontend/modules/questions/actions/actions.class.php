<?php

/**
 * questions actions.
 *
 * @package    cpc
 * @subpackage questions
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class questionsActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->question = doctrine::getTable('QuestionEcrite')->find($request->getParameter('id'));
    $this->forward404Unless($this->question);
    $this->parlementaire = doctrine::getTable('Parlementaire')->find($this->question->parlementaire_id);
    $this->forward404Unless($this->parlementaire);
  }

  public function executeParlementaire(sfWebRequest $request)
  {
    $this->parlementaire = doctrine::getTable('Parlementaire')->findOneBySlug($request->getParameter('slug'));
    $this->forward404Unless($this->parlementaire);
    $this->questions = doctrine::getTable('QuestionEcrite')->createQuery('q')
      ->where('q.parlementaire_id = ?', $this->parlementaire->id)
      ->orderBy('q.date DESC');
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->mots = $request->getParameter('search');
    $mots = $this->mots;
    $mcle = array();

    if (preg_match_all('/("[^"]+")/', $mots, $quotes)) {
      foreach(array_values($quotes[0]) as $q)
	$mcle[] = '+'.$q;
      $mots = preg_replace('/\s*"([^\"]+)"\s*/', ' ', $mots);
    }

    foreach(split(' ', $mots) as $mot) {
      if ($mot && !preg_match('/^[\-\+]/', $mot))
	$mcle[] = '+'.$mot;
    }

    $this->high = array();
    foreach($mcle as $m) {
      $this->high[] = preg_replace('/^[+-]"?([^"]*)"?$/', '\\1', $m);
    }

    $sql = 'SELECT i.id FROM question_ecrite i WHERE MATCH (i.question) AGAINST (\''.str_replace("'", "\\'", implode(' ', $mcle)).'\' IN BOOLEAN MODE)';

    $search = Doctrine_Manager::connection()
      ->getDbh()
      ->query($sql)->fetchAll();

    $ids = array();
    foreach($search as $s) {
      $ids[] = $s['id'];
    }

    $this->query = doctrine::getTable('QuestionEcrite')->createQuery('i');
    if (count($ids))
      $this->query->whereIn('i.id', $ids);
    else if (count($mcle))
      foreach($mcle as $m) {
	$this->query->andWhere('i.question LIKE ?', '% '.$m.' %');
	$this->query->orWhere('i.reponse LIKE ?', '% '.$m.' %');
	$this->query->orWhere('i.themes LIKE ?', '% '.$m.' %');
      } else {
      $this->query->where('0');
      return ;
    }

    if ($slug = $request->getParameter('parlementaire')) {
      $this->parlementaire = doctrine::getTable('Parlementaire')
	->findOneBySlug($slug);
      if ($this->parlementaire)
	$this->query->andWhere('i.parlementaire_id = ?', $this->parlementaire->id);
    } else $this->query->leftJoin('i.Parlementaire p');

    $this->query->orderBy('date DESC');
  }
}
