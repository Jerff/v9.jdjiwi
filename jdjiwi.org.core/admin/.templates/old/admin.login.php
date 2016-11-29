<?

if (cAjax::is()) {
    cAjax::get()->html('#mainIndex', $content);
}
?>
<?= $this->page('/admin/header/') ?>
<?= $content ?>
<?= $this->page('/admin/footer/') ?>