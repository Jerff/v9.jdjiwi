<?php


class cmfPaymentSberbank extends cmfPaymentDriver {

    static public function view($data) {
        extract($data);
        $commission = number_format($price * ($commission/100), 2, '.', ' ');
        $price = number_format($price, 2, '.', ' ');
        $orderId = sprintf("%012d", $orderId);
?>
<TABLE borderColor=#000000 cellSpacing=0 cellPadding=0 width=629 bgColor=#ffffff border=0 align="center">
  <TBODY>
  <TR>
    <TD>
<P align=left><span style="font-size:15px;">
     <span style="color:#ff0000;">ВНИМАНИЕ!!!</span><br>

Срок зачисления платежей:<br>
&nbsp;&nbsp;&nbsp;- в отделениях Сбербанка в Москве - 10 рабочих дней.<br>
&nbsp;&nbsp;&nbsp;- в других отделениях Сбербанка - 10-15 дней.<br>
Для ускорения зачисления переведенных сумм Вам необходимо прислать копию квитанции по факсу: <?=$fax ?> или на e-mail: <b><?=$email ?></b><br>

<span style="font-size:12px;"><i><?=$notice ?></i></span>
</span></P>
</TD></TR></TBODY></TABLE><br>

<DIV align=center>
<CENTER>
<TABLE borderColor=#000000 height=499 cellSpacing=0 cellPadding=0 width=629
bgColor=#ffffff border=1>
  <TBODY>
  <TR>
    <TD width=155 height=302 rowSpan=6>
      <TABLE height="100%" cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top width="100%" height=65>
            <P align=center><B>&nbsp; ИЗВЕЩЕНИЕ</B> </P></TD></TR>
        <TR>
          <TD vAlign=bottom width="100%" height=220>&nbsp;&nbsp;
            <P>&nbsp; Кассир</P></TD></TR></TBODY></TABLE></TD>
    <TD width=470 colSpan=3 height=27><B>&nbsp;&nbsp; <?=$name ?></B></TD></TR>
  <TR>
    <TD width=470 colSpan=3 height=86>
      <P align=left>Расчетный счет <STRONG><?=$currentAccount ?></STRONG>
      <SMALL><?=$bank ?></SMALL> БИК <?=$bik ?><br>
      к/с <?=$ks ?> <BR>ИНН/КПП <?=$inn ?></P></TD></TR>
  <TR>
    <TD width=470 colSpan=3 height=32>&nbsp; Договор № &nbsp; <?=$orderId ?><B><FONT
      size=4></FONT></B></TD></TR>
  <TR>
    <TD width=111 height=27>&nbsp; Код услуги</TD>
    <TD width=166 height=27>&nbsp; Дата платежа</TD>
    <TD width=193 height=27>&nbsp; Сумма (руб.-коп.)</TD></TR>
  <TR>
    <TD vAlign=top width=111 height=53>&nbsp; Оплата счёта<BR><FONT size=4>&nbsp;<B><?=$orderId ?></B></FONT></TD>
    <TD width=166 height=53>&nbsp; <B><FONT size=4></FONT></B></TD>
    <TD width=193 height=53><B>&nbsp; <FONT size=4><?=$price ?></FONT></B></TD></TR>
  <TR>
    <TD id="tr1" vAlign=bottom width=470 colSpan=3 height=67>&nbsp;В т.ч. НДС (18%): <?=$commission ?> руб.<BR><BR>
    <B>&nbsp;Плательщик:</B>&nbsp;<span id="name1"><?=$fio ?></span><input id="input1" type="text" class="cHide width50" value="<?=cString::specialchars($fio) ?>"></TD></TR>
  <TR>
    <TD width=155 height=301 rowSpan=6>
      <TABLE height="100%" cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top width="100%" height=65>
            <P align=center><B>&nbsp; КВИТАНЦИЯ</B> </P></TD></TR>
        <TR>
          <TD vAlign=bottom width="100%" height=220>&nbsp;&nbsp;
            <P>&nbsp; Кассир</P></TD></TR></TBODY></TABLE></TD>
    <TD width=470 colSpan=3 height=27><B>&nbsp;&nbsp; <?=$name ?></B></TD></TR>
  <TR>
    <TD width=470 colSpan=3 height=86>
      <P align=left>Расчетный счет <STRONG><?=$currentAccount ?></STRONG>
      <SMALL><?=$bank ?></SMALL> БИК <?=$bik ?><br>
      к/с <?=$ks ?> <BR>ИНН/КПП <?=$inn ?></P></TD></TR>
  <TR>
    <TD width=470 colSpan=3 height=31>&nbsp; Договор № &nbsp;<?=$orderId ?><B><FONT
      size=4></FONT></B></TD></TR>
  <TR>
    <TD width=111 height=27>&nbsp; Код услуги</TD>
    <TD width=166 height=27>&nbsp; Дата платежа</TD>
    <TD width=193 height=27>&nbsp; Сумма (руб.-коп.)</TD></TR>
  <TR>
    <TD vAlign=top width=111 height=53>&nbsp; Оплата счёта<BR><FONT size=4>&nbsp;<B><?=$orderId ?></B></FONT></TD>
    <TD width=166 height=53>&nbsp; <B><FONT size=4></FONT></B></TD>
    <TD width=193 height=53><B>&nbsp; <FONT size=4><?=$price ?></FONT></B></TD></TR>
  <TR>
    <TD id="tr2" vAlign=bottom width=470 colSpan=3 height=67>&nbsp;В т.ч. НДС (18%): <?=$commission ?> руб.<BR><BR>
    &nbsp;<B>Плательщик:</B>&nbsp;<span id="name2"><?=$fio ?></span><input id="input2" type="text" class="cHide width50" value="<?=cString::specialchars($fio) ?>"></TD></TR></TBODY></TABLE></CENTER></DIV>


<script language="JavaScript">
<? $key = 1; ?>
$('#tr<?=$key ?>').parent().bind("mouseleave", function(){
    $('#input<?=$key ?>').hide();
    var txt = $('#input1').attr('value');
    $('#input2').attr('value', txt);
    $('#name1').text(txt);
    $('#name2').text(txt);
    $('#name<?=$key ?>').show();
}).bind("mouseenter", function(){
    $('#name<?=$key ?>').hide();
    $('#input<?=$key ?>').show();
});
<? $key = 2; ?>
$('#tr<?=$key ?>').parent().bind("mouseleave", function(){
    $('#input<?=$key ?>').hide();
    var txt = $('#input2').attr('value');
    $('#input1').attr('value', txt);
    $('#name1').text(txt);
    $('#name2').text(txt);
    $('#name<?=$key ?>').show();
}).bind("mouseenter", function(){
    $('#name<?=$key ?>').hide();
    $('#input<?=$key ?>').show();
});
</script>
<?
    }
}

?>