<ul id="leftmenu">
<?php
	foreach($pagedata as $pk=>$pd){ 
?>
    <li class="<?php echo $page == $pk ? 'active' : '' ?>">
        <a href="index.php?page=<?php echo $pk ?>&theme=<?php echo $theme ?>"><span><?php echo $pd['title_1'] ?></span></a>
    </li>
<?php	   
	}
?>
</ul>