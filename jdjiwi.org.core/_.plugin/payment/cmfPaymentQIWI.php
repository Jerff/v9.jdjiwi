<?php


class cmfPaymentQIWI extends cmfPaymentDriver {

    static private $login = '12152';
    static public function run($data) {
        /*$view = cmfPages::getParam(4);
        if($view!=='send') return true;

        $from = self::$login;
        $to = get($_POST, 'to');
        $com = urlencode(get($_POST, 'com'));
        $txn_id = $data['orderId'];
        $price = $data['price'];

        $url = "https://w.qiwi.ru/setInetBill_utf.do?from={$from}&to={$to}&summ={$price}&com={$com}&txn_id=$txn_id&check_agt=false&lifetime=0.25";

        cmfRedirect($url);
        // print URL if you need
        //echo "<a href='$url'>$url</a>";
        exit;*/
        return true;
    }


    static public function result($type, $data) {
        $out_summ = (int)get($_POST, "OutSum");
        $orderId = $inv_id = get($_POST, "InvId");
        $crc = get($_POST, "SignatureValue");

        $crc = strtoupper($crc);
        $mrh_pass2 = self::$password2;
        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));
        if ($my_crc !=$crc) {
            exit();
        }

        $mrh_login = self::$login;
        $crc = md5("$mrh_login:$inv_id:$mrh_pass2");

        $url = "https://merchant.roboxchange.com/WebService/Service.asmx/OpState?MerchantLogin=$mrh_login&InvoiceID=$inv_id&Signature=$crc";
        $url = "http://test.robokassa.ru/Webservice/Service.asmx/OpState?MerchantLogin=$mrh_login&InvoiceID=$inv_id&Signature=$crc&StateCode=100";
        $res = file_get_contents($url);
        $xml = new SimpleXMLElement($res);

        $code = (int)$xml->Result->Code;
        if(!empty($code)) exit;
        $code = (int)$xml->State->Code;
        if($code!=100) exit;
        $sum = (int)$xml->Info->OutSum;
        //if($sum!=$out_summ) exit;

        $sql = cRegister::sql();
        $status = cmfOrder::getStatusList(0, 1, 2);
        $order = $sql->placeholder("SELECT id, pay, isPay FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $orderId, array_keys($status))
                                ->fetchAssoc();
        if(!$order) exit;
        if($order['isPay']==='yes') exit;
        if($order['pay']!=$out_summ) exit;

        cmfControllerOrder::addOrderPayment($order['id']);
        $sql->add(db_basket, array('isPay'=>'yes'), $orderId);
    }

    static public function success() {
        $mrh_pass1 = self::$password1;

        // чтение параметров
        // read parameters
        $out_summ = get($_POST, "OutSum");
        $inv_id = get($_POST, "InvId");
        $crc = get($_POST, "SignatureValue");

        $crc = strtoupper($crc);
        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1"));

        // проверка корректности подписи
        // check signature
        if ($my_crc != $crc) {
            return 404;
        }
        return $inv_id;
    }

    static public function fail() {
        return get($_POST, "InvId");
    }


    static public function view($data) {
        extract($data);

        $login = self::$login;
        $price = self::percentageAdd($data['price'], $data['commission']);

?>
<script type="text/javascript">
var ie=document.all;var moz=(navigator.userAgent.indexOf("Mozilla")!=-1);var opera=window.opera;var brodilka="";if(ie&&!opera){brodilka="ie"}else{if(moz){brodilka="moz"}else{if(opera){brodilka="opera"}}}var inputMasks=new Array();function kdown(a,c){var e=a.getAttribute("id");var b=e.substring(0,e.length-1);var d=Number(e.substring(e.length-1));inputMasks[b].BlKPress(d,a,c)}function kup(a,b){if(Number(a.getAttribute("size"))==a.value.length){var f=a.getAttribute("id");var d=f.substring(0,f.length-1);var e=Number((f.substring(f.length-1)))+1;var c=document.getElementById(d+e);if(b!=8&&b!=9){if(c){c.focus()}}else{if(b==8){a.value=a.value.substring(0,a.value.length-1)}}}}function Mask(d){var c="(\\d{3})\\d{3}-\\d{2}-\\d{2}";var f=[];var g=[];var a=[];var e="";var b=function(k){var j=Number(k.substring(3,k.indexOf("}")));var i=d.getAttribute("id");var m=g.length;var l="";var h=function(n){return((n>=48)&&(n<=57))||((n>=96)&&(n<=105))||(n==27)||(n==8)||(n==9)||(n==13)||(n==45)||(n==46)||(n==144)||((n>=33)&&(n<=40))||((n>=16)&&(n<=18))||((n>=112)&&(n<=123))};this.makeInput=function(){return"<input type='text' size='"+j+"' maxlength='"+j+"' id='"+i+m+"' onKeyDown='kdown(this, event)' onKeyUp='kup(this, event.keyCode)' value='"+l+"'>"};this.key=function(n,q){if(opera){return}if(!h(q.keyCode)){switch(brodilka){case"ie":q.cancelBubble=true;q.returnValue=false;break;case"moz":q.preventDefault();q.stopPropagation();break;case"opera":break;default:}return}if(q.keyCode==8&&n.value==""){var s=n.getAttribute("id");var r=s.substring(0,s.length-1);var o=Number(s.substring(s.length-1))-1;var p=document.getElementById(r+o);if(p!=null){p.focus()}}};this.getText=function(){l=document.getElementById(i+m).value;return l};this.setText=function(n){l=n};this.getSize=function(){return j}};this.drawInputs=function(){var k="<span class='Field'>";var l=0;var h=0;for(var j=0;j<a.length;j++){if(a[j]=="p"){k+=f[l];l++}else{k+=g[h].makeInput();h++}}k+="</span>";document.getElementById("div_"+d.getAttribute("id")).innerHTML=k;d.style.display="none"};this.buildFromFields=function(){var i=c;while(i.indexOf("\\")!=-1){var h=i.indexOf("\\");var k="";if(i.substring(0,h)!=""){f[f.length]=i.substring(0,h);a[a.length]="p";i=i.substring(h)}var j=i.indexOf("}");g[g.length]=new b(i.substring(0,j+1),k);i=i.substring(j+1);a[a.length]="b"}if(i!=""){f[f.length]=i;a[a.length]="p"}this.drawInputs()};this.buildFromFields();this.BlKPress=function(j,h,i){g[j].key(h,i)};this.makeHInput=function(){var h=d.getAttribute("name");document.getElementById("div_"+d.getAttribute("id")).innerHTML="<input type='text' readonly='readonly' name='"+h+"' value='"+this.getValue()+"'>"};this.getFName=function(){return d.getAttribute("name")};this.getValue=function(){e="";var k=0;var h=0;for(var j=0;j<a.length;j++){if(a[j]!="p"){e+=g[h].getText();h++}}return e};this.check=function(){for(var h in g){if(g[h].getText().length==0){return false}}return true}};
</script>
<div style="margin:0 auto; padding:5px; width:450px; border:1px solid #ddd; background:#fff; border-radius: 7px; -webkit-border-radius: 7px; -moz-border-radius: 7px; font:normal 14px/14px Geneva,Verdana,Arial,Helvetica,Tahoma,sans-serif;">
	<form action="https://w.qiwi.ru/setInetBill_utf.do" method="post" onSubmit="return checkSubmit();">
		<!-- используйте это поле для передачи уникального идентификатора заказа/платежа в вашей системе -->
    	<!-- <input type="hidden" name="txn_id" value=""/> -->

		<p style="text-align:center; color:#006699; padding:20px 0px; background:url(http://ishop.qiwi.ru/img/button/logo_31x50.jpg) no-repeat 10px 50%;">Выставить счёт за покупку</p>
		<table style="border-collapse:collapse;">
			<tr style="background:#f1f5fa;">
				<td style="color:#a3b52d; width:45%; text-align:center; padding:10px 0px;">Мобильный телефон (пример: 9057772233)</td>
				<td style="padding:10px">
					<input name="from" type="hidden" value="<?=$login ?>">
					<input type="text" name="to" id="idto" style="width:130px; border: 1px inset #555;">
					<input name="summ" type="hidden" value="<?=$price ?>">
					<input name="com" type="hidden" value="<?=cString::specialchars($desc) ?>">
					<input name="lifetime" type="hidden" value="10">

					<span id="div_idto"></span>
					<script type="text/javascript">
						inputMasks["idto"] = new Mask(document.getElementById("idto"));
						function checkSubmit() {
							if (inputMasks["idto"].getValue().match(/^\d{10}$/)) {
								document.getElementById("idto").setAttribute("disabled", "disabled");
								inputMasks["idto"].makeHInput();
								return true;
							} else {
								alert("Введите номер телефона в федеральном формате без \"8\" и без \"+7\"");
								return false;
							}
						}
					</script>
    			</td>
			</tr>
			<tr>
				<td style="color:#a3b52d; padding:10px 0px; width:45%; text-align:center;">Сумма</td>
				<td style="padding:10px">
					<?=$priceView ?> руб.
				</td>
			</tr>
			<tr style="background:#f1f5fa;">
				<td style="color:#a3b52d; padding:10px 0px; width:45%; text-align:center;">Комментарий</td>
				<td style="padding:10px"><textarea rows="2" cols="30" name="com" style="width:200px; border: 1px inset #555;"><?=cString::specialchars($desc) ?></textarea></td>
			</tr>
		</table>
		<p style="text-align:center;"><input type="submit" value="Выставить счёт за покупку" style=" padding:10px 0;border:none; background:url(http://ishop.qiwi.ru/img/button/superBtBlue.jpg) no-repeat 0 50%; color:#fff; width:300px;"/></p>
	</form>
</div>
<?
    }

}

?>