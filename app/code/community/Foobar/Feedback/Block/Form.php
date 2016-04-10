<?php

class Foobar_Feedback_Block_Form extends Mage_Core_Block_Template
{
    /**
     * Set up the form data and form errors if any exist.
     */
    protected function _construct()
    {
        $session = Mage::getSingleton('core/session');
        $this->data = is_array($data = $session->getFormData()) ? $data : array();
        $this->errors = is_array($errors = $session->getFormErrors()) ? $errors : array();
    }
    
    /**
     * Get the name of the person giving feedback.
     *
     * @return string
     */
    public function getName()
    {
        return isset($this->data['name']) ? $this->data['name'] : $this->getData('name');
    }
    
    /**
     * Get the email of the person giving feedback.
     *
     * @return string
     */
    public function getEmail()
    {
        return isset($this->data['email']) ? $this->data['email'] : $this->getData('email');
    }
    
    /**
     * Get the details of the person giving feedback.
     *
     * @return string
     */
    public function getFeedback()
    {
        return isset($this->data['feedback']) ? $this->data['feedback'] : $this->getData('feedback');
    }
    
    /**
     * Get the error for a given field.
     *
     * @return string
     */
    public function getError($key, $default = '')
    {
        return isset($this->errors[$key]) ? $this->errors[$key] : $default;
    }
    
    /**
     * Determine if the given field has an error.
     *
     * @return bool
     */
    public function hasError($key)
    {
        return array_key_exists($key, $this->errors);
    }
}
