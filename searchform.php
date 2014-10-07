<?php
/**
 * Search Form Template
 *
 * @package Wordpress
 * @subpackage daltons
 */
?>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<div>
        <input type="text" value="" name="s" id="s" placeholder="search"/>
        <button type="submit" id="search-button" alt="Search"><i class="icon-search"></i></button>
    </div>    
</form>