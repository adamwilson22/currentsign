<!DOCTYPE HTML>
<html>
    <head>
    	<meta http-equiv="content-type" content="text/html;charset=utf-8" />  
    	<title><?php echo $title_1 ?> - <?php echo $title_2 ?> - eXtended CRUD &amp; Data Management System</title>
        <link href="assets/shCore.css" rel="stylesheet" type="text/css" />
        <link href="assets/shThemeDjango.css" rel="stylesheet" type="text/css" /> 
        <link href="assets/style.css" rel="stylesheet" type="text/css" />
        
        <?php if($theme == 'bootstrap'){ ?>
        
        <link href="../xcrud/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../xcrud/plugins/tabulator-master/dist/css/bootstrap/tabulator_bootstrap.css" rel="stylesheet" type="text/css" />
        <?php }elseif($theme == 'bootstrap4'){ ?>
        
        <!--
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
        <link href="../xcrud/plugins/bootstrap-4.5.0/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../xcrud/plugins/Font-Awesome-fa-4/css/font-awesome.min.css" rel="stylesheet" type="text/css" />'
        
        <!--<link rel="stylesheet" type="text/css" href="../xcrud/plugins/tabulator-master/dist/css/tabulator.css">-->
        <!--<link href="../xcrud/plugins/tabulator-master/dist/css/bootstrap/tabulator_bootstrap4.css" rel="stylesheet">-->
        <?php }elseif($theme == 'default'){ ?>
        <!--<link href="../xcrud/plugins/tabulator-master/dist/css/tabulator_simple.css" rel="stylesheet" type="text/css" />-->
        <?php } ?>
        
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-160326193-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		
		  gtag('config', 'UA-160326193-1');
		</script>
        
        <script src="../xcrud/plugins/jquery-3.5.1.min.js"></script>
        <script src="../xcrud/plugins/jquery-migrate-3.0.0.min.js"></script>
       <!-- <script src="../xcrud/plugins/tabulator-master/dist/js/tabulator.js"></script> -->      
    </head>  
    <body>
    	<div id="header-top"><?php include(dirname(__FILE__).'/menu-top.php') ?></div>
        <div id="page">       	
            <div id="menu"><?php include(dirname(__FILE__).'/menu.php') ?></div>
            <div id="content">
                <!--<div class="clr">&nbsp;</div>-->
                <h1><?php echo $title_1 ?> <small><?php echo $title_2 ?></small></h1>
                <p><?php echo $description ?></p>
                <div id="hide_code" onclick="toggle('hide');" style="cursor:pointer;padding:3px;color:#ff0000;">HIDE CODE</div>
                <div id="show_code" onclick="toggle('show');" style="cursor:pointer;padding:3px;color:#ff0000;display:none;">SHOW CODE</div>
                
                <pre class="brush: php"><?php echo htmlspecialchars($code) ?></pre>
                                
                <script>
                   
                    function toggle(status){
                        
                        localStorage.setItem('show_hide',status);
                    	
                    	if(status == 'show'){
                    		$(".syntaxhighlighter").show();
                    		$("#show_code").hide();
                    		$("#hide_code").show();
                    	}else if(status == 'hide'){
                    		
                    		$(".syntaxhighlighter").hide();
                    		$("#show_code").show();
                    		$("#hide_code").hide();
                    	}else{
                    		$(".syntaxhighlighter").show();
                    		$("#show_code").hide();
                    		$("#hide_code").show();
                    	}
                    	//$(".syntaxhighlighter").toggle();
                    }  
                    
                   
                    
                        	
                	
                </script>
                
                <?php include($file) ?>
                <div class="clr">&nbsp;</div>
            </div>
        </div>
        <!--<a class="buy-xcrud" href="http://codecanyon.net/item/xcrud-data-management-system-php-crud/3215400?ref=f0ska">Buy it now<small>for only $13</small></a>-->
        <!--  -->
        
        <script src="assets/shCore.js" type="text/javascript"></script>
        <script src="assets/shBrushPhp.js" type="text/javascript"></script>
        <script src="assets/shBrushJScript.js" type="text/javascript"></script>
        <script type="text/javascript">
        	SyntaxHighlighter.all();
        	
            $( document ).ready(function() {
			    var show_hide = localStorage.getItem('show_hide');
                console.log(show_hide);
                               
                  var checkExist = setInterval(function() {
					   if ($('.syntaxhighlighter').length) {
					      toggle(show_hide); 
					      console.log("Exists!");
					      clearInterval(checkExist);
					   }else{
					   	  
					   	  console.log("Not Exists!");
					   }
				   
				  }, 100); // check every 100ms 
                
                       
			});
        	
        </script>
        <?php if($theme == 'bootstrap'){ ?>
        <script src="../xcrud/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <?php }elseif($theme == 'bootstrap4'){ ?>
           
        <script src="../xcrud/plugins/popper-core-master/popper.min.js" ></script>
        <script src="../xcrud/plugins/bootstrap-4.5.0/dist/js/bootstrap.min.js" type="text/javascript"></script>
        
        <?php } ?>
        
        <script src="../xcrud/plugins/jspdf/jspdf.min.js"></script>
        <script src="../xcrud/plugins/jspdf/jspdf.plugin.autotable.js"></script>
        
        <script src="../xcrud/plugins/tableHeadFixer.js"></script>
         
    </body>
</html>