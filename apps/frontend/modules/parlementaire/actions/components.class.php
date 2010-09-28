<?php 
class parlementaireComponents extends sfComponents
{
  public function executeList() 
  {
    $this->parlementaires = $this->parlementairequery
      ->select('p.*, i.id, count(i.id) as nb')
      ->groupBy('p.id')
      ->orderBy('nb DESC')
      ->execute();

  }
  public function executeHeader()
  {
  }
  public function executeDuJour()
  {
    $this->parlementaire = Doctrine::getTable('Parlementaire')->createQuery('p')->where('fin_mandat IS NULL')->orderBy('rand()')->fetchOne();
    return ;
  }
  public function executeSearch() {
    $this->search = $this->query;

    $query = Doctrine::getTable('Parlementaire')->createQuery('p');

    $searchs = explode(' ', preg_replace('/\W/', ' ', $this->search));
    $ns = count($searchs);
    for ($i=0; $i<$ns; $i++)
      $searchs[$i] = '%'.$searchs[$i].'%';
    $likes = 'p.nom LIKE ?';
    for ($i=1; $i<$ns; $i++)
      $likes .= ' AND p.nom LIKE ?';
    $query->where($likes, $searchs);
    $query->orderBy('p.nom_de_famille ASC');
    
    $this->parlementaires = $query->execute();
    
    $nb = count($this->parlementaires);
    if ($nb == 0) {
      $this->similars = Doctrine::getTable('Parlementaire')->similarTo($this->search, null, 1);
    }
  }
}