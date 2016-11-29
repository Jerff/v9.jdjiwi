<?php

cLoader::library('catalog/function');
cLoader::library('cron/cmfCronUpdateDriver');
class cmfCronSiteMap extends cmfCronUpdateDriver {

	static protected function file() {
		return 'sitemap.xml';
	}
	static private function getFile() {
		return cWWWPath . self::file();
	}
	static public function getUrlFile() {
		return cBaseAppUrl . self::file();
	}


	static public function run() {
		$sql = cRegister::sql();
		$res = $sql->placeholder("SELECT id, name, changefreq, priority FROM ?t WHERE visible='yes'", db_seo_sitemap)
					->fetchAssocAll('id');

		$file = fopen(self::getFile(), 'w');
		fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>');
		fwrite($file, '
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
		foreach($res as $id=>$row) {
			cmfCronRun::run();
			$changefreq = $row['changefreq'];
			$priority = $row['priority'];
			$date = date('Y-m-d');

			$_url = self::getUrlList($row['name']);
			foreach((array)$_url as $url) {
				$url = cString::specialchars($url);
				$html =<<<HTML

		   <url>
		      <loc>$url</loc>
		      <lastmod>$date</lastmod>
		      <changefreq>$changefreq</changefreq>
		      <priority>$priority</priority>
		   </url>
HTML;
				fwrite($file, $html);
            }
		}
		fwrite($file, '
		</urlset>');
		fclose($file);
	}


	static public function getUrlList($modul) {
		$sql = cRegister::sql();
		switch($modul) {
            case 'index':
            	return cUrl::get('/index/');

            case 'contact':
            	return cUrl::get('/contact/');

            case 'news':
				$res = $sql->placeholder("SELECT uri FROM ?t WHERE visible='yes'", db_news)
								->fetchAssocAll();
				$url = array();
				foreach($res as $row) {
					$url[] = cUrl::get('/news/', $row['uri']);
				}
				return $url;

             case 'catalog':
            	$section = cRegister::sql()->placeholder("SELECT id, isUri FROM ?t WHERE id IN(SELECT section FROM ?t WHERE brand='0' AND isMenu='yes') AND isVisible='yes' ORDER BY pos", db_section, db_section_is_brand)
							->fetchRowAll(0, 1);
				$url = array();
				foreach($section as $k=>$v) {
					$url[] = cUrl::get('/section/', $v);
				}

            	$brand = cRegister::sql()->placeholder("SELECT id, uri FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE section='0' AND isMenu='yes') AND visible='yes' ORDER BY pos", db_brand, db_section_is_brand)
							->fetchRowAll(0, 1);
				foreach($brand as $k=>$v) {
					$url[] = cUrl::get('/brand/', $v);
				}

				$res = cRegister::sql()->placeholder("SELECT u.url FROM ?t p LEFT JOIN ?t u ON(u.product=p.id) WHERE (u.product=p.id AND u.brand='0') AND p.section ?@ AND p.brand ?@", db_product, db_product_url, array_keys($section), array_keys($brand))
                            ->fetchRowAll();
				foreach($res as $v) {
					$url[] = cUrl::get('/product/', $v[0]);
				}
            	return $url;

            case 'info':
				$res = $sql->placeholder("SELECT isUri FROM ?t WHERE isVisible='yes'", db_content_info)
								->fetchAssocAll();
				$url = array();
				foreach($res as $row) {
					$url[] = cUrl::get('/info/', $row['isUri']);
				}

				$res = $sql->placeholder("SELECT isUri FROM ?t WHERE isVisible='yes'", db_content)
								->fetchAssocAll();
				foreach($res as $row) {
					$url[] = cUrl::get('/content/', $row['isUri']);
				}
				return $url;
		}
		return array();
	}
}

?>