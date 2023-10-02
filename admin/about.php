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
 *  \file       htdocs/extmail/index.php
 *  \ingroup    extmail
 *  \brief      Page to show product set
 */


$res=@include("../../main.inc.php");                   // For root directory
if (! $res) $res=@include("../../../main.inc.php");    // For "custom" directory


// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

dol_include_once("/extmail/lib/extmail.lib.php");

// Translations
$langs->load("extmail@extmail");

// Translations
$langs->load("errors");
$langs->load("admin");
$langs->load("other");

// Access control
if (! $user->admin) {
    accessforbidden();
}

$versions = array(
    array('version' => '1.0.0', 'date' => '02/10/2023', 'updates' => $langs->trans('ExtMailTagsFirstVersion')),
);
/*
 * View
 */

$form = new Form($db);

llxHeader('', $langs->trans('ExtMailTagsAbout'));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'. $langs->trans("BackToModuleList") . '</a>';
print load_fiche_titre($langs->trans('ExtMailTagsAbout'), $linkback);

// Configuration header
$head = extmail_prepare_admin_head();
dol_fiche_head(
    $head,
    'about',
    $langs->trans("ModuleExtMailTagsName"),
    0,
    'extmail@extmail'
);

// About page goes here
echo $langs->transnoentities("ExtMailTagsAboutPage");

echo '<br />';

print '<h2>'.$langs->trans("About").'</h2>';
print $langs->transnoentities("ExtMailTagsAboutDescLong");

print '<hr />';
print '<h2>'.$langs->transnoentities("ExtMailTagsChangeLog").'</h2>';


print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("ExtMailTagsChangeLogVersion").'</td>';
print '<td>'.$langs->trans("ExtMailTagsChangeLogDate").'</td>';
print '<td>'.$langs->trans("ExtMailTagsChangeLogUpdates").'</td>';
print "</tr>\n";

foreach ($versions as $version)
{
    print '<tr class="oddeven">';
    print '<td>';
    print $version['version'];
    print '</td>';
    print '<td>';
    print $version['date'];
    print '</td>';
    print '<td>';
    print $version['updates'];
    print '</td>';
    print '</tr>';
}


print '</table>';

// Page end
dol_fiche_end();
llxFooter();
$db->close();
