<?php


if($_menu = cmfCache::get('_footer')) {
    list($_menu, $email, $network, $copyright, $counters, $subscribeYes, $cart) = $_menu;
} else {

    $sql = cRegister::sql();
    $_menu = cmfMenu::getFooter();
    $email = cSettings::get('showcase', 'email');
    $network = str_replace('%itemUrl%', urlencode(cInput::url()->adress()), cSettings::get('showcase', 'network'));

    $copyright = nl2br(cSettings::get('seo', 'copyright'));
	$counters = $sql->placeholder("SELECT id, counters FROM ?t WHERE main='yes' AND visible='yes' ORDER BY pos ", db_seo_counters)
                            ->fetchRowAll(0, 1);
    $counters = implode(' ', $counters);

    cLoader::library('subscribe/cSubscribeYes');
    $subscribeYes = new cSubscribeYes('leftSubscribeYes');

    $cart = array();
	$cart['notice'] = cSettings::get('payment', 'notice');
	$cart['payment'] = cSettings::get('payment', 'payment');

    cmfCache::set('_footer', array($_menu, $email, $network, $copyright, $counters, $subscribeYes, $cart), 'menu,seoCounters,subscribe,seoCopyright');
}

$this->assing('_menu', $_menu);
$this->assing('email', $email);
$this->assing('network', $network);

$this->assing('copyright', $copyright);
$this->assing('counters', $counters);

$this->assing('subscribeYes', $subscribeYes);
$this->assing('form',         $subscribeYes->form()->get());


$this->assing('cart',   $cart);

?>