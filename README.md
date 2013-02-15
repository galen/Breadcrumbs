#Breadcrumbs

This is a class for use in creating breadcrumbs in your PHP projects.

##Basic usage

    $breadcrumbs = new Breadcrumbs;
    $breadcrumbs->addBreadcrumb( 'Home', '/' );
    $breadcrumbs->addBreadcrumb( 'Electronics', '/electronics/' );
    $breadcrumbs->addBreadcrumb( 'Projectors', '/electronics/projectors/' );
    $breadcrumbs->addBreadcrumb( 'ViewSonic 8200 HD' );
    echo $breadcrumbs->getBreadcrumbsHtml();

[Home](/home/) &raquo; [Electronics](/electronics/) &raquo; [Projectors](/electronics/projectors/) &raquo; ViewSonic 8200 HD

---

##Automatic breadcrumbs

You can get the breadcrumbs from a url as well using `addBreadcrumbsFromUrl()`. The second argument to this method is a function that will be run on each breadcrumb.  The following function replaces `-` with a space and capitalizes the words.

You would want to use a server variable like `$_SERVER['REQUEST_URI']`.

    $breadcrumbs = new Breadcrumbs;
    $breadcrumbs->addBreadcrumbsFromUrl(
        '/electronics/projectors/viewsonic-8200-hd/584948202/',
        function( $bc ){
            return ucwords( str_replace( '-', ' ', $bc ) );
        }    
    );
    echo $breadcrumbs->getBreadcrumbsHtml();

[Electronics](/electronics/) &raquo; [Projectors](/electronics/projectors/) &raquo; [Viewsonic 8200 Hd](/electronics/projectors/viewsonic-8200-hd/) &raquo; 584948202

There are a couple issues

 - No home link
 - Unwanted product ID in the breadcrumbs
 - The callback function incorrectly capitalized the product name

To add a home breadcrumb use the `insertBreadcrumb()` method

    // Add the home breadcrumb at position 0
    $breadcrumbs->insertBreadcrumb( 0, 'Home', '/' );
    echo $breadcrumbs->getBreadcrumbsHtml();

[Home](/home/) &raquo; [Electronics](/electronics/) &raquo; [Projectors](/electronics/projectors/) &raquo; [Viewsonic 8200 Hd](/electronics/projectors/viewsonic-8200-hd/) &raquo; 584948202

To delete the unwanted ID breadcrumb use `deleteBreadcrumb()`

    // Set position to -1 to delete the last breadcrumb
    $breadcrumb->deleteBreadcrumb( -1 );
    echo $breadcrumbs->getBreadcrumbsHtml();

[Home](/home/) &raquo; [Electronics](/electronics/) &raquo; [Projectors](/electronics/projectors/) &raquo; [Viewsonic 8200 Hd](/electronics/projectors/viewsonic-8200-hd/)

Since this is a product page you will surely get the name of the product from your datasource.  You can replace the last breadcrumb with the correct product name.

    // Set $position to -1 to start from the right
    $breadcrumbs->replaceBreadcrumb( -1, $product['name'] );
    echo $breadcrumbs->getBreadcrumbsHtml();

[Home](/home/) &raquo; [Electronics](/electronics/) &raquo; [Projectors](/electronics/projectors/) &raquo; ViewSonic 8200 HD

All together

    $breadcrumbs = new Breadcrumbs;
    $breadcrumbs->addBreadcrumbsFromUrl(
        '/electronics/projectors/viewsonic-8200-hd/584948202/',
        function( $bc ){
            return ucwords( str_replace( '-', ' ', $bc ) );
        }    
    )
    ->insertBreadcrumb( 0, 'Home', '/' )
    ->deleteBreadcrumb( -1 )
    ->replaceBreadcrumb( -1, $product['name'] );
    echo $breadcrumbs->getBreadcrumbsHtml();

[Home](/home/) &raquo; [Electronics](/electronics/) &raquo; [Projectors](/electronics/projectors/) &raquo; ViewSonic 8200 HD

##Other methods

Delete a breadcrumb at a certain position

    $breadcrumbs->deleteBreadcrumb( 3 );

Replace a breadcrumb

    $breadcrumbs->replaceBreadcrumb( 2, 'Breadcrumb Title', 'Breadcrumb Link' );

Get the breadcrumb count

    $breadcrumbs->getBreadcrumbCount()

##Editing the breadcrumb html

There are 4 methods you can use to change the html of the breadcrumbs

Html wrapper for the breadcrumbs

    $breadcrumbs->setBreadcrumbsHtml();
    
Html that wraps the breadcrumb text

    $breadcrumbs->setBreadcrumbHtml();


This is the html for the last/active breadcrumb

    $breadcrumbs->setActiveBreadcrumbHtml();

This goes in between each breadcrumb

    $breadcrumbs->setBreadcrumbSeparatorHtml();
    
##Advanced usage

When changing the breadcrumb html you can insert your own variables.

    $bc->setBreadcrumbHtml( '<a href="{link}" onclick="alert(\'{alert}\');return false;">{text}</a>' );
    
This adds a variabled named `{alert}` to the html. To set this variable pass an array as the third argument to `addBreadcrumb()`

    $bc->addBreadcrumb( 'Electronics', '/electronics/', array( 'alert' => 'This is an alert' ) );

`This is an alert` will replace `{alert}` in the html