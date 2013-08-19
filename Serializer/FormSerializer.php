<?php

namespace Smirik\PropelSerializerBundle\Serializer;
    
class FormSerializer
{
    
    public function serialize($form)
    {
        $errors = array();
        $res = array();
        foreach ($form->getErrors() as $error) {
            $res[] = $error->getMessage();
        }
        if (count($res) > 0) {
            $errors['global'] = $res;
        }
        foreach ($form->all() as $child) {
            $res = array();
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $res[] = $error->getMessage();
                }
            }
            if (count($res) > 0) {
                $errors[$child->getName()] = $res;
            }
        }
        
        $response = array(
            "status"      => "error",
            "status_code" => 400,
            "status_text" => "Form is not valid",
            'message'     => array('fields' => $errors),
        );
        return $response;
    }
    
}