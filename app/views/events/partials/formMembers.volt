<div class="tab-content">
    <div class="tab-pane active" id="tab-account">
        <div class="row">
            <div class="col-sm-12">
                <!--First Tab-->
                <div class="row">
                    <div class="col-sm-6 col-left">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4 required">
                                    <label for="title" class="control-label"><?php echo $mt->_("Title"); ?></label>
                                    <?php echo $form->render("title") ?>
                                </div>
                                <div class="col-sm-8 required">
                                    <label for="firstname" class="control-label"><?php echo $mt->_("Firstname"); ?></label>
                                    <?php echo $form->render("firstname") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="title" class="control-label"><?php echo $mt->_("Title EN"); ?></label>
                                    <?php echo $form->render("title_en") ?>
                                </div>
                                <div class="col-sm-8">
                                    <label for="firstname" class="control-label"><?php echo $mt->_("Firstname EN"); ?></label>
                                    <?php echo $form->render("meta[firstname_en]") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 required">
                                    <label for="position" class="control-label">
                                        <?php echo $mt->_("Current Position"); ?> / <?php echo $mt->_("Latest Position"); ?>
                                    </label>
                                    <?php echo $form->render("position") ?>
                                </div>
                                <div class="col-sm-6 required">
                                    <label for="citizenid" class="control-label">
                                        <?php echo $mt->_("Citizen Id"); ?>
                                    </label>
                                    <?php echo $form->render("citizenid") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-5 required">
                                    <label for="standing" class="control-label"><?php echo $t->_("Academic Standing"); ?></label>
                                    <?php echo $form->render("standing") ?>
                                </div>
                                <div class="col-sm-3">
                                    <label for="bloodgroup" class="control-label"><?php echo $t->_("Blood Group"); ?></label>
                                    <?php echo $form->render("bloodgroup") ?>
                                </div>
                                <div class="col-sm-4">
                                    <label for="nationality" class="control-label"><?php echo $t->_("Nationality"); ?></label>
                                    <?php echo $form->render("nationality") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="address" class="control-label">
                                <?php echo $mt->_("Address"); ?>
                                <small> (<?php echo $t->_("Address Description"); ?>) </small>
                           </label>
                            <?php echo $form->render("address") ?>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 required">
                                    <label for="geo" class="control-label">
                                        <?php echo $mt->_("Zone"); ?>
                                    </label>
                                    <?php echo $form->render("geo") ?>
                                </div>
                                <div class="col-sm-6 required">
                                    <label for="province" class="control-label">
                                        <?php echo $mt->_("Province"); ?>
                                    </label>
                                    <?php echo $form->render("province") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4 required">
                                    <label for="zipcode" class="control-label">
                                        <?php echo $mt->_("Zipcode"); ?>
                                   </label>
                                    <?php echo $form->render("zipcode") ?>
                                </div>
                                <div class="col-sm-8">
                                    <label for="email" class="control-label">
                                        <?php echo $mt->_("Email"); ?>
                                   </label>
                                    <?php echo $form->render("email") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fax" class="control-label">
                                <?php echo $mt->_("Fax"); ?>
                                <small><?php echo $mt->_("Format Fax"); ?></small>
                           </label>
                            <?php echo $form->render("fax") ?>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="education_graduate" class="control-label"><?php echo $t->_("Graduate"); ?></label>
                                    <?php echo $form->render("meta[education_graduate]") ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="education_major" class="control-label"><?php echo $t->_("Major"); ?></label>
                                    <?php echo $form->render("meta[education_major]") ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="retired" class="control-label"><?php echo $t->_("Retired Date"); ?>
                                        <small><?php echo $mt->_("Format Date"); ?></small>
                                    </label>
                                     <div class="input-group">
                                        <?php echo $form->render("meta[retired]") ?>
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <label for="mate" class="control-label"><?php echo $t->_("Mate"); ?></label>
                                    <?php echo $form->render("meta[mate]") ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <code><?php echo $t->_("Only English Language Image Name"); ?></code><br/>

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                            
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 250px; height: 151px;">
                               
                                <?php echo $this->tag->image($memberMeta['image']); ?>

                                </div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new"><?php echo $mt->_("Select image"); ?></span>
                                    <span class="fileinput-exists"><?php echo $mt->_("Change"); ?></span>
                                    <?php echo $this->tag->fileField(array("image", 'id' => 'image')) ?>
                                    </span>
                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?php echo $mt->_("Remove"); ?></a>
                                </div>
                            </div>
                        </div>

                    </div><!-- End Left-->

                    <div class="col-sm-6 col-right">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 required">
                                    <label for="lastname" class="control-label">
                                        <?php echo $mt->_("Lastname"); ?>
                                   </label>
                                    <?php echo $form->render("lastname") ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="code" class="control-label">
                                        <?php echo $mt->_("Code"); ?><code><font size="1">โทรสอบถาม 02-282-2890</font></code>
                                   </label>
                                    <?php echo $form->render("code") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="lastname_en" class="control-label">
                                        <?php echo $mt->_("Lastname EN"); ?>
                                   </label>
                                    <?php echo $form->render("meta[lastname_en]") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 required">
                                    <label for="type" class="control-label">
                                        <?php echo $mt->_("Member Type"); ?>
                                    </label>
                                    <?php echo $form->render("type") ?>
                                </div>
                                <div class="col-sm-6">
                                    <label for="registered" class="control-label"><?php echo $t->_("Registered"); ?>
                                        <small><?php echo $mt->_("Format Date"); ?></small>
                                    </label>
                                     <div class="input-group">
                                        <?php echo $form->render("registered") ?>
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="birthday" class="control-label">
                                <?php echo $t->_("Birth Date"); ?>
                                <small><?php echo $t->_("Format Year AD"); ?></small>
                           </label>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo $form->render("birthday") ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php echo $form->render("birthmonth") ?>
                                </div>
                                 <div class="col-sm-4">
                                    <?php echo $form->render("birthyear") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address2" class="control-label">
                                <?php echo $mt->_("Address"); ?>
                                <small> (<?php echo $mt->_("Additional") . ' ' . $t->_("Additional Description"); ?>) </small>
                           </label>
                            <?php echo $form->render("address2") ?>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 required">
                                    <label for="district" class="control-label">
                                        <?php echo $mt->_("District"); ?>
                                    </label>
                                    <?php echo $form->render("district") ?>
                                </div>
                                <div class="col-sm-6 required">
                                    <label for="subdistrict" class="control-label">
                                        <?php echo $mt->_("Sub District"); ?>
                                    </label>
                                    <?php echo $form->render("subdistrict") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="telephone" class="control-label">
                                        <?php echo $mt->_("Telephone"); ?>
                                        <small><?php echo $mt->_("Format Telephone"); ?></small>
                                    </label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <?php echo $form->render("area_code") ?>
                                        </div>
                                        <div class="col-sm-8">
                                            <?php echo $form->render("telephone") ?>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-sm-6">
                                    <label for="mobile" class="control-label">
                                        <?php echo $mt->_("Mobile"); ?>
                                        <small><?php echo $mt->_("Format Mobile"); ?></small>
                                   </label>
                                    <?php echo $form->render("mobile") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="textfield" class="control-label"><?php echo $mt->_("Status"); ?></label>
                            <div class="row">
                                <div class="check-line col-sm-6">
                                    <?php echo Phalcon\Tag::radioField(array("status", "value" => "1", 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'id' => 'active'))?> 
                                    <label class='inline' for="active"><?php echo $t->_("Active"); ?></label>
                                </div>
                                <div class="check-line col-sm-6">
                                    <?php echo Phalcon\Tag::radioField(array("status", "value" => "0", 'class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'id' => 'inactive'))?> 
                                    <label class='inline' for="inactive"><?php echo $t->_("Inactive"); ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="education_academy" class="control-label"><?php echo $t->_("Academy"); ?></label>
                            <?php echo $form->render("meta[education_academy]") ?>
                        </div>

                        <div class="form-group">
                            <label for="note" class="control-label"><?php echo $mt->_("Note"); ?></label>
                            <?php echo $form->render("meta[note]") ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-actions pull-right">
                            <?php echo $this->tag->hiddenField("area_id") ?>
                            <?php echo $this->tag->hiddenField("academy_id") ?>
                            <?php echo $this->tag->submitButton(array($mt->_('Save Changes'), 'class' => 'btn btn-primary'))?>
                            <?php echo $this->tag->linkTo(array("members", $mt->_('Cancel'), 'class' => 'btn')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--tab-account-->

</div>