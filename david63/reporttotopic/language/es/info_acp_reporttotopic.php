<?php
/**
*
* @package Report to Topic
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPORTTOTOPIC'					=> 'Reportar el Tema',
	'ACP_REPORTTOTOPIC_CONFIG'			=> 'Configuración de Reportar el Tema',
	'ACP_REPORTTOTOPIC_CONFIG_EXPLAIN'	=> 'Configuración principal de Reportar el Tema.',
	'ACP_REPORTTOTOPIC_CONFIG_SUCCESS'	=> '¡La configuración principal ha sido actualizada correctamente!',
	'ACP_REPORTTOTOPIC_PM_CONFIG'		=> 'Configuración de Reporte de MP',

	'FORUM_NOT_EXISTS'					=> 'El foro requerido (%1$s) no existe',

	'PARSE_SIG'							=> 'Mostrar firma',

	'R2T_DEST_FORUM'					=> 'Foro destino predeterminado',
	'R2T_DEST_FORUM_EXPLAIN'			=> 'Seleccionar el foro que se utilizará como foro destino predeterminado para los mensajes de reporte. Usted puede seleccionar de manera foro un foro de reporte diferente.',
	'R2T_PM_DEST_FORUM'					=> 'Foro destino predeterminado de Reporte de MP',
	'R2T_PM_DEST_FORUM_EXPLAIN'			=> 'Seleccione un foro que se utilizará para los mensaje MP reportados.',
	'R2T_PM_TITLE'						=> 'Título de Reporte de MP',
	'R2T_PM_TITLE_EXPLAIN'				=> 'Aquí puede definir el título del mensaje para publicar MP reportados. Puede usar fichas (tokens) en el título del tema.',
	'R2T_PM_TEMPLATE'					=> 'Plantilla de MP',
	'R2T_PM_TEMPLATE_EXPLAIN'			=> 'Aquí puede definir el formato que tendrán los mensajes de reportes de PM. Mediante el uso de fichas (tokens) puede especificar qué información se mostrará. Puede usar BBCodes en la plantilla del mensaje.',
	'R2T_POST_TITLE'					=> 'Título de mensaje reportado',
	'R2T_POST_TITLE_EXPLAIN'			=> 'Aquí puede definir el título del mensaje para publicar temas reportados. Puede usar fichas (tokens) en el título del tema',
	'R2T_POST_TEMPLATE'					=> 'Plantilla de mensaje',
	'R2T_POST_TEMPLATE_EXPLAIN'			=> 'Aquí puede definir el formato que tendrán los mensajes reportados de mensajes. Mediante el uso de fichas (tokens) puede especificar qué información se mostrará. Puede usar BBCodes en la plantilla del mensaje.',
	'R2T_SELECT_DEST_FORUM'				=> 'Foro destino de Reporte',
	'R2T_SELECT_DEST_FORUM_EXPLAIN'		=> 'Seleccione el foro en el que se publicarán los reportes que se hacen de este foro. Si no selecciona ninguno, se utilizará el foro por defecto.',

	'REPORTTOTOPIC_LOG'					=> '<strong>Ajustes de Reportar el Tema actualizado</strong>',

	'TOKEN'								=> 'Ficha',
	'TOKENS'							=> 'Fichas',
	'TOKENS_EXPLAIN'					=> 'Fichas (Tokens) son marcadores de posición para varias piezas de información que se pueden mostrar en el mensaje de reporte.<br /><br /><strong>Tenga en cuenta que sólo las fichas (tokens) que se indican a continuación pueden ser utilizados en el mensaje reportado.</strong>',
	'TOKEN_DEFINITION'					=> '¿Qué va a reemplazar?',
));