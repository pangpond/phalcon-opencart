<?php use Phalcon\Tag; ?>
<!doctype html>
<html lang="en" ng-app>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Apple devices fullscreen -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Apple devices fullscreen -->
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />

    <?php echo Tag::getTitle() ?>

    <?php

        //<!-- Bootstrap -->
        echo $this->tag->stylesheetLink("css/flat/bootstrap.min.css");
        //<!-- icheck -->
        echo $this->tag->stylesheetLink("css/flat/plugins/icheck/all.css");
        //<!-- jQuery UI -->
        echo $this->tag->stylesheetLink("css/flat/plugins/jquery-ui/smoothness/jquery-ui.css");
        echo $this->tag->stylesheetLink("css/flat/plugins/jquery-ui/smoothness/jquery.ui.theme.css");
        //<!-- dataTables -->
        echo $this->tag->stylesheetLink("css/flat/plugins/datatable/TableTools.css");
        echo $this->tag->stylesheetLink("css/flat/plugins/datepicker/datepicker.css");
        //<!-- date time picker ->
        // echo $this->tag->stylesheetLink("css/flat/plugins/datetimepicker/bootstrap-datetimepicker.css");

        //<!-- Fullcalendar -->
        echo $this->tag->stylesheetLink("css/flat/plugins/fullcalendar/fullcalendar.css");

        //<!-- Plupload -->
        echo $this->tag->stylesheetLink("css/flat/plugins/plupload/jquery.plupload.queue.css");
        //<!-- Tagsinput -->
        echo $this->tag->stylesheetLink("css/flat/plugins/tagsinput/jquery.tagsinput.css");
        //<!-- chosen -->
        echo $this->tag->stylesheetLink("css/flat/plugins/chosen/chosen.css");

        //<!-- colorbox -->
        echo $this->tag->stylesheetLink("css/flat/plugins/colorbox/colorbox.css");


        //<!-- Theme CSS -->
        echo $this->tag->stylesheetLink("css/flat/style.css");
        //<!-- Color CSS -->
        echo $this->tag->stylesheetLink("css/flat/themes.css");

        //<!-- jQuery -->
        echo $this->tag->javascriptInclude("js/flat/jquery.min.js");

        //<!-- Nice Scroll -->
        echo $this->tag->javascriptInclude("js/flat/plugins/nicescroll/jquery.nicescroll.min.js");
        //<!-- imagesLoaded -->
        echo $this->tag->javascriptInclude("js/flat/plugins/imagesLoaded/jquery.imagesloaded.min.js");
        //<!-- jQuery UI -->
        echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.core.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.widget.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.mouse.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.draggable.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.resizable.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.sortable.min.js");
        // echo $this->tag->javascriptInclude("js/flat/plugins/jquery-ui/jquery.ui.datepicker.min.js");
        
        


        //<!-- Touch enable for jquery UI -->
        echo $this->tag->javascriptInclude("js/flat/plugins/touch-punch/jquery.touch-punch.min.js");
        //<!-- slimScroll -->
        echo $this->tag->javascriptInclude("js/flat/plugins/slimscroll/jquery.slimscroll.min.js");
        //<!-- Bootstrap -->
        echo $this->tag->javascriptInclude("js/flat/bootstrap.min.js");

        //<!-- Datepicker -->
        // echo $this->tag->javascriptInclude("js/flat/plugins/datepicker/bootstrap-datepicker.js");
        // echo $this->tag->javascriptInclude("js/flat/plugins/datepicker/locales/bootstrap-datepicker.th.js");

        //<!-- date time picker -->
        // echo $this->tag->javascriptInclude("js/flat/plugins/datetimepicker/moment.min.js");
        // echo $this->tag->javascriptInclude("js/flat/plugins/datetimepicker/bootstrap-datetimepicker.js");
        // echo $this->tag->javascriptInclude("js/flat/plugins/datetimepicker/locales/bootstrap-datetimepicker.th.js");

        //<!-- vmap -->
        echo $this->tag->javascriptInclude("js/flat/plugins/vmap/jquery.vmap.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/vmap/jquery.vmap.world.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/vmap/jquery.vmap.sampledata.js");
        //<!-- Bootbox -->
        echo $this->tag->javascriptInclude("js/flat/plugins/bootbox/jquery.bootbox.js");
        //<!-- Masked inputs -->
        echo $this->tag->javascriptInclude("js/flat/plugins/maskedinput/jquery.maskedinput.min.js");

        //<!-- Flot -->
        echo $this->tag->javascriptInclude("js/flat/plugins/flot/jquery.flot.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/flot/jquery.flot.bar.order.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/flot/jquery.flot.pie.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/flot/jquery.flot.resize.min.js");
        //<!-- imagesLoaded -->
        echo $this->tag->javascriptInclude("js/flat/plugins/imagesLoaded/jquery.imagesloaded.min.js");
        //<!-- PageGuide -->
        echo $this->tag->javascriptInclude("js/flat/plugins/pageguide/jquery.pageguide.js");
        //<!-- FullCalendar -->
        echo $this->tag->javascriptInclude("js/flat/plugins/fullcalendar/fullcalendar.min.js");
        //<!-- Chosen -->
        echo $this->tag->javascriptInclude("js/flat/plugins/chosen/chosen.jquery.min.js");
        //<!-- select2 -->
        echo $this->tag->javascriptInclude("js/flat/plugins/select2/select2.min.js");
        //<!-- Validation -->
        echo $this->tag->javascriptInclude("js/flat/plugins/validation/jquery.validate.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/validation/additional-methods.min.js");

        //<!-- PLUpload -->
        echo $this->tag->javascriptInclude("js/flat/plugins/plupload/plupload.full.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/plupload/jquery.plupload.queue.js");
        //<!-- Custom file upload -->
        echo $this->tag->javascriptInclude("js/flat/plugins/fileupload/bootstrap-fileupload.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/mockjax/jquery.mockjax.js");

        //<!-- icheck -->
        echo $this->tag->javascriptInclude("js/flat/plugins/icheck/jquery.icheck.min.js");
        //<!-- dataTables -->
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/jquery.dataTables.min.js");
        // echo $this->tag->javascriptInclude("//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/TableTools.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/ColReorderWithResize.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/ColVis.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/FixedColumns.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/jquery.dataTables.columnFilter.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/datatable/jquery.dataTables.grouping.js");

        //<!-- Chosen -->
        echo $this->tag->javascriptInclude("js/flat/plugins/chosen/chosen.jquery.min.js");
        //<!-- TagsInput -->
        echo $this->tag->javascriptInclude("js/flat/plugins/tagsinput/jquery.tagsinput.min.js");
        //<!-- colorbox -->
        echo $this->tag->javascriptInclude("js/flat/plugins/colorbox/jquery.colorbox-min.js");
        //<!-- masonry -->
        echo $this->tag->javascriptInclude("js/flat/plugins/masonry/jquery.masonry.min.js");
        //<!-- imagesloaded -->
        echo $this->tag->javascriptInclude("js/flat/plugins/imagesLoaded/jquery.imagesloaded.min.js");


        //<!-- Form -->
        echo $this->tag->javascriptInclude("js/flat/plugins/form/jquery.form.min.js");
        //<!-- Wizard -->
        echo $this->tag->javascriptInclude("js/flat/plugins/wizard/jquery.form.wizard.min.js");
        echo $this->tag->javascriptInclude("js/flat/plugins/mockjax/jquery.mockjax.js");

        //<!-- Bootstrap datetimepicker -->
        echo $this->tag->javascriptInclude("js/bower_components/moment/min/moment.min.js");
        echo $this->tag->javascriptInclude("js/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js");
        echo $this->tag->stylesheetLink("js/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css");


        //<!-- Theme framework -->
        echo $this->tag->javascriptInclude("js/flat/eakroko.js");
        //<!-- Theme scripts -->
        echo $this->tag->javascriptInclude("js/flat/application.js");
        //<!-- Just for demonstration -->
        echo $this->tag->javascriptInclude("js/flat/demonstration.js");

    ?>
    <!--[if lte IE 9]>
        <?php
        echo $this->tag->javascriptInclude("js/flat/flat/plugins/placeholder/jquery.placeholder.min.js");
    ?>
        <script>
            $(document).ready(function() {
                $('input, textarea').placeholder();
            });
        </script>
    <![endif]-->

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" />
    <!-- Apple devices Homescreen icon -->
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png" />

    <!-- Google Analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-58727593-2', 'auto');
      ga('send', 'pageview');

    </script>
</head>

<?php echo $this->getContent(); ?>


<?php 
    echo $this->tag->javascriptInclude("js/angular/angular.min.js");
    echo $this->tag->javascriptInclude("js/angular/angular-resource.min.js");
 ?>
</html>