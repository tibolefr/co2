<style>
    
.el-nowList:hover{
    width: 100%;
}

</style>

<?php if(sizeof($result) > 0 && @$title){ ?>
    <h5 class="titleDirMin"><i class="fa fa-angle-down"></i> <?php echo $title; ?></h5>
<?php } ?>

<?php
    foreach ($result as $key => $v) { 
        $specs = Element::getElementSpecsByType(@$v["type"]);

        $type = null;
        if(@$specs) $type = @$v["type"];
        else if(@$v["typeSig"]) $type = $v["typeSig"];

        $class = "";
        $img = Element::getImgProfil($v, "profilThumbImageUrl", $this->module->assetsUrl);
        if(!@$v["profilMediumImageUrl"] || $v["profilThumbImageUrl"] == "") 
            $class = "no-img";

        $elUrl = Element::getHash($v);
    ?>

    <a href="<?php echo $elUrl; ?>" class="<?php if(!@$target){ ?>lbh<?php } ?> shadow2 margin-bottom-5 col-xs-12 no-padding el-nowList <?php echo $type." ". $class; ?>" <?php if(@$target=="blank") { ?> target="_blank"<?php } ?>>
        <div class="pull-left no-padding cnt-img">
            <div class="add2fav elemt_img">
                <img src="<?php echo $img ?>" class="pull-left hidden-xs">
            </div>
        </div>
        <div class="pull-left elemt_name elipsis">
            <i class="fa fa-<?php echo $specs["icon"]?> text-<?php echo @$specs["text-color"]?>"></i> 
            <span class=""><?php echo $v["name"]; ?></span>
            <?php 
                $id = null;
                if(@$v["_id"]) $id = (string)@$v["_id"];
                else if(@$v["id"])  $id = $v["id"];
            ?>
        </div><br>
        <div class="elemt_date pull-left text-left elipsis">
            <span class="dateTZ">
                <?php echo @$v["updatedLbl"];?>
                <?php if(@$v["price"]) echo " | <span class='text-azure'>" .@$v["price"].' '.@$v["devise"].'</span>';?> 
            </span> 
            <?php if(@$v["address"]["addressLocality"]) { ?>        
            <span class="address text-red">
                <i class="fa fa-map-marker margin-left-5"></i> <?php echo @$v["address"]["addressLocality"]; ?> 
            </span>
            <?php } ?> 
        </div>
    </a>
<?php } ?>