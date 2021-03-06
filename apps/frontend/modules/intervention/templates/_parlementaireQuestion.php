<?php if (!count($questions)) { ?>
    <i class="paddingleft"><?php echo $parlementaire->ceCette; ?> n'a posé aucune question orale.</i>
<?php return ;}?>
<ul>
<?php foreach($questions as $question) {
  $titre = myTools::displayVeryShortDate($question->date).'&nbsp;: ';
  $section = $question->getSection();
  if ($section->getSection()) {
     if (!preg_match('/reprise des questions/i', $section->getTitre()) && preg_match('/question/i', $section->getSection()->getTitre()))
      $titre .= ucfirst($section->getTitre());
    else $titre .= ucfirst($section->getSection()->getTitre());
  } else $titre .= ucfirst($section->getTitre());
  if ($question->nb_commentaires)
    $titre .= ' (<span class="list_com">'.$question->nb_commentaires.'&nbsp;commentaire';
  if ($question->nb_commentaires > 1)
    $titre .= 's';
  if ($question->nb_commentaires)
    $titre .= '</span>)';
  echo '<li>'.link_to($titre, url_for('@interventions_seance?seance='.$question->getSeance()->id).'#table_'.$section->id).'</li>';
} ?>
</ul>
<p class="suivant"><?php echo link_to('Toutes ses questions orales', '@parlementaire_interventions?slug='.$parlementaire->getSlug().'&type=question'); ?></p>

