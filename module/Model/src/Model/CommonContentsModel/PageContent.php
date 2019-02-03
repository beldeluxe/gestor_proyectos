<?php

namespace Model\CommonContentsModel;

class PageContent
{
    const TABLE = 'pages_content';
   
    protected $id; 
    protected $page_id; 
    protected $language_id; 
    protected $is_visible; 
    protected $slug;
    protected $title; 
    protected $content;   
    protected $seo_title;
    protected $seo_keywords;
    protected $seo_description;
    protected $modification_date;

    public function __construct()
    {
        $this->exchangeArray( array() );
    }    

    /**
        * @return int
    */
    public function getId ()
    {
        return $this->id;
    }
    
    /**
        * @param int $value
    */
    public function setId ( $value )
    {
        $this->id = $value;
    
        return $this;
    } 

    /**
        * @return int
    */
    public function getPageId ()
    {
        return $this->page_id;
    }
    
    /**
        * @param int $value 
    */
    public function setPageId ( $value )
    {
        $this->page_id = $value;
    
        return $this;
    } 

    /**
        * @return int
    */
    public function getLanguageId ()
    {
        return $this->language_id;
    }
    
    /**
        * @param int $value 
    */
    public function setLanguageId ( $value )
    {
        $this->language_id = $value;
    
        return $this;
    } 

    /**
        * @return bool
    */
    public function getIsVisible ()
    {
        return $this->is_visible;
    }
    
    /**
        * @param bool $value
    */
    public function setIsVisible ( $value )
    {
        $this->is_visible = $value;
    
        return $this;
    } 

    /**
        * @return string
    */
    public function getSlug ()
    {
        return $this->slug;
    }
    
    /**
        * @param string $value maxLength: 100
    */
    public function setSlug ( $value )
    {
        $this->slug = substr( (string) $value, 0, 100);
    
        return $this;
    } 

    /**
        * @return string
    */
    public function getTitle ()
    {
        return $this->title;
    }
    
    /**
        * @param string $value 
    */
    public function setTitle ( $value )
    {
        $this->title = $value;
    
        return $this;
    } 

    /**
        * @return string
    */
    public function getContent ()
    {
        return $this->content;
    }
    
    /**
        * @param string $value HTML CONTENT
    */
    public function setContent ( $value )
    {
        $this->content = $value;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getSeoTitle ()
    {
        return $this->seo_title;
    }

    /**
     * @param string $value maxLength: 256
     */
    public function setSeoTitle ( $value )
    {
        $this->seo_title = substr( (string) $value, 0, 256);

        return $this;
    }

    /**
     * @return string
     */
    public function getSeoKeywords ()
    {
        return $this->seo_keywords;
    }

    /**
     * @param string $value maxLength: 256
     */
    public function setSeoKeywords( $value )
    {
        $this->seo_keywords = substr( (string) $value, 0, 256);

        return $this;
    }
    /**
     * @return string
     */
    public function getSeoDescription ()
    {
        return $this->seo_description;
    }

    /**
     * @param string $value maxLength: 256
     */
    public function setSeoDescription( $value )
    {
        $this->seo_description = substr( (string) $value, 0, 256);

        return $this;
    }    

    /**
     * ModificationDate
     * @return string
     */
    public function getModificationDate ( $useDbFormat = false )
    {
        $date = $this->modification_date;
        if ($useDbFormat) {
            $date = DateTime::dbToTimeValue($date);
        }
        return (string) $date;
    }
    /**
     * @param string 
     */
    public function setModificationDate ( $value, $useDbFormat = false )
    {
        if ($useDbFormat) {
            $value = DateTime::dbToTimeValue($value);
        }
        $this->modification_date = (string) $value;
    
        return $this;
    }

    public function exchangeArray($data)
    {
        $this->setId( (!empty($data['id'])) ? $data['id'] : 0 );
        $this->setPageId( (!empty($data['page_id'])) ? $data['page_id'] : 0 );
        $this->setLanguageId( (!empty($data['language_id'])) ? $data['language_id'] : 0 );
        $this->setIsVisible( (!empty($data['is_visible'])) ? $data['is_visible'] : 0 );
        $this->setTitle( (!empty($data['title'])) ? $data['title'] : 0 );
        $this->setSlug( (!empty($data['slug'])) ? $data['slug'] : 0 );
        $this->setContent( (!empty($data['content'])) ? $data['content'] : 0 );
        $this->setSeoTitle( (!empty($data['seo_title'])) ? $data['seo_title'] : '' );
        $this->setSeoKeywords( (!empty($data['seo_keywords'])) ? $data['seo_keywords'] : '' );
        $this->setSeoDescription( (!empty($data['seo_description'])) ? $data['seo_description'] : '' );
        $this->setModificationDate( (!empty($data['modification_date'])) ? $data['modification_date'] : 0 );
    }

    public function getDataArray( $includeId = false )
    {
        $data = array();
        if ($includeId) $data['id'] = $this->getId();
        $data['page_id'] = $this->getPageId();
        $data['language_id'] = $this->getLanguageId();
        $data['is_visible'] = $this->getIsVisible();
        $data['title'] = $this->getTitle();
        $data['slug'] = $this->getSlug();
        $data['content'] = $this->getContent();
        $data['seo_title'] = $this->getSeoTitle();
        $data['seo_keywords'] = $this->getSeoKeywords();
        $data['seo_description'] = $this->getSeoDescription();
        $data['modification_date'] = $this->getModificationDate();
        return $data;
    }

    // Funciones estáticas para la generación de formularios
    // de tipo BasicForm...

    public static function getEditFormArray()
    {
        return array(
            /*
            '{element_name}' => array(
                'type' => {text|radio|checkbox|date|phone|nif|password},
                'validate' => {true|false},
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            */
            /*
            '{radio_button_example}' => array(
                'type' => 'radio',
                'validate' => true,
                'formElementOptions' => 
                    array( 'label' => '', 'value_options' => array( 'H' => ' Hombre', 'M' => ' Mujer' ),
                'formElementAttributes' => 
                    'attributes' => array( 'class' => 'data' )
            ),
            */
            /*
            '{document_number}' => array(
                'type' => 'text',
                'validate' => true,
                'formElementOptions' => 
                    array(),
                'formElementAttributes' => 
                    'attributes' => array( 'type' => 'text', 'required' => true, 'maxlength' => '9' )
            ),
            */            
            'page_name' => array(
                'type' => 'text',
                'validate' => false,
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            'type' => array(
                'type' => 'radio',
                'validate' => true,
                'formElementOptions' => array(
                    'value_options' => array( '1' => 'Página Wiki', '0' => 'Página genenal' )
                ),
                'formElementAttributes' => array()
            ),

            'title' => array(
                'type' => 'text',
                'validate' => false,
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            'is_visible' => array(
                'type' => 'radio',
                'validate' => true,
                'default' => 1,
                'formElementOptions' => array(
                    'value_options' => array( '1' => 'Publicar', '0' => 'Borrador' )
                ),
                'formElementAttributes' => array()
            ),
            'language_id' => array(
                'type' => 'radio',
                'validate' => true,
                'default' => 1,
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            'content' => array(
                'type' => 'text',
                'validate' => false,
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            'seo_title' => array(
                'type' => 'text',
                'validate' => false,
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            'seo_keywords' => array(
                'type' => 'text',
                'validate' => false,
                'formElementOptions' => array(),
                'formElementAttributes' => array()
            ),
            'seo_description' => array(
                'type' => 'text',
                'validate' => false,
                'formElementOptions' => array(),
                'formElementAttributes' => array('type'=>'textarea')
            ),
        );
    }
}
