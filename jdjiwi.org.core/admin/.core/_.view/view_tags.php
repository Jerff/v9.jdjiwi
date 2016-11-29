<?php

class view_tags {

    static public function tagList($id, $mTags) {
        ?>
        <script>
            var jTags = new(function() {
                var tags = this;

                tags.set = function(id) {
                    tags.item = $(id);
                };

                tags.add = function(str) {
                    var filter = function(v) {
                        v = v.replace(/\s+/i, ' ');
                        v = v.replace(/,\s+,/i, ',');
                        return v.replace(/\,+/i, ',');
                    };
                    var value = filter(tags.item.val());
                    if(value.indexOf(str)==-1) {
                        value = value =='' ? str : value +', '+ str;
                    }
                    tags.item.val(filter(value));
                };

                tags.init = function() {
                    $('#tagsList a').click(function() {
                        tags.add($(this).html());
                    });
                };
            });
            jTags.set('#<?= $id ?>');
        </script>
        <div id="tagsList"><?= self::tagListContent($mTags) ?></div>
        <div class="clearFloat"></div>
        <?
    }

    static public function tagListContent($mTags) {
        ob_start();
        $i = 0;
        foreach ($mTags as $tag=>$weight) {
            if ($i++) {
                ?><span>, </span><?
            }
            ?><a class="tagsList<?=$weight ?>"><?= $tag ?></a><?
        }
        ?>
        <script>
            jTags.init();
        </script>
        <?
        return ob_get_clean();
    }

}
?>
