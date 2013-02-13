<?php

class Breadcrumb {

    /**
     * Breadcrumb variables
     * 
     * @var array
     * @access  protected
     */
    protected $vars = array();

    /**
     * Get breadcrumb variable
     * 
     * @param string $var Variable to get
     * @return string
     * @access  public
     */
    public function getVar( $var ) {
        return isset( $this->vars[ $var ] ) ? $this->vars[ $var ] : null;
    }

    /**
     * Get breadcrumb variables
     * 
     * @return array
     * @access  public
     */
    public function getVars() {
        return $this->vars;
    }

    /**
     * Set a breadcrumb variable
     * 
     * @param string $var Variable to set
     * @param  string $val Value
     * @return void
     * @access public
     */
    public function setVar( $var, $val ) {
        $this->vars[$var] = $val;
    }

    /**
     * Set breadcrumb variables
     *
     * Merges new variables over existing variables
     * 
     * @param string $text Breadrumb variables
     * @return void
     * @access  public
     */
    public function setVars( array $vars ) {
        $this->vars += $vars;
    }

    /**
     * Constructor
     * 
     * @param string $text Breadcrumb text
     * @param string $link Breadcrumb link
     * @param array $vars Breadcrumb variables
     */
    public function __construct( $text, $link, array $vars = null ) {
        if ( !$vars ) {
            $vars = array();
        }
        $vars['text'] = $text;
        $vars['link'] = $link;
        $this->setVars( $vars );
    }

}