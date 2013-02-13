<?php

class Breadcrumbs {

    /**
     * Breadcrumbs array
     * 
     * @var array
     * @access protected
     */
    protected $breadcrumbs = array();

    /**
     * Breadcrumbs html
     *
     * Html that wraps the entire breadcrumbs element
     *
     * Default is <p class="breadcrumbs">{breadcrumbs}</p>
     * 
     * @see Breadcrumbs::setBreadcrumbsHtml()
     * @var string
     * @access protected
     */
    protected $breadcrumbs_html = '<p class="breadcrumbs">{breadcrumbs}</p>';

    /**
     * Breadcrumb html
     *
     * Html that wraps each individual breadcrumb
     *
     * Default is <span class="breadcrumb"><a href="{link}">{text}</a></span>
     * 
     * @see Breadcrumbs::setBreadcrumbHtml()
     * @var string
     * @access protected
     */
    protected $breadcrumb_html = '<span class="breadcrumb"><a href="{link}">{text}</a></span>';

    /**
     * Active breadcrumb html
     *
     * Html that wraps the active breadcrumb
     *
     * Default is <span class="breadcrumb active_breadcrumb">{breadcrumb}</span>
     * 
     * @see Breadcrumbs::setActiveBreadcrumbHtml()
     * @var string
     * @access protected
     */
    protected $active_breadcrumb_html = '<span class="breadcrumb active_breadcrumb">{breadcrumb}</span>';

    /**
     * Breadcrumb separator html
     *
     * Html for the breadcrumb separator
     *
     * Default is <span class="breadcrumb_separator"> &raquo; </span>
     * 
     * @see Breadcrumbs::setBreadcrumbSeparatorHtml()
     * @var string
     * @access protected
     */
    protected $breadcrumb_separator_html = '<span class="breadcrumb_separator"> &raquo; </span>';

    /**
     * Set the breadcrumbs html
     *
     * Html that wraps the entire breadcrumbs element
     *
     * Default is <p class="breadcrumbs">{breadcrumbs}</p>
     *
     * {breadcrumbs} is required to be in the html. This is where the breadcrumbs will be inserted.
     * 
     * @param string $html Breadcrumbs wrapper html
     * @access public
     * @return void
     */
    public function setBreadcrumbsHtml( $html ) {
        $required_variables = array( '{breadcrumbs}' );
        $this->checkRequiredHtmlText( $required_variables, $html );
        $this->breadcrumbs_html = $html;
    }

    /**
     * Set the breadcrumb html
     *
     * Html that wraps each individual breadcrumb
     *
     * Default is <span class="breadcrumb"><a href="{link}">{text}</a></span>
     *
     * {breadcrumb} is required to be in the html. This is where the breadcrumb link will be inserted.
     * 
     * @param string $html Breadcrumb wrapper html
     * @access public
     * @return void
     */
    public function setBreadcrumbHtml( $html ) {
        $required_variables = array( '{text}', '{link}' );
        $this->checkRequiredHtmlText( $required_variables, $html );
        $this->breadcrumb_html = $html;
    }

    /**
     * Set the active breadcrumbs html
     *
     * Html that wraps the active breadcrumb
     *
     * Default is <span class="breadcrumb active_breadcrumb">{breadcrumb}</span>
     *
     * {breadcrumb} is required to be in the html. This is where the active breadcrumb text will be inserted.
     * 
     * @param string $html Active breadcrumb html
     * @access public
     * @return void
     */
    public function setActiveBreadcrumbHtml( $html ) {
        $required_variables = array( '{breadcrumb}' );
        $this->checkRequiredHtmlText( $required_variables, $html );
        $this->active_breadcrumb_html = $html;
    }

    /**
     * Set the breadcrumbs separator html
     *
     * Html for the breadcrumb separator
     *
     * Default is <span class="breadcrumb_separator"> &raquo; </span>
     * 
     * @param string $html Breadcrumb separator html
     * @access public
     * @return void
     */
    public function setBreadcrumbSeparatorHtml( $html ) {
        $this->breadcrumb_separator_html = $html;
    }

    /**
     * Check required variables
     *
     * Throws an error if the new html doesn't contain the required variables
     * 
     * @param array $required_variables Variables required by the method
     * @param string $html New html
     * @access protected
     * @return void
     */
    protected function checkRequiredHtmlText( array $required_variables, $html ) {
        foreach( $required_variables as $required ) {
            if ( strpos( $html, $required ) === false ) {
                $trace = debug_backtrace();
                trigger_error(
                    sprintf( 'The required variables for %s are: %s', $trace[1]['function'], implode( ',', $trace[0]['args'][0] ) ),
                    E_USER_ERROR
                );
            }
        }
    }

    /**
     * Get breadcrumb count
     * 
     * @return int
     * @access public
     * @return int Returns the amount of breadcrumbs
     */
    public function getBreadcrumbCount() {
        return count( $this->breadcrumbs );
    }

