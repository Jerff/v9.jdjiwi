<?php
# Внимание! Данный файл должен оставаться в кодировке WINDOWS-1251! #

setlocale(LC_ALL, 'ru_RU.CP1251');

/**
 * Типограф, версия 2.2.0 (PHP5)
 * ------------------------------------------------------------
 * Страничка: http://rmcreative.ru/article/programming/typograph/  
 *
 * Авторы:
 * — Оранский Максим ( http://smee-again.livejournal.com/ )
 *    Первоначальный код и правила, тестирование.
 * — Макаров Александр ( http://rmcreative.ru/ )
 *    Код, тестирование, правила, идеи, дальнейшая поддержка.
 *
 * Спасибо:
 * — faZeful, Naruvi, Shaman, Eagle, grasshopper, Max, Reki, 
 *   Zav (за предложение и начальную реализацию некоторых правил для версии 2.0.7)
 *
 * Отдельное спасибо Max-у за плагин к Wordpress.
 *
 * При создании правил типографа помимо личного опыта использовались:
 * — http://philigon.ru/
 * — http://artlebedev.ru/kovodstvo/
 * — http://pvt.livejournal.com/
 * ------------------------------------------------------------
 */
class Typographus{
    //Ключи опций
    const CONVERT_E = 'convert_e';
    const HTML_ENTITIES = 'html_entities';
  
    private $_options = array(
        self::CONVERT_E => false,
        self::HTML_ENTITIES => false
    );
    
    private $_encoding;
    
	private $_sym = array(
		'nbsp'    => '&nbsp;',
		'lnowrap' => '<nobr>',
		'rnowrap' => '</nobr>',

		'lquote'  => '«',
		'rquote'  => '»',
		'lquote2' => '„',
		'rquote2' => '“',
		'mdash'   => '&mdash;',
		'ndash'   => '&ndash;',
		'minus'   => '–', // соотв. по ширине символу +, есть во всех шрифтах

		'hellip'  => '…',
		'copy'    => '©',
		'trade'   => '<sup>™</sup>',
		'apos'    => '&#39;',   // см. http://fishbowl.pastiche.org/2003/07/01/the_curse_of_apos
		'reg'     => '<sup><small>®</small></sup>',
		'multiply' => '&times;',
		'1/2' => '&frac12;',
		'1/4' => '&frac14;',
		'3/4' => '&frac34;',
		'plusmn' => '&plusmn;',
		'rarr' => '&rarr;',
		'larr' => '&larr;',
	    'rsquo' => '&rsquo;'
	);

	/**
	 * Укажите кодировку для обработки текста в кодировке, отличной от WINDOWS-1251.
	 * @param String $encoding
	 */
	public function __construct($encoding = null){
	   $this->_encoding = $encoding;
	}

	private $_safeBlocks = array(
		'<pre[^>]*>' => '<\/pre>',
		'<style[^>]*>' => '<\/style>',
		'<script[^>]*>' => '<\/script>',
		'<!--' => '-->',
	    '<code[^>]*>' => '<\/code>',
	);

	/**
	 * Добавляет безопасный блок, который не будет обрабатываться типографом.
	 *
	 * @param String $openTag
	 * @param String $closeTag
	 */
	public function addSafeBlock($openTag, $closeTag){
		$this->_safeBlocks[$openTag] = $closeTag;
	}
	
	/**
	 * Убирает все безопасные блоки типографа.
	 * Полезно, если необходимо задать полностью свой набор.
	 */
	public function removeAllSafeBlocks(){
		$this->_safeBlocks = array();
	}

	/**
	 * Устанавливает соответствие между символом и его представлением.
	 *
	 * @param String $sym
	 * @param String $entity
	 */
	public function setSym($sym, $entity){
		$this->_sym[$sym] = $entity;
	}
	
	/**
	 * Устанавливает опцию типографа.
	 * см. «Ключи опций» выше.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setOpt($key, $value){
		$this->_options[$key] = $value;
	}
	
	/**
	 * При $opt = true все ё преобразуются в е.
	 * 
	 * @deprecated Оставлено для совместимости с предыдущими версиями.
	 * @param boolean $opt
	 */
	public function convertE($opt = false){
        $this->setOpt[self::CONVERT_E] = $opt;
	}

