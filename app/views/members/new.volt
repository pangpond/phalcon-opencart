<?php use Phoenix\Models\Province; ?>

<?php $this->partial("common/partials/page-header", array('title' => $t->_("Add Member"), 'size' => 'big')) ?>


<?php echo $this->getContent(); ?>

<div class="breadcrumbs">
    <ul>
        <?php $this->crumbs->render();?>
    </ul>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="pull-left">
            <?php echo $this->tag->linkTo(array("members", '<i class="fa fa-reply"></i> ' . $mt->_("Back"), 'class' => 'btn', 'rel' => 'tooltip', 'title' => $mt->_("Back"))) ?>
        </div>
    </div>
</div>


<!-- <ul ng-init="prefixs = <?php echo htmlspecialchars($prefixName); ?>">
    <li ng-repeat="prefix in prefixs | filter:query">
       <p>{{prefix.label}}</p>
    </li>
</ul>

<address>
    <strong>{{title.name}} {{firstname}} {{lastname}}</strong>
    <br>{{address}}
    <br>{{address}}
    <br>{{subdistrict}} {{district}} {{province}} {{zipcode}}
    <br>
    <abbr title="Phone">โทร:</abbr>
    {{area}} - {{telephone}}
    <abbr title="Phone">แฟกซ์:</abbr>
    {{fax}}
</address> -->


<div class="row">
    <div class="col-sm-6">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="fa fa-bars"></i>
                    <?php echo $t->_("Office Area"); ?>
                </h3>
            </div>
            <div class="box-content">
                <?php echo $this->tag->form(array("", "method" => "post", "class" => "form-validate form-vertical form-column", 'id' => "frmFindArea")) ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo $form->render("areas") ?>
                                <address id="areaDetail"></address>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="fa fa-bars"></i>
                    <?php echo $t->_("Academy"); ?>
                </h3>
            </div>
            <div class="box-content">
                <?php echo $this->tag->form(array("", "method" => "post", "class" => "form-validate form-horizontal", 'id' => "frmFindAcademy")) ?>
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2"><?php echo $mt->_("School"); ?></label>
                        <div class="col-sm-10">
                            <?php echo $form->render("academies") ?>
                            <address id="academyDetail"></address>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="fa fa-bars"></i>
                    <?php echo $t->_("Members"); ?>
                </h3>
            </div>
            <div class="box-content nopadding">
                <?php echo $this->tag->form(array("members/create", "method" => "post", "class" => "form-validate form-vertical form-column form-bordered", 'enctype' => 'multipart/form-data', 'id' => "membersCreate")) ?>
                    <?php $this->partial("members/partials/formMembers") ?>
                </form>
            </div><!--box-content-->
        </div>
    </div>
</div>


<script>
    $(function(){
        if ($('.datepick').length > 0) {
            $('.datepick').datetimepicker({ 
                format: 'YYYY-MM-DD hh:mm:ss' 
            });
        }
    });


</script>

<script type="text/javascript">

    $(document).ready(function() {

        var getProvinceUrl = "/members/grabProvince";
        var getDistrictUrl = "/members/grabDistrict";
        var getSubDistrictUrl = "/members/grabSubDistrict";

        $("#geo").change(function() {
            var value = $(this).val();


            $("#district option")
                .not(":first").remove();
            $("#subdistrict option")
                .not(":first").remove();

            $.ajax({
                type: "POST",
                url: getProvinceUrl,
                data: {"id": value},
                success: function(response){
                    $("#province option")
                        .not(":first").remove();

                    parsed = $.parseJSON(response);

                    $.each(parsed, function(key, value) {
                        $("#province")
                            .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.name));
                    });
                }
            });
        });

        $("#province").change(function() {
            var value = $(this).val();


            $("#subdistrict option")
                .not(":first").remove();
                
            $.ajax({
                type: "POST",
                url: getDistrictUrl,
                data: {"id": value},
                success: function(response){
                    $("#district option")
                        .not(":first").remove();

                    parsed = $.parseJSON(response);

                    $.each(parsed, function(key, value) {
                        $("#district")
                            .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.name));
                    });
                }
            });
        });

        $("#district").change(function() {
            var value = $(this).val();

            $.ajax({
                type: "POST",
                url: getSubDistrictUrl,
                data: {"id": value},
                success: function(response){
                    $("#subdistrict option")
                        .not(":first").remove();

                    parsed = $.parseJSON(response);

                    $.each(parsed, function(key, value) {
                        $("#subdistrict")
                            .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.name));
                    });
                }
            });
        });

        //$('#geo').val('1').change();


        var getAcademiesUrl = "/members/grabAcademies";
        var getAreaUrl = "/members/grabArea";
        var getAcademyUrl = "/members/grabAcademy";

        $("#areas").change(function() {
            var value = $(this).val();

            $("#area_id").val(value);
            $.ajax({
                type: "POST",
                url: getAcademiesUrl,
                data: {"id": value},
                success: function(response){
                    $("#academies option")
                        .not(":first").remove();

                    parsed = $.parseJSON(response);

                    $.each(parsed, function(key, value) {
                        $("#academies")
                            .append($("<option></option>")
                            .attr("value",value.id)
                            .text(value.name));
                    });
                }
            });

            $("#areaDetail").html('').hide();
            $("#academyDetail").html('').hide();
            $.ajax({
                type: "POST",
                url: getAreaUrl,
                data: {"id": value},
                success: function(response){

                    area = $.parseJSON(response);
                    $("#areaDetail").html(
                        "<strong>" + area.name + "</strong>"
                        + "<br>" + area.address
                        + "<br>" + area.subdistrict_prefix + area.subdistrict_name
                        + " " + area.district_prefix + area.district.district_name
                        + " จังหวัด" + area.province.province_name
                        + " " + area.zipcode
                        + "<br><abbr title='Phone'>โทร:</abbr> " + area.telephone
                        + "<br><abbr title='Fax'>แฟกซ์:</abbr> " + area.fax
                    ).show('slow').fadeIn('slow');
                }
            });
        });

        $("#academies").change(function() {
            var value = $(this).val();

            $("#academy_id").val(value);

            $("#academyDetail").html('').hide();
            $.ajax({
                type: "POST",
                url: getAcademyUrl,
                data: {"id": value},
                success: function(response){

                    academy = $.parseJSON(response);
                    $("#academyDetail").html(
                        "<strong>โรงเรียน" + academy.name + "</strong>"
                        + "<br>" + academy.address
                        + "<br>" + academy.subdistrict_prefix + academy.subdistrict_name
                        + " " + academy.district_prefix + academy.district_name
                        + " จังหวัด" + academy.province_name
                        + " " + academy.zipcode
                        + "<br><abbr title='Phone'>โทร:</abbr> " + academy.telephone
                        + "<br><abbr title='Fax'>แฟกซ์:</abbr> " + academy.fax
                    ).show('slow').fadeIn('slow');
                }
            });
        });

        $('#areas').val('<?php echo $area ?>').change();

        $("#membersCreate").submit(function() {
            if($('#academies').val() == ''){
                alert('กรุณาเลือกสถานศึกษา');
                return false;
            }
        });

        $("#membersEdit").submit(function() {
            if($('#academies').val() == ''){
                alert('กรุณาเลือกสถานศึกษา');
                return false;
            }
        });
    });
</script>