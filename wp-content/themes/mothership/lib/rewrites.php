<?php 
function new_author_base() {
    global $wp_rewrite;
    $author_slug = 'team';
    $author = '%author%';
    $wp_rewrite->author_base = $author_slug;
    $wp_rewrite->author_structure = $author_slug.'/'.$author;
}
add_action('init', 'new_author_base');

//add_action( 'init', 'add_author_rules' );
function add_author_rules() { 
    add_rewrite_rule(
        "team/([^/]+)/?",
        "index.php?author_name=$matches[1]",
        "top");
	
    add_rewrite_rule(
	"team/([^/]+)/page/?([0-9]{1,})/?",
	"index.php?author_name=$matches[1]&paged=$matches[2]",
	"top");
	
    add_rewrite_rule(
	"team/([^/]+)/(feed|rdf|rss|rss2|atom)/?",
	"index.php?author_name=$matches[1]&feed=$matches[2]",
	"top");
		
    add_rewrite_rule(
	"team/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?",
	"index.php?author_name=$matches[1]&feed=$matches[2]",
	"top");
}

?>