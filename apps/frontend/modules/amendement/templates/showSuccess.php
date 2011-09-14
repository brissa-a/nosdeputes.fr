<?php $titre1 = $amendement->getShortTitre(1);
      if ($section) $titre2 = link_to(ucfirst($section->titre), '@section?id='.$section->id);
      else $titre2=""; ?>
<?php $sf_response->setTitle(strip_tags($titre2.'  '.$titre1)); ?>
<div class="amendement" id="L<?php echo $amendement->texteloi_id; ?>-A<?php echo $amendement->numero; ?>">
<div class="source"><a href="<?php echo $amendement->source; ?>">source</a> - <a href="<?php echo $amendement->getLinkPDF(); ?>">PDF</a></div>
<h1><?php echo $titre1; ?></h1>
<?php if ($com = $amendement->Commission) echo "<h2>".link_to($com->nom, '@list_parlementaires_organisme?slug='.$com->slug)."</h2>"; ?>
<h2><?php echo $titre2; ?></h2>
<div class="identiques">

</div>
<?php if ($seance || count($identiques) > 1) { ?>
<div class="seance_amendements">
  <h3><?php if ($seance) echo 'Discuté en '.link_to('séance le '.myTools::displayDate($seance['date']), '@interventions_seance?seance='.$seance['seance_id'].'#amend_'.$amendement->numero);
  if (count($identiques) > 1) {
    if (count($identiques) > 2)
      $ident_titre = " ( amendements identiques : ";
    else $ident_titre = " ( amendement identique : "; ?>
  <em><?php echo $ident_titre; foreach($identiques as $identique) if ($identique->numero != $amendement->numero)
      echo link_to($identique->numero, '@amendement?loi='.$identique->texteloi_id.'&numero='.$identique->numero)." "; ?>)</em>
  <?php }
  if ($amendement->avis_comm) echo " Avis de la Commission : ".$amendement->avis_comm;
  if ($amendement->avis_comm && $amendement->avis_gouv) echo "&nbsp;&mdash; ";
  if ($amendement->avis_gouv) echo " Avis du Gouvernement : ".$amendement->avis_gouv;
  ?></h3>
</div>
<?php } ?>
<?php if ($sous_admts) { ?>
<p>Sous-amendements associés&nbsp: <?php foreach($sous_admts as $sous) {
    if ($sous['sort'] === 'Adopté') echo '<strong>';
    echo link_to($sous['numero'], '@amendement?loi='.$amendement->texteloi_id.'&numero='.$sous['numero']).' ';
    if ($sous['sort'] === 'Adopté') echo '(Adopté)</strong> ';
  } ?></p>
<?php } ?>
<p>Déposé le <?php echo myTools::displayDate($amendement->date); ?> par : <span id="liste_senateurs"><?php echo preg_replace('/(M[.mle]+)\s+/', '\\1&nbsp;', $amendement->getSignataires(1)); ?>.</span></p>
<div class="signataires">
  <div class="photos"><p>
<?php $senateurs = $amendement->Parlementaires;
  include_partial('parlementaire/photos', array("senateurs" => $senateurs)); ?>
  </p></div>
</div>
<div class="sujet">
  <h3><?php $sujet = $amendement->getSujet();
    if ($titreloi && preg_match('/^(.*)?(article\s*)((\d+|premier).*)$/i', $sujet, $match)) {
      $art = preg_replace('/premier/i', '1er', $match[3]);
      $art = preg_replace('/\s+/', '-', $art);
      $sujet = $match[1].link_to($match[2].$match[3], '@loi_article?loi='.$titreloi->texteloi_id.'&article='.$art);
    }
    if ($titreloi)
      echo link_to(preg_replace('/(Simplifions la loi 2\.0 : )?(.*)\s*<br.*$/', '\2', $titreloi->titre), '@loi?loi='.$titreloi->texteloi_id);
    else if ($loi)
      echo link_to($loi->getTitre(), '@document?id='.$loi->id);
    else echo 'Texte de loi N°&nbsp;'.$amendement->texteloi_id;
    echo '</h3><h3>'.$sujet;
    if ($l = $amendement->getLettreLoi()) echo "($l)"; ?></h3>
</div>
<div class="texte_intervention amd_txt">
  <?php $texte = $amendement->getTexte();
  if ($titreloi && preg_match('/alin(e|é)a\s*(\d+)[^\d]/', $texte, $match)) {
    $link = link_to('alinéa '.$match[2], '@loi_article?loi='.$titreloi->texteloi_id.'&article='.$art.'#alinea_'.$match[2]);
    $texte = preg_replace('/(alin(e|é)a\s*\d+)([^\d])/', $link.'\3', $texte);
  }
  echo myTools::escape_blanks($texte); ?>
</div>
<?php if (isset($amendement->expose)) { ?>
  <h3>Exposé Sommaire :</h3>
  <div class="expose_amendement amd_txt">
    <?php echo myTools::escape_blanks($amendement->getExpose()); ?>
  </div>
<?php } ?>
<div class="commentaires" id="commentaires">
<?php if ($amendement->nb_commentaires == 0)
  echo '<h3 class="list_com">Aucun commentaire n\'a encore été formulé sur cet amendement</h3>';
else echo include_component('commentaire', 'showAll', array('object' => $amendement));
echo include_component('commentaire', 'form', array('object' => $amendement)); ?>
</div>
</div>
<script type="text/javascript">
<!--
$('#liste_senateurs a').live('mouseover', function() {
 nom = $(this).attr('href').split('='); $('.photo_fiche[alt*="'+nom[1]+'"]').css('opacity', '1');
});
$('#liste_senateurs').bind('mouseover mouseout', function(event) {
 if (event.type == "mouseover") { $('.photo_fiche').css('opacity', '0.3'); $("#liste_senateurs").die("mouseover"); }
 else { $('.photo_fiche').css('opacity', '1'); $("#liste_senateurs").die("mouseout"); }
});
// -->
</script>
