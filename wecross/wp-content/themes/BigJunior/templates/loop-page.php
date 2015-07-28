<?php
if (have_posts()) {
    while (have_posts()) { the_post();
        ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
            <?php the_content(); ?>
        </div>
        
    <?php
    $pageid = get_the_ID();
    if($pageid == '5393'){
?>
    <script>

    jQuery(document).ready(function(){
        
            function goToByScroll(id){
            jQuery('html,body').animate({
                scrollTop: jQuery("").offset().top},
                'slow');
        }
        
        
        jQuery(".tab").on("click", function(){            
            var innertext = jQuery(this).find('h4').html();

           switch(innertext){
           
                case ("Concept en Strategie"):
                     jQuery('html, body').animate({scrollTop: jQuery('.strategie-anchor').offset().top}, 500);
                    break;
                case ("Campagnes"):
                     jQuery('html, body').animate({scrollTop: jQuery('.campagne-anchor').offset().top}, 500);
                    break;
                case ("Webapplicaties"):
                     jQuery('html, body').animate({scrollTop: jQuery('.apps-anchor').offset().top}, 500);  
                    break;
                case ("Big data"):
                     jQuery('html, body').animate({scrollTop: jQuery('.data-anchor').offset().top}, 500); 
                    break;     
                case ("Analytics en analyse"):
                     jQuery('html, body').animate({scrollTop: jQuery('.analytics-anchor').offset().top}, 500);  
                    break;                   
                case ("Hosting en Beheer"):
                     jQuery('html, body').animate({scrollTop: jQuery('.beheer-anchor').offset().top}, 500);
                    break;    
                default:
                    break;
           }
           
           
        });
    
    
    });
    
    </script>
        <?php    
        }
    }//While have_posts
}//If have_posts