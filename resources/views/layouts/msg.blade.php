<script>
var msg = "<?php echo Session::get('msg'); ?>"
$(window).on('load', function()
{
    iziToast.success({
        message: msg,
        position: 'topRight'
    });
});
</script>