    /**
     * Get a specific bredcrumb
     * 
     * @param int $position Position of the breadcrumb in the breadcrumb array
     * @access public
     * @return mixed Returns a breadcrumb or false on error
     */
    public function getBreadcrumb( $position ) {
        if ( isset( $this->breadcrumbs[ $position ] ) ) {
            return $this->breadcrumbs[ $position ];
        }
        return false;
    }

    /**
     * Get the breadcrumbs array
     *
     * @access public
     * @return array Returns the array of breadcrumbs
     */
    public function getBreadcrumbs() {
        return $this->breadcrumbs;
    }

    /**
     * Add breadcrumbs from a url
     *
     * This method takes a url and splits it into pieces, adding a breadcrumb for each piece
     *
     * E.g. /shoes/mens/casual/vans/
     *
     * would become
     *
     * Shoes > Mens > Casual > Vans
     * 
     * @param string $url Url to extract breadcrumbs from
     * @param mixed $breadcrumb_text_callback Function to run each breadcrumb through
     * @access public
     * @return void
     */
    public function addBreadcrumbsFromUrl( $url, $breadcrumb_text_callback ) {
        $url_parts = explode( '/', trim( $url, '/' ) );
        $current_url = '/';
        foreach( $url_parts as $index => $url_part ) {
            $current_url .= $url_part . '/';
            $this->addBreadcrumb( call_user_func( $breadcrumb_text_callback, $url_part ), $index == count( $url_parts ) - 1 ? null : $current_url );
        }
    }

    /**
     * Add a breadcrumb to the end of the breadcrumb stack
     *
     * You can add your own variables to breadcrumbs and set them with the third argument
     * 
     * @param string $breadcrumb_text Breadcrumb text
     * @param string $breadcrumb_link Breadcrumb link
     * @param array $breadcrumb_vars extra variables passed to the breadcrumb
     * @access public
     * @return Breadcrumb Returns the newly created breadcrumb
     */
    public function addBreadcrumb( $breadcrumb_text, $breadcrumb_link = null, array $breadcrumb_vars = null ) {
        return $this->breadcrumbs[] = new Breadcrumb( $breadcrumb_text, $breadcrumb_link, $breadcrumb_vars );
    }

    /**
     * Insert a breadcrumb
     *
     * Insert a breadcrumb into a specific position
     *
     * @param int $position Position in the stack to insert the breadcrumb into
     * @param string $breadcrumb_text Breadcrumb text
     * @param string $breadcrumb_link Breadcrumb link
     * @param array $breadcrumb_vars extra variables passed to the breadcrumb
     * @access public
     * @return mixed False on error, Breadcrumb on success
     */
    public function insertBreadcrumb( $position, $breadcrumb_text, $breadcrumb_link = null, array $breadcrumb_vars = null ) {
        if ( $position < 0 || $position > count( $this->breadcrumbs ) ) {
            return false;
        }
        $breadcrumb = new Breadcrumb( $breadcrumb_text, $breadcrumb_link, $breadcrumb_vars );
        array_splice(
            $this->breadcrumbs,
            $position,
            0,
            array(
                $breadcrumb
            )
        );
        return true;
    }

    /**
     * Delete a breadcrumb
     * 
     * @param int $position Position of the breadcrumb to be deleted
     * @access public
     * @return Breadcrumb Returns the deleted breadcrumb
     */
    public function deleteBreadcrumb( $position ) {
        return array_splice( $this->breadcrumbs, $position, 1 );
    }

    /**
     * Replace a breadcrumb in the stack
     *
     * @param int $position Position in the stack to insert the breadcrumb into
     * @param string $breadcrumb_text Breadcrumb text
     * @param string $breadcrumb_link Breadcrumb link
     * @param array $breadcrumb_vars extra variables passed to the breadcrumb
     * @access public
     * @return Breadcrumb Returns the replaced breadcrumb
     */
    public function replaceBreadcrumb( $position, $breadcrumb_text, $breadcrumb_link = null, array $breadcrumb_vars = null ) {
        return array_splice( $this->breadcrumbs, $position, 1, array ( new Breadcrumb( $breadcrumb_text, $breadcrumb_link, $breadcrumb_vars ) ) );
    }

    /**
     * Get the breadcrumbs html
     *
     * @access public
     * @return string Returns the html code for the breadcrumbs
     */
    public function getBreadcrumbsHtml() {
        foreach( $this->breadcrumbs as $breadcrumb ) {
            if ( $breadcrumb->getVar( 'link' ) ) {
                $vars = $breadcrumb->getVars();
                $breadcrumb_html = preg_replace_callback(
                    '~\{(.*?)\}~',
                    function( $m ) use( $vars ) {
                        return isset( $vars[$m[1]] ) ? $vars[$m[1]] : $m[0];
                    },
                    $this->breadcrumb_html
                );
                $breadcrumb_array[] = $breadcrumb_html;
            }
            else {
                $breadcrumb_html = str_replace( '{breadcrumb}', $breadcrumb->getVar( 'text' ), $this->active_breadcrumb_html );
                $breadcrumb_array[] = $breadcrumb_html;
            }
        }
        return str_replace( '{breadcrumbs}', implode( $this->breadcrumb_separator_html, $breadcrumb_array ), $this->breadcrumbs_html );
    }
}