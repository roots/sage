<?php

/**
 * Localization of CSS Optimiser Interface of CSSTidy
 *
 * Copyright 2005, 2006, 2007 Florian Schmitz
 *
 * This file is part of CSSTidy.
 *
 *  CSSTidy is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation; either version 2.1 of the License, or
 *   (at your option) any later version.
 *
 *   CSSTidy is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Lesser General Public License for more details.
 *
 *   You should have received a copy of the GNU Lesser General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
 * @package csstidy
 * @author Florian Schmitz (floele at gmail dot com) 2005-2007
 * @author Brett Zamir (brettz9 at yahoo dot com) 2007
 */


if (isset($_GET['lang'])) {
	$l = $_GET['lang'];
} elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	$l = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$l = strtolower(substr($l, 0, 2));
} else {
	$l = '';
}

$l = (in_array($l, array('de', 'fr', 'zh'))) ? $l : 'en';

// note 5 in all but French, and 40 in all are orphaned

$lang = array();
$lang['en'][0] = 'CSS Formatter and Optimiser/Optimizer (based on CSSTidy ';
$lang['en'][1] = 'CSS Formatter and Optimiser';
$lang['en'][2] = '(based on';
$lang['en'][3] = '(plaintext)';
$lang['en'][4] = 'Important Note:';
$lang['en'][6] = 'Your code should be well-formed. This is <strong>not a validator</strong> which points out errors in your CSS code. To make sure that your code is valid, use the <a href="http://jigsaw.w3.org/css-validator/">W3C Validator</a>.';
$lang['en'][7] = 'all comments are removed';
$lang['en'][8] = 'CSS Input:';
$lang['en'][9] = 'CSS-Code:';
$lang['en'][10] = 'CSS from URL:';
$lang['en'][11] = 'Code Layout:';
$lang['en'][12] = 'Compression&#160;(code&#160;layout):';
$lang['en'][13] = 'Highest (no readability, smallest size)';
$lang['en'][14] = 'High (moderate readability, smaller size)';
$lang['en'][15] = 'Standard (balance between readability and size)';
$lang['en'][16] = 'Low (higher readability)';
$lang['en'][17] = 'Custom (enter below)';
$lang['en'][18] = 'Custom <a href="http://csstidy.sourceforge.net/templates.php">template</a>';
$lang['en'][19] = 'Options';
$lang['en'][20] = 'Sort Selectors (caution)';
$lang['en'][21] = 'Sort Properties';
$lang['en'][22] = 'Regroup selectors';
$lang['en'][23] = 'Optimise shorthands';
$lang['en'][24] = 'Compress colors';
$lang['en'][25] = 'Lowercase selectors';
$lang['en'][26] = 'Case for properties:';
$lang['en'][27] = 'Lowercase';
$lang['en'][28] = 'No or invalid CSS input or wrong URL!';
$lang['en'][29] = 'Uppercase';
$lang['en'][30] = 'lowercase elementnames needed for XHTML';
$lang['en'][31] = 'Remove unnecessary backslashes';
$lang['en'][32] = 'convert !important-hack';
$lang['en'][33] = 'Output as file';
$lang['en'][34] = 'Bigger compression because of smaller newlines (copy &#38; paste doesn\'t work)';
$lang['en'][35] = 'Process CSS';
$lang['en'][36] = 'Compression Ratio';
$lang['en'][37] = 'Input';
$lang['en'][38] = 'Output';
$lang['en'][39] = 'Language';
$lang['en'][41] = 'Attention: This may change the behaviour of your CSS Code!';
$lang['en'][42] = 'Remove last ;';
$lang['en'][43] = 'Discard invalid properties';
$lang['en'][44] = 'Only safe optimisations';
$lang['en'][45] = 'Compress font-weight';
$lang['en'][46] = 'Save comments';
$lang['en'][47] = 'Do not change anything';
$lang['en'][48] = 'Only seperate selectors (split at ,)';
$lang['en'][49] = 'Merge selectors with the same properties (fast)';
$lang['en'][50] = 'Merge selectors intelligently (slow)';
$lang['en'][51] = 'Preserve CSS';
$lang['en'][52] = 'Save comments, hacks, etc. Most optimisations can *not* be applied if this is enabled.';
$lang['en'][53] = 'None';
$lang['en'][54] = 'Don\'t optimise';
$lang['en'][55] = 'Safe optimisations';
$lang['en'][56] = 'All optimisations';
$lang['en'][57] = 'Add timestamp';
$lang['en'][58] = 'Copy to clipboard';
$lang['en'][59] = 'Back to top';
$lang['en'][60] = 'Your browser doesn\'t support copy to clipboard.';
$lang['en'][61] = 'For bugs and suggestions feel free to';
$lang['en'][62] = 'contact me';
$lang['en'][63] = 'Output CSS code as complete HTML document';
$lang['en'][64] = 'Code';
$lang['en'][65] = 'CSS to style CSS output';
$lang['en'][66] = 'You need to go to about:config in your URL bar, select \'signed.applets.codebase_principal_support\' in the filter field, and set its value to true in order to use this feature; however, be aware that doing so increases security risks.';


