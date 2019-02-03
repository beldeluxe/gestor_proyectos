<?php
namespace Model\Form;

 use Model\CommonContentsModel\MenuEntryLabel;
 use Zend\Form\Fieldset;
 use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class MenuEntryLabelFieldset extends Fieldset
{


    public function __construct($name=null, $options=array())
    {

        parent::__construct($name, $options);

        $this
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setObject(new MenuEntryLabel())
        ;

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'language_id',
            'options' => array(
                'required' => 'required',
            )
        ));

        $this->add( array(
            'type' => 'text',
            'name' => 'menu_entry_label',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Etiqueta de la entrada',
            ),
            'options' => array(
               'label' => ' '
            )
        ) );



    }

}
