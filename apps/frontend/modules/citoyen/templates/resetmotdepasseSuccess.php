<?php 
if ($sf_user->hasAttribute('resetmdp'))
{
  $route = 'citoyen/resetmotdepasse?slug='.$slug.'&token='.$token;
  $titre = 'Choisissez un nouveau mot de passe';
?>
<div class="boite_form">
  <div class="b_f_h"><div class="b_f_hg"></div><div class="b_f_hd"></div></div>
    <div class="b_f_cont">
      <div class="b_f_text">
        <?php echo $form->renderFormTag(url_for($route)); ?>
        <table>
          <tr class="cel1">
            <th colspan="2">
              <h1><?php echo $titre; ?></h1>
            </th>
          </tr>
          <tr class="cel2">
            <th><?php echo $form['password']->renderLabel() ?></th>
            <td>
              <?php echo $form['password']->renderError(); ?>
              <?php echo $form['password']; ?>
            </td>
          </tr>
          <tr class="cel1">
            <th><?php echo $form['password_bis']->renderLabel() ?></th>
            <td>
              <?php echo $form['password_bis']->renderError(); ?>
              <?php echo $form['password_bis']; ?>
            </td>
          </tr>
          <tr class="cel2">  
            <td colspan="2"><input type="submit" value="Valider" style="float:right;" /></td>
          </tr>
          <tr class="cel1">
            <th colspan="2"><a href="<?php echo url_for('@homepage') ?>">Annuler</a></th>
          </tr>
        </table>
        </form>
        <br />
      </div>
    </div>
  <div class="b_f_b"><div class="b_f_bg"></div><div class="b_f_bd"></div></div>
</div>
<?php
}
else
{
  $route = 'citoyen/resetmotdepasse';
  $titre = 'Mot de passe oublié';
?>
<div class="boite_form">
  <div class="b_f_h"><div class="b_f_hg"></div><div class="b_f_hd"></div></div>
    <div class="b_f_cont">
      <div class="b_f_text">
        <?php echo $form->renderFormTag(url_for($route)); ?>
        <table>
          <tr class="cel1">
            <th colspan="2">
              <h1><?php echo $titre; ?></h1>
            </th>
          </tr>
          <tr class="cel2">
            <th><?php echo $form['login']->renderLabel() ?></th>
            <td>
              <?php echo $form['login']->renderError(); ?>
              <?php echo $form['login']; ?>
            </td>
          </tr>
          <tr class="cel1">
            <td colspan="2"><input type="submit" value="Valider" style="float:right;" /></td>
          </tr>
          <tr class="cel1">
            <th colspan="2"><a href="<?php echo url_for('@homepage') ?>">Annuler</a></th>
          </tr>
        </table>
        </form>
        <br />
      </div>
    </div>
  <div class="b_f_b"><div class="b_f_bg"></div><div class="b_f_bd"></div></div>
</div>
<?php
}
?>