$lang['de'][0] = 'CSS Formatierer und Optimierer (basierend auf CSSTidy ';
$lang['de'][1] = 'CSS Formatierer und Optimierer';
$lang['de'][2] = '(basierend auf';
$lang['de'][3] = '(Textversion)';
$lang['de'][4] = 'Wichtiger Hinweis:';
$lang['de'][6] = 'Der CSS Code sollte wohlgeformt sein. Der CSS Code wird <strong>nicht auf Gültigkeit überprüft</strong>. Um sicherzugehen dass dein Code valide ist, benutze den <a href="http://jigsaw.w3.org/css-validator/">W3C Validierungsservice</a>.';
$lang['de'][7] = 'alle Kommentare werden entfernt';
$lang['de'][8] = 'CSS Eingabe:';
$lang['de'][9] = 'CSS-Code:';
$lang['de'][10] = 'CSS von URL:';
$lang['de'][11] = 'Code Layout:';
$lang['de'][12] = 'Komprimierung&#160;(Code&#160;Layout):';
$lang['de'][13] = 'Höchste (keine Lesbarkeit, niedrigste Größe)';
$lang['de'][14] = 'Hoch (mittelmäßige Lesbarkeit, geringe Größe)';
$lang['de'][15] = 'Standard (Kompromiss zwischen Lesbarkeit und Größe)';
$lang['de'][16] = 'Niedrig (höhere Lesbarkeit)';
$lang['de'][17] = 'Benutzerdefiniert (unten eingeben)';
$lang['de'][18] = 'Benutzerdefinierte <a href="http://csstidy.sourceforge.net/templates.php">Vorlage</a>';
$lang['de'][19] = 'Optionen';
$lang['de'][20] = 'Selektoren sortieren (Vorsicht)';
$lang['de'][21] = 'Eigenschaften sortieren';
$lang['de'][22] = 'Selektoren umgruppieren';
$lang['de'][23] = 'Shorthands optimieren';
$lang['de'][24] = 'Farben komprimieren';
$lang['de'][25] = 'Selektoren in Kleinbuchstaben';
$lang['de'][26] = 'Groß-/Kleinschreibung für Eigenschaften';
$lang['de'][27] = 'Kleinbuchstaben';
$lang['de'][28] = 'Keine oder ungültige CSS Eingabe oder falsche URL!';
$lang['de'][29] = 'Großbuchstaben';
$lang['de'][30] = 'kleingeschriebene Elementnamen benötigt für XHTML';
$lang['de'][31] = 'Unnötige Backslashes entfernen';
$lang['de'][32] = '!important-Hack konvertieren';
$lang['de'][33] = 'Als Datei ausgeben';
$lang['de'][34] = 'Größere Komprimierung augrund von kleineren Neuezeile-Zeichen';
$lang['de'][35] = 'CSS verarbeiten';
$lang['de'][36] = 'Komprimierungsrate';
$lang['de'][37] = 'Eingabe';
$lang['de'][38] = 'Ausgabe';
$lang['de'][39] = 'Sprache';
$lang['de'][41] = 'Achtung: Dies könnte das Verhalten ihres CSS-Codes verändern!';
$lang['de'][42] = 'Letztes ; entfernen';
$lang['de'][43] = 'Ungültige Eigenschaften entfernen';
$lang['de'][44] = 'Nur sichere Optimierungen';
$lang['de'][45] = 'font-weight komprimieren';
$lang['de'][46] = 'Kommentare beibehalten';
$lang['de'][47] = 'Nichts ändern';
$lang['de'][48] = 'Selektoren nur trennen (am Komma)';
$lang['de'][49] = 'Selektoren mit gleichen Eigenschaften zusammenfassen (schnell)';
$lang['de'][50] = 'Selektoren intelligent zusammenfassen (langsam!)';
$lang['de'][51] = 'CSS erhalten';
$lang['de'][52] = 'Kommentare, Hacks, etc. speichern. Viele Optimierungen sind dann aber nicht mehr möglich.';
$lang['de'][53] = 'Keine';
$lang['de'][54] = 'Nicht optimieren';
$lang['de'][55] = 'Sichere Optimierungen';
$lang['de'][56] = 'Alle Optimierungen';
$lang['de'][57] = 'Zeitstempel hinzufügen';
$lang['de'][58] = 'Copy to clipboard';
$lang['de'][59] = 'Back to top';
$lang['de'][60] = 'Your browser doesn\'t support copy to clipboard.';
$lang['de'][61] = 'For bugs and suggestions feel free to';
$lang['de'][62] = 'contact me';
$lang['de'][63] = 'Output CSS code as complete HTML document';
$lang['de'][64] = 'Code';
$lang['de'][65] = 'CSS to style CSS output';
$lang['de'][66] = 'You need to go to about:config in your URL bar, select \'signed.applets.codebase_principal_support\' in the filter field, and set its value to true in order to use this feature; however, be aware that doing so increases security risks.';


