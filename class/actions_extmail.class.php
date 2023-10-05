<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2017 Mikael Carlavan <contact@mika-carl.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/extmail/class/actions_extmail.class.php
 *  \ingroup    extmail
 *  \brief      File of class to manage actions
 */
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/usergroup.class.php';
require_once DOL_DOCUMENT_ROOT.'/comm/action/class/actioncomm.class.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/usergroup.class.php';


class ActionsExtMail
{
    function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager)
    {
        global $langs, $db, $mysoc, $conf, $user, $arrayfields;

        $langs->load('extmail@extmail');

        $elements = array('commande', 'facture', 'propal', 'project');

        if (in_array($object->element, $elements) && empty($user->socid) && $user->rights->extmail->lire) {
            if ($object->element == 'project') {
                if ($object->statut != Project::STATUS_CLOSED) {
                    print dolGetButtonAction('', $langs->trans('SendMail'), 'default', $_SERVER["PHP_SELF"].'?action=presend&token='.newToken().'&id='.$object->id.'&mode=init#formmailbeforetitle', '');
                }
            } else if ($object->element == 'commande') {
                $usercansend = (empty($conf->global->MAIN_USE_ADVANCED_PERMS) || $user->hasRight('commande', 'order_advance', 'send'));

                if ($object->statut > Commande::STATUS_DRAFT || !empty($conf->global->COMMANDE_SENDBYEMAIL_FOR_ALL_STATUS)) {
                    if ($usercansend) {
                        print dolGetButtonAction('', $langs->trans('SendMail'), 'default', $_SERVER["PHP_SELF"].'?action=presend&token='.newToken().'&id='.$object->id.'&mode=init#formmailbeforetitle', '');
                    } else {
                        print dolGetButtonAction('', $langs->trans('SendMail'), 'default', $_SERVER['PHP_SELF']. '#', '', false);
                    }
                }
            } else if ($object->element == 'facture') {
                $objectidnext = $object->getIdReplacingInvoice();
                $usercansend = (empty($conf->global->MAIN_USE_ADVANCED_PERMS) || (!empty($conf->global->MAIN_USE_ADVANCED_PERMS) && !empty($user->rights->facture->invoice_advance->send)));
                $params = array(
                    'attr' => array(
                        'class' => 'classfortooltip'
                    )
                );

                if (($object->statut == Facture::STATUS_VALIDATED || $object->statut == Facture::STATUS_CLOSED) || !empty($conf->global->FACTURE_SENDBYEMAIL_FOR_ALL_STATUS)) {
                    if ($objectidnext) {
                        print '<span class="butActionRefused classfortooltip" title="'.$langs->trans("DisabledBecauseReplacedInvoice").'">'.$langs->trans('SendMail').'</span>';
                    } else {
                        if ($usercansend) {
                            print dolGetButtonAction('', $langs->trans('SendMail'), 'default', $_SERVER['PHP_SELF'].'?facid='.$object->id.'&action=presend&mode=init#formmailbeforetitle', '', true, $params);
                        } else {
                            print dolGetButtonAction('', $langs->trans('SendMail'), 'default', '#', '', false, $params);
                        }
                    }
                }
            } else if ($object->element == 'propal') {
                $usercansend = (empty($conf->global->MAIN_USE_ADVANCED_PERMS) || (!empty($conf->global->MAIN_USE_ADVANCED_PERMS) && !empty($user->rights->propal->propal_advance->send)));

                if ($object->statut == Propal::STATUS_VALIDATED || $object->statut == Propal::STATUS_SIGNED || !empty($conf->global->PROPOSAL_SENDBYEMAIL_FOR_ALL_STATUS)) {
                    print dolGetButtonAction('', $langs->trans('SendMail'), 'default', $_SERVER["PHP_SELF"].'?action=presend&token='.newToken().'&id='.$object->id.'&mode=init#formmailbeforetitle', '', $usercansend);
                }
            }
        }

        return 0;
    }
}