	/**
	 * Форматирует число как телефон.
	 * //TODO: доработать либо выкинуть…
	 * @param String $phone
	 * @return String
	 */
	/*private static function _phone($phone){
	    //89109179966 => 8 (910) 917-99-66
	    $result = $phone[0].' ('.$phone[1].$phone[2].$phone[3].') ';
	    $count = 0;
	    $buff='';
	    for ($i=strlen($phone)-1; $i>3; $i--){
	        $left = $i-3;
	        if ($left!=3){
	            if ($count<1){
	                $buff.=$phone[$i];
	                $count++;
	            }
	            else{
	                $buff.=$phone[$i].'-';
	                $count=0;
	            }
	        }
	        else{
	            $buff.=strrev(substr($phone, $i-2, 3));
	            break;
	        }
	    }
	    $result.=strrev($buff);
	    return $result;
	}*/

	/**
	 * Вызывает типограф, обходя html-блоки и безопасные блоки
	 * Для обработки текста в кодировке, отличной от WINDOWS-1251, укажите её
	 * вторым параметром.
	 *
	 * @param string $str
	 * @return string
	 */
	public function process($str){
		if($this->_encoding!=null){
			$str = iconv($this->_encoding, 'WINDOWS-1251', $str);	 
		}
		$str = html_entity_decode($str);
		
		$pattern = '(';
		foreach ($this->_safeBlocks as $start => $end){
			$pattern .= "$start.*$end|";
		}
		$pattern .= '<[^>]*[\s][^>]*>)';
		$str = preg_replace_callback("~$pattern~isU", array('self', '_stack'), $str);
		$str = $this->typo_text($str);
		$str = strtr($str, self::_stack());
		if($this->_encoding!=null){
			$str = iconv('WINDOWS-1251', $this->_encoding, $str);
		}
		return $str;
	}

	/**
	 * Накапливает исходный код безопасных блоков при использовании в качестве
	 * обратного вызова. При отдельном использовании возвращает накопленный
	 * массив.
	 *
	 * @param array $matches
	 * @return array
	 */
	private static function _stack($matches = false){
		static $safe_blocks = array();
		if ($matches !== false){
			$key = '<'.count($safe_blocks).'>';
			$safe_blocks[$key] = $matches[0];
			return $key;
		}
		else{
			$tmp = $safe_blocks;
			unset($safe_blocks);
			return $tmp;
		}
	}
	
	/**
	 * Применяет все переданные правила к тексту
	 * @param array
	 * @return String
	 */
	private function apply_rules($rules, $str){
      return preg_replace(array_keys($rules), array_values($rules), $str);
	}

