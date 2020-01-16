<?php 

namespace IS\QueryBuilder\Database\Helper;

Class Debug
{

	/**
	 *
	 * Exception fırlatır veya hata basar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $name
	 * @param array $data
	 * @param string $sql
	 * @param boolean $exception
	 * @return string 
	 *
	 */
	public static function sendException($name, $data, $sql, $exception = true)
	{

		if ($exception) {
			throw new DatabaseException($name);
		}

		die(self::getExceptionTemplate($name, $data[0], $data[2], $sql));
	}

	/**
	 *
	 * Exception template'ini döndürür.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param string $name
	 * @param string $errorNumber
	 * @param string $erroMessage
	 * @param string $sql
	 * @return string 
	 *
	 */
	private static function getExceptionTemplate($name, $errorNumber, $erroMessage, $sql)
	{

		$template = file_get_contents(__DIR__ . '/ExceptionTemplate.html');
		$template = str_replace('{{ name }}', $name, $template);
		$template = str_replace('{{ errornumber }}', $errorNumber, $template);
		$template = str_replace('{{ errormessage }}', $erroMessage, $template);
		$template = str_replace('{{ sqlquery }}', $sql, $template);
		return $template;
	}

	/**
	 *
	 * Ekrana sql sorgularının detaylarını basar.
	 *
	 * @author Ismail Satilmis <ismaiil_0234@hotmail.com>
	 * @param array $debug
	 * @return string 
	 *
	 */
    public static function debugOutput($debug)
    {

		$i = $speed = 0;
		$content = '';
		foreach($debug as $log){
		    $content .= '<tr>';
		    $content .= '<td>' . (++$i) . ' </td>';
		    $content .= '<td>' . $log['sql'] . '</td>';
		    $content .= '<td>' . number_format($log['speed'], 3, '.', '') . ' MS</td>';
		    $content .= '<td>' . $log['speed'] . '</td>';
		    $content .= '</tr>';
		    $speed += $log['speed'];
		}

		$content .= '<tr>';
		$content .= '<td>' . (++$i) . '</td>';
		$content .= '<td>Total</td>';
		$content .= '<td>' . number_format($speed, 3, '.', '') . ' MS</td>';
		$content .= '<td>' . $speed . '</td>';
		$content .= '</tr>';

		return str_replace('{{ content }}', $content, file_get_contents(__DIR__ . '/DebugOutputTemplate.html'));
    }

}