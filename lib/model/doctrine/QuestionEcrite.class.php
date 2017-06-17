<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class QuestionEcrite extends BaseQuestionEcrite
{

  public function getLink() {
    sfProjectConfiguration::getActive()->loadHelpers(array('Url'));
    return url_for('@question_numero?numero='.$this->numero);
  }
  public function getLinkSource() {
    return $this->source;
  }
  public function getPersonne() {
    return $this->getParlementaire()->getNom();
  }

  public function getGroupeAcronyme() {
    return myTools::getObjectGroupeAcronyme($this);
  }

  public function __toString() {
    $str = substr(strip_tags($this->question), 0, 250);
    if (strlen($str) == 250) {
      $str .= '...';
    } else if (!$str) $str = "";
    return $str;
  }

  public function getLastDate() {
    if ($this->date_cloture)
      return $this->date_cloture;
    return $this->date;
  }

  public function getTitre() {
    $titre = 'Question Écrite N° '.$this->numero.' du '.myTools::displayVeryShortDate($this->date).' ('.preg_replace('/\s*[\/\(].*$/', '', $this->ministere).')';
    if ($this->date_cloture && !$this->reponse && date("Y-m-d") > $this->date_cloture) $titre .= ' (Retirée)';
    else if (!$this->reponse) $titre .= ' (Sans réponse)';
    else $titre .= ' (Réponse le '.myTools::displayVeryShortDate($this->date_cloture).')';
    return $titre;
  }

  public function setAuteur($depute) {
    $sexe = null;
    if (preg_match('/^\s*(M+[\s\.ml]{1})[a-z]*\s*([dA-Z].*)\s*$/', $depute, $match)) {
        $nom = $match[2];
        if (preg_match('/M[ml]/', $match[1]))
          $sexe = 'F';
        else $sexe = 'H';
    } else $nom = preg_replace("/^\s*(.*)\s*$/", "\\1", $depute);
    $depute = Doctrine::getTable('Parlementaire')->findOneByNomSexeGroupeCirco($nom, $sexe);
    if (!$depute) print "ERROR: Auteur introuvable in ".$this->source." : ".$nom." // ".$sexe."\n";
    else {
      $this->_set('parlementaire_id', $depute->id);
      $depute->free();
    }
  }
  public function uniqueMinistere()
  {
    $min = trim(preg_replace('/^.* \/ /', '', $this->ministere));
    if ($min == 'Premier ministre') return $min;
    if (preg_match('/^Secrétariat /', $min)) {
      $min = preg_replace('/^Secrétariat .* chargé( des)? d/', '', $min);
      $min = preg_replace('/^([^,]+\s*),.*$/', '\1', $min);
      $min = preg_replace('/^(.*?)\s+et d.*$/', '\1', $min);
      $min = preg_replace('/^es /', 'aux ', $min);
      $min = preg_replace('/^u /', 'au ', $min);
      $min = preg_replace('/^e l/', 'à l', $min);
      return "Secrétariat d'état ".$min;
    }
    if (preg_match('/^Ministère /', $min)) {
      $min = preg_replace('/stère chargé /', 'stère ', (preg_match('/ PME/', $min) ? $min : strtolower($min)));
      $min = preg_replace('/^([^,]+\s*),.*$/', '\1', $min);
      $min = preg_replace('/^(.*?)\s+et d.*$/', '\1', $min);
      return ucfirst($min);
    }
    $min = preg_replace('/^([^,]+\s*),.*$/', '\1', $min);
    $min = preg_replace('/^(.*) et .*?$/', '\1', $min);
    if ($min == "PME") return 'Ministère des PME';
    if (preg_match('/^\S+s( |$)/', $min))
      $art = "es ";
    elseif (preg_match('/^[AEIOUYÉ]/', $min))
      $art = "e l'";
    elseif (preg_match('/^(Culture|Décentralisation|Défense|Famille|Formation|Francophonie|Justice|Politique|Réforme|Réussite|Ville)/', $min))
      $art = "e la ";
    else $art = "u ";
    $min = preg_replace('/^É/', 'é', $min);
    return 'Ministère d'.$art.lcfirst($min);
  }

  public function firstTheme()
  {
    $theme = preg_replace('/^\s*([\w\-àéëêèïîôöûüÉ\s]+)*[,\/:].*$/', '\\1', $this->themes);
    $theme = preg_replace('/^(.*)\s+$/', '\\1', $theme);
    return $theme;
  }
}