$lang['fr'][0] = 'CSS Formatteur et Optimiseur (basé sur CSSTidy ';
$lang['fr'][1] = 'CSS Formatteur et Optimiseur';
$lang['fr'][2] = '(basé sur ';
$lang['fr'][3] = '(Version texte)';
$lang['fr'][4] = 'Note Importante&#160;:';
$lang['fr'][6] = 'Votre code doit être valide. Ce n’est <strong>pas un validateur</strong> qui signale les erreurs dans votre code CSS. Pour être sûr que votre code est correct, utilisez le validateur&#160;: <a href="http://jigsaw.w3.org/css-validator/">W3C Validator</a>.';
$lang['fr'][7] = 'tous les commentaires sont enlevés';
$lang['fr'][8] = 'Champ CSS&#160;:';
$lang['fr'][9] = 'Code CSS&#160;:';
$lang['fr'][10] = 'CSS en provenance d’une URL&#160;:<br />';
$lang['fr'][11] = 'Mise en page du code&#160;:';
$lang['fr'][12] = 'Compression (mise en page du code)&#160;:';
$lang['fr'][13] = 'La plus élevée (aucune lisibilité, taille minimale)';
$lang['fr'][14] = 'Élevée (lisibilité modérée, petite taille)';
$lang['fr'][15] = 'Normale (équilibre entre lisibilité et taille)';
$lang['fr'][16] = 'Faible (lisibilité élevée)';
$lang['fr'][17] = 'Sur mesure (entrer ci-dessous)';
$lang['fr'][18] = '<a href="http://csstidy.sourceforge.net/templates.php">Gabarit</a> sur mesure';
$lang['fr'][19] = 'Options';
$lang['fr'][20] = 'Trier les sélecteurs (attention)';
$lang['fr'][21] = 'Trier les propriétés';
$lang['fr'][22] = 'Regrouper les sélecteurs';
$lang['fr'][23] = 'Propriétés raccourcies';
$lang['fr'][24] = 'Compresser les couleurs';
$lang['fr'][25] = 'Sélecteurs en minuscules';
$lang['fr'][26] = 'Case pour les propriétés&#160;:';
$lang['fr'][27] = 'Minuscule';
$lang['fr'][28] = 'CSS non valide ou URL incorrecte&#160;!';
$lang['fr'][29] = 'Majuscule';
$lang['fr'][30] = 'les noms des éléments en minuscules (indispensables pour XHTML)';
$lang['fr'][31] = 'enlever les antislashs inutiles';
$lang['fr'][32] = 'convertir !important-hack';
$lang['fr'][33] = 'Sauver en tant que fichier';
$lang['fr'][34] = 'Meilleure compression grâce aux caractères de saut de ligne plus petits (copier &#38; coller ne marche pas)';
$lang['fr'][35] = 'Compresser la CSS';
$lang['fr'][36] = 'Facteur de Compression';
$lang['fr'][37] = 'Entrée';
$lang['fr'][38] = 'Sortie';
$lang['fr'][39] = 'Langue';
$lang['fr'][41] = 'Attention&#160;: ceci peut changer le comportement de votre code CSS&#160;!';
$lang['fr'][42] = 'Enlever le dernier ;';
$lang['fr'][43] = 'Supprimer les propriétés non valide';
$lang['fr'][44] = 'Seulement les optimisations sûres';
$lang['fr'][45] = 'Compresser font-weight';
$lang['fr'][46] = 'Sauvegarder les commentaires ';
$lang['fr'][47] = 'Ne rien changer';
$lang['fr'][48] = 'Sépare les sélecteurs (sépare au niveau de ,)';
$lang['fr'][49] = 'Fusionne les sélecteurs avec les mêmes propriétés (rapide)';
$lang['fr'][50] = 'Fusionne les sélecteurs intelligemment (lent)';
$lang['fr'][51] = 'Préserver la CSS';
$lang['fr'][52] = 'Sauvegarder les commentaires, hacks, etc. La plupart des optimisations ne peuvent *pas* être appliquées si cela est activé.';
$lang['fr'][53] = 'Aucun';
$lang['fr'][54] = 'Ne pas optimiser';
$lang['fr'][55] = 'Optimisations sûres';
$lang['fr'][56] = 'Toutes les optimisations';
$lang['fr'][57] = 'Ajouter un timestamp';
$lang['fr'][58] = 'Copier dans le presse-papiers';
$lang['fr'][59] = 'Retour en haut';
$lang['fr'][60] = 'Votre navigateur ne suporte pas la copie vers le presse-papiers.';
$lang['fr'][61] = 'Pour signaler des bugs ou pour des suggestions,';
$lang['fr'][62] = 'contactez-moi';
$lang['fr'][63] = 'Sauver le code CSS comme document complet HTML';
$lang['fr'][64] = 'Code';
$lang['fr'][65] = 'CSS pour colorier la sortie CSS';
$lang['fr'][66] = 'Vous devez aller dans about:config dans votre barre d’adresse, selectionner \'signed.applets.codebase_principal_support\' dans le champ Filtre et attribuez-lui la valeur \'true\' pour utiliser cette fonctionnalité; toutefois, soyez conscient que cela augmente les risques de sécurité.';


