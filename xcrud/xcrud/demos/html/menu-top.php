<div id="logo"></div>
<div id="caption">DEMO SITE <small><?php echo $version ?></small></div>
<a id="buy-button"></a>
<ul id="st">
    <li class="<?php echo $theme == 'default' ? 'active' : '' ?>">
        <a href="index.php?page=<?php echo $page ?>&theme=default">Default theme</a>
    </li>
    <li class="<?php echo $theme == 'bootstrap4' ? 'active' : '' ?>">
        <a href="index.php?page=<?php echo $page ?>&theme=bootstrap4">Bootstrap theme (4.*) <span class="new">NEW</span></a>
    </li>
    <li class="<?php echo $theme == 'bootstrap' ? 'active' : '' ?>">
        <a href="index.php?page=<?php echo $page ?>&theme=bootstrap">Bootstrap theme (3.0)</a>
    </li>
    <li class="<?php echo $theme == 'minimal' ? 'active' : '' ?>">
        <a href="index.php?page=<?php echo $page ?>&theme=minimal">Minimal theme</a>
    </li>
</ul>