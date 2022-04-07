<?php 
//Template Name: Institutional Page
get_header( );
$fields = get_fields();
?>

<main class='container my-3'>
    <?php if(have_posts(  )){
        while (have_posts(  )) {
                the_post(  ); ?>
            <h1 class='my-3'>Page: <?php echo $fields['title'];?></h1>
            <img src="<?php echo $fields['image'];?>" alt="imagen"/>
            <hr>
            <?php the_content( );?>
                   <?php
        }
    }?>
</main>

<?php get_footer( );?>