$lang['zh'][0] = 'CSS整形與最佳化工具(使用 CSSTidy ';
$lang['zh'][1] = 'CSS整形與最佳化工具';
$lang['zh'][2] = '(使用';
$lang['zh'][3] = '(純文字)';
$lang['zh'][4] = '重要事項:';
$lang['zh'][6] = '你的原始碼必須是良構的(well-formed). 這個工具<strong>沒有內建驗證器(validator)</strong>. 驗證器能夠指出你CSS原始碼裡的錯誤. 請使用 <a href="http://jigsaw.w3.org/css-validator/">W3C 驗證器</a>, 確保你的原始碼合乎規範.';
$lang['zh'][7] = '所有註解都移除了';
$lang['zh'][8] = 'CSS 輸入:';
$lang['zh'][9] = 'CSS 原始碼:';
$lang['zh'][10] = 'CSS 檔案網址(URL):';
$lang['zh'][11] = '原始碼規劃:';
$lang['zh'][12] = '壓縮程度(原始碼規劃):';
$lang['zh'][13] = '最高 (沒有辦法讀, 檔案最小)';
$lang['zh'][14] = '高 (適度的可讀性, 檔案小)';
$lang['zh'][15] = '標準 (兼顧可讀性與檔案大小)';
$lang['zh'][16] = '低 (注重可讀性)';
$lang['zh'][17] = '自訂 (在下方設定)';
$lang['zh'][18] = '自訂<a href="http://csstidy.sourceforge.net/templates.php">樣板</a>';
$lang['zh'][19] = '選項';
$lang['zh'][20] = '整理選擇符(請謹慎使用)';
$lang['zh'][21] = '整理屬性';
$lang['zh'][22] = '重組選擇符';
$lang['zh'][23] = '速記法(shorthand)最佳化';
$lang['zh'][24] = '壓縮色彩語法';
$lang['zh'][25] = '改用小寫選擇符';
$lang['zh'][26] = '屬性的字形:';
$lang['zh'][27] = '小寫';
$lang['zh'][28] = '沒有輸入CSS, 語法不符合規定, 或是網址錯誤!';
$lang['zh'][29] = '大寫';
$lang['zh'][30] = 'XHTML必須使用小寫的元素名稱';
$lang['zh'][31] = '移除不必要的反斜線';
$lang['zh'][32] = '轉換 !important-hack';
$lang['zh'][33] = '輸出成檔案形式';
$lang['zh'][34] = '由於比較少換行字元, 會有更大的壓縮比率(複製&#38;貼上沒有用)';
$lang['zh'][35] = '執行';
$lang['zh'][36] = '壓縮比率';
$lang['zh'][37] = '輸入';
$lang['zh'][38] = '輸出';
$lang['zh'][39] = '語言';
$lang['zh'][41] = '注意: 這或許會變更你CSS原始碼的行為!';
$lang['zh'][42] = '除去最後一個分號';
$lang['zh'][43] = '拋棄不符合規定的屬性';
$lang['zh'][44] = '只安全地最佳化';
$lang['zh'][45] = '壓縮 font-weight';
$lang['zh'][46] = '保留註解';
$lang['zh'][47] = '什麼都不要改';
$lang['zh'][48] = '只分開原本用逗號分隔的選擇符';
$lang['zh'][49] = '合併有相同屬性的選擇符(快速)';
$lang['zh'][50] = '聰明地合併選擇符(慢速)';
$lang['zh'][51] = '保護CSS';
$lang['zh'][52] = '保留註解與 hack 等等. 如果啟用這個選項, 大多數的最佳化程序都不會執行.';
$lang['zh'][53] = '不改變';
$lang['zh'][54] = '不做最佳化';
$lang['zh'][55] = '安全地最佳化';
$lang['zh'][56] = '全部最佳化';
$lang['zh'][57] = '加上時間戳記';
$lang['zh'][58] = '复制到剪贴板';
$lang['zh'][59] = '回到页面上方';
$lang['zh'][60] = '你的浏览器不支持复制到剪贴板。';
$lang['zh'][61] = '如果程序有错误或你有建议，欢迎';
$lang['zh'][62] = '和我联系';
$lang['zh'][63] = 'Output CSS code as complete HTML document';
$lang['zh'][64] = '代码';
$lang['zh'][65] = 'CSS to style CSS output';
$lang['zh'][66] = 'You need to go to about:config in your URL bar, select \'signed.applets.codebase_principal_support\' in the filter field, and set its value to true in order to use this feature; however, be aware that doing so increases security risks.';
