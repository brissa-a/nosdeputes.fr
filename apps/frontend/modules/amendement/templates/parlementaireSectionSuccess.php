<?php
if ($section->getSection())
  $surtitre = link_to($section->getSection()->getTitre(), '@section?id='.$section->section_id).'<br><soustitre>('.link_to($section->titre, '@section?id='.$section->id).')</soustitre>';
else $surtitre = link_to($surtitre, '@section?id='.$section->id);
echo include_component('parlementaire', 'header', array('parlementaire' => $parlementaire, 'surtitre' => $surtitre, 'titre' => 'Les amendements'));
?>
<p><?php echo link_to('Les interventions de '.$parlementaire->nom.' sur ce dossier', '@parlementaire_texte?slug='.$parlementaire->slug.'&id='.$section->id); ?></p>
<?php
  echo include_component('amendement', 'pagerAmendements', array('amendement_query' => $qamendements));
?>
