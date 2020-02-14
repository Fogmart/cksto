<?php echo $header; ?>

<?php 
    require_once(DIR_SYSTEM . 'soconfig/classes/soconfig.php');
    if(isset($registry)){$this->soconfig = new Soconfig($registry);}
?>
<?php 
    //Select Type Of Header
    if(isset($typelayout)){
        switch ($typelayout) {
        case "1":
            include(DIR_TEMPLATE.$theme.'/template/home/home1.tpl');break;
        case "2":
            include(DIR_TEMPLATE.$theme.'/template/home/home2.tpl');break;
        }
    }else{
        include(DIR_TEMPLATE.$theme.'/template/home/home1.tpl');
    }
?>

<?php echo $footer; ?>