	/**
	 * Главная функция типографа.
	 * @param String $str
	 * @return String
	 */
	private function typo_text($str){
		$sym = $this->_sym;
		if (trim($str) == '') return '';
		$html_tag = '(?:(?U)<.*>)';
		$hellip = '\.{3,5}';
		
		//Слово
		$word = '[a-zA-Zа-яА-Я_]';
		
		//Начало слова
		$phrase_begin = "(?:$hellip|$word|\n)";
		//Конец слова
        $phrase_end   = '(?:[)!?.:;#*\\\]|$|'.$word.'|'.$sym['rquote'].'|'.$sym['rquote2'].'|&quot;|"|'.$sym['hellip'].'|'.$sym['copy'].'|'.$sym['trade'].'|'.$sym['apos'].'|'.$sym['reg'].'|\')';
        //Знаки препинания (троеточие и точка - отдельный случай!)
        $punctuation = '[?!:,;]';
		//Аббревиатуры
        $abbr = 'ООО|ОАО|ЗАО|ЧП|ИП|НПФ|НИИ';
        //Предлоги и союзы
        $prepos = 'а|в|во|вне|и|или|к|о|с|у|о|со|об|обо|от|ото|то|на|не|ни|но|из|изо|за|уж|на|по|под|подо|пред|предо|про|над|надо|как|без|безо|что|да|для|до|там|ещё|их|или|ко|меж|между|перед|передо|около|через|сквозь|для|при|я';
        
        $metrics = 'мм|см|м|км|г|кг|б|кб|мб|гб|dpi|px';
        
        $shortages = 'г|гр|тов|пос|c|ул|д|пер|м';

        $money = 'руб\.|долл\.|евро|у\.е\.';
        $counts = 'млн\.|тыс\.';
        
		$any_quote = "(?:$sym[lquote]|$sym[rquote]|$sym[lquote2]|$sym[rquote2]|&quot;|\")";

		$rules_strict = array(
		  // Много пробелов или табуляций -> один пробел
		  '~( |\t)+~' => ' ',
		  // Запятые после «а» и «но». Если уже есть — не ставим.
		  '~([^,])\s(а|но)\s~' => '$1, $2 ',
		);
		
        $rules_symbols = array(
            //Лишние знаки.
            //TODO: сделать красиво
            '~([^!])!!([^!])~' => '$1!$2',        
            '~([^?])\?\?([^?])~' => '$1?$2',
            '~(\w);;(\s)~' => '$1;$2',
            '~(\w)\.\.(\s)~' => '$1.$2',
            '~(\w),,(\s)~' => '$1,$2',
            '~(\w)::(\s)~' => '$1:$2',
        
            '~(!!!)!+~' => '$1',
            '~(\?\?\?)\?+~' => '$1',
            '~(;;;);+~' => '$1',
            '~(\.\.\.)\.+~' => '$1',
            '~(,,,),+~' => '$1',
            '~(:::):+\s~' => '$1',
        
            //Занятная комбинация
            '~!\?~' => '?!',
        
            // Знаки (c), (r), (tm)
			'~\((c|с)\)~i' 	=> $sym['copy'],
			'~\(r\)~i' 	=>	$sym['reg'],
			'~\(tm\)~i'	=>	$sym['trade'],
        
            // От 2 до 5 знака точки подряд - на знак многоточия (больше - мб авторской задумкой).
			"~$hellip~" => $sym['hellip'],

			// Спецсимволы для 1/2 1/4 3/4
			'~\b1/2\b~'	=> $sym['1/2'],
			'~\b1/4\b~' => $sym['1/4'],
			'~\b3/4\b~' => $sym['3/4'],

            //LО'Лайт
            "~([a-zA-Z])'([а-яА-Я])~i" => '$1'.$sym['rsquo'].'$2',
        
			"~'~" => $sym['apos'], //str_replace?
		
			// Размеры 10x10, правильный знак + убираем лишние пробелы
			'~(\d+)\s{0,}?[x|X|х|Х|*]\s{0,}(\d+)~' => '$1'.$sym['multiply'].'$2',
        
        	//+-
			'~([^\+]|^)\+-~' => '$1'.$sym['plusmn'],
        
			//Стрелки
			'~([^-]|^)->~' => '$1'.$sym['rarr'],
			'~<-([^-]|$)~' => $sym['larr'].'$1',
        );
        
        $rules_quotes = array(
             // Разносим неправильные кавычки
		     '~([^"]\w+)"(\w+)"~' => '$1 "$2"',
		     '~"(\w+)"(\w+)~' => '"$1" $2',

             // Превращаем кавычки в ёлочки. Двойные кавычки склеиваем.
             "~(?<=\\s|^|[>(])($html_tag*)($any_quote)($html_tag*$phrase_begin$html_tag*)~"	=> '$1'.$sym['lquote'].'$3',
             "~($html_tag*(?:$phrase_end|[0-9]+)$html_tag*)($any_quote)($html_tag*$phrase_end$html_tag*|\\s|[,<-])~"	=> '$1'.$sym['rquote'].'$3',
        );
        
        $rules_braces = array(
		  // Оторвать скобку от слова
			'~(\w)\(~' => '$1 (',
          //Слепляем скобки со словами
		     '~\( ~s' => '(',
			 '~ \)~s' => ')',
		);

		$rules_main = array(
		    // Конфликт с «газо- и электросварка»
			// Оторвать тире от слова
			//'~(\w)- ~' => '$1 - ',
		
            //Знаки с предшествующим пробелом… нехорошо!
			'~('.$phrase_end.') +('.$punctuation.'|'.$sym['hellip'].')~' => '$1$2',
			'~('.$punctuation.')('.$phrase_begin.')~' => '$1 $2',
		
		    //Для точки отдельно
		    '~(\w)\s(?:\.)(\s|$)~' => '$1.$2',

		     //Неразрывные названия организаций и абревиатуры форм собственности
		     // ~ почему не один &nbsp;?
             // ! названия организаций тоже могут содержать пробел !
			'~('.$abbr.')\s+(«.*»)~' => $sym['lnowrap'].'$1 $2'.$sym['rnowrap'],

			 //Нельзя отрывать сокращение от относящегося к нему слова.
			 //Например: тов. Сталин, г. Воронеж
 			 //Ставит пробел, если его нет.
 			 '~(^|[^a-zA-Zа-яА-Я])('.$shortages.')\.\s?([А-Я0-9]+)~s' => '$1$2.'.$sym['nbsp'].'$3',

			 //Не отделять стр., с. и т.д. от номера.
			 '~(стр|с|табл|рис|илл)\.\s*(\d+)~si' => '$1.'.$sym['nbsp'].'$2',

			 //Не разделять 2007 г., ставить пробел, если его нет. Ставит точку, если её нет.
			'~([0-9]+)\s*([гГ])\.\s~s' => '$1'.$sym['nbsp'].'$2. ',
			
			 //Неразрывный пробел между цифрой и единицей измерения
			 '~([0-9]+)\s*('.$metrics.')~s' => '$1'.$sym['nbsp'].'$2',
			
             //Сантиметр и другие ед. измерения в квадрате, кубе и т.д.
             '~(\s'.$metrics.')(\d+)~' => '$1<sup>$2</sup>',	

			// Знак дефиса или два знака дефиса подряд — на знак длинного тире.
			// + Нельзя разрывать строку перед тире, например: Знание — сила, Курить — здоровью вредить.
			'~(\xa0| )+(?:--?|—|&mdash;)(?=\s)~' => $sym['nbsp'].$sym['mdash'],
		    '~^(?:--?|—|&mdash;)(?=\s)~' => $sym['mdash'],
		
    		//Прямая речь
		    '~(?:^|\s+)(?:--?|—|&mdash;)(?=\s)~' => "\n".$sym['nbsp'].$sym['mdash'],

			// Знак дефиса, ограниченный с обоих сторон цифрами — на знак короткого тире.
			'~(?<=\d)-(?=\d)~' => $sym['ndash'],

			// Нельзя оставлять в конце строки предлоги и союзы
			'~(?<=\s|^|\W)('.$prepos.')(\s+)~i' => '$1'.$sym['nbsp'],

			// Нельзя отрывать частицы бы, ли, же от предшествующего слова, например: как бы, вряд ли, так же.
			"~(?<=\\S)(\\s+)(ж|бы|б|же|ли|ль|либо|или)(?=$html_tag*[\\s)!?.])~i" => $sym['nbsp'].'$2',

			// Неразрывный пробел после инициалов.
			'~([А-ЯA-Z]\.)\s?([А-ЯA-Z]\.)\s?([А-Яа-яA-Za-z]+)~s' => '$1$2'.$sym['nbsp'].'$3',

            // Сокращения сумм не отделяются от чисел.			
			'~(\d+)\s?('.$counts.')~s'	=>	'$1'.$sym['nbsp'].'$2',
		
		    //«уе» в денежных суммах
		    '~(\d+|'.$counts.')\s?уе~s'	=>	'$1'.$sym['nbsp'].'у.е.',
		    
     		// Денежные суммы, расставляя пробелы в нужных местах.
			'~(\d+|'.$counts.')\s?('.$money.')~s'	=>	'$1'.$sym['nbsp'].'$2',

			// Неразрывные пробелы в кавычках
			//"/($sym[lquote]\S*)(\s+)(\S*$sym[rquote])/U" => '$1'.$sym["nbsp"].'$3',
			
			//Телефоны
            //'~(?:тел\.?/?факс:?\s?\((\d+)\))~i' => 'тел./факс:'.$sym['nbsp'].'($1)',
			
			//'~тел[:.] ?(\d+)~ie' => "'<span style=\"white-space:nowrap\">тел: '.self::_phone('$1').'</span>'",
			
			//Номер версии программы пишем неразрывно с буковкой v.
			'~([vв]\.) ?([0-9])~i' => '$1'.$sym['nbsp'].'$2',
			'~(\w) ([vв]\.)~i' => '$1'.$sym['nbsp'].'$2',
		
		    //% не отделяется от числа
		    '~([0-9]+)\s+%~' => '$1%',
		
			//IP-адреса рвать нехорошо
			'~(1\d{0,2}|2(\d|[0-5]\d)?)\.(0|1\d{0,2}|2(\d|[0-5]\d)?)\.(0|1\d{0,2}|2(\d|[0-5]\d)?)\.(0|1\d{0,2}|2(\d|[0-5]\d)?)~' =>
			$sym['lnowrap'].'$0'.$sym['rnowrap'],
		);
		
		$str = $this->apply_rules($rules_quotes, $str);
				
		// Вложенные кавыки.
        $i=0; $lev = 5;
        while (($i<$lev) && preg_match('~«(?:[^»]*?)«~', $str)){
			$i++;
            $str = preg_replace('~«([^»]*?)«(.*?)»~s', '«$1'.$sym['lquote2'].'$2'.$sym['rquote2'], $str);
		}

        $i=0;
        while (($i++<$lev) && preg_match('~»(?:[^«]*?)»~', $str)){
		  $i++;
          $str = preg_replace('~»([^«]*?)»~', $sym['rquote2'].'$1»', $str);
		}
        
        $str = $this->apply_rules($rules_strict, $str);        
		$str = $this->apply_rules($rules_main, $str);
		$str = $this->apply_rules($rules_symbols, $str);
		$str = $this->apply_rules($rules_braces, $str);
		
        if($this->_options[self::CONVERT_E]){
            $str = str_replace(array('Ё', 'ё'), array('Е', 'е'), $str);
        }
        if($this->_options[self::HTML_ENTITIES]){
          $str = htmlentities($str);
        }
		return $str;
	}
}