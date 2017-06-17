<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ParlementaireOrganisme extends BaseParlementaireOrganisme
{
  public function __tostring() {
    return $this->getNom().' ('.$this->getFonction().')';
  }

  public static function defImportance($fonction) {
    if (preg_match('/^(président|président)/i', $fonction)) {
      if (preg_match('/droit/i', $fonction)) return 98;
      return 100;
    } else if (preg_match('/rapporteure? général/i', $fonction)) return 95;
    else if (preg_match('/(président|président)/i', $fonction)) return 90;
    else if (preg_match('/questeur/i', $fonction)) {
      if (preg_match('/membre/i', $fonction)) return 80;
      return 70;
    } else if (preg_match('/(secretaire|secrétaire)/i', $fonction)) {
      if (!preg_match('/[âa]ge/i', $fonction)) return 65;
      return 50;
    }
    else if (preg_match('/rapporteur/i', $fonction)) {
      if (preg_match('/spécial/i', $fonction)) return 60;
      return 55;
    } else if (preg_match('/membre/i', $fonction)) {
      if (preg_match('/(suppleant|suppléant)/i', $fonction)) return 30;
      else if ($fonction === "membre") return 40;
      else if (preg_match('/bureau/i', $fonction)) return 85;
      return 50;
    } else if (preg_match('/apparent/i', $fonction)) return 20;
    else if (preg_match('/reprise/i', $fonction)) return 10;
    return 0;
  }

  public function getNom() {
    return $this->getOrganisme()->getNom();
  }
  public function getType() {
    return $this->getOrganisme()->getType();
  }
  public function getSlug() {
    return $this->getOrganisme()->getSlug();
  }

  public function getGroupeAcronyme() {
    return myTools::getObjectGroupeAcronyme($this);
  }
}
