<?php

namespace Smirik\PropelSerializerBundle\Serializer;
    
class FormSerializer
{
    
    public function serialize($form)
    {
        $errors = $this->getErrors($form, 'global');

        $response = array(
            "status"      => "error",
            "status_code" => 400,
            "status_text" => "Form is not valid",
            'message'     => array('fields' => $errors),
        );

        return $response;
    }


    private function getErrors($element, $name = null)
    {
        $errors = array();

        if (!$element->isValid()) {

            foreach ($element->getErrors() as $error) {
                if (is_null($name)) {
                    $errors[] = $error->getMessage();
                } else {
                    $errors[$name][] = $error->getMessage();
                }
            }

            foreach ($element->all() as $child) {
                $childErrors = $this->getErrors($child);
                if (!empty($childErrors)) {
                    $errors[$child->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

}
