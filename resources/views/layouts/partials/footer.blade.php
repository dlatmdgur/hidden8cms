<!-- footer content -->
<footer>
    <div class="pull-right">
    {{ (count(explode(config('hosting.app_host'), $_SERVER['HTTP_HOST'])) > 1 ? config('hosting.header_name') : config('hosting.another_name')) }}
    </div>
    <div class="clearfix"></div>
</footer>
<!-- /footer content -->
